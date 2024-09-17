<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Client;
use App\Models\DetailProduct;
use App\Models\Product;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\ReqVisit;
use App\Models\SerialProduct;
use App\Models\Target;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $weekPerMonth = $this->getWeekperMonth();
        $commodity = Product::count();
        $dproduct = DetailProduct::count();
        $sproduct = SerialProduct::count();
        // dd($weekPerMonth);
        $target = Target::where('id_sales', Auth::user()->id)->first();
        // dd($target);
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
        $visit = Activities::select('activities.*')
            ->join('client as c', 'activities.id_client', '=', 'c.id')
            ->join('users as u', 'c.id_sales', '=', 'u.id')
            ->whereMonth("date", $monthNow)
            ->where('u.id', Auth::user()->id)
            ->where('status', 'Responded')
            ->where('activities.name', 'Visit')
            ->count();
        $quotation = Quotation::whereMonth("estimated_date", $monthNow)->where("id_sales", Auth::user()->id)->where('level', '1')->where('is_primary', '1')->get();
        $po = Quotation::whereMonth("po_date", $monthNow)->where("id_sales", Auth::user()->id)->where("status", "100")->where('level', '1')->where('is_primary', '1')->get();
        $poTotalPrice = Quotation::whereMonth("po_date", $monthNow)->where("id_sales", Auth::user()->id)->where("status", "100")->where('level', '1')->where('is_primary', '1')->sum('nett');
        $poTotalPriceAdmin = Quotation::whereMonth("po_date", $monthNow)->where("status", "100")->where('level', '1')->where('is_primary', '1')->sum('nett');
        $formattedTotalPrice = $this->formatNumber($poTotalPrice);
        $formattedTotalPriceAdmin = $this->formatNumber($poTotalPriceAdmin);
        $sales = User::where('role', 'Sales')->where('active', '1')->get();
        $totalPO = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('po_date', $monthNow)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        });
        $totalProspect = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $total = $sale->quotation()->whereMonth('estimated_date', $monthNow)->where('status', '80')->where('level', '1')->where('is_primary', '1')->sum('nett');
            return number_format($total, 2, ',', '.');
        });
        $totalForecast = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $total = $sale->quotation()->whereMonth('estimated_date', $monthNow)->whereIn('status', ['20', '30', '40', '60', '80'])->where('level', '1')->where('is_primary', '1')->sum('nett');
            return number_format($total, 2, ',', '.');
        });
        $filteredPO = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('po_date', $monthNow)->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        });
        $filteredQuote = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('estimated_date', $monthNow)->where('level', '1')->where('is_primary', '1')->count();
        });
        $filteredDC = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->clients->flatMap(function ($client) use ($monthNow) {
                return $client->activities()->whereMonth('date', $monthNow)->where('status', 'Responded')->get();
            })->count();
        });
        $filteredCRM = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->clients->flatMap(function ($client) use ($monthNow) {
                return $client->activities()->whereMonth('date', $monthNow)->where('status', 'Responded')->where('name', 'CRM')->get();
            })->count();
        });
        $filteredVisit = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->clients->flatMap(function ($client) use ($monthNow) {
                return $client->activities()->whereMonth('date', $monthNow)->where('status', 'Responded')->where('name', 'Visit')->get();
            })->count();
        });

        $dataDc = $this->getWeekDataDC();
        $dataCRM = $this->getWeekDataCRM();
        $dataVisit = $this->getWeekDataVisit();
        $dataQuote = $this->getWeekDataQuote();
        $dataPO = $this->getWeekDataPO();
        $targett = $sales->map(function ($sale) {
            return $sale->target()->pluck('total')->sum();
        });
        $targetSales = $sales->map(function ($sale) {
            return $sale->target()->groupBy('id_sales')->get();
        });
        $visits = ReqVisit::whereNull('date')->get();
        $visited = ReqVisit::whereNotNull('date')->whereNull('visit_date')->get();
        $prospects = Prospect::where('id_sales', Auth::id())->whereNull('level')->get();
        // dd($targetSales);
        return view("pages.sales.dashboard", compact('prospects','targetSales', 'visit', 'dailyCall', 'customers', 'quotation', 'po', 'formattedTotalPrice', 'weekPerMonth', 'target', 'sales', 'poTotalPrice', 'totalPO', 'filteredPO', 'filteredCRM', 'filteredVisit', 'filteredDC', 'filteredQuote', 'poTotalPriceAdmin', 'formattedTotalPriceAdmin', 'totalForecast', 'totalProspect', 'dataQuote', 'dataPO', 'dataDc', 'dataCRM', 'dataVisit', 'commodity', 'sproduct', 'targett', 'visits', 'visited'));
    }

    public function overviewIndex()
    {
        $sales = User::where('role', 'Sales')->get();
        $totalPO = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('po_date', $monthNow)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        });
        $totalForecast = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $total = $sale->quotation()->whereMonth('estimated_date', $monthNow)->whereIn('status', ['20', '30', '40', '60', '80'])->where('level', '1')->where('is_primary', '1')->sum('nett');
            return number_format($total, 2, ',', '.');
        });
        $filteredPO = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('po_date', $monthNow)->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        });
        $filteredQuote = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('estimated_date', $monthNow)->where('level', '1')->where('is_primary', '1')->count();
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
        $filteredVisit = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->clients->flatMap(function ($client) use ($monthNow) {
                return $client->activities()->whereMonth('date', $monthNow)->where('status', 'Responded')->where('name', 'Visit')->get();
            })->count();
        });
        $targett = $sales->map(function ($sale) {
            return $sale->target()->pluck('total')->sum();
        });
        // dd($targett);
        return view('pages.admin.overview', compact('sales', 'totalPO', 'totalForecast', 'filteredPO', 'filteredQuote', 'filteredDC', 'filteredVisit', 'filteredCRM', 'targett'));
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
        $formattedAngka = number_format($number, 2, ',', '.');
        // $formattedAngka = number_format($number, ($i == 0 || $number >= 10) ? 2 : 0, ',', '.');

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
    protected function getWeekDataDC()
    {
        $sales = User::where('role', 'sales')->get();

        $dateNow = Carbon::now();
        $yearNow = $dateNow->year;
        $monthNow = $dateNow->month;
        $firstDayOfMonth = "{$yearNow}-{$monthNow}-01";
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

        $firstDayOfWeek = date('N', strtotime($firstDayOfMonth));
        $weekEnd = date('W', strtotime($firstDayOfMonth));
        $endWeek = date('W', strtotime($lastDayOfMonth));
        $weekStart = $firstDayOfWeek > 1 ? $weekEnd + 1 : $weekEnd;

        foreach ($sales as $sales) {
            // Mengambil ID sales
            $salesId = $sales->id;

            // Inisialisasi array untuk menyimpan data aktivitas per minggu
            $weeklyData = [];

            // Loop melalui setiap minggu dalam sebulan
            for ($week = $weekStart; $week <= $endWeek; $week++) {
                $weekKey = "{$week}";

                $weekDays = date('t', strtotime("{$yearNow}-W{$weekKey}")); // Jumlah hari dalam minggu
                if ($weekDays >= 4) {
                    // Mengambil data aktivitas untuk sales tertentu dan minggu tertentu
                    $dCallPerWeek = Activities::select(DB::raw('COUNT(*) as total'))
                        ->join('client as c', 'activities.id_client', '=', 'c.id')
                        ->where('c.id_sales', $salesId) // Filter berdasarkan ID sales
                        ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                        ->where(DB::raw('WEEK(date, 4)'), $weekKey)
                        ->whereIn('activities.name', ['Daily Call', 'Follow Up']) // Menggunakan whereIn untuk memeriksa beberapa nilai
                        ->where('status', 'Responded')
                        ->pluck('total')
                        ->first(); // Mengambil total aktivitas

                    // Menambahkan data aktivitas per minggu ke dalam array $weeklyData
                    $weeklyData[$weekKey] = $dCallPerWeek;
                }
            }

            // Menambahkan data aktivitas per sales ke dalam array $fullMonthData
            $fullMonthData[$sales->name] = $weeklyData;
        }
        return $fullMonthData;
    }
    protected function getWeekDataCRM()
    {
        $sales = User::where('role', 'sales')->get();

        $dateNow = Carbon::now();
        $yearNow = $dateNow->year;
        $monthNow = $dateNow->month;
        $firstDayOfMonth = "{$yearNow}-{$monthNow}-01";
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

        $firstDayOfWeek = date('N', strtotime($firstDayOfMonth));
        $weekEnd = date('W', strtotime($firstDayOfMonth));
        $endWeek = date('W', strtotime($lastDayOfMonth));
        $weekStart = $firstDayOfWeek > 1 ? $weekEnd + 1 : $weekEnd;

        foreach ($sales as $sales) {
            // Mengambil ID sales
            $salesId = $sales->id;

            // Inisialisasi array untuk menyimpan data aktivitas per minggu
            $weeklyData = [];

            // Loop melalui setiap minggu dalam sebulan
            for ($week = $weekStart; $week <= $endWeek; $week++) {
                $weekKey = "{$week}";

                $weekDays = date('t', strtotime("{$yearNow}-W{$weekKey}")); // Jumlah hari dalam minggu
                if ($weekDays >= 4) {
                    // Mengambil data aktivitas untuk sales tertentu dan minggu tertentu
                    $dCallPerWeek = Activities::select(DB::raw('COUNT(*) as total'))
                        ->join('client as c', 'activities.id_client', '=', 'c.id')
                        ->where('c.id_sales', $salesId) // Filter berdasarkan ID sales
                        ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                        ->where(DB::raw('WEEK(date, 4)'), $weekKey)
                        ->where('activities.name', 'Crm') // Menggunakan whereIn untuk memeriksa beberapa nilai
                        ->where('status', 'Responded')
                        ->pluck('total')
                        ->first(); // Mengambil total aktivitas

                    // Menambahkan data aktivitas per minggu ke dalam array $weeklyData
                    $weeklyData[$weekKey] = $dCallPerWeek;
                }
            }

            // Menambahkan data aktivitas per sales ke dalam array $fullMonthData
            $fullMonthData[$sales->name] = $weeklyData;
        }
        return $fullMonthData;
    }
    protected function getWeekDataVisit()
    {
        $sales = User::where('role', 'sales')->get();

        $dateNow = Carbon::now();
        $yearNow = $dateNow->year;
        $monthNow = $dateNow->month;
        $firstDayOfMonth = "{$yearNow}-{$monthNow}-01";
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

        $firstDayOfWeek = date('N', strtotime($firstDayOfMonth));
        $weekEnd = date('W', strtotime($firstDayOfMonth));
        $endWeek = date('W', strtotime($lastDayOfMonth));
        $weekStart = $firstDayOfWeek > 1 ? $weekEnd + 1 : $weekEnd;

        foreach ($sales as $sales) {
            // Mengambil ID sales
            $salesId = $sales->id;

            // Inisialisasi array untuk menyimpan data aktivitas per minggu
            $weeklyData = [];

            // Loop melalui setiap minggu dalam sebulan
            for ($week = $weekStart; $week <= $endWeek; $week++) {
                $weekKey = "{$week}";

                $weekDays = date('t', strtotime("{$yearNow}-W{$weekKey}")); // Jumlah hari dalam minggu
                if ($weekDays >= 4) {
                    // Mengambil data aktivitas untuk sales tertentu dan minggu tertentu
                    $dCallPerWeek = Activities::select(DB::raw('COUNT(*) as total'))
                        ->join('client as c', 'activities.id_client', '=', 'c.id')
                        ->where('c.id_sales', $salesId) // Filter berdasarkan ID sales
                        ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                        ->where(DB::raw('WEEK(date, 4)'), $weekKey)
                        ->where('activities.name', 'Visit') // Menggunakan whereIn untuk memeriksa beberapa nilai
                        ->where('status', 'Responded')
                        ->pluck('total')
                        ->first(); // Mengambil total aktivitas

                    // Menambahkan data aktivitas per minggu ke dalam array $weeklyData
                    $weeklyData[$weekKey] = $dCallPerWeek;
                }
            }

            // Menambahkan data aktivitas per sales ke dalam array $fullMonthData
            $fullMonthData[$sales->name] = $weeklyData;
        }
        return $fullMonthData;
    }
    protected function getWeekDataQuote()
    {
        $sales = User::where('role', 'sales')->get();

        $dateNow = Carbon::now();
        $yearNow = $dateNow->year;
        $monthNow = $dateNow->month;
        $firstDayOfMonth = "{$yearNow}-{$monthNow}-01";
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

        $firstDayOfWeek = date('N', strtotime($firstDayOfMonth));
        $weekEnd = date('W', strtotime($firstDayOfMonth));
        $endWeek = date('W', strtotime($lastDayOfMonth));
        $weekStart = $firstDayOfWeek > 1 ? $weekEnd + 1 : $weekEnd;

        foreach ($sales as $sales) {
            // Mengambil ID sales
            $salesId = $sales->id;

            // Inisialisasi array untuk menyimpan data aktivitas per minggu
            $weeklyData = [];

            // Loop melalui setiap minggu dalam sebulan
            for ($week = $weekStart; $week <= $endWeek; $week++) {
                $weekKey = "{$week}";

                $weekDays = date('t', strtotime("{$yearNow}-W{$weekKey}")); // Jumlah hari dalam minggu
                if ($weekDays >= 4) {
                    // Mengambil data aktivitas untuk sales tertentu dan minggu tertentu
                    $dCallPerWeek = Quotation::select(DB::raw('COUNT(*) as total'))
                        ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
                        ->where(DB::raw('WEEK(estimated_date, 4)'), $weekKey)
                        ->where('id_sales', $salesId)
                        ->pluck('total')
                        ->where('level', '1')
                        ->where('is_primary', '1')
                        ->first(); // Mengambil total aktivitas

                    // Menambahkan data aktivitas per minggu ke dalam array $weeklyData
                    $weeklyData[$weekKey] = $dCallPerWeek;
                }
            }

            // Menambahkan data aktivitas per sales ke dalam array $fullMonthData
            $fullMonthData[$sales->name] = $weeklyData;
        }
        return $fullMonthData;
    }
    protected function getWeekDataPO()
    {
        $sales = User::where('role', 'sales')->get();

        $dateNow = Carbon::now();
        $yearNow = $dateNow->year;
        $monthNow = $dateNow->month;
        $firstDayOfMonth = "{$yearNow}-{$monthNow}-01";
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

        $firstDayOfWeek = date('N', strtotime($firstDayOfMonth));
        $weekEnd = date('W', strtotime($firstDayOfMonth));
        $endWeek = date('W', strtotime($lastDayOfMonth));
        $weekStart = $firstDayOfWeek > 1 ? $weekEnd + 1 : $weekEnd;

        foreach ($sales as $sales) {
            // Mengambil ID sales
            $salesId = $sales->id;

            // Inisialisasi array untuk menyimpan data aktivitas per minggu
            $weeklyData = [];

            // Loop melalui setiap minggu dalam sebulan
            for ($week = $weekStart; $week <= $endWeek; $week++) {
                $weekKey = "{$week}";

                $weekDays = date('t', strtotime("{$yearNow}-W{$weekKey}")); // Jumlah hari dalam minggu
                if ($weekDays >= 4) {
                    // Mengambil data aktivitas untuk sales tertentu dan minggu tertentu
                    $dCallPerWeek = Quotation::select(DB::raw('COUNT(*) as total'))
                        ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                        ->where(DB::raw('WEEK(po_date, 4)'), $weekKey)
                        ->where('status', '100')
                        ->where('level', '1')
                        ->where('is_primary', '1')
                        ->where('id_sales', $salesId)
                        ->pluck('total')
                        ->first(); // Mengambil total aktivitas

                    // Menambahkan data aktivitas per minggu ke dalam array $weeklyData
                    $weeklyData[$weekKey] = $dCallPerWeek;
                }
            }

            // Menambahkan data aktivitas per sales ke dalam array $fullMonthData
            $fullMonthData[$sales->name] = $weeklyData;
        }
        return $fullMonthData;
    }
}
