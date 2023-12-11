<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DetailQuotation;
use App\Models\Quotation;
use App\Models\Termncon;
use App\Models\User;
use PDF;
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
            'no_quote' => 'required',
            'product' => 'required',
            'detail_product' => 'required',
            'expired_date' => 'required',
            'folup_date' => 'required',
            'validity' => 'required',
            'pricing' => 'required',
            'delivery_process' => 'required',
            'payment' => 'required',
            'shipping' => 'required',
        ];
        $message = [
            'no_quote.required'=> 'Field No Quote Wajib Diisi',
            'product.required'=> 'Field Product Wajib Diisi',
            'detail_product.required'=> 'Field Detail Product Wajib Diisi',
            'expired_date.required'=> 'Wajib isi Expired Date',
            'folup_date.required'=> 'Wajib isi Follow Up Date',
            'termcon.required'=> 'Field Term and Conditions Wajib Diisi',
            'shipping.required'=> 'Quotation Wajib memiliki harga Antar',
        ];
        $this->validate($request, $rule, $message);

        // Masukan Data ke Tabel Quotataion
        $quotation = new Quotation();
        $quotation->id_client = $request->id_client;
        $quotation->id_sales = $request->id_sales;
        $quotation->id_service = NULL;
        $quotation->status = "Draft";
        $quotation->estimated_date = \Carbon\Carbon::today()->format('Y-m-d H:i:s');
        $quotation->expired_date = $request->expired_date;
        $quotation->folup_date = $request->folup_date;
        if ($request->tax != NULL) {
            $quotation->tax = $request->tax;
        } else {
            $quotation->tax = 0;
        }
        $quotation->shipping = $request->shipping;
        $quotation->no_quote = $request->no_quote;
        $quotation->subtotal = $request->subtotal;
        $quotation->harga_total = $request->harga_total;
        $status = $quotation->save();

        // Masukan Data Ke Tabel Detail Quotataion
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

        // Masukan Data ke dalam Tabel Term n Condition
        $termncon = new Termncon;
        $termncon->id_quotation = $quotation->id;
        $termncon->validity = $request->validity;
        $termncon->pricing = $request->pricing;
        $termncon->delivery_process = $request->delivery_process;
        $termncon->payment = $request->payment;
        $status = $termncon->save();

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
        $quote = Quotation::where('id', $id)->first();
        $dquote = DetailQuotation::where('id_quotation', $id)->get();
        // dd($quote);
        return view("pages.sales.quotation.detail", compact('quote', 'dquote'));
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
    public function print_quote($id)
    {
        $quote = Quotation::where('id', $id)->first();
        $dquote = DetailQuotation::where('id_quotation', $id)->get();
        // dd($termncon);
        return view("pages.sales.quotation.detail-print", compact('quote', 'dquote'));
    }

    public function pdf_quote($id){
        $quote = Quotation::where('id', $id)->first();
        $dquote = DetailQuotation::where('id_quotation', $id)->get();
        // return view("pages.sales.quotation.detail-pdf", compact('quote', 'dquote'));
        $pdf = PDF::loadView("pages.sales.quotation.detail-pdf", compact('quote', 'dquote'));
        return $pdf->download('invoice.pdf');
    }
}
