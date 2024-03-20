<?php

namespace App\Http\Controllers;

use App\Models\DetailProduct;
use App\Models\Product;
use App\Models\SerialProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commodity = Product::count();
        $dproduct = DetailProduct::count();
        return view('pages.warehouse.product.index', compact('commodity', 'dproduct'));
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
        $rule = [
            'commodity' =>
                'required',

            'dimension' =>
                'required',

            'description' =>
                'required',

            'note' =>
                'required',
        ];

        $message = [
            'commodity.required' => 'Field commodity Wajib Diisi',
            'dimension.required' => 'Field dimension Wajib Diisi',
            'description.required' => 'Field description Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        // dd($request);

        $product = new Product;
        $product->commodity = $request->commodity;
        $product->dimension = $request->dimension;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->go = $request->go;
        $product->stock = 0;
        $product->unit = $request->unit;
        $product->note = $request->note;
        $productSave = $product->save();

        if ($productSave) {
            return redirect('/product')->with('message', 'data telah ditambahkan');
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
        $product = Product::find($id);
        $details = DetailProduct::where('id_product', $id)->get();
        $serials = SerialProduct::where('id_product', $id)->get();
        return view('pages.warehouse.product.detail', compact('product', 'details', 'serials'));
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
            'commodity' =>
                'required',

            'dimension' =>
                'required',

            'description' =>
                'required',
        ];

        $message = [
            'commodity.required' => 'Field commodity Wajib Diisi',
            'dimension.required' => 'Field dimension Wajib Diisi',
            'description.required' => 'Field description Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        // dd($request);

        $product = Product::find($id);
        $product->commodity = $request->commodity;
        $product->dimension = $request->dimension;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->go = $request->go;
        $productSave = $product->save();

        if ($productSave) {
            return redirect('/product/' . $id)->with('message', 'data telah ditambahkan');
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
        $product = Product::find($id);

        if (!$product) {
            return redirect('/product/' . $id)->with('error', 'Produk tidak ditemukan');
        }

        $replacement = DetailProduct::where('id_product', $id)->get();
        $equivalents = SerialProduct::where('id_product', $id)->get();

        $delProduct = $product->delete();

        foreach ($replacement as $replace) {
            $delReplace = $replace->delete();
        }

        foreach ($equivalents as $equivalent) {
            $delEqui = $equivalent->delete();
        }

        if ($delProduct || $delReplace || $delEqui) {
            return 1;
        } else {
            return 0;
        }
    }

    public function storeReplacement(Request $request, $id)
    {

        $rule = [
            'replacement' =>
                'required',
        ];

        $message = [
            'replacement.required' => 'Field Replacement Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        // dd($request);

        $replace = new DetailProduct;
        $replace->id_product = $id;
        $replace->replacement = $request->replacement;
        $replace->modal = 0;
        $replace->stock = 0;
        $replaceSave = $replace->save();

        if ($replaceSave) {
            return redirect('/product/' . $id)->with('message', 'data telah ditambahkan');
        }
    }
    public function destroyReplacement($id)
    {
        $replacement = DetailProduct::find($id);
        
        $delReplace = $replacement->delete();

        if ($delReplace) {
            return 1;
        } else {
            return 0;
        }
    }
    public function storeEquivalent(Request $request, $id)
    {

        $rule = [
            'fxp' =>
                'required',

            'brand' =>
                'required',

            'pn' =>
                'required',

            'price' =>
                'required',
        ];

        $message = [
            'fxp.required' => 'Field fxp Wajib Diisi',
            'brand.required' => 'Field brand Wajib Diisi',
            'pn.required' => 'Field pn Wajib Diisi',
            'price.required' => 'Field price Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        // dd($request);

        $equiv = new SerialProduct;
        $equiv->id_product = $id;
        $equiv->fxp_parts = $request->fxp;
        $equiv->brand = $request->brand;
        $equiv->pn = $request->pn;
        $equiv->price = $request->price;
        $equiv->image = 'image';
        $equivSave = $equiv->save();

        if ($equivSave) {
            return redirect('/product/' . $id)->with('message', 'data telah ditambahkan');
        }
    }
    public function destroyEquivalent($id)
    {
        $equivalent = SerialProduct::find($id);
        $delEqui = $equivalent->delete();

        if ($delEqui) {
            return 1;
        } else {
            return 0;
        }
    }
}
