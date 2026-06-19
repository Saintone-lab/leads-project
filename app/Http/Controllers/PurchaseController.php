<?php

namespace App\Http\Controllers;

use App\Models\ChangeStatus;
use App\Models\DetailProduct;
use App\Models\DetailProductIn;
use App\Models\DetailQuotation;
use App\Models\Invoice;
use App\Models\PendingPO;
use App\Models\Product;
use App\Models\ProductIn;
use App\Models\PurchaseRequest;
use App\Models\Quotation;
use App\Models\SerialProduct;
use App\Models\SubtitleQuotation;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('pages.warehouse.purchase.index');
    }
    public function store(Request $request, $id)
    {
        // dd($request->all());
        $pending = PendingPO::find($id);
        $quotation = Quotation::find($pending->id_quotation);

        $dQuote = DetailQuotation::where('id_quotation', $quotation->id)->get();

        $success = false;

        foreach ($request->qty as $key => $value) {
            if ($value != 0) {

                $purchase = new PurchaseRequest();
                $purchase->id_pending = $id;
                $purchase->id_equivalent = $dQuote[$key]->id_equivalent;
                $purchase->qty = $request->qty[$key];   // perbaikan
                $purchase->note = $request->note[$key]; // perbaikan
                $purchase->status = '0';
                $purchase->date = Carbon::now();

                if ($purchase->save()) {
                    $success = true;
                }
            }
        }

        if ($success) {
            return redirect('pending-po/' . $id)->with('success', 'Purchase Request telah dibuat');
        }
    }
    public function store_project(Request $request, $id)
    {
        // dd($request->all());
        $success = false;

        $purchase = new PurchaseRequest();
        $purchase->id_pending = $id;
        $purchase->id_equivalent = $request->id_equivalent;
        $purchase->qty = $request->qty;  
        $purchase->note = $request->note;
        $purchase->status = '0';
        $purchase->date = Carbon::now();

        if ($purchase->save()) {
            $success = true;
        }

        if ($success) {
            return redirect('pending-po/' . $id)->with('success', 'Purchase Request telah dibuat');
        }
    }
    public function show($id)
    {
        $pending = PendingPO::find($id);
        $quotation = Quotation::find($pending->id_quotation);
        $detQuotation = DetailQuotation::where('id_quotation', $pending->id_quotation)->get();
        $subQuote = SubtitleQuotation::with('detail')->where('id_quotation', $pending->id_quotation)->get();
        $invoice = Invoice::where('id_quotation', $quotation->id)->first();
        $activity = ChangeStatus::where('id_pending', $id)->with('comment')->get();
        $serial = SerialProduct::all();
        $purchase = PurchaseRequest::where('id_pending', $id)->get();
        return view('pages.warehouse.purchase.detail', compact('purchase', 'serial', 'activity', 'subQuote', 'pending', 'quotation', 'invoice', 'detQuotation'));
    }
    public function delete($id)
    {
        $purchase = PurchaseRequest::find($id);
        $delPurchase = $purchase->delete();
        if ($delPurchase) {
            return 1;
        } else {
            return 0;
        }

    }
    public function acc($id)
    {
        $purchase = PurchaseRequest::find($id);
        $purchase->status = '1';
        $purchaseSave = $purchase->save();
        if ($purchaseSave) {
            return 1;
        } else {
            return 0;
        }
    }
    public function acc_all($id)
    {
        $purchases = PurchaseRequest::where('id_pending', $id)->get();
        foreach ($purchases as $purchase) {
            $purchase->status = '1';
            $purchaseSave = $purchase->save();
        }
        if ($purchaseSave) {
            return 1;
        } else {
            return 0;
        }
    }
    public function delivery($id)
    {
        $purchase = PurchaseRequest::find($id);
        $purchase->status = '2';
        $purchaseSave = $purchase->save();
        if ($purchaseSave) {
            return 1;
        } else {
            return 0;
        }
    }
    public function delivery_all($id)
    {
        $purchases = PurchaseRequest::where('id_pending', $id)->get();
        foreach ($purchases as $purchase) {
            $purchase->status = '2';
            $purchaseSave = $purchase->save();
        }
        if ($purchaseSave) {
            return 1;
        } else {
            return 0;
        }
    }

    public function done_all($id)
    {
        $pending = PendingPO::find($id);

        $purchases = PurchaseRequest::where('id_pending', $id)->get();
        $fullRep = [];

        foreach ($purchases as $key => $purchase) {
            $equivalent = SerialProduct::where('id', $purchase->id_equivalent)->first();

            if ($equivalent) {
                $fullRep[$key] = DetailProduct::where('id_product', $equivalent->id_product)->get();
            }
        }
        // dd($fullRep);
        $suppliers = Supplier::all();
        $detProduct = DetailProduct::join('product', 'detail_product.id_product', '=', 'product.id')->get('detail_product.*');
        return view('pages.warehouse.purchase.form', compact('detProduct', 'suppliers', 'fullRep', 'purchases', 'pending'));
    }
    public function store_done_all(Request $request, $id)
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
        $purchases = PurchaseRequest::where('id_pending', $id)->get();
        foreach ($purchases as $purchase) {
            $purchase->status = '3';
            $purchase->save();
        }
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
                $dProductIn = new DetailProductIn();
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
    public function store_done_all_logistic(Request $request, $id)
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
        $productIn->info = $supplier->info;
        // $productIn->info = $request->info;
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
}