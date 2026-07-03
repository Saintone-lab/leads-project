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
        if (!$report) {
            abort(404, 'Data semester tidak ditemukan.');
        }
        $year = $report->year;

        $getDCS1 = $this->getMonthlyDataDC(1, $year);
        $getDCS2 = $this->getMonthlyDataDC(2, $year);
        $getCRMS1 = $this->getMonthlyDataCRM(1, $year);
        $getCRMS2 = $this->getMonthlyDataCRM(2, $year);
        $getVisitS1 = $this->getMonthlyDataVisit(1, $year);
        $getVisitS2 = $this->getMonthlyDataVisit(2, $year);
        $getQuoteS1 = $this->getMonthlyDataQuote(1, $year);
        $getQuoteS2 = $this->getMonthlyDataQuote(2, $year);
        $getPOS1 = $this->getMonthlyDataPO(1, $year);
        $getPOS2 = $this->getMonthlyDataPO(2, $year);
        $getLeadsS1 = $this->getMonthlyDataLeads(1, $year);
        $getLeadsS2 = $this->getMonthlyDataLeads(2, $year);
        $getPOModalS1 = $this->getMonthlyDataPOModal(1, $year);
        $getPOModalS2 = $this->getMonthlyDataPOModal(2, $year);
        $getTotalForecastS1 = $this->getMonthlyDataTotalForecast(1, $year);
        $getTotalForecastS2 = $this->getMonthlyDataTotalForecast(2, $year);
        $getTotalPOS1 = $this->getMonthlyDataTotalPO(1, $year);
        $getTotalPOS2 = $this->getMonthlyDataTotalPO(2, $year);
        $targett = Target::where('id_sales', Auth::user()->id)->pluck('total')->sum();;
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
        $getDC = $report->semester == 1 ? $getDCS1 : $getDCS2;
        $getCRM = $report->semester == 1 ? $getCRMS1 : $getCRMS2;
        $getVisit = $report->semester == 1 ? $getVisitS1 : $getVisitS2;
        $getQuote = $report->semester == 1 ? $getQuoteS1 : $getQuoteS2;
        $getPO = $report->semester == 1 ? $getPOS1 : $getPOS2;
        $getLeads = $report->semester == 1 ? $getLeadsS1 : $getLeadsS2;
        $getPOModal = $report->semester == 1 ? $getPOModalS1 : $getPOModalS2;
        $getTotalForecast = $report->semester == 1 ? $getTotalForecastS1 : $getTotalForecastS2;
        $getTotalPO = $report->semester == 1 ? $getTotalPOS1 : $getTotalPOS2;

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
        $totalPOSemester = Quotation::whereBetween('po_date', [$first, $lastDay])->where('id_sales', $sales)->where('status', 100)->where('level', '1')->where('is_primary', '1')->sum('nett');

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
        // dd($sales);
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
    public function reportCurrent()
    {
        $now = Carbon::now();
        $semester = $now->month > 6 ? 2 : 1;

        $report = SalesReports::where('semester', $semester)
            ->where('year', $now->year)
            ->first();

        if (!$report) {
            abort(404, 'Data semester untuk tahun ini belum tersedia.');
        }

        return redirect()->route('report.semester', $report->id);
    }

    public function supportReport($semesterId)
    {
        $report   = SalesReports::find($semesterId);
        $semester = SalesReports::all();

        $firstDay = $report->semester == 1 ? "{$report->year}-01-01" : "{$report->year}-07-01";
        $lastDay  = $report->semester == 1
            ? date('Y-m-t', strtotime("{$report->year}-06-01"))
            : date('Y-m-t', strtotime("{$report->year}-12-01"));

        $smktProspectCount = Prospect::whereBetween('date', [$firstDay, $lastDay])->whereNotNull('id_support')->count();
        $smktQuoteCount    = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->count();
        $smktQuoteTotal    = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $smktPoCount       = Quotation::whereBetween('po_date', [$firstDay, $lastDay])->whereNotNull('id_support')->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        $smktPoTotal       = Quotation::whereBetween('po_date', [$firstDay, $lastDay])->whereNotNull('id_support')->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $smktLossCount     = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->whereNotNull('id_support')->where('status', '0')->where('level', '1')->where('is_primary', '1')->count();
        $smktLossTotal     = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->whereNotNull('id_support')->where('status', '0')->where('level', '1')->where('is_primary', '1')->sum('nett');

        $smktProspectByStatus = Prospect::whereBetween('date', [$firstDay, $lastDay])
            ->whereNotNull('id_support')
            ->selectRaw('
                SUM(CASE WHEN provide IS NULL THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN provide = "1"  THEN 1 ELSE 0 END) as provided,
                SUM(CASE WHEN provide = "0"  THEN 1 ELSE 0 END) as no_provide
            ')
            ->first();

        $smktPerPerson = Prospect::join('users', 'users.id', '=', 'prospect.id_support')
            ->whereBetween('prospect.date', [$firstDay, $lastDay])
            ->whereNotNull('prospect.id_support')
            ->selectRaw('
                users.id, users.name, users.image,
                COUNT(*) as total,
                SUM(CASE WHEN prospect.provide = "1"  THEN 1 ELSE 0 END) as provided,
                SUM(CASE WHEN prospect.provide = "0"  THEN 1 ELSE 0 END) as no_provide,
                SUM(CASE WHEN prospect.provide IS NULL THEN 1 ELSE 0 END) as pending
            ')
            ->groupBy('users.id', 'users.name', 'users.image')
            ->orderByDesc('total')
            ->get();

        $smktProspectBySource = Prospect::join('pic', 'pic.id', '=', 'prospect.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->whereBetween('prospect.date', [$firstDay, $lastDay])
            ->whereNotNull('prospect.id_support')
            ->selectRaw('COALESCE(client.source, "Other") as source, COUNT(*) as total')
            ->groupBy('source')->orderByDesc('total')->get();

        $smktProspectByDomain = Prospect::join('pic', 'pic.id', '=', 'prospect.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->whereBetween('prospect.date', [$firstDay, $lastDay])
            ->whereNotNull('prospect.id_support')
            ->where('client.source', 'Website')
            ->whereNotNull('client.source_detail')
            ->where('client.source_detail', '!=', '')
            ->selectRaw('client.source_detail as domain, COUNT(*) as total')
            ->groupBy('domain')->orderByDesc('total')->get();

        $smktProspectByCategory = Prospect::whereBetween('date', [$firstDay, $lastDay])
            ->whereNotNull('id_support')
            ->selectRaw('COALESCE(category, "Uncategorized") as category, COUNT(*) as total')
            ->groupBy('category')->orderByDesc('total')->get();

        return view('pages.support.report.index', compact(
            'report', 'semester',
            'smktProspectCount', 'smktQuoteCount', 'smktQuoteTotal',
            'smktPoCount', 'smktPoTotal', 'smktLossCount', 'smktLossTotal',
            'smktProspectByStatus', 'smktPerPerson',
            'smktProspectBySource', 'smktProspectByDomain', 'smktProspectByCategory'
        ));
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
        $poTotal = Quotation::whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $lossTotal = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->where('status', '0')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quoteTotal = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->whereIn('status', ['20', '40', '60', '80', '90'])->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quoteOnTotal = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->where('level', '1')->where('is_primary', '1')->sum('nett');
        $sales = User::where('role', 'Sales')->where('active', '1')->get();
        $totalTarget = $report->target ? intval($report->target / 6) : 0;
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
        $poTotalSupport    = Quotation::whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])->whereNotNull('id_support')->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quoteTotalSupport = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quoteCountSupport = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->count();

        // Marketing report — semester
        $smktProspectCount = Prospect::whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])->whereNotNull('id_support')->count();
        $smktQuoteCount    = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->count();
        $smktQuoteTotal    = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $smktPoCount       = Quotation::whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])->whereNotNull('id_support')->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        $smktPoTotal       = Quotation::whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])->whereNotNull('id_support')->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $smktLossCount     = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->whereNotNull('id_support')->where('status', '0')->where('level', '1')->where('is_primary', '1')->count();
        $smktLossTotal     = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->whereNotNull('id_support')->where('status', '0')->where('level', '1')->where('is_primary', '1')->sum('nett');

        $smktProspectByStatus = Prospect::whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
            ->whereNotNull('id_support')
            ->selectRaw('
                SUM(CASE WHEN provide IS NULL THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN provide = "1"  THEN 1 ELSE 0 END) as provided,
                SUM(CASE WHEN provide = "0"  THEN 1 ELSE 0 END) as no_provide
            ')
            ->first();

        $smktPerPerson = Prospect::join('users', 'users.id', '=', 'prospect.id_support')
            ->whereBetween('prospect.date', [$firstDayOfMonth, $lastDayOfMonth])
            ->whereNotNull('prospect.id_support')
            ->selectRaw('
                users.id, users.name, users.image,
                COUNT(*) as total,
                SUM(CASE WHEN prospect.provide = "1"  THEN 1 ELSE 0 END) as provided,
                SUM(CASE WHEN prospect.provide = "0"  THEN 1 ELSE 0 END) as no_provide,
                SUM(CASE WHEN prospect.provide IS NULL THEN 1 ELSE 0 END) as pending
            ')
            ->groupBy('users.id', 'users.name', 'users.image')
            ->orderByDesc('total')
            ->get();

        $smktProspectBySource = Prospect::join('pic', 'pic.id', '=', 'prospect.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->whereBetween('prospect.date', [$firstDayOfMonth, $lastDayOfMonth])
            ->whereNotNull('prospect.id_support')
            ->selectRaw('COALESCE(client.source, "Other") as source, COUNT(*) as total')
            ->groupBy('source')->orderByDesc('total')->get();

        $smktProspectByDomain = Prospect::join('pic', 'pic.id', '=', 'prospect.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->whereBetween('prospect.date', [$firstDayOfMonth, $lastDayOfMonth])
            ->whereNotNull('prospect.id_support')
            ->where('client.source', 'Website')
            ->whereNotNull('client.source_detail')
            ->where('client.source_detail', '!=', '')
            ->selectRaw('client.source_detail as domain, COUNT(*) as total')
            ->groupBy('domain')->orderByDesc('total')->get();

        $smktProspectByCategory = Prospect::whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
            ->whereNotNull('id_support')
            ->selectRaw('COALESCE(category, "Uncategorized") as category, COUNT(*) as total')
            ->groupBy('category')->orderByDesc('total')->get();

        // dd($dataSupport);

        $data = [];

        foreach ($sales as $user) {
            $poTotalSales = Quotation::whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])->where('id_sales', $user->id)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
            $bulanan = DB::table('quotation')
                ->selectRaw('MONTH(po_date) as bulan, SUM(nett) as total')
                ->where('id_sales', $user->id)
                ->where('status', 100)
                ->where('level', '1')
                ->where('is_primary', '1')
                ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
                ->groupBy(DB::raw('MONTH(po_date)'))
                ->pluck('total', 'bulan')
                ->toArray();
            $jumlah = [];
            for ($i = $startMonth; $i <= $endMonth; $i++) {
                $jumlah[] = [
                    'bulan' => $i,
                    'total' => (int) ($bulanan[$i] ?? 0)
                ];
            }

            $smktProspectForSales = Prospect::whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])->where('id_sales', $user->id)->whereNotNull('id_support')->count();
            $smktQuoteForSales    = Quotation::whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])->where('id_sales', $user->id)->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->count();
            $smktPoForSales       = Quotation::whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])->where('id_sales', $user->id)->whereNotNull('id_support')->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();

            $data[] = [
                'id'          => $user->id,
                'image'       => $user->image,
                'name'        => $user->name,
                'target'      => Target::where('id_sales', $user->id)->sum('total'),
                'total'       => $poTotalSales,
                'jumlah'      => $jumlah,
                'mktProspect' => $smktProspectForSales,
                'mktQuote'    => $smktQuoteForSales,
                'mktPo'       => $smktPoForSales,
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
            'poTotalSupport',
            'quoteTotalSupport',
            'quoteCountSupport',
            'smktProspectCount', 'smktQuoteCount', 'smktQuoteTotal',
            'smktPoCount', 'smktPoTotal', 'smktLossCount', 'smktLossTotal',
            'smktProspectByStatus', 'smktPerPerson',
            'smktProspectBySource', 'smktProspectByDomain', 'smktProspectByCategory'
        ));
    }

    public function reportsByYear($year)
    {
        $yearList = SalesReports::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        $firstDay = "{$year}-01-01";
        $lastDay  = "{$year}-12-31";

        $poCount      = Quotation::whereBetween('po_date', [$firstDay, $lastDay])->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        $lossCount    = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->where('status', '0')->where('level', '1')->where('is_primary', '1')->count();
        $quoteCount   = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->whereIn('status', ['20', '40', '60', '80', '90'])->where('level', '1')->where('is_primary', '1')->count();
        $quoteOnCount = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->where('level', '1')->where('is_primary', '1')->count();
        $poTotal      = Quotation::whereBetween('po_date', [$firstDay, $lastDay])->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $lossTotal    = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->where('status', '0')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quoteTotal   = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->whereIn('status', ['20', '40', '60', '80', '90'])->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quoteOnTotal = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->where('level', '1')->where('is_primary', '1')->sum('nett');

        $sales    = User::where('role', 'Sales')->where('active', '1')->get();
        $support  = User::find('22');
        $reportS1 = SalesReports::where('year', $year)->where('semester', 1)->first();
        $reportS2 = SalesReports::where('year', $year)->where('semester', 2)->first();
        $totalTarget = intval((($reportS1->target ?? 0) + ($reportS2->target ?? 0)) / 12);

        $dataSupportRaw = DB::table('quotation')
            ->selectRaw('MONTH(po_date) as bulan, SUM(nett) as total')
            ->whereNotNull('id_support')
            ->where('status', 100)
            ->where('level', '1')
            ->where('is_primary', '1')
            ->whereYear('po_date', $year)
            ->groupBy(DB::raw('MONTH(po_date)'))
            ->pluck('total', 'bulan')
            ->toArray();

        $dataSupport = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataSupport[$i] = (int) ($dataSupportRaw[$i] ?? 0);
        }
        $poTotalSupport    = array_sum($dataSupport);
        $quoteTotalSupport = Quotation::whereYear('estimated_date', $year)->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quoteCountSupport = Quotation::whereYear('estimated_date', $year)->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->count();

        $data = [];
        foreach ($sales as $user) {
            $bulanan = DB::table('quotation')
                ->selectRaw('MONTH(po_date) as bulan, SUM(nett) as total')
                ->where('id_sales', $user->id)
                ->where('status', 100)
                ->where('level', '1')
                ->where('is_primary', '1')
                ->whereYear('po_date', $year)
                ->groupBy(DB::raw('MONTH(po_date)'))
                ->pluck('total', 'bulan')
                ->toArray();

            $jumlah = [];
            for ($i = 1; $i <= 12; $i++) {
                $jumlah[$i] = (int) ($bulanan[$i] ?? 0);
            }

            $data[] = [
                'id'     => $user->id,
                'image'  => $user->image,
                'name'   => $user->name,
                'target' => Target::where('id_sales', $user->id)->sum('total'),
                'total'  => array_sum($jumlah),
                'jumlah' => $jumlah,
            ];
        }

        return view('pages.admin.report-year', compact(
            'year', 'yearList',
            'poCount', 'lossCount', 'quoteCount', 'quoteOnCount',
            'poTotal', 'lossTotal', 'quoteTotal', 'quoteOnTotal',
            'totalTarget', 'data', 'support', 'dataSupport', 'poTotalSupport',
            'quoteTotalSupport', 'quoteCountSupport',
            'reportS1', 'reportS2'
        ));
    }

    public function reportMonthly($year = null, $month = null)
    {
        $now   = Carbon::now();
        $year  = (int) ($year  ?? $now->year);
        $month = (int) ($month ?? $now->month);

        $firstDay = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $lastDay  = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();

        $sales = User::where('role', 'Sales')->where('active', '1')->get();

        $poCount      = Quotation::whereBetween('po_date', [$firstDay, $lastDay])->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        $poTotal      = Quotation::whereBetween('po_date', [$firstDay, $lastDay])->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quoteCount   = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->whereIn('status', ['20', '40', '60', '80'])->where('level', '1')->where('is_primary', '1')->count();
        $quoteTotal   = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->whereIn('status', ['20', '40', '60', '80'])->where('level', '1')->where('is_primary', '1')->sum('nett');
        $lossCount    = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->where('status', '0')->where('level', '1')->where('is_primary', '1')->count();
        $lossTotal    = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->where('status', '0')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quoteOnCount = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->where('level', '1')->where('is_primary', '1')->count();
        $totalTarget  = Target::whereIn('id_sales', $sales->pluck('id'))->sum('total');

        $data = [];
        foreach ($sales as $user) {
            $leads   = Client::whereMonth('created_at', $month)->whereYear('created_at', $year)->where('id_sales', $user->id)->count();
            $dc      = Activities::join('client as c', 'activities.id_client', '=', 'c.id')
                        ->whereBetween('activities.date', [$firstDay, $lastDay])
                        ->where('c.id_sales', $user->id)
                        ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
                        ->where('activities.status', 'Responded')
                        ->distinct('activities.id_client')
                        ->count('activities.id_client');
            $crm     = Activities::join('client as c', 'activities.id_client', '=', 'c.id')
                        ->whereBetween('activities.date', [$firstDay, $lastDay])
                        ->where('c.id_sales', $user->id)
                        ->where('activities.name', 'CRM')
                        ->where('activities.status', 'Responded')
                        ->distinct('activities.id_client')
                        ->count('activities.id_client');
            $userQuoteCount   = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->where('id_sales', $user->id)->where('level', '1')->where('is_primary', '1')->count();
            $userQuoteTotal   = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->where('id_sales', $user->id)->where('level', '1')->where('is_primary', '1')->sum('nett');
            $userProspectCount = Quotation::where('status', '80')->whereBetween('estimated_date', [$firstDay, $lastDay])->where('id_sales', $user->id)->where('level', '1')->where('is_primary', '1')->count();
            $userPoCount      = Quotation::whereBetween('po_date', [$firstDay, $lastDay])->where('id_sales', $user->id)->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
            $userPoTotal      = Quotation::whereBetween('po_date', [$firstDay, $lastDay])->where('id_sales', $user->id)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
            $userLossCount    = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->where('id_sales', $user->id)->where('status', '0')->where('level', '1')->where('is_primary', '1')->count();
            $target = Target::where('id_sales', $user->id)->sum('total');

            $mktProspectForSales = Prospect::whereBetween('date', [$firstDay, $lastDay])->where('id_sales', $user->id)->whereNotNull('id_support')->count();
            $mktQuoteForSales    = Quotation::whereBetween('estimated_date', [$firstDay, $lastDay])->where('id_sales', $user->id)->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->count();
            $mktPoForSales       = Quotation::whereBetween('po_date', [$firstDay, $lastDay])->where('id_sales', $user->id)->whereNotNull('id_support')->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();

            $data[] = [
                'id'                  => $user->id,
                'name'                => $user->name,
                'image'               => $user->image,
                'leads'               => $leads,
                'dc'                  => $dc,
                'crm'                 => $crm,
                'quoteCount'          => $userQuoteCount,
                'quoteTotal'          => $userQuoteTotal,
                'prospectCount'       => $userProspectCount,
                'poCount'             => $userPoCount,
                'poTotal'             => $userPoTotal,
                'lossCount'           => $userLossCount,
                'target'              => $target,
                'mktProspect'         => $mktProspectForSales,
                'mktQuote'            => $mktQuoteForSales,
                'mktPo'               => $mktPoForSales,
            ];
        }

        usort($data, fn($a, $b) => $b['poTotal'] <=> $a['poTotal']);

        $yearList = SalesReports::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        // Marketing funnel
        $mktProspectCount  = Prospect::whereMonth('date', $month)->whereYear('date', $year)->whereNotNull('id_support')->count();
        $mktQuoteCount     = Quotation::whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->count();
        $mktQuoteTotal     = Quotation::whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $mktPoCount        = Quotation::whereMonth('po_date', $month)->whereYear('po_date', $year)->whereNotNull('id_support')->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        $mktPoTotal        = Quotation::whereMonth('po_date', $month)->whereYear('po_date', $year)->whereNotNull('id_support')->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');

        $mktProspectBySource = Prospect::join('pic', 'pic.id', '=', 'prospect.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->whereMonth('prospect.date', $month)
            ->whereYear('prospect.date', $year)
            ->whereNotNull('prospect.id_support')
            ->selectRaw('COALESCE(client.source, "Other") as source, COUNT(*) as total')
            ->groupBy('source')
            ->orderByDesc('total')
            ->get();

        $mktProspectByCategory = Prospect::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereNotNull('id_support')
            ->selectRaw('COALESCE(category, "Uncategorized") as category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $mktProspectByArea = Prospect::join('pic', 'pic.id', '=', 'prospect.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->whereMonth('prospect.date', $month)
            ->whereYear('prospect.date', $year)
            ->whereNotNull('prospect.id_support')
            ->selectRaw('COALESCE(NULLIF(client.area, ""), "Unknown") as area, COUNT(*) as total')
            ->groupBy('area')
            ->orderByDesc('total')
            ->get();

        $mktProspectByDomain = Prospect::join('pic', 'pic.id', '=', 'prospect.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->whereMonth('prospect.date', $month)
            ->whereYear('prospect.date', $year)
            ->whereNotNull('prospect.id_support')
            ->where('client.source', 'Website')
            ->whereNotNull('client.source_detail')
            ->where('client.source_detail', '!=', '')
            ->selectRaw('client.source_detail as domain, COUNT(*) as total')
            ->groupBy('domain')
            ->orderByDesc('total')
            ->get();

        // Status prospect (pending / provided / no provide)
        $mktProspectByStatus = Prospect::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereNotNull('id_support')
            ->selectRaw('
                SUM(CASE WHEN provide IS NULL THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN provide = "1"  THEN 1 ELSE 0 END) as provided,
                SUM(CASE WHEN provide = "0"  THEN 1 ELSE 0 END) as no_provide
            ')
            ->first();

        // Per marketing person
        $mktPerPerson = Prospect::join('users', 'users.id', '=', 'prospect.id_support')
            ->whereMonth('prospect.date', $month)
            ->whereYear('prospect.date', $year)
            ->whereNotNull('prospect.id_support')
            ->selectRaw('
                users.id, users.name, users.image,
                COUNT(*) as total,
                SUM(CASE WHEN prospect.provide = "1"  THEN 1 ELSE 0 END) as provided,
                SUM(CASE WHEN prospect.provide = "0"  THEN 1 ELSE 0 END) as no_provide,
                SUM(CASE WHEN prospect.provide IS NULL THEN 1 ELSE 0 END) as pending
            ')
            ->groupBy('users.id', 'users.name', 'users.image')
            ->orderByDesc('total')
            ->get();

        // Loss dari marketing leads
        $mktLossCount = Quotation::whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->whereNotNull('id_support')->where('status', '0')->where('level', '1')->where('is_primary', '1')->count();
        $mktLossTotal = Quotation::whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->whereNotNull('id_support')->where('status', '0')->where('level', '1')->where('is_primary', '1')->sum('nett');

        return view('pages.admin.report-monthly', compact(
            'year', 'month', 'yearList',
            'data', 'poCount', 'poTotal',
            'quoteCount', 'quoteTotal',
            'lossCount', 'lossTotal',
            'quoteOnCount', 'totalTarget',
            'mktProspectCount', 'mktQuoteCount', 'mktQuoteTotal',
            'mktPoCount', 'mktPoTotal', 'mktProspectBySource', 'mktProspectByCategory', 'mktProspectByArea',
            'mktProspectByDomain',
            'mktProspectByStatus', 'mktPerPerson', 'mktLossCount', 'mktLossTotal'
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
