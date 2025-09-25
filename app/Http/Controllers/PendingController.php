<?php

namespace App\Http\Controllers;

use App\Models\ChangeStatus;
use App\Models\Comment;
use App\Models\DetailPendingPO;
use App\Models\DetailProduct;
use App\Models\DetailProductOut;
use App\Models\DetailQuotation;
use App\Models\DetailServiceQuotation;
use App\Models\Invoice;
use App\Models\PendingPO;
use App\Models\Product;
use App\Models\ProductOut;
use App\Models\Quotation;
use App\Models\SerialProduct;
use App\Models\SubtitleQuotation;
use Auth;
use Carbon\Carbon;
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
    public function indexOrder(){
            $newCount = PendingPO::where('status', operator: 0)
                ->where('type', 'Non Project')
                ->count();
            $listCount = PendingPO::whereIn('pending_po.status', [1, 2, 3, 4])
                ->where('type', 'Non Project')
                ->count();
            $deliveryCount = PendingPO::where('pending_po.status', 5)
                ->where('type', 'Non Project')
                ->count();
        return view('pages.pending.order', compact('newCount','deliveryCount','listCount'));
    }
    public function indexList(){
            $newCount = PendingPO::where('status', operator: 0)
                ->where('type', 'Non Project')
                ->count();
            $listCount = PendingPO::whereIn('pending_po.status', [1, 2, 3, 4])
                ->where('type', 'Non Project')
                ->count();
            $deliveryCount = PendingPO::where('pending_po.status', 5)
                ->where('type', 'Non Project')
                ->count();
        return view('pages.pending.list', compact('newCount','deliveryCount','listCount'));
    }
    public function indexDelivery(){
        return view('pages.pending.delivery');
    }
    public function indexCompleted(){
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
        // dd($subQuote);
        // dd($status->count());
        return view('pages.pending.detail', compact('serial', 'activity', 'subQuote', 'pending', 'quotation', 'invoice', 'detQuotation'));
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
        foreach ($request->status as $key => $value) {
            $product = Product::join('serial_product as sp', 'sp.id_product', '=', 'product.id')->where('sp.id', $dPending[$key]->id_equivalent)->select('product.*')->first();
            if ($dPending[$key]->bdg != 0 || $dPending[$key]->bks != 0) {
                $product->stock += $request->bdg[$key];
                $product->warehouse_stock += $request->bks[$key];
                $product->pending_stock -= $request->bdg[$key] + $request->bks[$key];
                $dPending[$key]->bdg = 0;
                $dPending[$key]->bks = 0;
            }
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
                $product->save();
            }
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
        $dPending = DetailPendingPO::where('id_pending', $id)->get();

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
        $dPending = DetailPendingPO::where('id_pending', $id)->get();

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
}
