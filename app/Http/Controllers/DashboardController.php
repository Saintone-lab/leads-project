<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\Target;
use App\Models\User;
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
            ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
            ->count();
        $customers = Activities::select('activities.*')
        ->join('client as c', 'activities.id_client', '=', 'c.id')
        ->join('users as u', 'c.id_sales', '=', 'u.id')
        ->whereMonth("date", $monthNow)
        ->where('u.id', Auth::user()->id)
        ->where('status', 'Responded')
        ->where('activities.name', 'CRM')
        ->count();
        $quotation = Quotation::whereMonth("estimated_date", $monthNow)->where("id_sales", Auth::user()->id)->get();
        $po = Quotation::whereMonth("estimated_date", $monthNow)->where("id_sales", Auth::user()->id)->where("status", "100")->get();
        $poTotalPrice = Quotation::whereMonth("estimated_date", $monthNow)->where("id_sales", Auth::user()->id)->where("status", "100")->sum('total_no_tax');
        $formattedTotalPrice = $this->formatNumber($poTotalPrice);
        $sales = User::where('role', 'Sales')->get();
        $totalPO = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('estimated_date', $monthNow)->where('status', '100')->sum('harga_total');
        });
        $filteredPO = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('estimated_date', $monthNow)->where('status', '100')->count();
        });
        $filteredQuote = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('estimated_date', $monthNow)->count();
        });
        $filteredDC = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->clients->flatMap(function ($client) use ($monthNow) {
                return $client->activities()->whereMonth('date', $monthNow)->where('status', 'Responded')->get();
            })->count();
        });

        $call = Activities::where('c.id_sales', Auth::user()->id)
            ->join('client as c', 'activities.id_client', '=', 'c.id')
            ->groupBy("id_client")
            ->orderBy("follow_up", "asc")
            ->limit(7)->get();
        // dd($call);
        return view("pages.sales.dashboard", compact('call', 'dailyCall', 'customers', 'quotation', 'po', 'formattedTotalPrice', 'weekPerMonth', 'target', 'sales', 'poTotalPrice', 'totalPO', 'filteredPO', 'filteredDC', 'filteredQuote'));
    }

    public function overviewIndex()
    {
        $sales = User::where('role', 'Sales')->get();
        $totalPO = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('po_date', $monthNow)->where('status', '100')->sum('harga_total');
        });
        $totalForecast = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $total = $sale->quotation()->whereMonth('estimated_date', $monthNow)->whereIn('status', ['20', '30', '40', '60', '80'])->sum('harga_total');
            return number_format($total, 2, ',', '.');
        });
        $filteredPO = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('po_date', $monthNow)->where('status', '100')->count();
        });
        $filteredQuote = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('estimated_date', $monthNow)->count();
        });
        $filteredDC = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->clients->flatMap(function ($client) use ($monthNow) {
                return $client->activities()->whereMonth('date', $monthNow)->where('status', 'Responded')->whereIn('name', ['Daily Call', 'Follow Up'])->get();
            })->count();
        });
        $filteredCRM = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->clients->flatMap(function ($client) use ($monthNow) {
                return $client->activities()->whereMonth('date', $monthNow)->where('status', 'Responded')->where('name', 'CRM')->get();
            })->count();
        });
        $targett = $sales->map(function ($sale) {
            return $sale->target()->pluck('total')->sum();
        });
        // dd($targett);
        return view('pages.admin.overview', compact('sales', 'totalPO', 'totalForecast', 'filteredPO', 'filteredQuote', 'filteredDC', 'filteredCRM', 'targett'));
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

    protected function getDailyCallPersales()
    {
        $sales = User::where('role', 'Sales')->get();

        $totalActivitiesBySale = [];

        foreach ($sales as $sale) {
            // Mengambil semua activities untuk setiap client dalam Sale
            $activities = $sale->clients->flatMap(function ($client) {
                return $client->activities;
            });

            // Menghitung total activities
            $totalActivities = $activities->count();

            // Menyimpan total activities dalam array
            $totalActivitiesBySale[$sale->id] = $totalActivities;
        }

        // Tampilkan hasil
        dd($totalActivitiesBySale);
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
