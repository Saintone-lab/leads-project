<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Comment;
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
        $sales = User::where('role', 'Sales')->where('active', '1')->get();
        return view('pages.overview', compact('sales'));
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
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        $leveledProspect = Prospect::whereNULL('level')->count();


        // Comment Buat Admin
        $firstComments = Comment::where('id_user', Auth::id())
            ->groupBy('id_status')
            ->get();

        $statusIds = $firstComments->pluck('id_status')->toArray();
        $dates = $firstComments->pluck('created_at', 'id_status');

        $commentsQuery = Comment::join('change_status as c', 'c.id', '=', 'comment.id_status')
            ->join('quotation as q', 'q.id', '=', 'c.id_quotation')
            ->join('users as u', 'u.id', '=', 'comment.id_user')
            ->whereIn('comment.id_status', $statusIds)
            ->where(function ($query) use ($dates) {
                foreach ($dates as $statusId => $createdAt) {
                    $query->orWhere(function ($subQuery) use ($statusId, $createdAt) {
                        $subQuery->where('comment.id_status', $statusId)
                            ->whereRaw('TIMESTAMPDIFF(SECOND, ?, comment.created_at) > 0', [$createdAt]);
                    });
                }
            })
            ->where('comment.id_user', '!=', Auth::id());

        // Ambil semua komentar yang relevan
        $commentAdmin = $commentsQuery->orderBy('comment.id_status')
            ->orderByDesc('comment.created_at')
            ->get(['q.id as idQ', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'q.no_quote', 'u.name', 'u.image']);

        // Filter untuk komentar dengan level '1'
        $unreadCommentAdmin = $commentsQuery->where('comment.level', '1')
            ->orderBy('comment.id_status')
            ->orderByDesc('comment.created_at')
            ->get(['q.id as idQ', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'q.no_quote', 'u.name', 'u.image']);

        // End Comment Admin
        $quotationComment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', 'o.id_status', '=', 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            ->where('quotation.id_sales', Auth::id())
            ->where('o.type', 'quotation')  // Pastikan filter type di sini
            ->where('o.id_user', '!=', Auth::id())
            ->orderBy('o.date', 'DESC')
            ->select(['quotation.id as idQ', 'o.id as idC', 'o.id_user', 'o.level', 'o.comment', 'o.date', 'o.type', 'quotation.no_quote', 'u.name', 'u.image']);

        // Query untuk mengambil data dengan type "prospect"
        $prospectComment = Comment::join('prospect as p', 'comment.id_prospect', '=', 'p.id')
            ->join('users as u', 'u.id', '=', 'comment.id_user')
            ->join('pic as pi', 'pi.id', '=', 'p.id_pic')
            ->join('client as c', 'c.id', '=', 'pi.id_client')
            ->where('p.id_sales', Auth::id())
            ->where('comment.type', 'prospect')  // Pastikan filter type di sini
            ->where('comment.id_user', '!=', Auth::id())
            ->orderBy('comment.date', 'DESC')
            ->select(['p.id as idP', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'comment.type', 'c.company', 'u.name', 'u.image']);

        // Menggabungkan kedua query menggunakan union
        $comment = $quotationComment->union($prospectComment)
            ->orderBy('date', 'DESC')
            ->take(5)
            ->get();
        $unreadComment = $quotationComment->union($prospectComment)
            ->orderBy('date', 'DESC')
            ->where('o.level', '1')
            ->take(5)
            ->get();
        return view('pages.sales.detail-overview', compact('noSaleProspect', 'comment', 'unreadComment', 'commentAdmin', 'unreadCommentAdmin', 'leveledProspect', 'report', 'getDC', 'getCRM', 'getVisit', 'getQuote', 'getPO', 'getPOModal', 'getTotalForecast', 'getTotalPO', 'targett'));
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
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        $leveledProspect = Prospect::whereNULL('level')->count();
        return view('pages.admin.overview.semester', compact('noSaleProspect', 'leveledProspect', 'user'));
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
        $totalDC = Activities::rightJoin('client', 'client.id', '=', 'activities.id_client')->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Responded')->whereIn('name', ['Daily Call', 'Follow Up'])->where('client.id_sales', $sales)->count();
        $totalCRM = Activities::rightJoin('client', 'client.id', '=', 'activities.id_client')->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Responded')->where('name', 'CRM')->where('client.id_sales', $sales)->count();
        $totalVisit = Activities::rightJoin('client', 'client.id', '=', 'activities.id_client')->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Responded')->where('name', 'Visit')->where('client.id_sales', $sales)->count();
        $totalQuote = Quotation::whereIn('status', ['20', '30', '40', '60', '80'])->whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->count();
        $totalPO = Quotation::where('status', '100')->whereMonth('po_date', $month)->whereYear('po_date', $year)->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->count();
        $amountSales = Quotation::whereMonth('po_date', $month)->whereYear('po_date', $year)->where('status', '100')->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $amountProspect = Quotation::whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->where('status', '80')->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $amountQuote = Quotation::whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->whereIn('status', ['20', '30', '40', '60', '80'])->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        $leveledProspect = Prospect::whereNULL('level')->count();
        return view('pages.admin.overview.kpi', compact('noSaleProspect', 'leveledProspect', 'user', 'dates', 'quotation', "totalDC", "totalCRM", "totalQuote", "totalVisit", "totalPO", "amountSales", "amountQuote", "amountProspect", "target"));
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
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        return view('pages.admin.overview.detail', compact('noSaleProspect', 'report', 'getDC', 'getCRM', 'getVisit', 'getQuote', 'getPO', 'getPOModal', 'getTotalForecast', 'getTotalPO', 'targett', 'user'));
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
                ->where('id_sales', $sales)
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
                ->where('id_sales', $sales)
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
                ->where('id_sales', $sales)
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
                ->where('id_sales', $sales)
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
