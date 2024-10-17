<?php

namespace App\Http\Controllers;

use App\Models\DetailProduct;
use App\Models\Product;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\SerialProduct;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();
        $comment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', first: 'o.id_status', operator: '=', second: 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            ->where('quotation.id_sales', Auth::id())
            ->where('o.level', '1')
            ->orderBy('o.date', 'DESC')
            ->get(['quotation.id as idQ', 'o.id as idC', 'o.id_user', 'o.comment', 'o.date', 'quotation.no_quote', 'u.name', 'u.image']);
        return view('pages.warehouse.product.index', compact('commodity', 'comment', 'leveledProspect', 'noSaleProspect', 'dproduct', 'sproduct', 'asset', 'revenue'));
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
        // Rules for validation
        $rule = [
            'commodity' => 'required',
            'dimension' => 'required',
            'description' => 'required',
            'note' => 'required',
        ];

        // Custom validation messages
        $message = [
            'commodity.required' => 'Field commodity Wajib Diisi',
            'dimension.required' => 'Field dimension Wajib Diisi',
            'description.required' => 'Field description Wajib Diisi',
            'note.required' => 'Field note Wajib Diisi',
        ];
        // Perform validation
        $this->validate($request, $rule, $message);

        $lastUnit = Unit::orderBy('id', 'desc')->first();
        $lastProduct = Product::orderBy('id', 'desc')->first();

        // Creating new product instance
        $product = new Product;
        if ($lastUnit->id > $lastProduct->id) {
            $product->id = $lastUnit->id + 1;
        } else {
            $product->id = $lastProduct->id + 1;
        }
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
        // Set note and current date
        $product->note = $request->note;
        $product->date = Carbon::now();

        // Save the product
        $productSave = $product->save();

        // Redirect based on product type
        if ($productSave) {
            return redirect('/product/' . $product->id)->with('message', 'Data telah ditambahkan');
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
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();
        $comment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', first: 'o.id_status', operator: '=', second: 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            ->where('quotation.id_sales', Auth::id())
            ->where('o.level', '1')
            ->orderBy('o.date', 'DESC')
            ->get(['quotation.id as idQ', 'o.id as idC', 'o.id_user', 'o.comment', 'o.date', 'quotation.no_quote', 'u.name', 'u.image']);
        return view('pages.warehouse.product.detail', compact('product', 'comment', 'details', 'leveledProspect', 'noSaleProspect', 'serials', 'allStock'));
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
        $replace->warehouse_stock = 0;
        $replace->stock = 0;
        $replaceSave = $replace->save();

        $previousUrl = url()->previous();
        if ($replaceSave) {
            return redirect($previousUrl)->with('success', 'Data berhasil disimpan!');
        }
    }
    public function updateReplacement(Request $request, $id)
    {
        $replace = DetailProduct::find($id);
        $replace->replacement = $request->replacement;
        if (Auth::user()->role == 'Admin') {
            $replace->modal = $request->modal;
        }
        $replaceSave = $replace->save();

        $previousUrl = url()->previous();
        if ($replaceSave) {
            return redirect($previousUrl)->with('success', 'Data berhasil disimpan!');
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
        $equiv->detail = $request->detail;
        $equiv->price = $request->price;
        $equiv->image = $request->image;
        $equivSave = $equiv->save();
        
        $previousUrl = url()->previous();
        if ($equivSave) {
            return redirect($previousUrl)->with('success', 'Data berhasil disimpan!');
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

        $previousUrl = url()->previous();
        if ($equivSave) {
            return redirect($previousUrl)->with('success', 'Data berhasil disimpan!');
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
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        return view('pages.warehouse.master.index', compact('commodity', 'dproduct', 'noSaleProspect', 'sproduct'));
    }
    public function indexUnit()
    {
        return view('pages.warehouse.unit.index');
    }
    public function showUnit($id)
    {
        $product = Product::find($id);
        $allStock = $product->stock + $product->warehouse_stock;
        $details = DetailProduct::where('id_product', $id)->get();
        $serials = SerialProduct::where('id_product', $id)->get();
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();
        $comment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', first: 'o.id_status', operator: '=', second: 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            ->where('quotation.id_sales', Auth::id())
            ->where('o.level', '1')
            ->orderBy('o.date', 'DESC')
            ->get(['quotation.id as idQ', 'o.id as idC', 'o.id_user', 'o.comment', 'o.date', 'quotation.no_quote', 'u.name', 'u.image']);
        return view('pages.warehouse.unit.detail', compact('product', 'comment', 'details', 'leveledProspect', 'noSaleProspect', 'serials', 'allStock'));
    }
    
}
