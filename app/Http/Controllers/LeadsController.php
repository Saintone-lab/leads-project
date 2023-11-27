<?php

namespace App\Http\Controllers;

use App\Http\Request\StoreLeadsRequest;

use App\Models\Client;
use App\Models\User;
use App\Models\Activities;
use App\Models\Quotation;
use App\Models\DetailCompressor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = Client::get();
        // dd($client);
        $sales = User::where('role', 'sales')->get();
        $dcompressor = DetailCompressor::get();
        // dd($sales);
        return view('pages.sales.leads.index', compact('client', 'sales', 'dcompressor'));
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
    public function store(StoreLeadsRequest $request)
    {
        $data = $request->all();
        dd($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leads = Client::where('id', $id)->first();
        $callhis = Activities::where('id_client', $id)->get();
        $quote = Quotation::where('id_client', $id)->get();
        // dd($quote);
        return view('pages.sales.leads.detail', compact('leads', 'callhis','quote'));
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
}
