<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\SalesReports;
use App\Models\Target;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OverviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = User::where('role', 'Sales')->get();
        $totalPO = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            return $sale->quotation()->whereMonth('po_date', $monthNow)->where('status', '100')->sum('nett');
        });
        $totalForecast = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $total = $sale->quotation()->whereMonth('estimated_date', $monthNow)->whereIn('status', ['20', '30', '40', '60', '80'])->sum('nett');
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
        // dd($sales);
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        $leveledProspect = Prospect::whereNULL('level')->count();
        $comment = Quotation::join('change_status as c', 'c.id_quotation', '=' , 'quotation.id')
        ->join('comment as o', first: 'o.id_status', operator: '=', second: 'c.id')
        ->join('users as u', 'u.id', '=', 'o.id_user')
        ->where('quotation.id_sales', Auth::id())
        ->where('o.level', '1')
        ->orderBy('o.date','DESC')
        ->get(['quotation.id as idQ','o.id as idC','o.id_user', 'o.comment', 'o.date', 'quotation.no_quote', 'u.name', 'u.image']);
        return view('pages.overview', compact('noSaleProspect', 'comment','leveledProspect', 'sales', 'totalPO', 'totalForecast', 'filteredPO', 'filteredQuote', 'filteredDC', 'filteredCRM', 'targett'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $report = SalesReports::find($id);
        $getDC = $this->getMonthlyDataDC($report->semester, $report->year);
        $cardDC = $this->cardMonthlyDC($report->semester, $report->year);
        $getCRM = $this->getMonthlyDataCRM($report->semester, $report->year);
        $getVisit = $this->getMonthlyDataVisit($report->semester, $report->year);
        $getQuote = $this->getMonthlyDataQuote($report->semester, $report->year);
        $getPO = $this->getMonthlyDataPO($report->semester, $report->year);
        $getPOModal = $this->getMonthlyDataPOModal($report->semester, $report->year);
        $getTotalForecast = $this->getMonthlyDataTotalForecast($report->semester, $report->year);
        $getTotalPO = $this->getMonthlyDataTotalPO($report->semester, $report->year);
        $targett = Target::where('id_sales', Auth::user()->id)->pluck('total')->sum();
        // dd($getTotalPO);
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        $leveledProspect = Prospect::whereNULL('level')->count();
        $comment = Quotation::join('change_status as c', 'c.id_quotation', '=' , 'quotation.id')
        ->join('comment as o', first: 'o.id_status', operator: '=', second: 'c.id')
        ->join('users as u', 'u.id', '=', 'o.id_user')
        ->where('quotation.id_sales', Auth::id())
        ->where('o.level', '1')
        ->orderBy('o.date','DESC')
        ->get(['quotation.id as idQ','o.id as idC','o.id_user', 'o.comment', 'o.date', 'quotation.no_quote', 'u.name', 'u.image']);
        return view('pages.sales.detail-overview', compact('noSaleProspect','comment','leveledProspect', 'report', 'getDC', 'getCRM', 'getVisit', 'getQuote', 'getPO', 'getPOModal', 'getTotalForecast', 'getTotalPO', 'targett'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function semesterOverviewsales($id)
    {
        $user = User::find($id);
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        $leveledProspect = Prospect::whereNULL('level')->count();
        return view('pages.admin.overview.semester', compact('noSaleProspect','leveledProspect','user'));
    }
    public function detailSemesterOverview($sales, $date)
    {
        $user = User::find($sales);
        $dates = $date;
        $dateOver = '01-' . $date;
        $dateCarbon = Carbon::createFromFormat('d-m-Y', $dateOver);

        $month = $dateCarbon->month;
        $year = $dateCarbon->year;
        $quotation = Quotation::where('status', '100')->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->whereMonth('po_date', $month)->whereYear('po_date', $year)->get();
        // admin
        $target = Target::where('id_sales', $sales)->first();
        $totalDC = Activities::rightJoin('client', 'client.id', '=', 'activities.id_client')->whereMonth('date', $month)->where('status', 'Responded')->whereIn('name', ['Daily Call', 'Follow Up'])->where('client.id_sales', $sales)->count();
        $totalCRM = Activities::rightJoin('client', 'client.id', '=', 'activities.id_client')->whereMonth('date', $month)->where('status', 'Responded')->where('name', 'CRM')->where('client.id_sales', $sales)->count();
        $totalVisit = Activities::rightJoin('client', 'client.id', '=', 'activities.id_client')->whereMonth('date', $month)->where('status', 'Responded')->where('name', 'Visit')->where('client.id_sales', $sales)->count();
        $totalQuote = Quotation::whereIn('status', ['20', '30', '40', '60', '80'])->whereMonth('estimated_date', $month)->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->count();
        $totalPO = Quotation::where('status', '100')->whereMonth('po_date', $month)->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->count();
        $amountSales = Quotation::whereMonth('po_date', $month)->where('status', '100')->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $amountProspect = Quotation::whereMonth('estimated_date', $month)->where('status', '80')->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $amountQuote = Quotation::whereMonth('estimated_date', $month)->whereIn('status', ['20', '30', '40', '60', '80'])->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        $leveledProspect = Prospect::whereNULL('level')->count();
        return view('pages.admin.overview.kpi', compact('noSaleProspect','leveledProspect','user', 'dates', 'quotation', "totalDC", "totalCRM", "totalQuote", "totalVisit", "totalPO", "amountSales", "amountQuote", "amountProspect", "target"));
    }

    public function overviewAdmin($semester, $sales)
    {
        $report = SalesReports::find($semester);
        $getDC = $this->getMonthlyDataDCSales($report->semester, $report->year, $sales);
        $cardDC = $this->cardMonthlyDCSales($report->semester, $report->year, $sales);
        $getCRM = $this->getMonthlyDataCRMSales($report->semester, $report->year, $sales);
        $getVisit = $this->getMonthlyDataVisitSales($report->semester, $report->year, $sales);
        $getQuote = $this->getMonthlyDataQuoteSales($report->semester, $report->year, $sales);
        $getPO = $this->getMonthlyDataPOSales($report->semester, $report->year, $sales);
        $getPOModal = $this->getMonthlyDataPOModalSales($report->semester, $report->year, $sales);
        $getTotalForecast = $this->getMonthlyDataTotalForecastSales($report->semester, $report->year, $sales);
        $getTotalPO = $this->getMonthlyDataTotalPOSales($report->semester, $report->year, $sales);
        $targett = Target::where('id_sales', $sales)->pluck('total')->sum();
        $user = User::find($sales);
        // dd($getPO);
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        return view('pages.admin.overview.detail', compact('noSaleProspect','report', 'getDC', 'getCRM', 'getVisit', 'getQuote', 'getPO', 'getPOModal', 'getTotalForecast', 'getTotalPO', 'targett', 'user'));
    }
    protected function getMonthlyDataDC($semester, $year)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-1-01";
            $firstDayOfLastMonth = "{$year}-6-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'monthKey' => $monthKey,
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'monthKey' => $monthKey,
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function cardMonthlyDC($semester, $year)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-1-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    // Cek apakah data untuk bulan tersebut ada dalam $dCallPerMonth
                    // Jika tidak ada, maka jumlahnya 0
                    $total = isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0;
                    // Tambahkan total ke dalam array $fullMonthData
                    $fullMonthData[] = $total;
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }

            return response()->json($fullMonthData);
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataCRM($semester, $year)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('activities.name', 'CRM')
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('activities.name', 'CRM')
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataVisit($semester, $year)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('activities.name', 'Visit')
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('activities.name', 'Visit')
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataQuote($semester, $year)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));


            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(estimated_date), "-", MONTH(estimated_date)) as date'), DB::raw('month(estimated_date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('level', '1')->where('is_primary', '1')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(estimated_date), "-", MONTH(estimated_date)) as date'), DB::raw('month(estimated_date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('level', '1')->where('is_primary', '1')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataPO($semester, $year)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));


            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(po_date), "-", MONTH(po_date)) as date'), DB::raw('month(po_date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('level', '1')->where('is_primary', '1')
                ->where('status', '100')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(po_date), "-", MONTH(po_date)) as date'), DB::raw('month(po_date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('level', '1')->where('is_primary', '1')
                ->where('status', '100')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataPOModal($semester, $year)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select('quotation.*')
                ->selectRaw('MONTH(po_date) as month')
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('quotation.id_sales', Auth::user()->id)
                ->where('status', '100')
                ->where('level', '1')->where('is_primary', '1')
                ->get();
            // dd(Auth::user()->id);
            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $dataForMonth = $dCallPerMonth->where('month', $monthKey);
                    $fullMonthData[$monthKey] = [
                        'monthKey' => $monthKey,
                        'month' => $formattedMonth,
                        'data' => $dataForMonth ? $dataForMonth->toArray() : null,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select('quotation.*')
                ->selectRaw('MONTH(po_date) as month')
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('quotation.id_sales', Auth::user()->id)
                ->where('status', '100')
                ->where('level', '1')->where('is_primary', '1')
                ->get();
            // dd($dCallPerMonth);
            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $dataForMonth = $dCallPerMonth->where('month', $monthKey);
                    $fullMonthData[$monthKey] = [
                        'monthKey' => $monthKey,
                        'month' => $formattedMonth,
                        'data' => $dataForMonth ? $dataForMonth->toArray() : null,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataTotalForecast($semester, $year)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));


            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(estimated_date), "-", MONTH(estimated_date)) as date'), DB::raw('month(estimated_date) as month'), DB::raw('SUM(nett) as total'))
                ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('level', '1')->where('is_primary', '1')
                ->whereIn('status', ['20', '30', '40', '60', '80', '100'])
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select(DB::raw('month(estimated_date) as month'), DB::raw('SUM(nett) as total'))
                ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('level', '1')->where('is_primary', '1')
                ->whereIn('status', ['20', '30', '40', '60', '80', '100'])
                ->groupBy(DB::raw('MONTH(estimated_date)'))
                ->orderBy('month')
                ->pluck('total', 'month');
            // dd($dCallPerMonth);

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            return $fullMonthData;
        }
    }
    protected function getMonthlyDataTotalPO($semester, $year)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));


            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(po_date), "-", MONTH(po_date)) as date'), DB::raw('month(po_date) as month'), DB::raw('SUM(nett) as total'))
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('level', '1')->where('is_primary', '1')
                ->where('status', '100')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(po_date), "-", MONTH(po_date)) as date'), DB::raw('month(po_date) as month'), DB::raw('SUM(nett) as total'))
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('level', '1')->where('is_primary', '1')
                ->where('status', '100')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataDCSales($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'monthKey' => $monthKey,
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'monthKey' => $monthKey,
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function cardMonthlyDCSales($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    // Cek apakah data untuk bulan tersebut ada dalam $dCallPerMonth
                    // Jika tidak ada, maka jumlahnya 0
                    $total = isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0;
                    // Tambahkan total ke dalam array $fullMonthData
                    $fullMonthData[] = $total;
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }

            return response()->json($fullMonthData);
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }

    protected function getMonthlyDataCRMSales($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('activities.name', 'CRM')
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('activities.name', 'CRM')
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataVisitSales($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('activities.name', 'Visit')
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('activities.name', 'Visit')
                ->where('status', 'Responded')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataQuoteSales($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));


            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(estimated_date), "-", MONTH(estimated_date)) as date'), DB::raw('month(estimated_date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->where('level', '1')->where('is_primary', '1')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(estimated_date), "-", MONTH(estimated_date)) as date'), DB::raw('month(estimated_date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->where('level', '1')->where('is_primary', '1')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataPOSales($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));


            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(po_date), "-", MONTH(po_date)) as date'), DB::raw('month(po_date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->where('status', '100')
                ->where('level', '1')->where('is_primary', '1')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(po_date), "-", MONTH(po_date)) as date'), DB::raw('month(po_date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->where('status', '100')
                ->where('level', '1')->where('is_primary', '1')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataPOModalSales($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select('quotation.*')
                ->selectRaw('MONTH(po_date) as month')
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('quotation.id_sales', $sales)
                ->where('status', '100')
                ->where('level', '1')->where('is_primary', '1')
                ->get();
            // dd($sales);
            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $dataForMonth = $dCallPerMonth->where('month', $monthKey);
                    $fullMonthData[$monthKey] = [
                        'monthKey' => $monthKey,
                        'month' => $formattedMonth,
                        'data' => $dataForMonth ? $dataForMonth->toArray() : null,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select('quotation.*')
                ->selectRaw('MONTH(po_date) as month')
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('quotation.id_sales', $sales)
                ->where('status', '100')
                ->where('level', '1')->where('is_primary', '1')
                ->get();
            // dd($dCallPerMonth);
            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $dataForMonth = $dCallPerMonth->where('month', $monthKey);
                    $fullMonthData[$monthKey] = [
                        'monthKey' => $monthKey,
                        'month' => $formattedMonth,
                        'data' => $dataForMonth ? $dataForMonth->toArray() : null,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataTotalForecastSales($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));


            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(estimated_date), "-", MONTH(estimated_date)) as date'), DB::raw('month(estimated_date) as month'), DB::raw('SUM(nett) as total'))
                ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->where('level', '1')->where('is_primary', '1')
                ->whereIn('status', ['20', '30', '40', '60', '80', '100'])
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(estimated_date), "-", MONTH(estimated_date)) as date'), DB::raw('month(estimated_date) as month'), DB::raw('SUM(nett) as total'))
                ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->where('level', '1')->where('is_primary', '1')
                ->whereIn('status', ['20', '30', '40', '60', '80'])
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        }
    }
    protected function getMonthlyDataTotalPOSales($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));


            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(po_date), "-", MONTH(po_date)) as date'), DB::raw('month(po_date) as month'), DB::raw('SUM(nett) as total'))
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->where('level', '1')->where('is_primary', '1')
                ->where('status', '100')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            $fullMonthData = [];
            for ($month = 1; $month <= 6; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }
            // dd($fullMonthData);

            return $fullMonthData;
        } else {
            $firstDayOfMonth = "{$year}-07-01";
            $firstDayOfLastMonth = "{$year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(po_date), "-", MONTH(po_date)) as date'), DB::raw('month(po_date) as month'), DB::raw('SUM(nett) as total'))
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->where('level', '1')->where('is_primary', '1')
                ->where('status', '100')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');
            // dd($dCallPerMonth);

            $fullMonthData = [];
            for ($month = 7; $month <= 12; $month++) {
                $monthKey = "{$month}";
                $carbonMonth = Carbon::parse($firstDayOfMonth);
                $formattedMonth = isset($plusMonth) ? $plusMonth->format('F') : $carbonMonth->format('F');
                $monthDays = date('t', strtotime($monthKey));
                if ($monthDays >= 4) {
                    $fullMonthData[$monthKey] = [
                        'month' => $formattedMonth,
                        'total' => isset($dCallPerMonth[$monthKey]) ? $dCallPerMonth[$monthKey] : 0,
                    ];
                }
                $plusMonth = isset($plusMonth) ? $plusMonth->addMonth() : $carbonMonth->addMonth();
            }

            return $fullMonthData;
        }
    }
}
