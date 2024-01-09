<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $weekPerMonth = $this->getWeekperMonth();
        // dd($weekPerMonth);
        $target = Target::where('id_sales', Auth::user()->id)->first();
        $dailyCall = Activities::select('activities.*')
            ->join('client as c', 'activities.id_client', '=', 'c.id')
            ->join('users as u', 'c.id_sales', '=', 'u.id')
            ->whereMonth("date", $monthNow)
            ->where('u.id', Auth::user()->id)
            ->where('status', 'Responded')
            ->count();
        $customers = Client::where("role", "Customers")->get();
        $quotation = Quotation::whereMonth("estimated_date", $monthNow)->where("id_sales", Auth::user()->id)->get();
        $po = Quotation::whereMonth("estimated_date", $monthNow)->where("id_sales", Auth::user()->id)->where("status", "100")->get();
        $poTotalPrice = Quotation::whereMonth("estimated_date", $monthNow)->where("id_sales", Auth::user()->id)->where("status", "100")->sum('harga_total');
        $formattedTotalPrice = $this->formatNumber($poTotalPrice);

        $call = Activities::where('c.id_sales', Auth::user()->id)
            ->join('client as c', 'activities.id_client', '=', 'c.id')
            ->groupBy("id_client")
            ->orderBy("follow_up", "asc")
            ->limit(7)->get();
        // dd($call);
        return view("pages.sales.dashboard", compact('call', 'dailyCall', 'customers', 'quotation', 'po', 'formattedTotalPrice', 'weekPerMonth', 'target'));
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

    protected function getWeekperMonth()
    {
        $dateNow = Carbon::now();
        $yearNow = $dateNow->year;
        $monthNow = $dateNow->month;
        $firstDayOfMonth = "{$yearNow}-{$monthNow}-01";
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

        $weekEnd = date('W', strtotime($lastDayOfMonth));
        $fullMonthData = [];
        for ($week = 1; $week <= $weekEnd; $week++) {
            $weekKey = "{$week}";

            $weekDays = date('t', strtotime($weekKey));
            if ($weekDays >= 4) {
                $fullMonthData[$weekKey] = [
                    'week' => $weekKey,
                ];
            }
        }
        return $fullMonthData;
    }
}
