<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $call = Activities::orderBy("follow_up","asc")->limit(7)->get();
        // dd($call);
        return view("pages.sales.dashboard", compact('call'));
    }
}
