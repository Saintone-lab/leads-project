<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.sales.quotation.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client = Client::all();
        $sales = User::where('role','sales')->get();
        return view('pages.sales.quotation.form', compact('client','sales'));
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
            'product' => 'required',
            'detail_product' => 'required',
            'termcon' => 'required',
        ];
        $this->validate($request, $rule);
        $quotation = new Quotation();
        $quotation->id_client = $request->id_client;
        $quotation->id_sales = $request->id_sales;
        $quotation->id_service = NULL;
        $quotation->status = "Draft";
        $quotation->expired_date = $request->expired_date;
        $quotation->folup_date = $request->folup_date;
        if( $request->tax != NULL){
            $quotation->tax = $request->tax;
        }else{
            $quotation->tax = 0;
        }
        $quotation->shipping = $request->shipping;
        $quotation->no_quote = $request->no_quote;
        $quotation->termcon = $request->termcon;
        $quotation->subtotal = $request->subtotal;
        $quotation->harga_total = $request->harga_total;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
