<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\SalesOnline;
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
        $support = User::find(22);
        return view('pages.overview', compact('sales', 'support'));
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
        $getLeads = $this->getMonthlyDataLeads($report->semester, $report->year);
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
        return view('pages.sales.detail-overview', compact('noSaleProspect', 'comment', 'unreadComment', 'commentAdmin', 'unreadCommentAdmin', 'leveledProspect', 'report', 'getDC', 'getCRM', 'getVisit', 'getQuote', 'getPO', 'getLeads', 'getPOModal', 'getTotalForecast', 'getTotalPO', 'targett'));
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
        $totalDC = Activities::rightJoin('client', 'client.id', '=', 'activities.id_client')->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Responded')->whereIn('name', ['Daily Call', 'Follow Up'])->where('client.id_sales', $sales)->distinct('client.id')->count();
        $totalCRM = Activities::rightJoin('client', 'client.id', '=', 'activities.id_client')->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Responded')->where('name', 'CRM')->where('client.id_sales', $sales)->distinct('client.id')->count();
        $totalVisit = Activities::rightJoin('client', 'client.id', '=', 'activities.id_client')->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Responded')->where('name', 'Visit')->where('client.id_sales', $sales)->count();
        $totalQuote = Quotation::whereIn('status', ['20', '30', '40', '60', '80'])->whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->count();
        $totalPO = Quotation::where('status', '100')->whereMonth('po_date', $month)->whereYear('po_date', $year)->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->count();
        $totalLoss = Quotation::where('status', '0')->whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->count();
        $totalProspect = Quotation::where('status', '80')->whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->count();
        $totalLeads = Client::whereMonth('created_at', $month)->whereYear('created_at', $year)->where('id_sales', $sales)->count();
        $amountSales = Quotation::whereMonth('po_date', $month)->whereYear('po_date', $year)->where('status', '100')->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $amountProspect = Quotation::whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->where('status', '80')->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $amountQuote = Quotation::whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->whereIn('status', ['20', '30', '40', '60', '80'])->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $amountQuoteLoss = Quotation::whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->where('status', '0')->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        $leveledProspect = Prospect::whereNULL('level')->count();
        $jumlahCustomer = Client::where('role', 'Customers')->where('id_sales', $sales)->count();
        // support
        $filteredProspect = Prospect::whereYear('date', $year)->whereMonth('date', $month)->where('id_support', $sales)->count();
        $filteredProvide = Prospect::whereYear('date', $year)->whereMonth('date', $month)->where('provide', '!=', '0')->where('id_support', $sales)->count();
        $filteredProspectQuote = Quotation::whereYear('estimated_date', $year)->whereMonth('estimated_date', $month)->where('id_support', $sales)->where('level', '1')->where('is_primary', '1')->count();
        $filteredProspectPO = Quotation::whereYear('po_date', $year)->whereMonth('po_date', $month)->where('id_support', $sales)->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        $totalProspectQuote = Quotation::whereYear('estimated_date', $year)->whereMonth('estimated_date', $month)->where('id_support', $sales)->where('status', '!=', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $totalProspectPO = Quotation::whereYear('po_date', $year)->whereMonth('po_date', $month)->where('id_support', $sales)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        // Overview Ari
        $akurasiCount = SalesOnline::where('id_sales', $sales)->where('type', 'Akurasi')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
        $deliveryCount = SalesOnline::where('id_sales', $sales)->where('type', 'Delivery')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
        $responseCount = SalesOnline::where('id_sales', $sales)->where('type', 'Response')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
        $ratingCount = SalesOnline::where('id_sales', $sales)->where('type', 'Rating')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
        $customerCount = SalesOnline::where('id_sales', $sales)->where('type', 'Customer')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
        $videoCount = SalesOnline::where('id_sales', $sales)->where('type', 'Video')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
        $SWCount = SalesOnline::where('id_sales', $sales)->where('type', 'SW')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
        $productCount = SalesOnline::where('id_sales', $sales)->where('type', 'Product')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->count();
        $POCount = Quotation::where('id_sales', $sales)->where('is_primary', '1')->where('status', '100')->where('level', '1')->whereMonth('po_date', Carbon::now())->whereYear('po_date', Carbon::now())->count();
        $onlineSales = SalesOnline::selectRaw("DATE_FORMAT(date, '%d-%m-%Y') as date")
            ->selectRaw("GROUP_CONCAT(product SEPARATOR '|') as product")
            ->selectRaw("GROUP_CONCAT(desc_product SEPARATOR '|') as desc_product")
            ->whereMonth('date', Carbon::now())
            ->whereYear('date', Carbon::now())
            ->groupBy(DB::raw("DATE_FORMAT(date, '%d-%m-%Y')"))
            ->orderBy(DB::raw("STR_TO_DATE(DATE_FORMAT(date, '%d-%m-%Y'), '%d-%m-%Y')"))
            ->get();

        $onSale = $onlineSales->map(function ($row) {
            $productArray = explode('|', $row->product);

            return [
                'date' => $row->date,
                'qty' => count($productArray),
                'link' => [
                    'product' => $productArray,
                    'desc_product' => explode('|', $row->desc_product)
                ]
            ];
        });
        // dd($onSale);

        return view('pages.admin.overview.kpi', compact('onSale', 'POCount', 'productCount', 'SWCount', 'videoCount', 'customerCount', 'ratingCount', 'responseCount', 'deliveryCount', 'akurasiCount', 'totalProspect', 'totalProspectPO', 'totalProspectQuote', 'filteredProvide', 'filteredProspectPO', 'filteredProspectQuote', 'filteredProspect', 'jumlahCustomer', 'noSaleProspect', 'leveledProspect', 'user', 'dates', 'quotation', "totalDC", "totalCRM", "totalQuote", "totalVisit", "totalPO", "totalLoss", "totalLeads", "amountSales", "amountQuote", "amountQuoteLoss", "amountProspect", "target"));
    }

    public function overviewAdmin($semester, $sales)
    {
        $report = SalesReports::find($semester);
        if ($report->semester == 1) {
            $first = "{$report->year}-01-01";
            $last = "{$report->year}-06-01";
            $lastDay = date('Y-m-t', strtotime($last));
        } else {
            $first = "{$report->year}-07-01";
            $last = "{$report->year}-12-01";
            $lastDay = date('Y-m-t', strtotime($last));
        }
        $semester = $report->semester;

        $months = $semester == 1
            ? [1, 2, 3, 4, 5, 6]       // Semester 1: Jan - Jun
            : [7, 8, 9, 10, 11, 12];   // Semester 2: Jul - Des

        $currentYear = $report->year ?? date('Y'); // Misal kamu punya kolom tahun

        $customerCounts = [];

        foreach ($months as $month) {
            $lastDay = Carbon::create($currentYear, $month)->endOfMonth();

            $count = Client::where('role', 'Customers')
                ->whereDate('created_at', '<=', $lastDay)
                ->count();

            $customerCounts[] = [
                'month' => $lastDay->format('F'),
                'count' => $count,
            ];
        }

        $avgCRM = 0;
        foreach ($customerCounts as $customer) {
            $avgCRM = +$customer['count'];
        }
        $averageCRM = round($avgCRM / 6);

        // dataSemester
        $quoteSemester = Quotation::whereBetween('estimated_date', [$first, $lastDay])->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->count();
        $POSemester = Quotation::whereBetween('po_date', [$first, $lastDay])->where('id_sales', $sales)->where('status', 100)->where('level', '1')->where('is_primary', '1')->count();
        $lossSemester = Quotation::whereBetween('estimated_date', [$first, $lastDay])->where('id_sales', $sales)->where('status', 0)->where('level', '1')->where('is_primary', '1')->count();
        $totalQuoteSemester = Quotation::whereBetween('estimated_date', [$first, $lastDay])->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $payments = DB::table('payment')
            ->select(
                'id_quotation',
                DB::raw('SUM(amount - pph - cost) as total_payment')
            )
            ->groupBy('id_quotation');
        $totalPOSemester = Quotation::whereBetween('po_date', [$first, $lastDay])
            ->leftJoinSub($payments, 'p', function ($join) {
                $join->on('p.id_quotation', '=', 'id');
            })
            ->where('id_sales', $sales)
            ->where('status', 100)
            ->where('level', '1')
            ->where('is_primary', '1')
            ->sum(DB::raw('nett - COALESCE(p.total_payment,0)'));

        $totalLossSemester = Quotation::whereBetween('estimated_date', [$first, $lastDay])->where('id_sales', $sales)->where('status', 0)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $totalDCSemester = Activities::whereBetween('date', [$first, $lastDay])->rightJoin('client', 'client.id', '=', 'activities.id_client')->where('status', 'Responded')->whereIn('name', ['Daily Call', 'Follow Up'])->where('client.id_sales', $sales)->count();
        $totalCRMSemester = Activities::whereBetween('date', [$first, $lastDay])->rightJoin('client', 'client.id', '=', 'activities.id_client')->where('status', 'Responded')->where('name', 'CRM')->where('client.id_sales', $sales)->distinct('client.id')->count();
        $totalLeadsSemester = Client::whereBetween('created_date', [$first, $lastDay])->where('id_sales', $sales)->count();
        // dd($first);

        //  Prospect Semster
        $quoteSemesterProspect = Quotation::whereBetween('estimated_date', [$first, $lastDay])->where('id_support', $sales)->where('level', '1')->where('is_primary', '1')->count();
        $POSemesterProspect = Quotation::whereBetween('po_date', [$first, $lastDay])->where('id_support', $sales)->where('status', 100)->where('level', '1')->where('is_primary', '1')->count();
        $lossSemesterProspect = Quotation::whereBetween('estimated_date', [$first, $lastDay])->where('id_support', $sales)->where('status', 0)->where('level', '1')->where('is_primary', '1')->count();
        $totalQuoteSemesterProspect = Quotation::whereBetween('estimated_date', [$first, $lastDay])->where('id_support', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $totalPOSemesterProspect = Quotation::whereBetween('po_date', [$first, $lastDay])->where('id_support', $sales)->where('status', 100)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $totalLossSemesterProspect = Quotation::whereBetween('estimated_date', [$first, $lastDay])->where('id_support', $sales)->where('status', 0)->where('level', '1')->where('is_primary', '1')->sum('nett');
        // dd($totalLossSemesterProspect

        // data all month
        $getDC = $this->getMonthlyDataDCSales($report->semester, $report->year, $sales);
        $cardDC = $this->cardMonthlyDCSales($report->semester, $report->year, $sales);
        $getCRM = $this->getMonthlyDataCRMSales($report->semester, $report->year, $sales);
        $getVisit = $this->getMonthlyDataVisitSales($report->semester, $report->year, $sales);
        $getQuote = $this->getMonthlyDataQuoteSales($report->semester, $report->year, $sales);
        $getPO = $this->getMonthlyDataPOSales($report->semester, $report->year, $sales);
        $getLoss = $this->getMonthlyDataLossSales($report->semester, $report->year, $sales);
        $getLeads = $this->getMonthlyDataLeadsSales($report->semester, $report->year, $sales);
        $getTotalForecast = $this->getMonthlyDataTotalForecastSales($report->semester, $report->year, $sales);
        $getTotalPO = $this->getMonthlyDataTotalPOSales($report->semester, $report->year, $sales);
        // dd($getLoss);

        // Prospect
        $getProspect = $this->getMonthlyDataProspect($report->semester, $report->year, $sales);
        $getProspectProvide = $this->getMonthlyDataProvideProspect($report->semester, $report->year, $sales);
        $getQuoteProspect = $this->getMonthlyDataQuoteProspect($report->semester, $report->year, $sales);
        $getPOProspect = $this->getMonthlyDataPOProspect($report->semester, $report->year, $sales);
        $getTotalForecastProspect = $this->getMonthlyDataTotalForecastProspect($report->semester, $report->year, $sales);
        $getTotalPOProspect = $this->getMonthlyDataTotalPOProspect($report->semester, $report->year, $sales);
        $targett = Target::where('id_sales', $sales)->pluck('total')->sum();
        $target = Target::where('id_sales', $sales)->first();
        $user = User::find($sales);
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();

        $getPOModal = $this->getMonthlyDataPOModalSales($report->semester, $report->year, $sales);
        // dd($target);

        return view('pages.admin.overview.detail', compact('averageCRM', 'totalDCSemester', 'totalCRMSemester', 'totalLeadsSemester', 'totalLossSemesterProspect', 'totalPOSemesterProspect', 'totalQuoteSemesterProspect', 'lossSemesterProspect', 'POSemesterProspect', 'quoteSemesterProspect', 'totalCRMSemester', 'totalDCSemester', 'totalLossSemester', 'totalPOSemester', 'totalQuoteSemester', 'lossSemester', 'POSemester', 'quoteSemester', 'getPOProspect', 'getQuoteProspect', 'getProspectProvide', 'getProspect', 'getTotalForecastProspect', 'getTotalPOProspect', 'noSaleProspect', 'report', 'getDC', 'getCRM', 'getVisit', 'getQuote', 'getPO', 'getLoss', 'getLeads', 'getPOModal', 'getTotalForecast', 'getTotalPO', 'target', 'targett', 'user'));
    }
    public function reportsSemester($semester)
    {
        $report = SalesReports::find($semester);
        $semester = SalesReports::all();
        $startMonth = $report->semester == 1 ? 1 : 7;
        $endMonth = $report->semester == 1 ? 6 : 12;
        if ($report->semester == 1) {
            $firstDayOfMonth = "{$report->year}-1-01";
            $firstDayOfLastMonth = "{$report->year}-6-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));
        } else {
            $firstDayOfMonth = "{$report->year}-07-01";
            $firstDayOfLastMonth = "{$report->year}-12-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));
        }
        $poCount = Quotation::whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        $lossCount = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->where('status', '0')->where('level', '1')->where('is_primary', '1')->count();
        $quoteCount = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->whereIn('status', ['20', '40', '60', '80', '90'])->where('level', '1')->where('is_primary', '1')->count();
        $quoteOnCount = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->where('level', '1')->where('is_primary', '1')->count();
        $payments = DB::table('payment')
            ->select(
                'id_quotation',
                DB::raw('SUM(amount - pph - cost) as total_payment')
            )
            ->groupBy('id_quotation');
        $poTotal = Quotation::whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
            ->leftJoinSub($payments, 'p', function ($join) {
                $join->on('p.id_quotation', '=', 'id');
            })
            ->where('status', '100')
            ->where('level', '1')
            ->where('is_primary', '1')
            ->sum(DB::raw('nett - COALESCE(p.total_payment,0)'));
        $lossTotal = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->where('status', '0')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quoteTotal = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->whereIn('status', ['20', '40', '60', '80', '90'])->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quoteOnTotal = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->where('level', '1')->where('is_primary', '1')->sum('nett');
        $sales = User::where('role', 'Sales')->where('active', '1')->get();
        $target = Target::orderBy('id_sales')->groupBy('id_sales')->get();
        $totalTarget = $target->sum('total');
        $support = User::find('22');
        $dataSupport = DB::table('quotation')
            ->selectRaw('MONTH(po_date) as bulan, SUM(nett) as total')
            ->whereNotNull('id_support')
            ->where('status', 100)
            ->where('level', '1')
            ->where('is_primary', '1')
            ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
            ->groupBy(DB::raw('MONTH(po_date)'))
            ->get();
        $poTotalSupport = Quotation::whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])->whereNotNull('id_support')->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');

        // dd($dataSupport);

        $data = [];

        foreach ($sales as $user) {

            $noPayment = DB::table('quotation as q')
                ->whereBetween('q.po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('q.id_sales', $sales->id)
                ->where('q.status', '100')
                ->where('q.level', '1')
                ->where('q.is_primary', '1')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('payment as p')
                        ->whereRaw('p.id_quotation = q.id');
                })
                ->select(DB::raw('q.nett as total'));

            $withPayment = DB::table('payment as p')
                ->join('quotation as q', 'q.id', '=', 'p.id_quotation')
                ->whereBetween('p.date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('q.id_sales', $sales->id)
                ->where('q.status', '100')
                ->where('q.level', '1')
                ->where('q.is_primary', '1')
                ->select(DB::raw('(p.amount - p.pph - p.cost) as total'));

            $poTotalSales = DB::query()
                ->fromSub(
                    $noPayment->unionAll($withPayment),
                    'x'
                )
                ->sum('total');

            // $poTotalSales = Quotation::whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])->where('id_sales', $user->id)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');

            $noPayment = DB::table('quotation as q')
                ->whereBetween('q.po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('q.id_sales', $user->id)
                ->where('q.status', '100')
                ->where('q.level', '1')
                ->where('q.is_primary', '1')
                ->whereNotExists(function ($query) use ($firstDayOfMonth, $lastDayOfMonth) {
                    $query->select(DB::raw(1))
                        ->from('payment as p')
                        ->whereRaw('p.id_quotation = q.id')
                        ->whereBetween('p.date', [$firstDayOfMonth, $lastDayOfMonth]); // 🔥 penting!
                })
                ->selectRaw('
        CAST(MONTH(q.po_date) AS UNSIGNED) as bulan,
        q.nett as total
    ');

            $withPayment = DB::table('payment as p')
                ->join('quotation as q', 'q.id', '=', 'p.id_quotation')
                ->where('q.id_sales', $user->id)
                ->whereBetween('p.date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('q.status', '100')
                ->where('q.level', '1')
                ->where('q.is_primary', '1')
                ->selectRaw('
        CAST(MONTH(p.date) AS UNSIGNED) as bulan,
        (COALESCE(p.amount,0) - COALESCE(p.pph,0) - COALESCE(p.cost,0)) as total
    ');

            $bulananRaw = DB::query()
                ->fromSub(
                    $noPayment->unionAll($withPayment),
                    'x'
                )
                ->selectRaw('bulan, SUM(total) as total')
                ->groupBy('bulan')
                ->pluck('total', 'bulan')
                ->toArray();

            $jumlah = [];

            for ($i = $startMonth; $i <= $endMonth; $i++) {
                $jumlah[] = [
                    'bulan' => $i,
                    'total' => (int) ($bulananRaw[$i] ?? 0)
                ];
            }

            $data[] = [
                'id' => $user->id,
                'image' => $user->image,
                'name' => $user->name,
                'target' => $target->where('id_sales', $user->id)->pluck('total')->first(),
                'total' => $poTotalSales,
                'jumlah' => $jumlah
            ];
        }
        // dd($data);
        return view('pages.admin.report', compact(
            'poCount',
            'lossCount',
            'quoteCount',
            'quoteOnCount',
            'poTotal',
            'lossTotal',
            'quoteTotal',
            'quoteOnTotal',
            'sales',
            'data',
            'totalTarget',
            'report',
            'semester',
            'support',
            'dataSupport',
            'poTotalSupport'
        ));
    }
    protected function getMonthlyDataDC($semester, $year)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-1-01";
            $firstDayOfLastMonth = "{$year}-6-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Activities::select(
                DB::raw('MONTH(date) as month'),
                'activities.id_client'
            )
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month', 'activities.id_client')
                ->get()
                ->groupBy('month')
                ->mapWithKeys(fn($items, $month) => [$month => $items->count()]);

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

            $dCallPerMonth = Activities::select(
                DB::raw('MONTH(date) as month'),
                'activities.id_client'
            )
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month', 'activities.id_client')
                ->get()
                ->groupBy('month')
                ->mapWithKeys(fn($items, $month) => [$month => $items->count()]);

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

            $dCallPerMonth = Activities::select(
                DB::raw('MONTH(date) as month'),
                'activities.id_client'
            )
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month', 'activities.id_client')
                ->get()
                ->groupBy('month')
                ->mapWithKeys(fn($items, $month) => [$month => $items->count()]);

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

            $dCallPerMonth = Activities::select(
                DB::raw('MONTH(date) as month'),
                'activities.id_client'
            )
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month', 'activities.id_client')
                ->get()
                ->groupBy('month')
                ->mapWithKeys(fn($items, $month) => [$month => $items->count()]);

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

            $dCallPerMonth = Activities::select(
                DB::raw('MONTH(date) as month'),
                'activities.id_client'
            )
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('activities.name', 'CRM')
                ->where('status', 'Responded')
                ->groupBy(DB::raw('MONTH(date)'), 'activities.id_client')
                ->get()
                ->groupBy('month')
                ->mapWithKeys(fn($items, $month) => [$month => $items->count()]);

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

            $dCallPerMonth = Activities::select(
                DB::raw('MONTH(date) as month'),
                'activities.id_client'
            )
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
                ->where('activities.name', 'CRM')
                ->where('status', 'Responded')
                ->groupBy(DB::raw('MONTH(date)'), 'activities.id_client')
                ->get()
                ->groupBy('month')
                ->mapWithKeys(fn($items, $month) => [$month => $items->count()]);

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
    protected function getMonthlyDataLeads($semester, $year)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Client::select(DB::raw('CONCAT(YEAR(created_at), "-", MONTH(created_at)) as date'), DB::raw('month(created_at) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
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

            $dCallPerMonth = Client::select(DB::raw('CONCAT(YEAR(created_at), "-", MONTH(created_at)) as date'), DB::raw('month(created_at) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', Auth::user()->id)
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

            $dCallPerMonth = Activities::select(
                DB::raw('MONTH(date) as month'),
                'activities.id_client'
            )
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month', 'activities.id_client')
                ->get()
                ->groupBy('month')
                ->mapWithKeys(fn($items, $month) => [$month => $items->count()]);

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

            $dCallPerMonth = Activities::select(
                DB::raw('MONTH(date) as month'),
                'activities.id_client'
            )
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month', 'activities.id_client')
                ->get()
                ->groupBy('month')
                ->mapWithKeys(fn($items, $month) => [$month => $items->count()]);

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

            $dCallPerMonth = Activities::select(
                DB::raw('MONTH(date) as month'),
                'activities.id_client'
            )
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month', 'activities.id_client')
                ->get()
                ->groupBy('month')
                ->mapWithKeys(fn($items, $month) => [$month => $items->count()]);

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

            $dCallPerMonth = Activities::select(
                DB::raw('MONTH(date) as month'),
                'activities.id_client'
            )
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                ->where('status', 'Responded')
                ->groupBy('month', 'activities.id_client')
                ->get()
                ->groupBy('month')
                ->mapWithKeys(fn($items, $month) => [$month => $items->count()]);

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

            $dCallPerMonth = Activities::select(
                DB::raw('MONTH(date) as month'),
                'activities.id_client'
            )
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->where('activities.name', 'CRM')
                ->where('status', 'Responded')
                ->groupBy(DB::raw('MONTH(date)'), 'activities.id_client')
                ->get()
                ->groupBy('month')
                ->mapWithKeys(fn($items, $month) => [$month => $items->count()]);

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

            $dCallPerMonth = Activities::select(
                DB::raw('MONTH(date) as month'),
                'activities.id_client'
            )
                ->join('client as c', 'activities.id_client', '=', 'c.id')
                ->join('users as u', 'c.id_sales', '=', 'u.id')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->where('activities.name', 'CRM')
                ->where('status', 'Responded')
                ->groupBy(DB::raw('MONTH(date)'), 'activities.id_client')
                ->get()
                ->groupBy('month')
                ->mapWithKeys(fn($items, $month) => [$month => $items->count()]);

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
    protected function getMonthlyDataLossSales($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(estimated_date), "-", MONTH(estimated_date)) as date'), DB::raw('month(estimated_date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
                ->where('status', '0')
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
                ->where('status', '0')
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
    protected function getMonthlyDataProspect($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Prospect::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_support', $sales)
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

            $dCallPerMonth = Prospect::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_support', $sales)
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
    protected function getMonthlyDataProvideProspect($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Prospect::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_support', $sales)
                ->where('provide', '!=', '0')
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

            $dCallPerMonth = Prospect::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date)) as date'), DB::raw('month(date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_support', $sales)
                ->where('provide', '!=', '0')
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
    protected function getMonthlyDataQuoteProspect($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));


            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(estimated_date), "-", MONTH(estimated_date)) as date'), DB::raw('month(estimated_date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_support', $sales)
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
                ->where('id_support', $sales)
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
    protected function getMonthlyDataPOProspect($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(po_date), "-", MONTH(po_date)) as date'), DB::raw('month(po_date) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_support', $sales)
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
                ->where('id_support', $sales)
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
    protected function getMonthlyDataLeadsSales($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Client::select(DB::raw('CONCAT(YEAR(created_at), "-", MONTH(created_at)) as date'), DB::raw('month(created_at) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
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

            $dCallPerMonth = Client::select(DB::raw('CONCAT(YEAR(created_at), "-", MONTH(created_at)) as date'), DB::raw('month(created_at) as month'), DB::raw('COUNT(*) as total'))
                ->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_sales', $sales)
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
        $user = User::find($sales);
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            if ($user->role == 'Sales') {
                $dCallPerMonth = Quotation::select('quotation.*')
                    ->selectRaw('MONTH(po_date) as month')
                    ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                    ->where('quotation.id_sales', $sales)
                    ->where('status', '100')
                    ->where('level', '1')->where('is_primary', '1')
                    ->get();
            } else {
                $dCallPerMonth = Quotation::select('quotation.*')
                    ->selectRaw('MONTH(po_date) as month')
                    ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                    ->where('quotation.id_support', $sales)
                    ->where('status', '100')
                    ->where('level', '1')->where('is_primary', '1')
                    ->get();
            }
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

            if ($user->role == 'Sales') {
                $dCallPerMonth = Quotation::select('quotation.*')
                    ->selectRaw('MONTH(po_date) as month')
                    ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                    ->where('quotation.id_sales', $sales)
                    ->where('status', '100')
                    ->where('level', '1')->where('is_primary', '1')
                    ->get();
            } else {
                $dCallPerMonth = Quotation::select('quotation.*')
                    ->selectRaw('MONTH(po_date) as month')
                    ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                    ->where('quotation.id_support', $sales)
                    ->where('status', '100')
                    ->where('level', '1')->where('is_primary', '1')
                    ->get();
            }
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
    protected function getMonthlyDataTotalForecastProspect($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));

            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(estimated_date), "-", MONTH(estimated_date)) as date'), DB::raw('month(estimated_date) as month'), DB::raw('SUM(nett) as total'))
                ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_support', $sales)
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
                ->where('id_support', $sales)
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

            $payments = DB::table('payment')
                ->select(
                    'id_quotation',
                    DB::raw('SUM(amount - pph - cost) as total_payment')
                )
                ->groupBy('id_quotation');

            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(po_date), "-", MONTH(po_date)) as date'), DB::raw('month(po_date) as month'), DB::raw('nett - COALESCE(p.total_payment,0) as total'))
                ->leftJoinSub($payments, 'p', function ($join) {
                    $join->on('p.id_quotation', '=', 'id');
                })
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
    protected function getMonthlyDataTotalPOProspect($semester, $year, $sales)
    {
        if ($semester == 1) {
            $firstDayOfMonth = "{$year}-01-01";
            $firstDayOfLastMonth = "{$year}-06-01";
            $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfLastMonth));


            $dCallPerMonth = Quotation::select(DB::raw('CONCAT(YEAR(po_date), "-", MONTH(po_date)) as date'), DB::raw('month(po_date) as month'), DB::raw('SUM(nett) as total'))
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->where('id_support', $sales)
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
                ->where('id_support', $sales)
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
