<?php

namespace App\Http\Controllers;

use App\Models\DetailPurchaseOrder;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class POController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.accounting.purchase.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('pages.accounting.purchase.form', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $supplier = Supplier::find($request->supplier);
        $purchase = new PurchaseOrder();
        $purchase->id_supplier = $request->supplier;
        $purchase->no_po = $request->no_po;
        $purchase->company = $supplier->supplier;
        $purchase->attn = $request->attn;
        $purchase->mobile = $request->mobile;
        $purchase->delivery = $request->delivery;
        $purchase->date = $request->date;
        $purchase->email = $supplier->email;
        $purchase->phone = $supplier->phone;
        $purchase->address = $supplier->address;
        $purchase->payment = $request->payment;
        $purchase->note = $request->note;
        $purchase->subtotal = $request->subtotal;
        $purchase->vat = $request->tax;
        $purchase->total = $request->harga_total;
        $purchaseSave = $purchase->save();
        if ($purchaseSave) {
            foreach ($request->product as $key => $value) {
                $dPurchase = new DetailPurchaseOrder();
                $dPurchase->id_purchase_order = $purchase->id;
                $dPurchase->product = $value;
                $dPurchase->qty = $request->qty[$key];
                $dPurchase->info_qty = $request->info_qty[$key];
                $dPurchase->price = $request->price[$key];
                $dPurchase->amount = $request->amount[$key];
                $dPurchaseSave = $dPurchase->save();
            }
        }
        if ($purchaseSave && $dPurchaseSave) {
            return redirect('purchase')->with('success', 'data berhasil ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase = PurchaseOrder::find($id);
        $dPurchase = DetailPurchaseOrder::where('id_purchase_order', $id)->get();
        $tax = $purchase->total * 11 / 100;
        return view('pages.accounting.purchase.detail', compact('purchase','dPurchase','tax'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = PurchaseOrder::find($id);
        $dPurchase = DetailPurchaseOrder::where('id_purchase_order', $id)->get();
        $purchaseDel = $purchase->delete();
        foreach ($dPurchase as $order) {
            $order->delete();
        }
        if ($purchaseDel) {
            return 1;
        } else {
            return 0;
        }
        
    }
    public function show_print($id)
    {
        $purchase = PurchaseOrder::find($id);
        $dPurchase = DetailPurchaseOrder::where('id_purchase_order', $id)->get();
        $tax = $purchase->total * 11 / 100;
        return view('pages.accounting.purchase.detail-print', compact('purchase','dPurchase','tax'));
    }
}
