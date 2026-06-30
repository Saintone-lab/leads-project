<?php

namespace App\Http\Controllers;

use App\Models\DetailProduct;
use App\Models\Product;
use App\Models\SerialProduct;
use App\Models\SparePartVendorPrice;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartInquiryController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('supplier')->get();
        return view('pages.warehouse.part-inquiry.index', compact('suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('supplier')->get();
        $products = Product::orderBy('commodity')->get();
        return view('pages.warehouse.part-inquiry.form', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand'         => 'required|string',
            'pn'            => 'required|string',
            'selling_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            if ($request->product_type === 'existing' && $request->id_product) {
                $productId = $request->id_product;
            } else {
                $request->validate([
                    'commodity'   => 'required|string',
                    'go'          => 'required|in:Genuine,Replacement',
                    'description' => 'required|string',
                ]);

                $product = Product::create([
                    'commodity'     => $request->commodity,
                    'go'            => $request->go,
                    'description'   => $request->description,
                    'detail_desc'   => $request->detail_desc ?? '-',
                    'category'      => $request->category ?? 'Non Consumable Part',
                    'unit'          => $request->unit ?? 'Pcs',
                    'type'          => 'sparepart',
                    'first_stock'   => 0,
                    'warehouse_stock' => 0,
                    'stock'         => 0,
                    'weight'        => 0,
                    'dimension'     => '-',
                    'note'          => $request->note ?? '',
                    'date'          => now()->toDateString(),
                ]);

                $productId = $product->id;
            }

            $serial = SerialProduct::create([
                'id_product' => $productId,
                'brand'      => $request->brand,
                'pn'         => $request->pn,
                'fxp_parts'  => '-',
                'image'      => '',
                'price'      => $request->selling_price,
                'rental'     => '0',
                'second'     => '0',
                'new'        => '0',
            ]);

            $minModal = 0;
            if ($request->has('vendors')) {
                $idrs = [];
                foreach ($request->vendors as $vendor) {
                    if (!empty($vendor['id_supplier']) && isset($vendor['price_usd'])) {
                        $kurs     = floatval($vendor['kurs_usd'] ?? 0);
                        $priceUsd = floatval($vendor['price_usd']);
                        $priceIdr = isset($vendor['price_idr']) && $vendor['price_idr'] !== ''
                            ? floatval($vendor['price_idr'])
                            : $priceUsd * $kurs;
                        SparePartVendorPrice::create([
                            'id_serial_product' => $serial->id,
                            'id_supplier'       => $vendor['id_supplier'],
                            'price_usd'         => $priceUsd,
                            'kurs_usd'          => $kurs,
                            'price_idr'         => $priceIdr,
                            'date'              => $vendor['date'] ?? now()->toDateString(),
                        ]);
                        if ($priceIdr > 0) $idrs[] = $priceIdr;
                    }
                }
                $minModal = count($idrs) ? min($idrs) : 0;
            }

            // Buat Replacement otomatis (detail_product) dengan modal = harga terendah dari vendor
            $commodity = $request->product_type === 'existing'
                ? Product::find($productId)->commodity ?? '-'
                : $request->commodity;

            DetailProduct::create([
                'id_product'      => $productId,
                'replacement'     => $commodity,
                'modal'           => $minModal,
                'stock'           => 0,
                'warehouse_stock' => 0,
            ]);
        });

        return redirect()->route('part-inquiry.index')->with('success', 'Part Inquiry berhasil disimpan!');
    }

    public function getEquivalents($id)
    {
        $serials = SerialProduct::where('id_product', $id)
            ->orderBy('brand')
            ->get(['id', 'brand', 'pn', 'price']);

        return response()->json($serials);
    }

    public function show($id)
    {
        $serial = SerialProduct::with('product')->findOrFail($id);
        $vendorPrices = SparePartVendorPrice::with('supplier')
            ->where('id_serial_product', $id)
            ->orderBy('date', 'desc')
            ->get();
        $suppliers = Supplier::orderBy('supplier')->get();
        return view('pages.warehouse.part-inquiry.show', compact('serial', 'vendorPrices', 'suppliers'));
    }

    public function storeVendorPrice(Request $request, $id)
    {
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id',
            'price_usd'   => 'required|numeric|min:0',
            'kurs_usd'    => 'required|numeric|min:0',
            'date'        => 'required|date',
        ]);

        $kurs     = floatval($request->kurs_usd);
        $priceUsd = floatval($request->price_usd);

        SparePartVendorPrice::create([
            'id_serial_product' => $id,
            'id_supplier'       => $request->id_supplier,
            'price_usd'         => $priceUsd,
            'kurs_usd'          => $kurs,
            'price_idr'         => $priceUsd * $kurs,
            'date'              => $request->date,
        ]);

        return redirect()->back()->with('success', 'Harga vendor berhasil ditambahkan!');
    }

    public function updateEquivalent(Request $request, $id)
    {
        $request->validate([
            'brand' => 'required|string',
            'pn'    => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $serial = SerialProduct::findOrFail($id);
        $serial->update([
            'brand' => $request->brand,
            'pn'    => $request->pn,
            'price' => $request->price,
        ]);

        return response()->json(['success' => true, 'data' => $serial->only('id', 'brand', 'pn', 'price')]);
    }

    public function updateSellingPrice(Request $request, $id)
    {
        $request->validate(['selling_price' => 'required|numeric|min:0']);
        $serial = SerialProduct::findOrFail($id);
        $serial->price = $request->selling_price;
        $serial->save();
        return redirect()->back()->with('success', 'Harga jual berhasil diupdate!');
    }

    public function destroyVendorPrice($id)
    {
        $vendorPrice = SparePartVendorPrice::findOrFail($id);
        $vendorPrice->delete();
        return 1;
    }
}
