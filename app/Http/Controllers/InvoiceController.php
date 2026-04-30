<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Delivery;
use App\Models\DetailQuotation;
use App\Models\Expense;
use App\Models\ExpenseInvoice;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\ProductOut;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\SubtitleQuotation;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requestContract = Contract::join('quotation as q', 'q.id', '=', 'contract.id_quotation')
            ->join('pic as p', 'p.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'p.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->where('contract.level', '0')
            ->count();
        $requestInvoice = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('invoice', 'invoice.id_quotation', '=', 'quotation.id')
            ->join('users', 'users.id', '=', 'quotation.id_sales')
            ->where('status', '100')
            ->whereNotNull('client.npwp')
            ->whereNotNull('quotation.po_file')
            ->whereNull('invoice.no_invoice')
            ->count();
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        return view('pages.accounting.invoice.index', compact('requestContract', 'requestInvoice', 'noSaleProspect'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $requestContract = Contract::join('quotation as q', 'q.id', '=', 'contract.id_quotation')
            ->join('pic as p', 'p.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'p.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->where('contract.level', '0')
            ->count();
        $requestInvoice = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('invoice', 'invoice.id_quotation', '=', 'quotation.id')
            ->join('users', 'users.id', '=', 'quotation.id_sales')
            ->where('status', '100')
            ->whereNotNull('client.npwp')
            ->whereNotNull('quotation.po_file')
            ->whereNull('invoice.no_invoice')
            ->count();
        $totalAmount = 0;
        $invoice = Invoice::find($id);
        $invoiceOrder = Invoice::where('id_quotation', $invoice->id_quotation)
            ->orderBy('id')
            ->pluck('id')
            ->search($id) + 1;
        $quote = Quotation::where('id', $invoice->id_quotation)->first();
        if ($quote->type != 'Sparepart') {
            $subQuote = SubtitleQuotation::with('detail')->where('id_quotation', $quote->id)->get();
        }
        // $return = ReturnQ::where('id_quotation', $invoice->id_quotation)->first();
        $dquote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $payments = Payment::where('id_quotation', $invoice->id_quotation)
        ->orderBy('id')
        ->take($invoiceOrder)
        ->get();
        // dd($payments);
        $expense = ExpenseInvoice::where('id_invoice', $id)->get();
        $totalExpense = ExpenseInvoice::where('id_invoice', $id)->sum('total');

        $totalPph23 = 0;
        $totalPph = 0;
        if ($quote->type == 'Sparepart') {
            foreach ($dquote as $product) {
                $pph = ($product->amount * $product->pph) / 100;
                $totalPph23 += $pph;
            }
        } else {
            foreach ($subQuote as $subtitle) {
                foreach ($subtitle->detail as $detail) {

                    $pph = ($detail->amount * $detail->pph) / 100;
                    $totalPph23 += $pph;
                }
            }
        }
        foreach ($payments as $payment) {
            $totalAmount += $payment->amount;
        }
        $totalPph = $totalPph23 + $invoice->pph;
        $hargaAfterExpanse = $quote->harga_total - $totalExpense;
        $remaining = $quote->harga_total - $totalAmount;
        $harga = Payment::where('id_quotation', $quote->id)->get();
        $priceDp = $this->terbilang($quote->harga_total * @$harga[0]->percent / 100 - $totalPph);
        $priceBp = $this->terbilang($quote->harga_total * @$harga[1]->percent / 100 - $totalPph);
        // $price = $this->terbilang(@$harga->amount - $totalPph);
        $fullPrice = $this->terbilang($quote->harga_total - $totalPph);
        $tax = ($quote->subtotal - $quote->diskon) * $quote->tax / 100;
        // $pph = $quote->subtotal * $invoice->pph / 100;
        // dd($quote->harga_total - $totalpph);
        $afterDisc = $quote->subtotal - $quote->diskon;

        $doTek = Delivery::where('id_invoice', $id)->where('type', 'teknisi')->whereNot('code', 'Manual')->get();
        $doEks = Delivery::where('id_invoice', $id)->where('type', 'ekspedisi')->whereNot('code', 'Manual')->get();
        $doTekMan = Delivery::where('id_invoice', $id)->where('type', 'teknisi')->where('code', 'Manual')->get();
        $doEksMan = Delivery::where('id_invoice', $id)->where('type', 'ekspedisi')->where('code', 'Manual')->get();
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        $pOut = ProductOut::where('invoice', $invoice->no_invoice)->first();
        $lastPayment = Payment::where('id_quotation', $quote->id)->orderByDesc('id')->first();
        // dd($doTekMan);
        if ($quote->type == 'Sparepart') {
            return view('pages.accounting.invoice.detail', compact('totalPph23', 'totalPph', 'lastPayment', 'requestContract', 'requestInvoice', 'hargaAfterExpanse', 'totalExpense', 'expense', 'noSaleProspect', 'pOut', 'quote', 'harga', 'dquote', 'priceDp', 'priceBp', 'fullPrice', 'tax', 'invoice', 'payments', 'remaining', 'afterDisc', 'doTek', 'doEks', 'doTekMan', 'doEksMan'));
        } else {
            return view('pages.accounting.invoice.detail', compact('totalPph23', 'totalPph', 'lastPayment', 'requestContract', 'requestInvoice', 'subQuote', 'hargaAfterExpanse', 'totalExpense', 'expense', 'noSaleProspect', 'pOut', 'quote', 'harga', 'dquote', 'priceDp', 'priceBp', 'fullPrice', 'tax', 'invoice', 'payments', 'remaining', 'afterDisc', 'doTek', 'doEks', 'doTekMan', 'doEksMan'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rule = [
            'invoice' => 'required',
            'payment' => 'required',
        ];
        $message = [
            'invoice.required' => 'Field invoice Wajib Diisi',
            'payment.required' => 'Field payment Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);
        $invoice->no_invoice = $request->invoice;
        $invoice->term = $request->payment;
        $invoice->invoiceTo = $quote->destination;
        $invoiceSave = $invoice->save();
        // $lastPayment = Payment::where('id_quotation', $quote->id)->orderByDesc('id')->first();
        // $lastPayment->due_date = Carbon::now()->addDays($request->due_date);
        // $lastPayment->save();
        if ($invoiceSave) {
            return redirect('/invoice/' . $id)->with('message', 'Invoice has been accepted');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        if ($invoice->type == "DP") {
            $allInvoice = Invoice::where('id_quotation', $invoice->id_quotation)->get();
            foreach ($allInvoice as $item) {
                $invoiceDel = $item->delete();
            }
        } else {
            $invoiceDel = $invoice->delete();
        }

        if ($invoiceDel) {
            return 1;
        } else {
            return 0;
        }
    }
    public function index_kojisha()
    {
        $requestContract = Contract::join('quotation as q', 'q.id', '=', 'contract.id_quotation')
            ->join('pic as p', 'p.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'p.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->where('contract.level', '0')
            ->count();
        $requestInvoice = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('invoice', 'invoice.id_quotation', '=', 'quotation.id')
            ->join('users', 'users.id', '=', 'quotation.id_sales')
            ->where('status', '100')
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('client.npwp')
            ->whereNull('invoice.no_invoice')
            ->count();
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        return view('pages.accounting.invoice.index-kojisha', compact('requestContract', 'requestInvoice', 'noSaleProspect'));
    }
    public function request()
    {
        $requestContract = Contract::join('quotation as q', 'q.id', '=', 'contract.id_quotation')
            ->join('pic as p', 'p.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'p.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->where('contract.level', '0')
            ->count();
        $requestInvoice = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('invoice', 'invoice.id_quotation', '=', 'quotation.id')
            ->join('users', 'users.id', '=', 'quotation.id_sales')
            ->where('status', '100')
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('client.npwp')
            ->whereNull('invoice.no_invoice')
            ->count();
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        return view('pages.accounting.invoice.index-request', compact('requestContract', 'requestInvoice', 'noSaleProspect'));
    }
    public function before_accept($id)
    {
        $requestContract = Contract::join('quotation as q', 'q.id', '=', 'contract.id_quotation')
            ->join('pic as p', 'p.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'p.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->where('contract.level', '0')
            ->count();
        $requestInvoice = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('invoice', 'invoice.id_quotation', '=', 'quotation.id')
            ->join('users', 'users.id', '=', 'quotation.id_sales')
            ->where('status', '100')
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('client.npwp')
            ->whereNull('invoice.no_invoice')
            ->count();
        $dateNow = Carbon::now();
        $year = $dateNow->year;
        $month = $dateNow->month;
        $monthCode = $this->convertToRoman($month);
        $lastInvoicePRef = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->leftJoin('payment as p', 'p.id_quotation', '=', 'quotation.id')
            ->where('quotation.tax', '11')
            ->where('invoice.flag', 'Reftech')
            ->whereNotNull('no_invoice')
            ->whereYear('invoice.created_at', $year)
            ->whereNot('p.method', 'Escrow')
            ->orderBy('invoice.no_invoice', 'desc')
            ->first(['invoice.*', 'quotation.tax']);
        $lastInvoiceNPRef = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->leftJoin('payment as p', 'p.id_quotation', '=', 'quotation.id')
            ->where('quotation.tax', '0')
            ->where('invoice.flag', 'Reftech')
            ->whereNotNull('no_invoice')
            ->whereYear('invoice.created_at', $year)
            ->whereNot('p.method', 'Escrow')
            ->orderBy('invoice.no_invoice', 'desc')
            ->first(['invoice.*', 'quotation.tax']);
        $lastInvoicePKoj = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->leftJoin('payment as p', 'p.id_quotation', '=', 'quotation.id')
            ->where('quotation.tax', '11')
            ->where('invoice.flag', 'Kojisha')
            ->whereNotNull('no_invoice')
            ->whereNotIn('quotation.id_sales', [16, 23])
            ->whereNot('p.method', 'Escrow')
            ->whereYear('invoice.created_at', $year)
            ->orderBy('invoice.no_invoice', 'desc')
            ->first(['invoice.*', 'quotation.tax']);
        $lastInvoiceNPKoj = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->leftJoin('payment as p', 'p.id_quotation', '=', 'quotation.id')
            ->where('quotation.tax', '0')
            ->where('invoice.flag', 'Kojisha')
            ->whereNotNull('no_invoice')
            ->whereNotIn('quotation.id_sales', [16, 23])
            ->whereNot('p.method', 'Escrow')
            ->whereYear('invoice.created_at', $year)
            ->orderBy('invoice.no_invoice', 'desc')
            ->first(['invoice.*', 'quotation.tax']);
        // dd($lastInvoicePKoj);
        function generateNextInvoiceNumber($lastInvoice, $defaultCode)
        {
            if ($lastInvoice) {
                // Ekstrak 3 digit numerik pertama dari no_invoice
                preg_match('/^\d{3}/', $lastInvoice->no_invoice, $matches);

                if (!empty($matches)) {
                    $lastNumber = $matches[0]; // Bagian numerik yang diekstrak, misal "004"
                    $newNumber = str_pad((int) $lastNumber + 1, 3, '0', STR_PAD_LEFT); // Increment dan pad angka

                    return $newNumber;
                } else {
                    // Jika tidak ada bagian numerik yang ditemukan, gunakan default
                    return $defaultCode;
                }
            } else {
                // Jika tidak ada invoice sebelumnya, mulai dari awal
                return $defaultCode;
            }
        }
        // Generate next invoice numbers
        $nextCodePR = generateNextInvoiceNumber($lastInvoicePRef, '001');
        $nextCodeNPR = generateNextInvoiceNumber($lastInvoiceNPRef, '001');
        $nextCodePK = generateNextInvoiceNumber($lastInvoicePKoj, '001');
        $nextCodeNPK = generateNextInvoiceNumber($lastInvoiceNPKoj, '001');
        // dd($nextCodePK);
        // dd($nextCodeNPK);

        $totalAmount = 0;
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);
        if ($quote->type != 'Sparepart') {
            $subQuote = SubtitleQuotation::with('detail')->where('id_quotation', $invoice->id_quotation)->get();
        }
        $dquote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $payments = Payment::where('id_quotation', $quote->id)->get();
        $lastPayment = Payment::where('id_quotation', $quote->id)->orderByDesc('id')->first();

        foreach ($payments as $payment) {
            $totalAmount += $payment->amount;
        }

        $remaining = $quote->harga_total - $totalAmount;
        $price = $this->terbilang($remaining);
        $tax = ($quote->subtotal - $quote->diskon) * $quote->tax / 100;
        // dd($price);
        if ($quote->type != 'Sparepart') {
            return view('pages.accounting.invoice.before-accept', compact('lastPayment', 'requestContract', 'requestInvoice', 'quote', 'subQuote', 'dquote', 'price', 'tax', 'invoice', 'payments', 'remaining', 'lastInvoicePRef', 'lastInvoiceNPRef', 'lastInvoicePKoj', 'lastInvoiceNPKoj', 'nextCodePR', 'nextCodeNPR', 'nextCodePK', 'nextCodeNPK', 'year', 'monthCode'));
        } else {
            return view('pages.accounting.invoice.before-accept', compact('lastPayment', 'requestContract', 'requestInvoice', 'quote', 'dquote', 'price', 'tax', 'invoice', 'payments', 'remaining', 'lastInvoicePRef', 'lastInvoiceNPRef', 'lastInvoicePKoj', 'lastInvoiceNPKoj', 'nextCodePR', 'nextCodeNPR', 'nextCodePK', 'nextCodeNPK', 'year', 'monthCode'));
        }
    }

    public function print_invoice($id)
    {
        $totalAmount = 0;
        $invoice = Invoice::find($id);
        $quote = Quotation::where('id', $invoice->id_quotation)->first();
        $dquote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $payments = Payment::where('id_quotation', $quote->id)->get();

        if ($quote->type != 'Sparepart') {
            $subQuote = SubtitleQuotation::with('detail')->where('id_quotation', $quote->id)->get();
            // dd($subQuote);
        }
        $totalPph = 0;

        if ($quote->type == 'Sparepart') {
            foreach ($dquote as $product) {
                $pph = ($product->amount * $product->pph) / 100;
                $totalPph += $pph;
            }
        } else {
            foreach ($subQuote as $subtitle) {
                foreach ($subtitle->detail as $detail) {

                    $pph = ($detail->amount * $detail->pph) / 100;
                    $totalPph += $pph;
                }
            }
        }
        foreach ($payments as $payment) {
            $totalAmount += $payment->amount;
        }

        $remaining = $quote->harga_total - $totalAmount;
        $harga = Payment::where('id_quotation', $quote->id)->get();
        $price = $this->terbilang(@$harga[0]->amount - $totalPph);
        $fullPrice = $this->terbilang($quote->harga_total - $totalPph);
        $priceDp = $this->terbilang($quote->harga_total * @$harga[0]->percent / 100 - $totalPph);
        $priceBp = $this->terbilang($quote->harga_total * @$harga[1]->percent / 100 - $totalPph);
        $tax = ($quote->subtotal - $quote->diskon) * $quote->tax / 100;
        // $pph = $quote->subtotal * $invoice->pph / 100;
        $afterDisc = $quote->subtotal - $quote->diskon;
        // dd($termncon);
        if ($quote->type == 'Sparepart') {
            return view("pages.accounting.invoice.detail-print", compact('harga', 'quote', 'priceDp', 'priceBp', 'dquote', 'tax', 'invoice', 'price', 'fullPrice', 'payments', 'remaining', 'afterDisc'));
        } else {
            return view("pages.accounting.invoice.detail-print", compact('subQuote', 'harga', 'priceDp', 'priceBp', 'quote', 'dquote', 'tax', 'invoice', 'price', 'fullPrice', 'payments', 'remaining', 'afterDisc'));
        }

    }

    public function hand_sign(Request $request, $id)
    {
        $photo = Invoice::find($id);
        $quote = Quotation::where('id', $photo->id_quotation)->first();
        $harga = Payment::where('id_quotation', $quote->id)->orderBy('created_at', 'DESC')->first();
        $jumlah = isset($harga) ? $harga->amount : $quote->harga_total;

        if ($photo->flag == "Reftech") {
            $photo->sign = $jumlah >= 5000000 ? 'asset/sign/reftech-m.jpeg' : 'asset/sign/reftech-nm.jpeg';
        } elseif ($photo->flag == "Kojisha") {
            $photo->sign = $jumlah >= 5000000 ? 'asset/sign/kojisha-m.jpeg' : 'asset/sign/kojisha-nm.jpeg';
        }

        // if ($request->hasFile('sign')) {
        //     $foto = $request->file('sign'); // Akses file sesuai dengan iterasi saat ini
        //     // Proses setiap file gambar
        //     $image_ext = $foto->getClientOriginalExtension();
        //     $image_name = Str::random(8);

        //     $upload_path = 'asset/invoice';
        //     $imagename = $upload_path . '/' . $image_name . '.' . $image_ext;

        //     // Pemrosesan gambar
        //     $img = Image::make($foto->path());
        //     $img->fit(800, 500, function ($constraint) {
        //         $constraint->aspectRatio();
        //     });
        //     $img->save($imagename);

        //     $photo['sign'] = $imagename;
        // }
        // dd($photo);

        $status = $photo->save();

        if ($status) {
            return 1;
        } else {
            0;
        }
    }
    public function delete_hand_sign($id)
    {
        $invoice = Invoice::find($id);

        // $delsign = File::delete($invoice->sign);
        // if ($delsign) {
        $invoice->sign = NULL;
        // }
        // dd($photo);
        $status = $invoice->save();

        if ($status) {
            return 1;
        } else {
            return 0;
        }
    }
    public function change_date(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        $invoice->date = $request->date;
        $invoice->invoiceTo = $request->destination;
        $invoiceSave = $invoice->save();

        if ($invoiceSave) {
            return redirect('/invoice/' . $id)->with('massage', 'Data telah terkirim');
        }
    }

    public function inputExpense(Request $request, $id)
    {
        // dd($request->all());
        $expense = new ExpenseInvoice();
        $expense->id_invoice = $id;
        $expense->desc = $request->desc;
        $expense->qty = $request->qty;
        $expense->price = $request->price;
        $expense->total = $request->price * $request->qty;
        $expenseSave = $expense->save();
        if ($expenseSave) {
            return redirect('/invoice/' . $id)->with('massage', 'Data telah terkirim');
        }
    }

    public function deleteExpense($id)
    {
        $expense = ExpenseInvoice::find($id);
        $expenseDel = $expense->delete();
        if ($expenseDel) {
            return 1;
        } else {
            return 0;
        }

    }

    public function do_ekspedisi($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();
        $client = Client::where('id', $quote->pic->id_client)->first();
        // dd($client);

        return view("pages.accounting.delivery.ekspedisi", compact('quote', 'invoice', 'dQuote', 'client'));
    }
    public function print_ekspedisi($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();

        return view("pages.accounting.delivery.ekspedisi-print", compact('quote', 'invoice', 'dQuote'));
    }

    public function do_teknisi($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();

        return view("pages.accounting.delivery.teknisi", compact('quote', 'invoice', 'dQuote'));
    }
    public function print_teknisi($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();

        return view("pages.accounting.delivery.teknisi-print", compact('quote', 'invoice', 'dQuote'));
    }
    public function form_ekspedisi(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        $invoice->dateDo = $request->date;
        $invoice->doTo = $request->destination;
        $status = $invoice->save();

        if ($status) {
            return redirect('/invoice/do_ekspedisi/' . $id)->with('massage', 'Data telah terkirim');
        }
    }
    public function form_teknisi(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        if ($request->check == '1') {
            $invoice->dateDo = NULL;
        } else {
            $invoice->dateDo = $request->date;
        }

        $invoice->doTo = $request->destination;
        $status = $invoice->save();

        if ($status) {
            return redirect('/invoice/do_teknisi/' . $id)->with('massage', 'Data telah terkirim');
        }
    }

    public function label_detail($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);

        return view("pages.accounting.label.detail", compact('quote', 'invoice'));
    }
    public function label_print($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);

        return view("pages.accounting.label.detail-print", compact('quote', 'invoice'));
    }

    public function add_pph(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();
        foreach ($dQuote as $item => $value) {
            $value->pph = $request->pph[$item];
            $status = $value->save();
        }
        if ($status) {
            return redirect('/invoice/' . $id)->with('massage', 'Data telah terkirim');
        }
    }
    public function add_pph_manual(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        $invoice->pph = $request->pph;
        $status = $invoice->save();
        if ($status) {
            return redirect('/invoice/' . $id)->with('massage', 'Data telah terkirim');
        }
    }
    public function add_pph_service(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        $subQuotes = SubtitleQuotation::with('detail')->where('id_quotation', $invoice->id_quotation)->get();
        $row = 0;
        foreach ($subQuotes as $subQuote) {
            foreach ($subQuote->detail as $index => $value) {
                $row++;
                $value->pph = $request->pph[$row]; // pastikan $request->pph ada dan indexnya sesuai
                $status = $value->save();
            }
        }
        if ($status) {
            return redirect('/invoice/' . $id)->with('massage', 'Data telah terkirim');
        }
    }

    public function delete_pph($id)
    {
        $invoice = Invoice::find($id);
        $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();
        foreach ($dQuote as $item => $value) {
            $value->pph = 0;
            $status = $value->save();
        }
        if ($status) {
            return 1;
        } else {
            return 0;
        }

    }
    public function delete_pph_manual($id)
    {
        $invoice = Invoice::find($id);
        $invoice->pph = 0;
        $status = $invoice->save();
        if ($status) {
            return 1;
        } else {
            return 0;
        }

    }
    public function delete_pph_service($id)
    {
        $invoice = Invoice::find($id);
        $subQuotes = SubtitleQuotation::with('detail')->where('id_quotation', $invoice->id_quotation)->get();

        foreach ($subQuotes as $subQuote) {
            foreach ($subQuote->detail as $index => $value) {
                $value->pph = 0;
                $status = $value->save();
            }
        }
        if ($status) {
            return 1;
        } else {
            return 0;
        }

    }
    public function change_desc(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();

        $checkedIds = (array) $request->input('checker', []);
        // dd($checkedIds);

        foreach ($dQuote as $value) {
            $value->view = in_array($value->id, $checkedIds) ? '1' : '0';
            $status = $value->save();
        }

        if ($status) {
            return redirect('/invoice/' . $id)->with('message', 'Data telah terkirim');
        } else {
            return redirect('/invoice/' . $id)->with('error', 'Terjadi kesalahan saat mengirim data');
        }
    }
    public function due_date(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);
        // dd($request->all());
        $lastPayment = Payment::where('id_quotation', $quote->id)->orderByDesc('id')->first();
        $lastPayment->overdue = $request->due_date;
        $lastPayment->due_date = Carbon::parse($request->date)->addDays($request->due_date);
        $paymentSave = $lastPayment->save();
        if ($paymentSave) {
            return redirect('/invoice/' . $id)->with('message', 'Data telah terkirim');
        }
    }
    public function confirm_payment(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        $invoice->status_p = 1;
        $invoice->note_p = $request->note;
        $invoiceSave = $invoice->save();
        if ($invoiceSave) {
            return redirect('/invoice/' . $id)->with('message', 'Data telah terkirim');
        }
    }
    public function undo_confirm_payment($id)
    {
        $invoice = Invoice::find($id);
        $invoice->status_p = 0;
        $invoice->note_p = null;
        $invoiceSave = $invoice->save();
        if ($invoiceSave) {
            return 1;
        } else {
            return 0;
        }
    }
    private function terbilang($number)
    {
        $number = abs($number);
        $words = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $result = "";

        if ($number < 12) {
            $result = " " . $words[$number];
        } elseif ($number < 20) {
            $result = $this->terbilang($number - 10) . " belas";
        } elseif ($number < 100) {
            $result = $this->terbilang((int) ($number / 10)) . " puluh " . $this->terbilang($number % 10);
        } elseif ($number < 200) {
            $result = " seratus " . $this->terbilang($number - 100);
        } elseif ($number < 1000) {
            $result = $this->terbilang((int) ($number / 100)) . " ratus " . $this->terbilang($number % 100);
        } elseif ($number < 2000) {
            $result = " seribu" . $this->terbilang($number - 1000);
        } elseif ($number < 1000000) {
            $result = $this->terbilang((int) ($number / 1000)) . " ribu " . $this->terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            $result = $this->terbilang((int) ($number / 1000000)) . " juta " . $this->terbilang($number % 1000000);
        } elseif ($number < 1000000000000) {
            $result = $this->terbilang((int) ($number / 1000000000)) . " milyar " . $this->terbilang($number % 1000000000);
        } elseif ($number < 1000000000000000) {
            $result = $this->terbilang((int) ($number / 1000000000000)) . " trilyun " . $this->terbilang($number % 1000000000000);
        }

        return ucwords(trim($result));
    }

    protected function convertToRoman($month)
    {
        $romanMonth = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        return $romanMonth[$month];
    }
}
