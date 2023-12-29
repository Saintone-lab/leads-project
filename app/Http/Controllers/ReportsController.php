<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(){
        $quotation = Quotation::all();
        return view("pages.sales.report.index", compact("quotation"));
    }
}
