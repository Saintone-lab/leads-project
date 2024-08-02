<?php

namespace App\Http\Controllers;

use App\Models\DetailProduct;
use App\Models\Product;
use App\Models\SerialProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $sproduct = SerialProduct::count();
        $replace = DetailProduct::all();
        $asset = $replace->sum(function ($replacement) {
            return $replacement->modal * $replacement->stock;
        });
        $equiv = SerialProduct::join('product', 'product.id', '=', 'serial_product.id_product')->groupBy('product.id')->get();
        $revenue = $equiv->sum(function ($equivalent) {
            return $equivalent->price * $equivalent->stock;
        });
        // dd($revenue);

        return view('pages.warehouse.product.index', compact('commodity', 'dproduct', 'sproduct', 'asset', 'revenue'));
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
        $product->detail_desc = $request->detail_desc;
        $product->category = $request->category;
        $product->go = $request->go;
        $product->weight = $request->weight;
        $product->first_stock = 0;
        $product->warehouse_stock = 0;
        $product->stock = 0;
        $product->unit = $request->unit;
        $product->note = $request->note;
        $product->date = Carbon::today();
        $productSave = $product->save();

        if ($productSave) {
            return redirect('/product/' . $product->id)->with('message', 'data telah ditambahkan');
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
        $allStock = $product->stock + $product->warehouse_stock;
        $details = DetailProduct::where('id_product', $id)->get();
        $serials = SerialProduct::where('id_product', $id)->get();
        return view('pages.warehouse.product.detail', compact('product', 'details', 'serials', 'allStock'));
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
        $product->detail_desc = $request->detail_desc;
        $product->category = $request->category;
        $product->unit = $request->unit;
        $product->weight = $request->weight;
        $product->go = $request->go;
        $product->note = $request->note;
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
    public function updateReplacement(Request $request, $id)
    {
        $rule = [
            'modal' =>
                'required',
        ];

        $message = [
            'modal.required' => 'Field modal Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        // dd($request->all());
        $replace = DetailProduct::find($id);
        $replace->modal = $request->modal;
        $replaceSave = $replace->save();

        if ($replaceSave) {
            return redirect('/product/' . $replace->id_product)->with('message', 'data telah ditambahkan');
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
            'image' =>
                'required',

            'brand' =>
                'required',

            'pn' =>
                'required',

            'price' =>
                'required',
        ];

        $message = [
            'image.required' => 'Field Image Wajib Diisi',
            'brand.required' => 'Field brand Wajib Diisi',
            'pn.required' => 'Field pn Wajib Diisi',
            'price.required' => 'Field price Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        // dd($request);

        $equiv = new SerialProduct;
        $equiv->id_product = $id;
        $equiv->brand = $request->brand;
        $equiv->fxp_parts = "-";
        $equiv->pn = $request->pn;
        $equiv->price = $request->price;
        $equiv->image = $request->image;
        $equivSave = $equiv->save();

        if ($equivSave) {
            return redirect('/product/' . $id)->with('message', 'data telah ditambahkan');
        }
    }
    public function updateEquivalent(Request $request, $id)
    {

        $rule = [
            'image' =>
                'required',

            'brand' =>
                'required',

            'pn' =>
                'required',

            'price' =>
                'required',
        ];

        $message = [
            'image.required' => 'Field Image Wajib Diisi',
            'brand.required' => 'Field brand Wajib Diisi',
            'pn.required' => 'Field pn Wajib Diisi',
            'price.required' => 'Field price Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        // dd($request);

        $equiv = SerialProduct::find($id);
        $equiv->brand = $request->brand;
        $equiv->fxp_parts = "-";
        $equiv->pn = $request->pn;
        $equiv->price = $request->price;
        $equiv->image = $request->image;
        $equivSave = $equiv->save();

        if ($equivSave) {
            return redirect('/product/' . $equiv->id_product)->with('message', 'data telah diupdate');
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

    public function indexMaster()
    {
        $commodity = Product::count();
        $dproduct = DetailProduct::count();
        $sproduct = SerialProduct::count();
        return view('pages.warehouse.master.index', compact('commodity', 'dproduct', 'sproduct'));
    }
}
