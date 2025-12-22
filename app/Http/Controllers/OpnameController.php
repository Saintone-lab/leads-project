<?php

namespace App\Http\Controllers;

use App\Models\DetailProduct;
use App\Models\DetailStockOpname;
use App\Models\StockOpname;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpnameController extends Controller
{
    public function index()
    {
        return view('pages.warehouse.opname.index');
    }
    public function store(Request $request)
    {
        $opname = new StockOpname;
        $opname->id_user = Auth::user()->id;
        $opname->date = Carbon::today();
        $opname->year = Carbon::today()->year;
        $opname->periode = $request->periode;
        $opname->note = $request->note ?? '-';
        $opnameSave = $opname->save();
        if ($opnameSave) {
            return redirect()->back()->with('success', 'Stock Opname berhasil ditambahkan!');
        }
    }
    public function show($id)
    {
        $opname = StockOpname::find($id);
        $detailOpname = DetailStockOpname::where('id_stock_opname', $id)->get();
        $product = DetailProduct::all();
        return view('pages.warehouse.opname.detail', compact('opname', 'detailOpname', 'product'));
    }
    public function show_print($id)
    {
        $opname = StockOpname::find($id);
        $detailOpname = DB::table('detail_product as dp')
            ->leftJoin('detail_stock_opname as dso', function ($join) use ($id) {
                $join->on('dso.id_product', '=', 'dp.id')
                    ->where('dso.id_stock_opname', '=', $id);
            })
            ->leftJoin('product as p', 'p.id', '=', 'dp.id_product')
            ->select(
                'dp.id',
                'dp.replacement',
                'p.description',
                'p.unit',
                // 'dp.stock as stock_sistem',
                DB::raw('COALESCE(dso.stock_sistem, 0) as stock_sistem'),
                DB::raw('COALESCE(dso.stock_gudang, 0) as stock_gudang'),
                DB::raw('COALESCE(dso.selisih, 0) as selisih')
            )
            ->orderBy('dp.replacement')
            ->get();
        $product = DetailProduct::all();
        return view('pages.warehouse.opname.detail-print', compact('opname', 'detailOpname', 'product'));
    }
    public function store_product(Request $request, $id)
    {
        $replacement = DetailProduct::find($request->replacement);
        $opname = new DetailStockOpname;
        $opname->id_stock_opname = $id;
        $opname->id_product = $request->replacement;
        $opname->stock_sistem = $replacement->stock;
        $opname->stock_gudang = $request->stock_gudang;
        $opname->selisih = $replacement->stock - $request->stock_gudang;
        $opnameSave = $opname->save();
        if ($opnameSave) {
            return redirect()->back()->with('success', 'Stock Opname Product berhasil ditambahkan!');
        }
    }
    public function stock_replacement($id)
    {
        $product = DetailProduct::find($id);

        return response()->json([
            'stock_sistem' => $product ? $product->stock : 0
        ]);
    }
}
