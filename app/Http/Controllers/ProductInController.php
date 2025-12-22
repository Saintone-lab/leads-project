<?php

namespace App\Http\Controllers;

use App\Models\DetailProduct;
use App\Models\DetailProductIn;
use App\Models\Product;
use App\Models\ProductIn;
use App\Models\Prospect;
use App\Models\Retur;
use App\Models\Supplier;
use Carbon\Carbon;
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
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        return view('pages.warehouse.product-in.index', compact('noSaleProspect'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $detProduct = DetailProduct::join('product', 'detail_product.id_product', '=', 'product.id')->get('detail_product.*');
        return view('pages.warehouse.product-in.form', compact('detProduct', 'suppliers'));
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
            'date' => 'required',
            'note' => 'required',
        ];
        $message = [
            'invoice.required' => 'Field No Invoice Wajib Diisi',
            'date.required' => 'Field Date Wajib Diisi',
            'note.required' => 'Field Note Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        // dd($request->all());
        $supplier = Supplier::find($request->supplier);
        // Masukan Data ke Tabel Quotataion
        $productIn = new ProductIn();
        $productIn->no_do = NULL;
        $productIn->invoice = $request->invoice;
        $productIn->id_supplier = $request->supplier;
        // $productIn->supplier = $request->suplier;
        $productIn->info = $supplier->info;
        $productIn->date = $request->date;
        $productIn->date_invoice = $request->date_invoice;
        $productIn->subtotal = $request->subtotal;
        $productIn->total_no_tax = $request->total_no_tax;
        $productIn->tax = $request->tax;
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
                $dProductIn->disc = $request->disc[$item];
                $dProductIn->amount = $request->amount[$item];
                $dProductIn->warehouse = $request->warehouse[$item];
                $productD = DetailProduct::where('id', $request->replacement[$item])->first();
                // dd($productD);
                $productD->modal = ((($productD->stock + $productD->warehouse_stock) * $productD->modal) + ($request->qty[$item] * $request->price[$item])) / (($productD->stock + $productD->warehouse_stock) + $request->qty[$item]);
                if ($request->warehouse[$item] == 'BDG') {
                    $productD->stock = $productD->stock + $request->qty[$item];
                } else {
                    $productD->warehouse_stock = $productD->warehouse_stock + $request->qty[$item];
                }
                $productD->save();
                $product = Product::where('id', $productD->id_product)->first();
                if ($request->warehouse[$item] == 'BDG') {
                    $product->stock = $product->stock + $request->qty[$item];
                } else {
                    $product->warehouse_stock = $product->warehouse_stock + $request->qty[$item];
                }
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
        $tax = $product->subtotal * $product->tax / 100;
        $detail = DetailProductIn::where('id_product_in', $id)->get();
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        $return = Retur::where('id_product_in', $id)->get();
        return view('pages.warehouse.product-in.detail', compact('product', 'detail', 'noSaleProspect', 'tax','return'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $productIn = ProductIn::find($id);
        $dProductIn = DetailProductIn::where('id_product_in', $id)->get();
        $suppliers = Supplier::all();
        // dd($dProductIn);
        return view('pages.warehouse.product-in.invoicing', compact('suppliers','productIn', 'dProductIn'));
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
        // dd($request->all());
        $total = 0;
        foreach ($dProductIn as $item) {
            $item->detailProduct->modal = $request->modal[$item->id];
            $item->modal = $request->modal[$item->id];
            // $item->disc = $request->disc[$item->id];
            $item->amount = $request->modal[$item->id] * $item->qty;
            $detailSave = $item->save();
            $item->detailProduct->save();
            $total += $item->amount;
        }
        if ($detailSave) {
            $taxed = $total * $product->tax;
            $product->subtotal = $total;
            $product->total_no_tax = $total + $product->shipping;
            $product->total = $total + $taxed + $product->shipping;
            $productSave = $product->save();
        }
        if ($productSave) {
            return redirect('/product-in/' . $id)->with('message', 'data telah di tambahkan');
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
            if ($dProductIn->warehouse == 'BDG') {
                $dProductIn->detailProduct->stock = $dProductIn->detailProduct->stock - $dProductIn->qty;
            } else {
                $dProductIn->detailProduct->warehouse_stock = $dProductIn->detailProduct->warehouse_stock - $dProductIn->qty;
            }
            $dProductIn->detailProduct->save();
            if ($dProductIn->warehouse == 'BDG') {
                $dProductIn->detailProduct->product->stock = $dProductIn->detailProduct->product->stock - $dProductIn->qty;
            } else {
                $dProductIn->detailProduct->product->warehouse_stock = $dProductIn->detailProduct->product->warehouse_stock - $dProductIn->qty;
            }
            $dProductIn->detailProduct->product->save();
            $delDetProductIn = $dProductIn->delete();
        }

        if ($delProductIn || $delDetProductIn) {
            return 1;
        } else {
            return 0;
        }
    }

    public function logistic_store(Request $request)
    {

        $rule = [
            'no_do' => 'required',
            'date' => 'required',
        ];
        $message = [
            'no_do.required' => 'Field No DO Wajib Diisi',
            'date.required' => 'Field Date Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        // dd($request->all());
        $supplier = Supplier::find($request->supplier);
        // Masukan Data ke Tabel Quotataion
        $productIn = new ProductIn();
        $productIn->no_do = $request->no_do;
        $productIn->invoice = null;
        // $productIn->id_supplier = null;
        $productIn->id_supplier = $request->supplier;
        $productIn->supplier = null;
        // $productIn->info = $supplier->info;
        $productIn->info = $request->info;
        $productIn->date = $request->date;
        $productIn->date_invoice = null;
        $productIn->subtotal = null;
        $productIn->total_no_tax = null;
        $productIn->tax = null;
        $productIn->note = null;
        $productIn->shipping = null;
        $productIn->total = null;
        $productInSave = $productIn->save();
        if ($productInSave) {
            // Masukan Data Ke Tabel Detail Quotataion
            foreach ($request->replacement as $item => $value) {
                $dProductIn = new DetailProductIn;
                $dProductIn->id_product_in = $productIn->id;
                $dProductIn->id_detail_product = $request->replacement[$item];
                $dProductIn->qty = $request->qty[$item];
                $dProductIn->modal = null;
                $dProductIn->amount = null;
                $dProductIn->warehouse = $request->warehouse[$item];
                $productD = DetailProduct::where('id', $request->replacement[$item])->first();
                // $productD->modal = ((($productD->stock + $productD->warehouse_stock) * $productD->modal) + ($request->qty[$item] * $request->price[$item])) / (($productD->stock + $productD->warehouse_stock) + $request->qty[$item]);
                if ($request->warehouse[$item] == 'BDG') {
                    $productD->stock = $productD->stock + $request->qty[$item];
                } else {
                    $productD->warehouse_stock = $productD->warehouse_stock + $request->qty[$item];
                }
                $productD->save();
                $product = Product::where('id', $productD->id_product)->first();
                if ($request->warehouse[$item] == 'BDG') {
                    $product->stock = $product->stock + $request->qty[$item];
                } else {
                    $product->warehouse_stock = $product->warehouse_stock + $request->qty[$item];
                }
                // dd($product);
                $product->save();
                $dProductSave = $dProductIn->save();
            }
        }
        if ($dProductSave) {
            return redirect('/product-in')->with('message', 'data telah di tambahkan');
        }
    }
    public function invoicing(Request $request, $id)
    {
        $productIn = ProductIn::find($id);
        $dProductIn = DetailProductIn::where('id_product_in', $id)->get();
        dd($request->all());
        $rule = [
            'invoice' => 'required',
            // 'suplier' => 'required',
            // 'date_invoice' => 'required',
            'note' => 'required',
        ];
        $message = [
            'invoice.required' => 'Field No Invoice Wajib Diisi',
            // 'suplier.required' => 'Field Suplier Wajib Diisi',
            // 'date_invoice.required' => 'Field Date Wajib Diisi',
            'note.required' => 'Field Note Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        // dd($request->all());
        // Masukan Data ke Tabel Quotataion
        $productIn->invoice = $request->invoice;
        $productIn->id_supplier = $request->supplier;
        // $productIn->supplier = $request->suplier;
        $productIn->date_invoice = $request->date_invoice;
        $productIn->subtotal = $request->subtotal;
        $productIn->total_no_tax = $request->total_no_tax;
        $productIn->tax = $request->tax;
        $productIn->note = $request->note;
        $productIn->shipping = $request->shipping;
        $productIn->total = $request->total;
        $productInSave = $productIn->save();
        if ($productInSave) {
            // Masukan Data Ke Tabel Detail Quotataion
            foreach ($dProductIn as $item => $value) {
                $value->modal = $request->price[$item];
                $value->amount = $request->amount[$item];
                $value->disc = $request->disc[$item];
                $dProductSave = $value->save();
            }
        }
        if ($dProductSave) {
            return redirect('/product-in')->with('message', 'data telah di tambahkan');
        }
    }

    public function productIn_print($id)
    {
        $product = ProductIn::find($id);
        $detail = DetailProductIn::where('id_product_in', $product->id)->get();
        $tax = $product->total_no_tax * $product->tax / 100;
        return view('pages.warehouse.product-in.detail-print', compact('product', 'detail', 'tax'));
    }

    public function indexSupplier()
    {
        return view('pages.warehouse.supplier.index');
    }
    public function detailSupplier($id)
    {
        $supplier = Supplier::find($id);
        return view('pages.warehouse.supplier.detail', compact('supplier'));
    }

    public function storeSupplier(Request $request)
    {
        $supplier = new Supplier();
        $supplier->supplier = $request->supplier;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->area = $request->area;
        $supplier->address = $request->address;
        $supplier->npwp = $request->npwp;
        $supplier->info = $request->info;
        $supplierSave = $supplier->save();
        if ($supplierSave) {
            return redirect()->back()->with('success', 'Supplier berhasil ditambahkan!');
        }
    }
    public function deleteSupplier($id)
    {
        $supplier = Supplier::find($id);
        $supplierDel = $supplier->delete();
        if ($supplierDel) {
            return 1;
        } else {
            return 0;
        }
    }
    public function updateSupplier(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        $supplier->supplier = $request->supplier;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->area = $request->area;
        $supplier->address = $request->address;
        $supplier->npwp = $request->npwp;
        $supplier->info = $request->info;
        $supplierSave = $supplier->save();
        if ($supplierSave) {
            return redirect()->back()->with('success', 'Supplier berhasil ditambahkan!');
        }
    }
    public function acceptIn($id)
    {
        $product = ProductIn::find($id);
        $product->accept = '1';
        $productSave = $product->save();
        if ($productSave) {
            return 1;
        } else {
            return 0;
        }
    }
    public function return(Request $request, $id){
        $detProduct = DetailProductIn::where('id_product_in', $id)->get();
        foreach ($request->qty as $key => $value) {
            if ($value != 0) {
                // dd($key);
                $return = new Retur();
                $return->id_product_in = $id;
                $return->id_replacement = $detProduct[$key]->id_detail_product;
                $return->qty = $value;
                $return->note = $request->note[$key] ?? '-';
                $return->status = 0;
                $return->date = Carbon::today();
                $returnSave = $return->save();
            }
        }
        if ($returnSave) {
            return redirect()->back()->with('success','Data Return Telah Ditambahkan');
        }
    }
    public function clearReturn($id)
    {
        $return = Retur::find($id);
        $return->status = 1;
        $returnSave = $return->save();
        if ($returnSave) {
            return 1;
        } else {
            return 0;
        }
    }
}
