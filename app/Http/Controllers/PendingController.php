<?php

namespace App\Http\Controllers;

use App\Models\ChangeStatus;
use App\Models\Comment;
use App\Models\DetailPendingPO;
use App\Models\DetailProduct;
use App\Models\DetailProductOut;
use App\Models\DetailQuotation;
use App\Models\DetailServiceQuotation;
use App\Models\Expanse;
use App\Models\Invoice;
use App\Models\PendingPO;
use App\Models\Product;
use App\Models\ProductOut;
use App\Models\PurchaseRequest;
use App\Models\Quotation;
use App\Models\Retur;
use App\Models\SerialProduct;
use App\Models\ServiceOrder;
use App\Models\SubtitleQuotation;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class PendingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->select(
                'pending_po.id',
                'u.name',
                'c.company',
                'i.no_po',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
            )
            ->get();
        // dd($data);
        $pendingPO = PendingPO::with('detail')->get();
        return view('pages.pending.index', compact('pendingPO'));
    }
    public function indexOrder()
    {
        $newCount = PendingPO::where('status', operator: 0)
            ->where('type', 'Non Project')
            ->count();
        $listCount = PendingPO::whereIn('pending_po.status', [1, 2, 3, 4])
            ->where('type', 'Non Project')
            ->count();
        $deliveryCount = PendingPO::where('pending_po.status', 5)
            ->where('type', 'Non Project')
            ->count();
        return view('pages.pending.order', compact('newCount', 'deliveryCount', 'listCount'));
    }
    public function indexList()
    {
        $newCount = PendingPO::where('status', operator: 0)
            ->where('type', 'Non Project')
            ->count();
        $listCount = PendingPO::whereIn('pending_po.status', [1, 2, 3, 4])
            ->where('type', 'Non Project')
            ->count();
        $deliveryCount = PendingPO::where('pending_po.status', 5)
            ->where('type', 'Non Project')
            ->count();
        return view('pages.pending.list', compact('newCount', 'deliveryCount', 'listCount'));
    }
    public function indexDelivery()
    {
        return view('pages.pending.delivery');
    }
    public function indexCompleted()
    {
        return view('pages.pending.completed');
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
        $pending = PendingPO::find($id);
        $quotation = Quotation::find($pending->id_quotation);
        $detQuotation = DetailQuotation::where('id_quotation', $pending->id_quotation)->get();
        $subQuote = SubtitleQuotation::with('detail')->where('id_quotation', $pending->id_quotation)->get();
        $invoice = Invoice::where('id_quotation', $quotation->id)->first();
        $activity = ChangeStatus::where('id_pending', $id)->with('comment')->get();
        $serial = SerialProduct::all();
        $resi = Expanse::where('id_pending', $id)->where('type', 'Resi')->first();
        $product = ProductOut::find($pending->id_product_out);
        $detProduct = DetailProductOut::where('id_product_out', $pending->id_product_out)->get();
        $return = Retur::where('id_pending', $id)->get();
        $allproductOut = ProductOut::leftJoin('pending_po', 'product_out.id', '=', 'pending_po.id_product_out')
            ->whereNull('pending_po.id_product_out')
            ->groupBy('product_out.id')
            ->select('product_out.*')
            ->get();
        // $allEquiv = SerialProduct::all();
        // $detProduct = DetailProductOut::where('id_product_out', $allproductOut[0]->id)->get();
        $purchase = PurchaseRequest::where('id_pending', $id)->get();
        // dd($detail);
        // dd($status->count());
        return view('pages.pending.detail', compact('purchase', 'serial', 'return', 'detProduct', 'activity', 'allproductOut', 'subQuote', 'pending', 'quotation', 'invoice', 'detQuotation', 'resi', 'product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pending = PendingPO::find($id);
        $quote = Quotation::find($pending->id_quotation);
        $Dquote = DetailQuotation::where('id_quotation', $pending->id_quotation)->get();
        $dPending = DetailPendingPO::where('id_pending', $id)->get();

        $fullRep = [];
        $no = 0;
        foreach ($Dquote as $item) {
            $equivalent = SerialProduct::find($item->id_equivalent);
            $fullRep[$no] = DetailProduct::where('id_product', $equivalent->id_product)->get();
            $no++;
        }
        // dd($fullRep);
        // dd($dPending);
        return view('pages.pending.form', compact('Dquote', 'fullRep', 'pending', 'quote', 'dPending', 'id'));
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
        // dd($request->all());
        $pending = PendingPO::find($id);
        $quote = Quotation::find($pending->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $dPending = DetailPendingPO::where('id_pending', $id)->get();

        // Hapus data lama
        foreach ($dPending as $item) {
            $item->delete();
        }

        // Simpan data baru
        $totalPendingQty = 0;
        foreach ($dQuote as $key => $value) {
            $itemPending = new DetailPendingPO;
            $itemPending->id_pending = $id;
            $itemPending->id_replacement = $request->replacement[$key];
            $itemPending->desc = $request->desc[$key];
            $itemPending->qty = $request->qty[$key];
            $itemPending->note = $request->note[$key];
            if ($value->qty == $request->qty[$key]) {
                $itemPending->status = 0;
            } else {
                $itemPending->status = 1;
            }
            $itemPending->save();
            $totalPendingQty += $request->qty[$key];
        }

        $totalQuoteQty = $dQuote->sum('qty');
        // $totalPendingQty = DetailPendingPO::where('id_pending', $id)->sum('qty');
        // dd($totalPendingQty);

        if ($totalPendingQty == $totalQuoteQty) {
            $pending->status = 2;
            $pending->save();
        } else {
            $pending->status = 1;
            $pending->save();
        }

        return redirect('/pending-po')->with('message', 'Pending PO telah dibuat');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function connect_out(Request $request, $id)
    {
        $pending = PendingPO::find($id);
        $quote = Quotation::find($pending->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $dPending = DetailPendingPO::where('id_pending', $id)->get();
        $cekstock = 0;
        foreach ($dPending as $detail) {
            $cekstock += $detail->bdg + $detail->bks;
        }
        if ($cekstock == 0) {
            foreach ($dQuote as $item) {
                $equivalent = SerialProduct::find($item->id_equivalent);
                $product = Product::find($equivalent->id_product);
                $product->pending_stock -= $item->qty;
                $product->stock += $item->qty;
                $productSave = $product->save();
            }
        }
        $pending->status = '6';
        $pending->id_product_out = $request->product;
        $pendingSave = $pending->save();
        if ($pendingSave) {
            return redirect('/pending-po/' . $id)->with('message', 'Product Out telah disambungkan');
        }
    }
    public function productEdit(Request $request, $id)
    {
        $pending = PendingPO::find($id);
        $quote = Quotation::find($pending->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $dPending = DetailPendingPO::where('id_pending', $id)->get();
        foreach ($request->status as $key => $value) {
            $product = Product::join('serial_product as sp', 'sp.id_product', '=', 'product.id')->where('sp.id', $dQuote[$key]->id_equivalent)->select('product.*')->first();
            // dd($dQuote[$key]->bdg);
            if ($dQuote[$key]->bdg != 0 || $dQuote[$key]->bks != 0) {
                $product->stock += $dQuote[$key]->bdg;
                $product->warehouse_stock += $dQuote[$key]->bks;
                $product->pending_stock -= $dQuote[$key]->bdg + $dQuote[$key]->bks;
                $dQuote[$key]->bdg = 0;
                $dQuote[$key]->bks = 0;
                $product->save();
            }
            $dQuote[$key]->status = $value;
            $dQuote[$key]->bdg = $request->bdg[$key];
            $dQuote[$key]->bks = $request->bks[$key];
            $dQuote[$key]->note = $request->note[$key];
            $dQuote[$key]->save();
            // dd($item->id_equivalent);
            if ($value == 2) {
                $product->stock -= $request->bdg[$key];
                $product->warehouse_stock -= $request->bks[$key];
                $product->pending_stock += $request->bdg[$key] + $request->bks[$key];
                $product->save();
            }
        }
        return redirect('/pending-po/' . $id)->with('message', 'Product Pending PO telah diedit');
    }
    public function projectEdit(Request $request, $id)
    {
        // dd($request->all());
        $pending = PendingPO::find($id);
        $quote = Quotation::find($pending->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $dPending = DetailPendingPO::where('id_pending', $id)->get();
        // dd($dPending);
        foreach ($request->status as $key => $value) {
            $product = Product::join('serial_product as sp', 'sp.id_product', '=', 'product.id')->where('sp.id', $request->equivalent[$key])->select('product.*')->first();
            // if ($dPending[$key]->bdg != 0 || $dPending[$key]->bks != 0) {
            $product->stock += $dPending[$key]->bdg;
            $product->warehouse_stock += $dPending[$key]->bks;
            $product->pending_stock -= $dPending[$key]->bdg + $dPending[$key]->bks;
            $dPending[$key]->bdg = 0;
            $dPending[$key]->bks = 0;
            // }
            $dPending[$key]->id_equivalent = $request->equivalent[$key];
            $dPending[$key]->status = $value;
            $dPending[$key]->bdg = $request->bdg[$key];
            $dPending[$key]->bks = $request->bks[$key];
            $dPending[$key]->note = $request->note[$key];
            $dPending[$key]->save();
            // dd($item->id_equivalent);
            if ($value == 2) {
                $product->stock -= $request->bdg[$key];
                $product->warehouse_stock -= $request->bks[$key];
                $product->pending_stock += $request->bdg[$key] + $request->bks[$key];
            }
            $product->save();
        }
        return redirect('/pending-po/' . $id)->with('message', 'Product Pending PO telah diedit');
    }
    public function statusEdit(Request $request, $id)
    {
        $pending = PendingPO::find($id);
        $pending->status = $request->status;
        $pending->save();

        switch ($request->status) {
            case 1:
                $note = 'On Check';
                break;
            case 2:
                $note = 'Reday Stock';
                break;
            case 3:
                $note = 'Kurang';
                break;
            case 4:
                $note = 'Pre order';
                break;
            case 5:
                $note = 'Delivery Process';
                break;
            case 6:
                $note = 'Done';
                break;
            default:
                $note = 'Cancel';
                break;
        }

        $status = new ChangeStatus();
        $status->id_pending = $pending->id;
        $status->status = $request->status;
        $status->note = $note;
        $status->date = Carbon::now();
        $status->save();
        if ($request->status == '7') {
            $quote = Quotation::find($pending->id_quotation);
            $Dquote = DetailQuotation::where('id_quotation', $pending->id_quotation)->get();
            foreach ($Dquote as $item) {
                $product = Product::join('serial_product as sp', 'sp.id', '=', 'product.id')->where('sp.id', $item->id_equivalent)->select('product.*')->first();
                $product->stock += $item->qty;
                $product->pending_stock -= $item->qty;
                $product->save();
            }
        }
        if ($request->status == '6') {
            if ($pending->type == 'Project') {
                return redirect('/pending-po/product-out-project/' . $id)->with('message', 'Status Product Pending PO telah diedit');
            } else {
                return redirect('/pending-po/product-out/' . $id)->with('message', 'Status Product Pending PO telah diedit');
            }
        } else {
            return redirect('/pending-po/' . $id)->with('message', 'Status Product Pending PO telah diedit');
        }
    }
    public function add_comment(Request $request, $id)
    {
        $stats = ChangeStatus::where('id_pending', $id)->orderByDesc('date')->first();
        $comment = new Comment();
        $comment->id_status = $stats->id;
        $comment->id_user = Auth::user()->id;
        $comment->date = Carbon::now();
        $comment->comment = $request->comment;
        $comment->level = '1';
        // $comment->type = 'quotation';
        $commentSave = $comment->save();
        if ($commentSave) {
            return redirect('/pending-po/' . $id)->with('message', 'Comment Pending PO telah dibuat');
        }
    }
    public function deliveryEdit(Request $request, $id)
    {
        $pending = PendingPO::find($id);
        $pending->delivery = $request->delivery;
        $pending->save();
        return redirect('/pending-po/' . $id)->with('message', 'Status Product Pending PO telah diedit');
    }
    public function pending_out($id)
    {
        $pending = PendingPO::find($id);
        $quote = Quotation::find($pending->id_quotation);
        $Dquote = DetailQuotation::where('id_quotation', $pending->id_quotation)->get();
        $dPending = DetailPendingPO::where('id_pending', $id)->whereNot('status', '7')->get();

        $fullRep = [];
        $no = 0;
        foreach ($Dquote as $item) {
            $equivalent = SerialProduct::find($item->id_equivalent);
            $fullRep[$no] = DetailProduct::where('id_product', $equivalent->id_product)->get();
            $no++;
        }
        return view('pages.pending.form', compact('Dquote', 'fullRep', 'pending', 'quote', 'dPending', 'id'));
    }
    public function pending_out_project($id)
    {
        $pending = PendingPO::find($id);
        $quote = Quotation::find($pending->id_quotation);
        // $Dquote = DetailServiceQuotation::where('id_quotation', $pending->id_quotation)->get();
        $dPending = DetailPendingPO::where('id_pending', $id)->whereNot('status', '7')->get();

        $fullRep = [];
        $fullEquiv = [];
        $no = 0;
        foreach ($dPending as $item) {
            $fullEquiv[$no] = SerialProduct::find($item->id_equivalent);
            $fullRep[$no] = DetailProduct::where('id_product', $fullEquiv[$no]->id_product)->get();
            $no++;
        }
        // dd($dPending);
        return view('pages.pending.form-project', compact('fullRep', 'fullEquiv', 'dPending', 'pending', 'quote', 'dPending', 'id'));
    }

    public function product_out(Request $request, $id)
    {
        $rule = [
            'invoice' => 'required',
            'detail_client' => 'required',
            'vers' => 'required',
            'date' => 'required',
            'shipping' => 'required',
            'note' => 'required',
        ];
        $message = [
            'invoice.required' => 'Field No Invoice Wajib Diisi',
            'detail_client.required' => 'Field Detail Client Wajib Diisi',
            'vers.required' => 'Field Offline / Online Wajib Diisi',
            'date.required' => 'Field Date Wajib Diisi',
            'shipping.required' => 'Field Shipping Wajib Diisi',
            'note.required' => 'Field Note Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        // dd($request->all());
        $pending = PendingPO::find($id);
        // Masukan Data ke Tabel Product Out
        $productOut = new ProductOut();
        $productOut->id_user = Auth::user()->id;
        $productOut->invoice = $request->invoice;
        $productOut->po = $request->po;
        $productOut->no_type = "1";
        $productOut->detail_client = $request->detail_client;
        $productOut->vers = $request->vers;
        $productOut->date = $request->date;
        $productOut->note = $request->note;
        $productOut->shipping = $request->shipping;
        $productOut->total = $request->total;
        $productOutSave = $productOut->save();
        $pending->id_product_out = $productOut->id;
        $pending->save();
        if ($productOutSave) {
            // Masukan Data Ke Tabel Detail Quotataion
            foreach ($request->equivalent as $item => $value) {
                $dProductIn = new DetailProductOut();
                $dProductIn->id_product_out = $productOut->id;
                $dProductIn->id_detail_product = $request->replacement[$item];
                $dProductIn->id_serial_product = $request->equivalent[$item];
                $dProductIn->qty = $request->qty[$item];
                $dProductIn->price = $request->price[$item];
                $dProductIn->amount = $request->amount[$item];
                $dProductIn->warehouse = $request->warehouse[$item];
                $productD = DetailProduct::where('id', $request->replacement[$item])->first();
                if ($request->warehouse[$item] == 'BDG') {
                    $productD->stock -= $request->qty[$item];
                } else {
                    $productD->warehouse_stock -= $request->qty[$item];
                }
                $productD->save();
                $product = Product::where('id', $productD->id_product)->first();
                // if ($request->warehouse[$item] == 'BDG') {
                $product->pending_stock -= $request->qty[$item];
                // } else {
                //     $product->pending_stock -= $request->qty[$item];
                //     $product->stock += $request->qty[$item];
                //     $product->warehouse_stock -= $request->qty[$item];
                // }
                $product->save();
                $dProductSave = $dProductIn->save();
            }
        }
        if ($dProductSave) {
            return redirect('/pending-po-done')->with('message', 'data telah di tambahkan');
        }
    }
    public function indexSOrder()
    {
        $newCount = PendingPO::where('status', operator: 0)
            ->count();
        $listCount = PendingPO::whereIn('pending_po.status', [1, 2, 3, 4])
            ->count();
        $readyCount = PendingPO::where('pending_po.status', 2)
            ->where('pending_po.type', 'Non Project')
            ->count();
        $jadwalCount = PendingPO::join('service_order as s', 's.id_sales_order', '=', 'pending_po.id')
            ->where('pending_po.status', 2)
            ->where('pending_po.type', 'Project')
            ->whereNull('s.date_schedule')
            ->distinct('pending_po.id')
            ->count('pending_po.id');
        $delayedCount = PendingPO::where('status', operator: 9)
            ->count();
        // dd($jadwalCount);
        $deliveryCount = PendingPO::where('pending_po.status', 5)
            ->count();
        $noInvoiceCountP = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->join('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->whereNotNull('i.no_invoice')
            ->where('pending_po.type', 'Project')
            ->where('pending_po.status', 6)
            ->count();
        $noInvoiceCountNP = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->join('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->whereNotNull('i.no_invoice')
            ->where('pending_po.type', 'Non Project')
            ->where('pending_po.status', 6)
            ->count();

        $schedules = ServiceOrder::join(DB::raw("(
        SELECT id_sales_order, MAX(id) as max_id
        FROM service_order
        GROUP BY id_sales_order
    ) so_max"), 'service_order.id', '=', 'so_max.max_id')
            ->join('pending_po as p', 'p.id', '=', 'service_order.id_sales_order')
            ->where('p.status', 2)
            ->select('service_order.*', 'p.no_pending', 'p.title')
            ->get();
        $orders = PendingPO::where('status', 2)->where('type', 'Project')->get();
        // dd($schedules);
        // $schedules = ServiceOrder::join('PendingPO as p', 'p.id', '=', 'service_order.id_sales_order')->where('p.status, 2')->get();
        return view('pages.sorder.index', compact(
            'schedules',
            'orders',
            'newCount',
            'listCount',
            'readyCount',
            'jadwalCount',
            'delayedCount',
            'deliveryCount',
            'noInvoiceCountP',
            'noInvoiceCountNP',
        ));
    }
    public function indexDone()
    {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->select(
                'pending_po.id',
                'u.name',
                'c.company',
                'i.no_po',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
            )
            ->get();
        // dd($data);
        return view('pages.pending.done');
    }
    public function indexProject()
    {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->select(
                'pending_po.id',
                'u.name',
                'c.company',
                'i.no_po',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
            )
            ->get();
        // dd($data);
        return view('pages.pending.project');
    }

    public function upload_resi(Request $request, $id)
    {
        // dd($request->all());
        $invoice = Invoice::find($id);
        $resi = new Expanse();
        if ($request->hasFile('file')) {
            $foto = $request->file('file');

            // Validasi
            $request->validate([
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            // Ekstensi
            $file_ext = $foto->getClientOriginalExtension();

            // Nama file aman
            // $sanitized_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $quote->no_quote);

            // Susun nama file
            $file_name = $request->no_track . '.' . $file_ext;

            // Path
            $upload_path = base_path('../public_html/asset/resi');
            $foto->move($upload_path, $file_name);

            // simpan di DB
            $resi->image = 'asset/resi/' . $file_name;
            $resi->id_pending = $id;
            $resi->kurir = $request->kurir;
            $resi->no_track = $request->no_track;
            $resi->charged = $request->charged;
            $resi->cost = $request->cost;
            $resi->type = "Resi";
            $resi->date = $request->date;
            $resiSave = $resi->save();
            if ($resiSave) {
                return redirect('/pending-po/' . $id)->with('message', 'data telah di tambahkan');
            }
        } else {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }
    }
    public function delete_resi($id)
    {
        $resi = Expanse::find($id);
        $delResi = $resi->delete();
        if ($delResi) {
            return 1;
        } else {
            return 0;
        }
    }
    public function schedule(Request $request, $id)
    {
        // dd($request->all());
        $schedule = new ServiceOrder();
        $schedule->id_sales_order = $id;
        $schedule->BA = '0';
        $schedule->SJ = '0';
        $schedule->note_schedule = $request->note;
        $schedule->date_schedule = $request->date_schedule;
        $schedulesave = $schedule->save();
        if ($schedulesave) {
            return redirect('/sales-order')->with('message', 'data telah di tambahkan');
        }
    }
    public function reschedule(Request $request, $id)
    {
        $schedule = ServiceOrder::find($id);
        $reschedule = new ServiceOrder();
        $reschedule->id_sales_order = $schedule->id_sales_order;
        $reschedule->BA = $schedule->BA;
        $reschedule->SJ = $schedule->SJ;
        $reschedule->note_schedule = $request->note;
        $reschedule->date_schedule = $request->date_schedule;
        $reschedulesave = $reschedule->save();
        if ($reschedulesave) {
            return redirect('/sales-order')->with('message', 'data telah di tambahkan');
        }
    }
    public function dokumentasi(Request $request, $id)
    {
        // dd($request->all());
        $schedule = ServiceOrder::find($id);
        $schedule->SJ = $request->has('SJ') ? '1' : '0';
        $schedule->BA = $request->has('BA') ? '1' : '0';
        $schedule->note_doc = $request->note;
        $schedulesave = $schedule->save();
        if ($schedule->SJ == '1' && $schedule->BA == '1') {
            $order = PendingPO::find($schedule->id_sales_order);
            $order->status = '9';
            $order->save();
        }
        if ($schedulesave) {
            return redirect('/sales-order')->with('message', 'data telah di tambahkan');
        }
    }
    public function returProduct(Request $request, $id)
    {
        $pending = PendingPO::find($id);
        $quote = Quotation::find($pending->id_quotation);
        // dd($pending);
        $pending->status = '8';
        $pending->save();
        $productOut = ProductOut::find($pending->id_product_out);
        $detProduct = DetailProductOut::where('id_product_out', $productOut->id)->get();
        foreach ($request->qty as $key => $value) {
            if ($value != 0) {
                $dproduct = DetailProduct::find($detProduct[$key]->id_detail_product);
                $product = Product::find($dproduct->id_product);
                $return = new Retur();
                $return->id_pending = $id;
                $return->id_replacement = $detProduct[$key]->id_detail_product;
                $return->qty = $value;
                $return->note = $request->note[$key] ?? '-';
                $return->status = 0;
                $return->date = Carbon::today();
                $returnSave = $return->save();
                // -- Stock
                $dproduct->stock += $value;
                $product->stock += $value;
                $dproduct->save();
                $product->save();
            }
        }
        if ($returnSave) {
            return redirect()->back()->with('success', 'Data Return Telah Ditambahkan');
        }
    }
    public function clearReturn($id)
    {
        $pending = PendingPO::find($id);
        $pending->status = '6';
        $pending->save();
        $return = Retur::where('id_pending', $id)->get();
        foreach ($return as $retur) {
            $dproduct = DetailProduct::find($retur->id_replacement);
            $product = Product::find($dproduct->id_product);
            $retur->status = 1;
            $returSave = $retur->save();
            // -- Stock
            $dproduct->stock -= $retur->qty;
            $product->stock -= $retur->qty;
            $dproduct->save();
            $product->save();
        }
        if ($returSave) {
            return 1;
        } else {
            return 0;
        }
    }
    public function donePending($id)
    {
        $pending = PendingPO::find($id);
        $pending->status = '6';
        $pendingSave = $pending->save();
        if ($pendingSave) {
            return 1;
        } else {
            return 0;
        }
    }
}
