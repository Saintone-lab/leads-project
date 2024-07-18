<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Client;
use App\Models\Pic;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("pages.sales.clients.customers.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customers = Client::where('id', $id)->first();
        $charge = Pic::where('id_client', $id)->get();
        $callhis = Activities::where('id_client', $id)->get();
        $quote = Quotation::join('pic','pic.id','=','quotation.id_pic')->where('pic.id_client', $id)->where('level', '1')->get();
        $sales = User::where('role', 'sales')->get();
        return view('pages.sales.clients.customers.detail', compact('customers', 'callhis', 'quote', 'sales', 'charge'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
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
        $rule = [
            'company' =>
                'required',

            'email' =>
                'required',

            'phone' =>
                'required',

            'web' =>
                'required',

            'source' =>
                'required',

            'mobile' =>
                'required',

            'address' =>
                'required',

            'area' =>
                'required',

            'machine' =>
                'required',
        ];

        $message = [
            'company.required'=> 'Field company Wajib Diisi',
            'email.required'=> 'Field Email Wajib Diisi',
            'phone.required'=> 'Field Phone Wajib Diisi',
            'ru.required'=> 'Wajib Pilih Reseller atau User',
            'web.required'=> 'Field Web Wajib Diisi',
            'source.required'=> 'Field Source Wajib Diisi',
            'mobile.required'=> 'Field Mobile Wajib Diisi',
            'address.required'=> 'Field Address Wajib Diisi',
            'area.required'=> 'Field Area Wajib Diisi',
            'machine.required'=> 'Field Machine Wajib Diisi',
        ];
        
        $this->validate($request, $rule, $message);
        
        //masukan data ke table Cust(client)
        $customers = Client::find($id);
        $customers->company = $request->company;
        $customers->email = $request->email;
        $customers->phone = $request->phone;
        $customers->ru = $request->ru;
        $customers->web = $request->web;
        $customers->source = $request->source;
        $customers->machine = $request->machine;
        $customers->mobile = $request->mobile;
        $customers->address = $request->address;
        $customers->area = $request->area;
        $customersave = $customers->save();

        if($customersave){
            return redirect('/customers/'.$id)->with('message', 'data telah diUpdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $custD = Client::find($id);
        $picD = Pic::where('id_client', $id)->get();
        $activitiesD = Activities::where('id_client', $id)->get();
        $visitD = Visit::where('id_client', $id)->get();
        $quoteD = Quotation::join('pic','pic.id','=','quotation.id_pic')->where('pic.id_client', $id)->get();

        $delCust = $custD->delete();
        if( $picD != NULL) {
            foreach ($picD as $pic) {
                $delpic = $pic->delete();
            }
        }
        if( $activitiesD != NULL) {
            foreach ($activitiesD as $activities) {
                $delActivities = $activities->delete();
            }
        }
        if($visitD != NULL) {
            foreach ($visitD as $visit) {
                $delVisits = $visit->delete();
            }
        }
        if($quoteD != NULL) {
            foreach ($quoteD as $quote) {
                $delQuote = $quote->delete();
            }
        }

        if($delCust || $delActivities || $delVisits || $delQuote || $delpic){
            return 1;
        }else{
            return 0;
        }
    }
}
