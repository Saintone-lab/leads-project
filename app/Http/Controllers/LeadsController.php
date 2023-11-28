<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreLeadsRequest;

use App\Models\Client;
use App\Models\User;
use App\Models\Activities;
use App\Models\Quotation;
use App\Models\DetailCompressor;
use App\Models\DetailClient;
use App\Models\Pic;
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
        $sales = User::where('role', 'sales')->get();
        $dcompressor = DetailCompressor::get();
        return view('pages.sales.leads.index', compact('client', 'sales', 'dcompressor'));
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
        $rule = [
            'sales' => 'required',

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

            'dcompressor' =>
                'required',

            'namePic' =>
                'required',

            'emailPic' =>
                'required',

            'phonePic' =>
                'required',

            'position' =>
                'required',
        ];
        $message = [
            'sales.required'=> 'Field Sales Wajib Diisi',
            'company.required'=> 'Field company Wajib Diisi',
            'email.required'=> 'Field Email Wajib Diisi',
            'phone.required'=> 'Field Phone Wajib Diisi',
            'web.required'=> 'Field Web Wajib Diisi',
            'source.required'=> 'Field Source Wajib Diisi',
            'mobile.required'=> 'Field Mobile Wajib Diisi',
            'address.required'=> 'Field Address Wajib Diisi',
            'area.required'=> 'Field Area Wajib Diisi',
            'dcompressor.required'=> 'Field Machine Wajib Diisi',
            'namePic.required'=> 'Field Nama PIC Wajib Diisi',
            'emailPic.required'=> 'Field Email PIC Wajib Diisi',
            'phonePic.required'=> 'Field Nomor PIC Wajib Diisi',
            'position.required'=> 'Field Posisi PIC Wajib Diisi',
        ];
        
        $this->validate($request, $rule, $message);
        $idPic = Pic::orderBy('id', 'desc')->first()->id;
        $idClient = Client::orderBy('id', 'desc')->first()->id;

        //masukan data ke table leads(client)
        $leads = new Client;
        $leads->id_sales = $request->sales;
        $leads->id_pic = $idPic + 1;
        $leads->id_issues = 1;
        $leads->company = $request->company;
        $leads->email = $request->email;
        $leads->phone = $request->phone;
        $leads->web = $request->web;
        $leads->image = 'profile.jpg';
        $leads->source = $request->source;
        $leads->created_date = Carbon::today()->toDateString();
        $leads->role = 'Leads';
        $leads->mobile = $request->mobile;
        $leads->address = $request->address;
        $leads->area = $request->area;
        $leadsave = $leads->save();

        // masukan data ke table detail client
        $dclient = new DetailClient;
        $dclient->id_client = $idClient + 1;
        $dclient->id_detail_compressor = $request->dcompressor;
        $dclientsave = $dclient->save();

        // masukan data ke table PIC
        $pic = new Pic;
        $pic->name_pic = $request->namePic;
        $pic->position = $request->position;
        $pic->email_pic = $request->emailPic;
        $pic->phone_pic = $request->phonePic;
        $picsave = $pic->save();

        if ($leadsave && $dclientsave && $picsave) {
            return redirect('leads')->with('message', 'data telah ditambahkan');
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
        $leads = Client::where('id', $id)->first();
        $callhis = Activities::where('id_client', $id)->get();
        $quote = Quotation::where('id_client', $id)->get();
        // dd($quote);
        return view('pages.sales.leads.detail', compact('leads', 'callhis', 'quote'));
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
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}
