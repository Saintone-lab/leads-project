<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\DetailQuotation;
use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sellcon = Contract::find($id);
        $quote = Quotation::where('id', $sellcon->id_quotation)->first();
        $tax = $quote->total_no_tax * $quote->tax / 100;
        $dquote = DetailQuotation::where('id_quotation', $quote->id)->get();
        return view('pages.accounting.contract.detail', compact('sellcon','quote','dquote','tax'));
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
        $contract = Contract::find($id);
        $contractDelete = $contract->delete();
        if ($contractDelete) {
            return 1;
        } else {
            return 0;
        }
        
    }
    public function create_selling_contract(Request $request, $id){
        $sellcon = new Contract;
        $sellcon->id_quotation = $id;
        $sellcon->no_contract = $request->no_contract;
        $sellcon->type = "Selling";
        $sellcon->date = Carbon::today();
        $sellconSave = $sellcon->save();
        if ($sellconSave) {
            return redirect('contract/'. $sellcon->id);
        }else{

        }
    }
    
    public function create_confirm_order(Request $request, $id){
        $sellcon = new Contract;
        $sellcon->id_quotation = $id;
        $sellcon->no_contract = $request->no_contract;
        $sellcon->type = "Order";
        $sellcon->date = Carbon::today();
        $sellconSave = $sellcon->save();
        if ($sellconSave) {
            return redirect('contract/'. $sellcon->id);
        }else{

        }
    }
    public function index_selling(){
        return view("pages.accounting.contract.index-selling");
    }
    public function index_order(){
        return view("pages.accounting.contract.index-order");
    }
    public function contract_print($id){
        $sellcon = Contract::find($id);
        $quote = Quotation::where('id', $sellcon->id_quotation)->first();
        $tax = $quote->total_no_tax * $quote->tax / 100;
        $dquote = DetailQuotation::where('id_quotation', $quote->id)->get();
        return view('pages.accounting.contract.detail-print', compact('sellcon','quote','dquote', 'tax'));
    }
}
