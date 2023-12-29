<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreLeadsRequest;

use App\Models\Client;
use App\Models\Issues;
use App\Models\User;
use App\Models\Activities;
use App\Models\Visit;
use App\Models\Quotation;
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
        $client = Client::where("role", "Leads")->get();
        // $folup = $client->activities()->orderBy("follow_up","desc");
        // dd($client);
        $issue = Issues::get();
        $sales = User::where('role', 'sales')->get();
        // $dcompressor = DetailCompressor::get();
        return view('pages.sales.leads.index', compact('client', 'sales', 'issue'));
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
            
            'ru' =>
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
            'ru.required'=> 'Wajib Pilih Reseller atau User',
            'web.required'=> 'Field Web Wajib Diisi',
            'source.required'=> 'Field Source Wajib Diisi',
            'mobile.required'=> 'Field Mobile Wajib Diisi',
            'address.required'=> 'Field Address Wajib Diisi',
            'area.required'=> 'Field Area Wajib Diisi',
            'namePic.required'=> 'Field Nama PIC Wajib Diisi',
            'emailPic.required'=> 'Field Email PIC Wajib Diisi',
            'phonePic.required'=> 'Field Nomor PIC Wajib Diisi',
            'position.required'=> 'Field Posisi PIC Wajib Diisi',
        ];
        
        $this->validate($request, $rule, $message);

        //masukan data ke table leads(client)
        $leads = new Client;
        $leads->id_sales = $request->sales;
        $leads->id_issues = 1;
        $leads->company = $request->company;
        $leads->email = $request->email;
        $leads->phone = $request->phone;
        $leads->ru = $request->ru;
        $leads->web = $request->web;
        $leads->image = 'profile.jpg';
        $leads->source = $request->source;
        $leads->created_date = Carbon::today()->toDateString();
        $leads->role = 'Leads';
        if( $request->machine != NULL){
            $leads->machine = $request->machine;
        }else {
            $leads->machine = NULL;
        }
        $leads->mobile = $request->mobile;
        $leads->address = $request->address;
        $leads->area = $request->area;
        $leadsave = $leads->save();

        // masukan data ke table PIC
        $pic = new Pic;
        $pic->id_client = $leads->id;
        $pic->name_pic = $request->namePic;
        $pic->position = $request->position;
        $pic->email_pic = $request->emailPic;
        $pic->phone_pic = $request->phonePic;
        $picsave = $pic->save();

        if ($leadsave && $picsave) {
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
        $charge = PIC::where('id_client', $id)->get();
        $callhis = Activities::where('id_client', $id)->get();
        $quote = Quotation::join('pic','pic.id','=','quotation.id_pic')->where('pic.id_client', $id)->get();
        $sales = User::where('role', 'sales')->get();
        return view('pages.sales.leads.detail', compact('leads', 'callhis', 'quote', 'sales', 'charge'));
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

            'machine' =>
                'required',
        ];

        $message = [
            'sales.required'=> 'Field Sales Wajib Diisi',
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
        
        //masukan data ke table leads(client)
        $leads = Client::find($id);
        $leads->id_sales = $request->sales;
        $leads->company = $request->company;
        $leads->email = $request->email;
        $leads->phone = $request->phone;
        $leads->ru = $request->ru;
        $leads->web = $request->web;
        $leads->source = $request->source;
        $leads->machine = $request->machine;
        $leads->mobile = $request->mobile;
        $leads->address = $request->address;
        $leads->area = $request->area;
        $leadsave = $leads->save();

        if($leadsave){
            return redirect('/leads/detail/'.$id)->with('message', 'data telah diUpdate');
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
        $leadsD = Client::find($id);
        $picD = Pic::where('id_client', $id)->get();
        $activitiesD = Activities::where('id_client', $id)->get();
        $visitD = Visit::where('id_client', $id)->get();
        $quoteD = Quotation::join('pic','pic.id','=','quotation.id_pic')->where('pic.id_client', $id)->get();

        $delLeads = $leadsD->delete();
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

        if($delLeads || $delActivities || $delVisits || $delQuote || $delpic){
            return 1;
        }else{
            return 0;
        }
    }

    public function storeActionWithLeads(Request $request, $id){
        $leads = Client::where("id", $id)->first();
        $leads->id_issues = $request->issues;
        if ($request->issues == '4'){
            $leads->role = 'Customers';
        }
        $isuSave = $leads->save();

        $action = new Activities;
        $action->id_client = $id;
        if( $leads->activities != Null){
            $action->name = "Follow Up";
        }else{
            $action->name = "Daily Call";
        }
        $action->status = $request->status;
        $action->action = $request->action;
        $action->schedule = $request->schedule;
        $action->date = \Carbon\Carbon::today();
        $action->follow_up = $request->follow_up;
        $activitiesSave = $action->save();
        if($isuSave && $activitiesSave){
            return redirect("leads")->with("success","Data telah ditambahkan");
        }
    }
}
