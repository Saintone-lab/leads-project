<?php

namespace App\Http\Controllers;

use App\Models\Pic;
use App\Models\Reports;
use App\Models\ReportsPict;
use Illuminate\Http\Request;

class ServiceReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.technician.service-reports.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pic = Pic::all();
        return view('pages.technician.service-reports.form', compact('pic'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Reports::where('id', $id)->first();
        $pict = ReportsPict::where('id_reports', $id)->get();
        return view('pages.technician.service-reports.detail', compact('service','pict'));
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
    
    public function print_reports($id)
    {
        $service = Reports::where('id', $id)->first();
        $pict = ReportsPict::where('id_reports', $id)->get();
        return view('pages.technician.service-reports.detail-print', compact('service','pict'));
    }
}
