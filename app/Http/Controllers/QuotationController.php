<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DetailQuotation;
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
        $idquote = Quotation::orderBy('id', 'desc')->first()->id;
        $idQ = $idquote + 1 ;
        $client = Client::all();
        $sales = User::where('role', 'sales')->get();
        return view('pages.sales.quotation.form', compact('client', 'sales', 'idQ'));
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
            'expired_date' => 'required',
            'folup_date' => 'required',
            'termcon' => 'required',
            'shipping' => 'required',
        ];
        $message = [
            'product.required'=> 'Field product Wajib Diisi',
            'detail_product.required'=> 'Field detail_product Wajib Diisi',
            'expired_date.required'=> 'Wajib isi Expired Date',
            'folup_date.required'=> 'Wajib isi Follow Up Date',
            'termcon.required'=> 'Field Term and Conditions Wajib Diisi',
            'shipping.required'=> 'Quotation Wajib memiliki harga Antar',
        ];
        $this->validate($request, $rule, $message);
        // dd($request->all());
        // Input Data Quotation
        $quotation = new Quotation();
        $quotation->id_client = $request->id_client;
        $quotation->id_sales = $request->id_sales;
        $quotation->id_service = NULL;
        $quotation->status = "Draft";
        $quotation->expired_date = $request->expired_date;
        $quotation->folup_date = $request->folup_date;
        if ($request->tax != NULL) {
            $quotation->tax = $request->tax;
        } else {
            $quotation->tax = 0;
        }
        $quotation->shipping = $request->shipping;
        $quotation->no_quote = $request->no_quote;
        $quotation->termcon = $request->termcon;
        $quotation->subtotal = $request->subtotal;
        $quotation->harga_total = $request->harga_total;
        $status = $quotation->save();
        // dd($status);

        foreach ($request->product as $item => $value) {
            $dQuote = new DetailQuotation;
            $dQuote->id_quotation = $quotation->id;
            $dQuote->product = $request->product[$item];
            $dQuote->detail_product = $request->detail_product[$item];
            $dQuote->price = $request->price[$item];
            $dQuote->qty = $request->qty[$item];
            $dQuote->disc = $request->disc[$item];
            $dQuote->amount = $request->amount[$item];
            $status = $dQuote->save();
        }
        if ($status){
            return redirect('quotation')->with("success","Data Quotation Telah Ditambahkan");
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
        return view("pages.sales.quotation.detail");
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
