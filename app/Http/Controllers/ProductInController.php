<?php

namespace App\Http\Controllers;

use App\Models\DetailProduct;
use App\Models\DetailProductIn;
use App\Models\Product;
use App\Models\ProductIn;
use Illuminate\Http\Request;

class ProductInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $product = ProductIn::all();
        // dd($product);
        return view('pages.warehouse.product-in.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $detProduct = DetailProduct::all();
        return view('pages.warehouse.product-in.form', compact('detProduct'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule = [
            'invoice' => 'required',
            'suplier' => 'required',
            'date' => 'required',
            'note' => 'required',
        ];
        $message = [
            'invoice.required' => 'Field No Invoice Wajib Diisi',
            'suplier.required' => 'Field Suplier Wajib Diisi',
            'date.required' => 'Field Date Wajib Diisi',
            'note.required' => 'Field Note Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        // dd($request->all());
        // Masukan Data ke Tabel Quotataion
        $productIn = new ProductIn();
        $productIn->invoice = $request->invoice;
        $productIn->supplier = $request->suplier;
        $productIn->date = $request->date;
        $productIn->note = $request->note;
        $productIn->shipping = $request->shipping;
        $productIn->total = $request->total;
        $productInSave = $productIn->save();
        if ($productInSave) {
            // Masukan Data Ke Tabel Detail Quotataion
            foreach ($request->replacement as $item => $value) {
                $dProductIn = new DetailProductIn;
                $dProductIn->id_product_in = $productIn->id;
                $dProductIn->id_detail_product = $request->replacement[$item];
                $dProductIn->qty = $request->qty[$item];
                $dProductIn->modal = $request->price[$item];
                $dProductIn->amount = $request->amount[$item];
                $productD = DetailProduct::where('id', $request->replacement[$item])->first();
                $productD->stock = $productD->stock + $request->qty[$item];
                $productD->modal = $request->price[$item];
                $productD->save();
                $product = Product::where('id', $productD->id_product)->first();
                $product->stock = $product->stock + $request->qty[$item];
                // dd($product);
                $product->save();
                $dProductSave = $dProductIn->save();
            }
        }
        if ($dProductSave) {
            return redirect('/product-in')->with('message', 'data telah di tambahkan');
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
        $product = ProductIn::find($id);
        $detail = DetailProductIn::where('id_product_in', $id)->get();
        return view('pages.warehouse.product-in.detail', compact('product', 'detail'));
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
        $product = ProductIn::find($id);
        $dProductIn = DetailProductIn::where('id_product_in', $id)->get();
        // dd($dProductIn);
        $total = 0;
        foreach ($dProductIn as $item) {
            $item->detailProduct->modal = $request->modal[$item->id]; 
            $item->modal = $request->modal[$item->id];
            $item->amount = $request->modal[$item->id] * $item->qty;
            $detailSave = $item->save();
            $item->detailProduct->save();
            $total += $item->amount;
        }
        if($detailSave){
            $product->total = $total + $product->shipping;
            $productSave = $product->save();
        }
        if ($productSave) {
            return redirect('/product-in/'. $id)->with('message', 'data telah di tambahkan');
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
        $product = ProductIn::find($id);
        $detail = DetailProductIn::where('id_product_in', $id)->get();

        $delProductIn = $product->delete();

        foreach ($detail as $dProductIn) {
            $delDetProductIn = $dProductIn->delete();
        }

        if ($delProductIn || $delDetProductIn) {
            return 1;
        } else {
            return 0;
        }
    }
}
