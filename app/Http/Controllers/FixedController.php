<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\FixedAsset;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FixedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.finance.fixed.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account = Account::all();
        $suppliers = Supplier::all();
        return view('pages.finance.fixed.form', compact('account', 'suppliers'));
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
        $fixed = new FixedAsset;
        $fixed->id_aktiva = $request->aktiva;
        $fixed->id_penyusutan = $request->penyusutan;
        $fixed->id_beban = $request->beban;
        $fixed->id_supplier = $request->supplier;
        $fixed->id_pengeluaran = $request->bank;
        $fixed->type = $request->type;
        $fixed->code = $request->code;
        $fixed->no_invoice = $request->no_invoice;
        $fixed->beli = $request->pay;
        $fixed->pakai = $request->pakai;
        $fixed->bayar = $request->date;
        $fixed->metode = $request->metode;
        $fixed->desc = $request->desc;
        $fixed->umur = $request->umur;
        $fixed->qty = $request->qty;
        $fixed->total = $request->total;
        $fixed->status = $request->status ?? 0;
        $fixedSave = $fixed->save();
        if ($fixedSave) {
            return redirect('fixed')->with('success', 'data telah di tambahkan');
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
        $fixed = FixedAsset::find($id);
        $startDate = Carbon::parse($fixed->beli);
        $endDate = Carbon::now();
        $diffMonth = $startDate->diffInMonths($endDate);
        $umurAktiva = $fixed->umur;
        $bulanPenyusutan = min($diffMonth, $umurAktiva);
        $penyusutanPerBulan = ($fixed->total * 0.25) / 12;
        $totalPenyusutan = $penyusutanPerBulan * $bulanPenyusutan;
        $nilaiBuku = $fixed->total - $totalPenyusutan;
        return view('pages.finance.fixed.detail', compact('fixed', 'totalPenyusutan', 'nilaiBuku'));
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
        $fixed = FixedAsset::find($id);
        $fixedDel = $fixed->delete();
        if ($fixedDel) {
            return 1;
        } else {
            return 0;
        }
    }
}
