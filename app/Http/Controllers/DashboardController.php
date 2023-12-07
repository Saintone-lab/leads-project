<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Client;
use App\Models\Quotation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $leads = Client::where("role", "Leads")->get();
        $customers = Client::where("role", "Customers")->get();
        $quotation = Quotation::where("id_sales", "2")->get();
        $po = Client::where("id_sales", "2")->where("id_issues", "5")->get();

        $call = Activities::groupBy("id_client")->orderBy("follow_up","asc")->limit(7)->get();
        // dd($sales);
        // dd($call);
        return view("pages.sales.dashboard", compact('call', 'leads','customers', 'quotation', 'po'));
    }   
}
