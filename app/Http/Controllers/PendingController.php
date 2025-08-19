<?php

namespace App\Http\Controllers;

use App\Models\DetailPendingPO;
use App\Models\DetailProduct;
use App\Models\DetailQuotation;
use App\Models\Invoice;
use App\Models\PendingPO;
use App\Models\Quotation;
use App\Models\SerialProduct;
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
        $invoice = Invoice::where('id_quotation', $quotation->id)->first();
        return view('pages.pending.detail', compact('pending', 'quotation', 'invoice', 'detQuotation'));
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
        }else {
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
    public function productEdit(Request $request, $id){
        $pending = PendingPO::find($id);
        $quote = Quotation::find($pending->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $dPending = DetailPendingPO::where('id_pending', $id)->get();
        foreach ($request->status as $key => $value) {
            $dQuote[$key]->status -> $value->status;
            $dQuote[$key]->note -> $value->note;
            $dQuote[$key]->save();
        }
        return redirect('/pending-po')->with('message', 'Product Pending PO telah diedit');
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
}
