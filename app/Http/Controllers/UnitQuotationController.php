<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Delivery;
use App\Models\DetailDelivery;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Pic;
use App\Models\Unit;
use App\Models\UnitQuotation;
use App\Models\UnitQuotationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UnitQuotationController extends Controller
{
    public function index()
    {
        return view('pages.unit-quotation.index');
    }

    public function create()
    {
        $dateNow          = Carbon::now();
        $monthNow         = $dateNow->month;
        $formattedMonth   = $this->convertToRoman($monthNow);
        $userCode         = Auth::user()->code ?? Auth::user()->name;
        $counter          = UnitQuotation::whereYear('created_at', $dateNow)->where('id_sales', Auth::id())->count();
        $formattedCounter = str_pad($counter + 1, 3, '0', STR_PAD_LEFT);
        $defaultNoQuote   = $formattedCounter . '-PU/BDG/RJO-' . $userCode . '/' . $formattedMonth . '/' . $dateNow->year;

        $clients = Client::where('id_sales', Auth::id())->orderBy('company')->get();
        return view('pages.unit-quotation.create', compact('clients', 'defaultNoQuote'));
    }

    public function getPics($clientId)
    {
        $pics = Pic::where('id_client', $clientId)->get(['id', 'name_pic', 'position']);
        return response()->json($pics);
    }

    public function store(Request $request)
    {
        $subtotal = 0;
        $items    = $request->input('items', []);

        foreach ($items as $item) {
            $qty    = floatval($item['qty']   ?? 1);
            $price  = floatval($item['price'] ?? 0);
            $disc   = floatval($item['disc']  ?? 0);
            $subtotal += $qty * $price * (1 - $disc / 100);
        }

        $diskon      = floatval($request->diskon ?? 0);
        $afterDiskon = $subtotal - ($subtotal * $diskon / 100);
        $tax         = $request->boolean('tax');
        $tax_amount  = $tax ? round($afterDiskon * 0.11) : 0;
        $total       = $afterDiskon + $tax_amount;

        $client = $request->id_client ? Client::find($request->id_client) : null;

        $quote = UnitQuotation::create([
            'id_client'        => $request->id_client ?: null,
            'id_pic'           => $request->id_pic ?: null,
            'id_sales'         => Auth::id(),
            'id_support'       => $client->id_support ?? null,
            'no_quote'         => $request->no_quote ?: $this->generateNoQuote(),
            'attn'             => $request->attn,
            'no_pr'            => $request->no_pr ?: null,
            'date'             => $request->date,
            'title'            => $request->title,
            'type'             => $request->type,
            'week'             => $request->week,
            'subtotal'         => $subtotal,
            'diskon'           => $diskon,
            'tax'              => $tax,
            'tax_amount'       => $tax_amount,
            'total'            => $total,
            'note'             => $request->note,
            'validity'         => $request->validity,
            'pricing'          => $request->pricing,
            'delivery_process' => $request->delivery_process,
            'payment'          => $request->payment,
            'status'           => 'draft',
            'revision_number'  => 0,
            'is_latest'        => 1,
        ]);

        $this->saveDetails($quote->id, $items);

        $quote->statusHistory()->create(['status' => 'draft', 'note' => null]);

        return redirect()->route('unit-quotation.show', $quote->id)
            ->with('success', 'Quotation created successfully.');
    }

    public function show($id)
    {
        $quote       = UnitQuotation::with(['client', 'pic', 'sales', 'details.unit', 'statusHistory'])->findOrFail($id);
        $allVersions = $quote->allVersions();
        $invoices    = Invoice::where('id_unit_quotation', $quote->id)->orderByRaw("FIELD(type,'DP','BP','CT')")->get();
        $contracts   = Contract::where('id_unit_quotation', $quote->id)->get();

        $thisYear      = Carbon::now()->year;
        $numberLastSC  = Contract::where('type', 'Selling')->where('level', '1')
            ->whereYear('date', Carbon::now())->orderByDesc('id')->first('no_contract');
        if ($numberLastSC && preg_match('/^\d{3}/', $numberLastSC->no_contract, $m)) {
            $formattedNumberSC = str_pad((int) $m[0] + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $formattedNumberSC = '001';
        }

        $payments = Payment::where('id_unit_quotation', $quote->id)->orderBy('id')->get();

        return view('pages.unit-quotation.detail', compact('quote', 'allVersions', 'invoices', 'contracts', 'payments', 'thisYear', 'formattedNumberSC'));
    }

    public function edit($id)
    {
        $quote   = UnitQuotation::with(['client', 'pic', 'details.unit', 'details.fixedAsset'])->findOrFail($id);
        $clients = Client::where('id_sales', Auth::id())->orderBy('company')->get();

        $editItems = $quote->details->map(function ($d) {
            return [
                'type'          => $d->type,
                'id_unit'       => $d->id_unit,
                'id_fixed_asset'=> $d->id_fixed_asset,
                'unit'          => $d->unit ? $d->unit->toArray() : null,
                'fixed_asset'   => $d->fixedAsset ? [
                    'id'            => $d->fixedAsset->id,
                    'code'          => $d->fixedAsset->code,
                    'serial_number' => $d->fixedAsset->serial_number,
                ] : null,
                'spec_visible'=> $d->getSpecVisibleArray(),
                'label'       => $d->label,
                'description' => $d->description,
                'qty'         => (float) $d->qty,
                'info_qty'    => $d->info_qty,
                'price'       => (float) $d->price,
                'disc'        => (float) $d->disc,
            ];
        })->values();

        return view('pages.unit-quotation.edit', compact('quote', 'clients', 'editItems'));
    }

    public function update(Request $request, $id)
    {
        $quote    = UnitQuotation::findOrFail($id);
        $subtotal = 0;
        $items    = $request->input('items', []);

        foreach ($items as $item) {
            $qty    = floatval($item['qty']   ?? 1);
            $price  = floatval($item['price'] ?? 0);
            $disc   = floatval($item['disc']  ?? 0);
            $subtotal += $qty * $price * (1 - $disc / 100);
        }

        $diskon      = floatval($request->diskon ?? 0);
        $afterDiskon = $subtotal - ($subtotal * $diskon / 100);
        $tax         = $request->boolean('tax');
        $tax_amount  = $tax ? round($afterDiskon * 0.11) : 0;
        $total       = $afterDiskon + $tax_amount;

        $quote->update([
            'id_client'        => $request->id_client ?: null,
            'id_pic'           => $request->id_pic ?: null,
            'no_quote'         => $request->no_quote ?: $quote->no_quote,
            'attn'             => $request->attn,
            'no_pr'            => $request->no_pr ?: null,
            'date'             => $request->date,
            'title'            => $request->title,
            'type'             => $request->type,
            'week'             => $request->week,
            'subtotal'         => $subtotal,
            'diskon'           => $diskon,
            'tax'              => $tax,
            'tax_amount'       => $tax_amount,
            'total'            => $total,
            'note'             => $request->note,
            'validity'         => $request->validity,
            'pricing'          => $request->pricing,
            'delivery_process' => $request->delivery_process,
            'payment'          => $request->payment,
        ]);

        $quote->details()->delete();
        $this->saveDetails($quote->id, $items);

        return redirect()->route('unit-quotation.show', $quote->id)
            ->with('success', 'Quotation updated successfully.');
    }

    public function revise($id)
    {
        $source = UnitQuotation::with('details')->findOrFail($id);

        $rootId  = $source->root_id ?? $source->id;
        $nextRev = UnitQuotation::where(function ($q) use ($rootId) {
            $q->where('id', $rootId)->orWhere('root_id', $rootId);
        })->max('revision_number') + 1;

        $baseNo      = preg_replace('/-R\d+$/', '', $source->no_quote);
        $newNoQuote  = $baseNo . '-R' . $nextRev;

        // Mark all versions as not latest, and set status to 'revision'
        UnitQuotation::where(function ($q) use ($rootId) {
            $q->where('id', $rootId)->orWhere('root_id', $rootId);
        })->update(['is_latest' => 0, 'status' => 'revision']);

        $newQuote = UnitQuotation::create([
            'root_id'          => $rootId,
            'revision_number'  => $nextRev,
            'is_latest'        => 1,
            'id_client'        => $source->id_client,
            'id_pic'           => $source->id_pic,
            'id_sales'         => $source->id_sales,
            'id_support'       => $source->id_support,
            'no_quote'         => $newNoQuote,
            'attn'             => $source->attn,
            'no_pr'            => $source->no_pr,
            'date'             => now()->toDateString(),
            'title'            => $source->title,
            'type'             => $source->type,
            'week'             => $source->week,
            'subtotal'         => $source->subtotal,
            'diskon'           => $source->diskon,
            'tax'              => $source->tax,
            'tax_amount'       => $source->tax_amount,
            'total'            => $source->total,
            'note'             => $source->note,
            'validity'         => $source->validity,
            'pricing'          => $source->pricing,
            'delivery_process' => $source->delivery_process,
            'payment'          => $source->payment,
            'status'           => 'revision',
        ]);

        foreach ($source->details as $d) {
            UnitQuotationDetail::create([
                'id_unit_quotation' => $newQuote->id,
                'type'              => $d->type,
                'id_unit'           => $d->id_unit,
                'id_fixed_asset'    => $d->id_fixed_asset,
                'spec_visible'      => $d->spec_visible,
                'label'             => $d->label,
                'description'       => $d->description,
                'qty'               => $d->qty,
                'info_qty'          => $d->info_qty,
                'price'             => $d->price,
                'disc'              => $d->disc,
                'amount'            => $d->amount,
                'sort_order'        => $d->sort_order,
            ]);
        }

        return redirect()->route('unit-quotation.show', $newQuote->id)
            ->with('success', 'Revisi berhasil dibuat: ' . $newNoQuote);
    }

    public function print($id)
    {
        $quote = UnitQuotation::with(['client', 'pic', 'sales', 'details.unit'])->findOrFail($id);
        return view('pages.unit-quotation.print', compact('quote'));
    }

    public function storeDelivery(Request $request, $id)
    {
        $quote = UnitQuotation::with('details.unit')->findOrFail($id);

        $delivery = new Delivery();
        $delivery->id_unit_quotation = $quote->id;
        $delivery->id_invoice        = $request->id_invoice ?: null;
        $delivery->date              = $request->date ?? Carbon::today()->toDateString();
        $delivery->destination       = $request->destination;
        $delivery->type              = $request->type ?? 'Ekspedisi';
        $delivery->code              = 'Unit';
        $delivery->save();

        foreach ($quote->details as $item) {
            $desc = $item->label ?: ($item->unit
                ? trim($item->unit->brand . ' ' . $item->unit->sku . ($item->unit->model ? ' — ' . $item->unit->model : ''))
                : $item->description);

            $dDelivery = new DetailDelivery();
            $dDelivery->id_delivery = $delivery->id;
            $dDelivery->id_pn       = null;
            $dDelivery->desc        = $desc;
            $dDelivery->qty         = $item->qty;
            $dDelivery->info_qty    = $item->info_qty ?? 'Unit';
            $dDelivery->view        = '0';
            $dDelivery->save();
        }

        if ($request->id_invoice) {
            return redirect()->route('invoice.show_unit', $request->id_invoice)
                ->with('success', 'Surat Jalan berhasil dibuat.');
        }

        return redirect()->route('unit-quotation.show', $quote->id)
            ->with('success', 'Surat Jalan berhasil dibuat.');
    }

    public function changeStatus(Request $request, $id)
    {
        $quote = UnitQuotation::findOrFail($id);
        $quote->update(['status' => $request->status]);

        $quote->statusHistory()->create([
            'status' => $request->status,
            'note'   => $request->note,
        ]);

        return redirect()->route('unit-quotation.show', $id)
            ->with('success', 'Status updated.');
    }

    public function uploadPO(Request $request, $id)
    {
        $request->validate([
            'po_number'    => 'required|string|max:100',
            'po_file'      => 'required|file|mimes:pdf|max:5120',
            'invoice_type' => 'required|in:DP,CT',
            'dp_percent'   => 'nullable|numeric|min:1|max:99',
        ]);

        $quote = UnitQuotation::findOrFail($id);

        $client = $quote->client;
        if (!$client) {
            return redirect()->back()->with('error', 'Data client tidak ditemukan.');
        }

        $npwpClean = preg_replace('/[^a-zA-Z0-9]/', '', $client->npwp ?? '');
        if (strlen($npwpClean) < 14) {
            return redirect()->route('unit-quotation.show', $id)
                ->with('error', 'NPWP client belum diisi atau kurang dari 14 karakter. Pengajuan PO tidak dapat diproses.');
        }

        $year = now()->year;
        $path = $request->file('po_file')->store("unit-quotation/po/{$year}", 'public');

        $quote->update([
            'po_number'      => $request->po_number,
            'po_file'        => $path,
            'payment_method' => $request->payment_method,
            'status'         => 'po_received',
            'po_received'    => now()->toDateString(),
        ]);

        $quote->statusHistory()->create([
            'status' => 'po_received',
            'note'   => 'PO No. ' . $request->po_number,
        ]);

        $this->createInvoiceRecords($quote, $request->invoice_type, $request->dp_percent);

        return redirect()->route('unit-quotation.show', $id)
            ->with('success', 'PO berhasil diupload. Status diubah ke PO Received.');
    }

    public function requestNextInvoice(Request $request, $id)
    {
        $request->validate([
            'percent' => 'required|numeric|min:1|max:100',
            'label'   => 'required|string|max:50',
        ]);

        $quote = UnitQuotation::findOrFail($id);

        if (Invoice::where('id_unit_quotation', $quote->id)->whereNull('no_invoice')->exists()) {
            return back()->with('error', 'Masih ada invoice yang belum diterbitkan.');
        }

        $issuedPercent = Invoice::where('id_unit_quotation', $quote->id)
            ->whereNotNull('no_invoice')
            ->sum('percent');

        if ($issuedPercent >= 100) {
            return back()->with('error', 'Semua tagihan sudah 100% diterbitkan.');
        }

        $remainingPercent = 100 - $issuedPercent;
        $percentOfTotal   = round($remainingPercent * floatval($request->percent) / 100, 2);

        Invoice::create([
            'id_unit_quotation' => $quote->id,
            'no_po'             => $quote->po_number,
            'flag'              => 'Reftech',
            'pph'               => 0,
            'type'              => $request->label,
            'percent'           => $percentOfTotal,
        ]);

        return redirect()->route('unit-quotation.show', $id)
            ->with('success', 'Invoice selanjutnya berhasil diajukan.');
    }

    public function destroy($id)
    {
        $quote = UnitQuotation::findOrFail($id);
        $quote->delete();
        return response()->json(1);
    }

    public function addPayment(Request $request, $id)
    {
        UnitQuotation::findOrFail($id);

        $payment                    = new Payment();
        $payment->id_unit_quotation = $id;
        $payment->amount            = $request->amount;
        $payment->percent           = $request->percent;
        $payment->note              = $request->note;
        $payment->type              = $request->type;
        $payment->method            = $request->method;
        $isEscrow                   = ($request->method === 'Escrow');
        $payment->level             = $isEscrow ? 1 : 0;
        $payment->date              = now()->toDateString();
        if ($isEscrow) {
            $payment->date_confirm = now()->toDateString();
        }
        if ($request->type === 'Tempo') {
            $payment->tempo = $request->tempo;
        }
        $payment->save();

        if ($isEscrow) {
            Invoice::where('id_unit_quotation', $id)
                ->whereNotNull('no_invoice')
                ->update(['status_p' => 1]);
        }

        return redirect()->route('unit-quotation.show', $id)->with('success', 'Payment berhasil ditambahkan.');
    }

    public function proofPayment(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $quote   = UnitQuotation::findOrFail($payment->id_unit_quotation);

        $request->validate(['file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120']);

        $foto       = $request->file('file');
        $ext        = $foto->getClientOriginalExtension();
        $safeName   = preg_replace('/[^A-Za-z0-9\-]/', '_', $quote->no_quote);
        $payCount   = Payment::where('id_unit_quotation', $quote->id)->count();
        $fileName   = $safeName . '-' . $payCount . '.' . $ext;
        $subDir     = 'asset/payment/' . now()->format('Y/m');
        $uploadPath = public_path($subDir);
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        $foto->move($uploadPath, $fileName);

        $payment->file = $subDir . '/' . $fileName;
        $payment->save();

        return response()->json([
            'success'    => true,
            'file_url'   => asset($payment->file),
            'payment_id' => $payment->id,
        ]);
    }

    public function deleteProof($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->file && file_exists(public_path($payment->file))) {
            unlink(public_path($payment->file));
        }

        $payment->file = null;
        $payment->save();

        return response()->json(['success' => true]);
    }

    public function deletePayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return response()->json(1);
    }

    public function cancelPO(Request $request, $id)
    {
        $quote = UnitQuotation::findOrFail($id);

        $hasIssuedInvoice = Invoice::where('id_unit_quotation', $id)
            ->whereNotNull('no_invoice')
            ->exists();

        if ($hasIssuedInvoice) {
            // Needs Accounting approval
            $quote->cancel_request = 1;
            $quote->save();
            return redirect()->route('unit-quotation.show', $id)
                ->with('info', 'Permintaan cancel PO dikirim ke Accounting untuk persetujuan.');
        }

        // No invoice yet — cancel directly
        $quote->status         = 'negotiation';
        $quote->cancel_request = 0;
        $quote->po_number      = null;
        $quote->po_file        = null;
        $quote->po_received    = null;
        $quote->save();

        $quote->statusHistory()->create([
            'status' => 'negotiation',
            'note'   => 'PO dibatalkan oleh ' . Auth::user()->name,
        ]);

        return redirect()->route('unit-quotation.show', $id)
            ->with('success', 'PO berhasil dibatalkan.');
    }

    public function approveCancelPO($id)
    {
        $quote = UnitQuotation::findOrFail($id);
        $quote->status         = 'negotiation';
        $quote->cancel_request = 0;
        $quote->po_number      = null;
        $quote->po_file        = null;
        $quote->po_received    = null;
        $quote->save();

        $quote->statusHistory()->create([
            'status' => 'negotiation',
            'note'   => 'Cancel PO disetujui Accounting oleh ' . Auth::user()->name,
        ]);

        return redirect()->route('unit-quotation.show', $id)
            ->with('success', 'Cancel PO disetujui. Status kembali ke Negotiation.');
    }

    public function rejectCancelPO($id)
    {
        $quote = UnitQuotation::findOrFail($id);
        $quote->cancel_request = 0;
        $quote->save();

        return redirect()->route('unit-quotation.show', $id)
            ->with('warning', 'Permintaan cancel PO ditolak.');
    }

    private function createInvoiceRecords(UnitQuotation $quote, string $invoiceType = 'CT', $dpPercent = null): void
    {
        Invoice::create([
            'id_unit_quotation' => $quote->id,
            'no_po'             => $quote->po_number,
            'flag'              => 'Reftech',
            'pph'               => 0,
            'type'              => $invoiceType,
            'percent'           => $invoiceType === 'DP' ? floatval($dpPercent ?? 50) : 100,
        ]);
    }

    private function saveDetails(int $quoteId, array $items): void
    {
        foreach ($items as $i => $item) {
            $qty    = floatval($item['qty']   ?? 1);
            $price  = floatval($item['price'] ?? 0);
            $disc   = floatval($item['disc']  ?? 0);
            $amount = $qty * $price * (1 - $disc / 100);

            UnitQuotationDetail::create([
                'id_unit_quotation' => $quoteId,
                'type'              => $item['type'],
                'id_unit'           => ($item['type'] === 'unit') ? ($item['id_unit'] ?? null) : null,
                'id_fixed_asset'    => ($item['type'] === 'unit') ? ($item['id_fixed_asset'] ?? null) : null,
                'spec_visible'      => ($item['type'] === 'unit') ? ($item['spec_visible'] ?? null) : null,
                'label'             => $item['label'] ?? null,
                'description'       => $item['description'] ?? null,
                'qty'               => $qty,
                'info_qty'          => $item['info_qty'] ?? null,
                'price'             => $price,
                'disc'              => $disc,
                'amount'            => $amount,
                'sort_order'        => $i,
            ]);
        }
    }

    private function generateNoQuote(): string
    {
        $dateNow  = Carbon::now();
        $month    = $this->convertToRoman($dateNow->month);
        $userCode = Auth::user()->code ?? Auth::user()->name;
        $counter  = UnitQuotation::whereYear('created_at', $dateNow)->where('id_sales', Auth::id())->count() + 1;
        return str_pad($counter, 3, '0', STR_PAD_LEFT) . '-PU/BDG/RJO-' . $userCode . '/' . $month . '/' . $dateNow->year;
    }

    private function convertToRoman(int $month): string
    {
        $roman = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',
                  7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'];
        return $roman[$month];
    }
}
