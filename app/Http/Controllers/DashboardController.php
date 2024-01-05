<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Client;
use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $dailyCall = Activities::select('activities.*')
            ->join('client as c', 'activities.id_client', '=', 'c.id')
            ->join('users as u', 'c.id_sales', '=', 'u.id')
            ->whereMonth("date", $monthNow)
            ->where('u.id', Auth::user()->id)
            ->count();
        $customers = Client::where("role", "Customers")->get();
        $quotation = Quotation::whereMonth("estimated_date", $monthNow)->where("id_sales", Auth::user()->id)->get();
        $po = Quotation::whereMonth("estimated_date", $monthNow)->where("id_sales", Auth::user()->id)->where("status", "100")->get();
        $poTotalPrice = Quotation::whereMonth("estimated_date", $monthNow)->where("id_sales", Auth::user()->id)->where("status", "100")->sum('harga_total');
        $formattedTotalPrice = $this->formatNumber($poTotalPrice);

        $call = Activities::groupBy("id_client")->orderBy("follow_up", "asc")->limit(7)->get();
        return view("pages.sales.dashboard", compact('call', 'dailyCall', 'customers', 'quotation', 'po', 'formattedTotalPrice'));
    }

    protected function formatNumber($number)
    {
        $satuan = ["", "ribu", "juta", "miliar", "triliun", "quadrillion"]; // Sesuaikan dengan kebutuhan

        $i = 0;
        while ($number >= 1000) {
            $number /= 1000;
            $i++;
        }

        // Menggunakan number_format untuk menghindari angka pecahan yang panjang
        $formattedAngka = number_format($number, ($i == 0 || $number >= 10) ? 2 : 0, ',', '.');

        // Menghilangkan angka pecahan jika nol di belakang koma
        $formattedAngka = rtrim($formattedAngka, '0');

        // Menghilangkan koma di belakang angka jika tidak ada angka pecahan
        $formattedAngka = rtrim($formattedAngka, '.');

        return $formattedAngka . ' ' . $satuan[$i];
    }

}
