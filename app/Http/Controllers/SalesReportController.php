<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Models\SalesReports;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        return view('pages.warehouse.reports.index',compact('noSaleProspect'));
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
            'year' =>
                'required',
            'semester' =>
                'required',
        ];

        $message = [
            'year.required' => 'Field year Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);

        $report = new SalesReports;
        $report->year = $request->year;
        $report->semester = $request->semester;
        $reportSave = $report->save();
        if ($reportSave) {
            return redirect('/sale-report')->with('message', 'data telah ditambahkan');
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
        $reports = SalesReports::find($id);
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        return view('pages.warehouse.reports.detail', compact('noSaleProspect', 'reports'));
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
        //
    }

    public function detailOnline($id)
    {
        $reports = SalesReports::find($id);
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        return view('pages.warehouse.reports.detail-online', compact('noSaleProspect', 'reports'));
    }

    public function detailOffline($id)
    {
        $reports = SalesReports::find($id);
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        return view('pages.warehouse.reports.detail-offline', compact('noSaleProspect', 'reports'));
    }
}
