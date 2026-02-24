<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Contract;
use App\Models\DetailProduct;
use App\Models\Invoice;
use App\Models\Issues;
use App\Models\Machine;
use App\Models\MonitoringActivities;
use App\Models\Notulen;
use App\Models\Payment;
use App\Models\PendingPO;
use App\Models\Product;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\Reminder;
use App\Models\Reports;
use App\Models\ReqVisit;
use App\Models\SalesOnline;
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
        $yearNow = $dateNow->year;
        $notulens = Notulen::join('mention_notulen as m', 'm.id_notulen', '=', 'notulen.id')->join('users as u', 'm.id_mention', '=', 'u.id')->get(['notulen.*', 'u.name', 'm.level']);
        if (Auth::user()->role == 'Sales' || Auth::user()->role == 'Support') {
            $clients = Client::where('id_sales', Auth::id())->get();
            $issue = Issues::all();
            // dd($clients);
            $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();
            $weekPerMonth = $this->getWeekperMonth();
            $dailyCall = $this->getDailyCallSales();
            $customers = $this->getCustomersSales();
            $quotation = $this->getQuotationSales();
            $po = $this->getPoSales();
            $leads = $this->getLeadsSales();
            $visit = $this->getVisitSales();
            $poTotalPrice = Quotation::whereYear('po_date', $yearNow)->whereMonth("po_date", $monthNow)->where("id_sales", Auth::user()->id)->where("status", "100")->where('level', '1')->where('is_primary', '1')->sum('nett');
            $formattedTotalPrice = $this->formatNumber($poTotalPrice);
            $target = Target::where('id_sales', Auth::user()->id)->first();
            $prospects = Prospect::where('id_sales', Auth::id())->whereNull('level')->get();
            $nextFollow = Activities::join('client as c', 'c.id', '=', 'activities.id_client')
                ->join('users as s', 'c.id_sales', '=', 's.id')
                ->select(['activities.id', 'c.company', 'activities.note', 'activities.follow_up as start', 'activities.follow_up as end', 'activities.name'])
                ->where('c.id_sales', Auth::id())
                ->groupBy('c.company')
                ->orderBy('activities.follow_up')
                ->get();

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

            // Sales Online
            $akurasi = SalesOnline::where('id_sales', Auth::user()->id)
                ->where('type', 'Akurasi')
                ->whereYear('date', Carbon::now()->year)
                ->whereRaw('WEEK(date, 1) = ?', [Carbon::now()->weekOfYear])
                ->first();
            $delivery = SalesOnline::where('id_sales', Auth::user()->id)
                ->where('type', 'Delivery')
                ->whereYear('date', Carbon::now()->year)
                ->whereRaw('WEEK(date, 1) = ?', [Carbon::now()->weekOfYear])
                ->first();
            $response = SalesOnline::where('id_sales', Auth::user()->id)
                ->where('type', 'Response')
                ->whereYear('date', Carbon::now()->year)
                ->whereRaw('WEEK(date, 1) = ?', [Carbon::now()->weekOfYear])
                ->first();
            $rating = SalesOnline::where('id_sales', Auth::user()->id)
                ->where('type', 'Rating')
                ->whereYear('date', Carbon::now()->year)
                ->whereRaw('WEEK(date, 1) = ?', [Carbon::now()->weekOfYear])
                ->first();
            $customer = SalesOnline::where('id_sales', Auth::user()->id)
                ->where('type', 'Customer')
                ->whereYear('date', Carbon::now()->year)
                ->whereRaw('WEEK(date, 1) = ?', [Carbon::now()->weekOfYear])
                ->first();
            $video = SalesOnline::where('id_sales', Auth::user()->id)
                ->where('type', 'Video')
                ->whereDate('date', Carbon::now())->first();
            $sw = SalesOnline::where('id_sales', Auth::user()->id)
                ->where('type', 'SW')
                ->whereDate('date', Carbon::now())->first();
            $product = SalesOnline::where('id_sales', Auth::user()->id)
                ->where('type', 'product')
                ->whereDate('date', Carbon::now())->get();
            $sales = Auth::user();
            // dd($delivery);
            // dd($sales);
            $akurasiCount = SalesOnline::where('id_sales', Auth::user()->id)->where('type', 'Akurasi')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
            $deliveryCount = SalesOnline::where('id_sales', Auth::user()->id)->where('type', 'Delivery')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
            $responseCount = SalesOnline::where('id_sales', Auth::user()->id)->where('type', 'Response')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
            $ratingCount = SalesOnline::where('id_sales', Auth::user()->id)->where('type', 'Rating')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
            $customerCount = SalesOnline::where('id_sales', Auth::user()->id)->where('type', 'Customer')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
            $videoCount = SalesOnline::where('id_sales', Auth::user()->id)->where('type', 'Video')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
            $SWCount = SalesOnline::where('id_sales', Auth::user()->id)->where('type', 'SW')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->get();
            $productCount = SalesOnline::where('id_sales', Auth::user()->id)->where('type', 'Product')->whereMonth('date', Carbon::now())->whereYear('date', Carbon::now())->count();
            $POCount = Quotation::where('id_sales', Auth::user()->id)->where('is_primary', '1')->where('status', '100')->where('level', '1')->whereMonth('po_date', Carbon::now())->whereYear('po_date', Carbon::now())->count();

            $jumlahCustomer = Client::where('role', 'Customers')->where('id_sales', Auth::user()->id)->count();
            $reportsCount = Reports::join('machine as m', 'm.id', '=', 'reports.id_machine')
                ->join('client as c', 'c.id', '=', 'm.id_client')
                ->join('users as u', 'u.id', '=', 'c.id_sales')
                ->where('u.id', Auth::user()->id)
                ->where('reports.viewed', 0)->count();
            // dd($ratingCount);

            // Prospect Monthly (By Support - Sandhy)
            $year = now()->year;
            $month = now()->month;
            $support = Auth::id();

            $previousMonth = now()->subMonth();
            $yearPrev = $previousMonth->year;
            $monthPrev = $previousMonth->month;


            $prospect = Prospect::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->where('id_support', $support)
                ->count();

            $provided = Prospect::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->where('provide', '!=', '0')
                ->where('id_support', $support)
                ->count();

            $quotation = Quotation::whereYear('estimated_date', $year)
                ->whereMonth('estimated_date', $month)
                ->where('id_support', $support)
                ->where('level', '1')
                ->where('is_primary', '1')
                ->count();

            $po = Quotation::whereYear('po_date', $year)
                ->whereMonth('po_date', $month)
                ->where('id_support', $support)
                ->where('status', '100')
                ->where('level', '1')
                ->where('is_primary', '1')
                ->count();

            $loss = Quotation::whereYear('estimated_date', $year)
                ->whereMonth('estimated_date', $month)
                ->where('id_support', $support)
                ->where('status', '0')
                ->where('level', '1')
                ->where('is_primary', '1')
                ->count();

            $prospectLastMonth = Prospect::whereYear('date', $yearPrev)
                ->whereMonth('date', $monthPrev)
                ->where('id_support', $support)
                ->count();

            $diffProspect = $prospect - $prospectLastMonth;

            $closingRate = $quotation > 0 ? round(($po / $quotation) * 100, 1) : 0;
            $conversionRate = $prospect > 0 ? round(($quotation / $prospect) * 100, 1) : 0;
            $providedRate = $prospect > 0 ? round(($provided / $prospect) * 100, 1) : 0;
            $targetProspect = Target::where('id_sales', Auth::id())->first()->prospect ?? 100;
            $progress = $targetProspect > 0
                ? round(($prospect / $targetProspect) * 100, 1)
                : 0;

            return view(
                "pages.sales.dashboard",
                compact(
                    'reportsCount',
                    'sales',
                    'akurasi',
                    'delivery',
                    'response',
                    'rating',
                    'video',
                    'sw',
                    'customer',
                    'product',
                    'akurasiCount',
                    'deliveryCount',
                    'responseCount',
                    'ratingCount',
                    'videoCount',
                    'customerCount',
                    'SWCount',
                    'POCount',
                    'productCount',
                    'jumlahCustomer',
                    'notulens',
                    'prospects',
                    'leveledProspect',
                    'formattedTotalPrice',
                    'weekPerMonth',
                    'target',
                    'poTotalPrice',
                    'visit',
                    'dailyCall',
                    'quotation',
                    'po',
                    'leads',
                    'issue',
                    'clients',
                    'customers',
                    'unreadComment',
                    'comment',
                    'prospect',
                    'provided',
                    'quotation',
                    'po',
                    'loss',
                    'closingRate',
                    'conversionRate',
                    'providedRate',
                    'targetProspect',
                    'diffProspect'
                )
            );
        } elseif (Auth::user()->role == 'Admin') {
            $requestContract = Contract::join('quotation as q', 'q.id', '=', 'contract.id_quotation')
                ->join('pic as p', 'p.id', '=', 'q.id_pic')
                ->join('client as c', 'c.id', '=', 'p.id_client')
                ->join('users as u', 'u.id', '=', 'q.id_sales')
                ->where('contract.level', '0')
                ->count();
            $requestInvoice = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')
                ->join('client', 'client.id', '=', 'pic.id_client')
                ->join('invoice', 'invoice.id_quotation', '=', 'quotation.id')
                ->join('users', 'users.id', '=', 'quotation.id_sales')
                ->where('status', '100')
                ->whereNotNull('quotation.po_file')
                ->whereNull('invoice.no_invoice')
                ->count();
            $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
            $poTotalPriceAdmin = Quotation::whereYear('po_date', $yearNow)->whereMonth("po_date", $monthNow)->where("status", "100")->where('level', '1')->where('is_primary', '1')->sum('nett');
            $formattedTotalPriceAdmin = $this->formatNumber($poTotalPriceAdmin);
            $sales = User::whereIn('role', ['Sales', 'Support'])->where('active', '1')->orderByDesc('id')->get();
            $firstSales = User::find(1);
            $targett = Target::where('id_sales', $firstSales->id)->first('total');
            $targetAllSales = Target::join('users as u', 'u.id', '=', 'target.id_sales')->where('u.role', 'Sales')->where('u.active', '1')->sum('target.total');
            // dd($targetAllSales);
            $targetSales = $sales->map(function ($sale) {
                return $sale->target()->groupBy('id_sales')->get();
            });
            // dd($totalPO);
            // $totalProspectQuote = Quotation::whereYear('date', $yearNow)->whereMonth('date', $monthNow)->whereNotNull('id_support')->where('status', '!=', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
            // $prospectedQuotation = Prospect::join('quotation as q', 'q.id', '=', 'prospect.id_quotation')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('provide', '!=', '0')->where('status', '!=', '100')->where('q.level', '1')->where('is_primary', '1')->count();
            // $prospectedPO = Prospect::join('quotation as q', 'q.id', '=', 'prospect.id_quotation')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('provide', '!=', '0')->where('status', '100')->where('q.level', '1')->where('is_primary', '1')->count();
            // $prospectedQuotationTotal = Prospect::join('quotation as q', 'q.id', '=', 'prospect.id_quotation')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('provide', '!=', '0')->where('status', '!=', '100')->where('q.level', '1')->where('is_primary', '1')->sum('q.nett');
            // $prospectedPOTotal = Prospect::join('quotation as q', 'q.id', '=', 'prospect.id_quotation')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('provide', '!=', '0')->where('status', '100')->where('q.level', '1')->where('is_primary', '1')->sum('q.nett');
            // dd($totalProspectQuote);
            $totalProspectSupport = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $firstSales->id)->whereIn('status', ['20', '30', '40', '60', '80'])->where('level', '1')->where('is_primary', '1')->sum('nett');
            $totalForecast = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $firstSales->id)->where('status', '80')->where('level', '1')->where('is_primary', '1')->sum('nett');

            $totalQuotation = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $firstSales->id)->where('level', '1')->where('is_primary', '1')->sum('nett');
            $totalProspect = Quotation::join('prospect as p', 'quotation.id', '=', 'p.id_quotation')->whereNotNull('id_quotation')->whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('quotation.id_sales', $firstSales->id)->whereIn('status', ['80', '90'])->where('quotation.level', '1')->where('is_primary', '1')->sum('nett');
            $totalHotProspect = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $firstSales->id)->whereIn('status', ['80', '90'])->where('level', '1')->where('is_primary', '1')->sum('nett');
            $totalLoss = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $firstSales->id)->where('status', '0')->where('level', '1')->where('is_primary', '1')->sum('nett');
            $totalPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_sales', $firstSales->id)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
            $filteredLeads = Client::whereYear('created_at', $yearNow)->whereMonth('created_at', $monthNow)->where('id_sales', $firstSales->id)->count();
            $filteredDC = Activities::join('client as c', 'activities.id_client', '=', 'c.id')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('c.id_sales', $firstSales->id)->where('status', 'Responded')->whereIn('activities.name', ['Daily Call', 'Follow Up'])->count();
            $filteredCRM = Activities::join('client as c', 'activities.id_client', '=', 'c.id')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('c.id_sales', $firstSales->id)->where('status', 'Responded')->where('name', 'CRM')->distinct('c.id')->count();
            $filteredQuote = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $firstSales->id)->where('level', '1')->where('is_primary', '1')->count();
            $filteredProspect = Prospect::whereNotNull('id_quotation')->whereMonth('date', $monthNow)->whereYear('date', $yearNow)->count();
            $allProspect = Prospect::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->count();
            $filteredPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_sales', $firstSales->id)->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
            $filteredVisit = Activities::join('client as c', 'activities.id_client', '=', 'c.id')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('c.id_sales', $firstSales->id)->where('status', 'Responded')->where('name', 'Visit')->count();

            $dataDc = $this->getWeekDataDC();
            $dataCRM = $this->getWeekDataCRM();
            $dataVisit = $this->getWeekDataVisit();
            $dataQuote = $this->getWeekDataQuote();
            $dataOverview = $this->getDataOverview();

            // dd($dataOverview);
            $dataLeads = $this->getWeekDataLeads();
            $dataPO = $this->getWeekDataPO();
            $targetCrm = Client::where('role', 'Customers')
                ->select('id_sales', DB::RAW('COUNT(*) as total'))
                ->groupBy('id_sales')
                ->pluck('total', 'id_sales')->toArray();
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
            $newCount = PendingPO::where('status', operator: 0)
                ->where('type', 'Non Project')
                ->count();
            $listCount = PendingPO::whereIn('pending_po.status', [1, 2, 3, 4])
                ->where('type', 'Non Project')
                ->count();
            $deliveryCount = PendingPO::where('pending_po.status', 5)
                ->where('type', 'Non Project')
                ->count();

            return view(
                "pages.sales.dashboard",
                compact(
                    'requestContract',
                    'requestInvoice',
                    'newCount',
                    'listCount',
                    'deliveryCount',
                    'dataOverview',
                    'noSaleProspect',
                    'notulens',
                    'totalProspectSupport',
                    'totalForecast',
                    'targetSales',
                    'targetCrm',
                    'sales',
                    'totalPO',
                    'filteredLeads',
                    'filteredPO',
                    'filteredCRM',
                    'filteredVisit',
                    'filteredDC',
                    'filteredQuote',
                    'filteredProspect',
                    'allProspect',
                    'poTotalPriceAdmin',
                    'formattedTotalPriceAdmin',
                    'totalQuotation',
                    'totalProspect',
                    'totalHotProspect',
                    'totalLoss',
                    'totalPO',
                    'dataQuote',
                    'dataLeads',
                    'dataPO',
                    'dataDc',
                    'dataCRM',
                    'dataVisit',
                    'commentAdmin',
                    'unreadCommentAdmin',
                    'targett',
                    'targetAllSales',
                )
            );
        } elseif (Auth::user()->role == 'Accounting') {
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
            $commentAdmin = $commentsQuery->orderBy('comment.id_status')
                ->orderByDesc('comment.created_at')
                ->get(['q.id as idQ', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'q.no_quote', 'u.name', 'u.image']);

            // Filter untuk komentar dengan level '1'
            $unreadCommentAdmin = $commentsQuery->where('comment.level', '1')
                ->orderBy('comment.id_status')
                ->orderByDesc('comment.created_at')
                ->get(['q.id as idQ', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'q.no_quote', 'u.name', 'u.image']);


            $requestContract = Contract::join('quotation as q', 'q.id', '=', 'contract.id_quotation')
                ->join('pic as p', 'p.id', '=', 'q.id_pic')
                ->join('client as c', 'c.id', '=', 'p.id_client')
                ->join('users as u', 'u.id', '=', 'q.id_sales')
                ->where('contract.level', '0')
                ->count();
            $requestInvoice = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')
                ->join('client', 'client.id', '=', 'pic.id_client')
                ->join('invoice', 'invoice.id_quotation', '=', 'quotation.id')
                ->join('users', 'users.id', '=', 'quotation.id_sales')
                ->where('status', '100')
                ->whereNotNull('quotation.po_file')
                ->whereNull('invoice.no_invoice')
                ->count();
            $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
            $newCount = PendingPO::where('status', operator: 0)
                ->where('type', 'Non Project')
                ->count();
            $listCount = PendingPO::whereIn('pending_po.status', [1, 2, 3, 4])
                ->where('type', 'Non Project')
                ->count();
            $deliveryCount = PendingPO::where('pending_po.status', 5)
                ->where('type', 'Non Project')
                ->count();

            $start1 = Carbon::createFromDate(null, 1, 1);  // 1 Januari tahun ini
            $end1 = Carbon::createFromDate(null, 6, 30); // 30 Juni tahun ini
            $start2 = Carbon::createFromDate(null, 7, 1);  // 1 Juli tahun ini
            $end2 = Carbon::createFromDate(null, 12, 31); // 31 Desember tahun ini

            $allInvoice1 = Invoice::join('quotation as q', 'q.id', '=', 'invoice.id_quotation')
                ->join('pic as p', 'q.id_pic', '=', 'p.id')
                ->join('client as c', 'p.id_client', '=', 'c.id')
                ->whereBetween('invoice.date', [$start1, $end1])
                ->where('q.status', '100')
                ->where('q.level', '1')
                ->where('q.is_primary', '1')
                ->groupBy('q.id')
                ->select('q.harga_total', 'c.info')
                ->get();

            $allInvoice2 = Invoice::join('quotation as q', 'q.id', '=', 'invoice.id_quotation')
                ->join('pic as p', 'q.id_pic', '=', 'p.id')
                ->join('client as c', 'p.id_client', '=', 'c.id')
                ->whereBetween('invoice.date', [$start2, $end2])
                ->where('q.status', '100')
                ->where('q.level', '1')
                ->where('q.is_primary', '1')
                ->groupBy('invoice.id')
                ->select('q.harga_total', 'c.info')
                ->get();

            $paidInvoice1 = Invoice::join('quotation as q', 'q.id', '=', 'invoice.id_quotation')
                ->join('payment as pay', 'pay.id_quotation', '=', 'q.id')
                ->join('pic as p', 'q.id_pic', '=', 'p.id')
                ->join('client as c', 'p.id_client', '=', 'c.id')
                ->whereBetween('invoice.date', [$start1, $end1])
                ->where('pay.level', '1')
                ->groupBy('pay.id')
                ->select('pay.id', 'pay.amount', 'c.info')
                ->get();
            // dd($paidInvoice1);
            $paidInvoice2 = Invoice::join('quotation as q', 'q.id', '=', 'invoice.id_quotation')
                ->join('payment as pay', 'pay.id_quotation', '=', 'q.id')
                ->join('pic as p', 'q.id_pic', '=', 'p.id')
                ->join('client as c', 'p.id_client', '=', 'c.id')
                ->whereBetween('invoice.date', [$start2, $end2])
                ->where('pay.level', '1')
                ->groupBy('pay.id')
                ->select('pay.amount', 'c.info')
                ->get();

            // $unpaidInvoice = Invoice::
            $unpaidInvoice1 = Quotation::with(['invoice', 'payment', 'pic.client'])
                ->whereHas('invoice', function ($q) use ($start1, $end1) {
                    $q->whereBetween('date', [$start1, $end1]);
                })
                ->where('status', '100')
                ->where('level', '1')
                ->orderBy('quotation.id')
                ->get()
                ->map(function ($q) {
                    $info = $q->pic->client->info ?? '-';
                    $harga_total = $q->harga_total;
                    $id = $q->id;
                    $payments = $q->payment;

                    $unpaid_amount = 0;

                    // 0 PAYMENT
                    if ($payments->isEmpty()) {
                        $unpaid_amount = $harga_total;
                    }

                    // 1 PAYMENT
                    elseif ($payments->count() === 1) {
                        $p = $payments->first();
                        $unpaid_amount = $p->level == 0 ? $harga_total : 0;
                    }

                    // 2 PAYMENT (DP + BP/Tempo)
                    else {
                        $dp = $payments->where('type', 'DP')->first();
                        $second = $payments->where('type', '!=', 'DP')->first();

                        if ($dp && $dp->level == 0) {
                            $unpaid_amount = $harga_total;
                        } elseif ($dp && $dp->level == 1 && (!$second || $second->level == 0)) {
                            $unpaid_amount = $harga_total - $dp->amount;
                        } else {
                            $unpaid_amount = 0;
                        }
                    }

                    return (object) [
                        'id' => $id,
                        'unpaid_amount' => $unpaid_amount,
                        'harga_total' => $harga_total,
                        'info' => $info,
                    ];
                });

            // dd($unpaidInvoice1);
            $unpaidInvoice2 = Quotation::with(['invoice', 'payment', 'pic.client'])
                ->whereHas('invoice', function ($q) use ($start2, $end2) {
                    $q->whereBetween('date', [$start2, $end2]);
                })
                ->where('status', '100')
                ->where('level', '1')
                ->get()
                ->map(function ($q) {
                    $q->info = $q->pic->client->info ?? '-';
                    $payments = $q->payment;

                    // ✅ 0 PAYMENT
                    if ($payments->isEmpty()) {
                        $q->unpaid_amount = $q->harga_total;
                        return $q;
                    }

                    // ✅ 1 PAYMENT
                    if ($payments->count() === 1) {
                        $p = $payments->first();
                        $q->unpaid_amount = $p->level == 0 ? $q->harga_total : 0;
                        return $q;
                    }

                    // ✅ 2 PAYMENT (umumnya DP + BP/Tempo)
                    if ($payments->count() >= 2) {
                        $dp = $payments->where('type', 'DP')->first();
                        $second = $payments->where('type', '!=', 'DP')->first();

                        // kalau DP belum cair
                        if ($dp && $dp->level == 0) {
                            $q->unpaid_amount = $q->harga_total;
                        }
                        // kalau DP cair, tapi payment kedua belum (atau belum ada)
                        elseif ($dp && $dp->level == 1 && (!$second || $second->level == 0)) {
                            $q->unpaid_amount = $q->harga_total - $dp->amount;
                        }
                        // kalau DP dan payment kedua sudah cair semua
                        else {
                            $q->unpaid_amount = 0;
                        }
                    }

                    return $q;
                });

            $unpaidGeneral1 = $unpaidInvoice1->sum('unpaid_amount');
            $unpaidKojisha1 = $unpaidInvoice1->where('info', 'Kojisha')->sum('unpaid_amount');
            $unpaidReftech1 = $unpaidInvoice1->where('info', 'Reftech')->sum('unpaid_amount');
            $unpaidGeneral2 = $unpaidInvoice2->sum('unpaid_amount');
            $unpaidReftech2 = $unpaidInvoice2->where('info', 'Reftech')->sum('unpaid_amount');
            $unpaidKojisha2 = $unpaidInvoice2->where('info', 'Kojisha')->sum('unpaid_amount');

            $outstandingInvoice1 = Invoice::join('quotation as q', 'q.id', '=', 'invoice.id_quotation')
                ->join('payment as pay', 'pay.id_quotation', '=', 'q.id')
                ->join('pic as p', 'q.id_pic', '=', 'p.id')
                ->join('client as c', 'p.id_client', '=', 'c.id')
                ->whereBetween('invoice.date', [$start1, $end1])
                ->where('pay.type', "Tempo")
                ->where('pay.level', 0)
                ->groupBy('pay.id')
                ->select('pay.amount', 'c.info')
                ->get();
            $outstandingInvoice2 = Invoice::join('quotation as q', 'q.id', '=', 'invoice.id_quotation')
                ->join('payment as pay', 'pay.id_quotation', '=', 'q.id')
                ->join('pic as p', 'q.id_pic', '=', 'p.id')
                ->join('client as c', 'p.id_client', '=', 'c.id')
                ->whereBetween('invoice.date', [$start2, $end2])
                ->where('pay.type', "Tempo")
                ->where('pay.level', 0)
                ->groupBy('pay.id')
                ->select('pay.amount', 'c.info')
                ->get();

            $overdueInvoice1 = Invoice::join('quotation as q', 'q.id', '=', 'invoice.id_quotation')
                ->join('payment as pay', 'pay.id_quotation', '=', 'q.id')
                ->join('pic as p', 'q.id_pic', '=', 'p.id')
                ->join('client as c', 'p.id_client', '=', 'c.id')
                ->whereBetween('invoice.date', [$start1, $end1])
                ->where('pay.type', "Tempo")
                ->where('pay.level', 0)
                ->whereDate('pay.due_date', '<=', Carbon::today())
                ->groupBy('pay.id')
                ->get();
            $overdueInvoice2 = Invoice::join('quotation as q', 'q.id', '=', 'invoice.id_quotation')
                ->join('payment as pay', 'pay.id_quotation', '=', 'q.id')
                ->join('pic as p', 'q.id_pic', '=', 'p.id')
                ->join('client as c', 'p.id_client', '=', 'c.id')
                ->whereBetween('invoice.date', [$start2, $end2])
                ->where('pay.type', "Tempo")
                ->where('pay.level', 0)
                ->whereDate('pay.due_date', '<=', Carbon::today())
                ->groupBy('pay.id')
                ->get();

            $reminder = Reminder::join('payment as p', 'p.id', '=', 'reminder.id_payment')
                ->join('quotation as q', 'q.id', '=', 'p.id_quotation')
                ->join('invoice as i', 'i.id_quotation', '=', 'q.id')
                ->join('pic as pic', 'q.id_pic', '=', 'pic.id')
                ->join('client as c', 'pic.id_client', '=', 'c.id')
                ->select('reminder.*', 'i.no_invoice', 'p.amount', 'c.company')
                ->limit(5)->get();

            $nodueCount = Payment::where('type', 'Tempo')->whereNull('due_date')->count();

            return view(
                "pages.sales.dashboard",
                compact(
                    'requestContract',
                    'requestInvoice',
                    'newCount',
                    'listCount',
                    'notulens',
                    'deliveryCount',
                    'nodueCount',
                    'noSaleProspect',
                    'commentAdmin',
                    'unreadCommentAdmin',
                    'allInvoice1',
                    'allInvoice2',
                    'paidInvoice1',
                    'paidInvoice2',
                    'unpaidGeneral1',
                    'unpaidReftech1',
                    'unpaidKojisha1',
                    'unpaidGeneral2',
                    'unpaidReftech2',
                    'unpaidKojisha2',
                    'outstandingInvoice1',
                    'outstandingInvoice2',
                    'overdueInvoice1',
                    'overdueInvoice2',
                    'reminder',
                )
            );
        } else {
            $today = Carbon::now()->toDateString();
            $commodity = Product::count();
            $dproduct = DetailProduct::count();
            $sproduct = SerialProduct::count();
            $user = User::find('25');
            $monitoring = MonitoringActivities::whereDate('date', $today)->first();

            $newCount = PendingPO::where('status', operator: 0)
                ->where('type', 'Non Project')
                ->count();
            $listCount = PendingPO::whereIn('pending_po.status', [1, 2, 3, 4])
                ->where('type', 'Non Project')
                ->count();
            $deliveryCount = PendingPO::where('pending_po.status', 5)
                ->where('type', 'Non Project')
                ->count();
            $doneCount = PendingPO::where('pending_po.status', 6)
                ->where('type', 'Non Project')
                ->count();

            $visits = ReqVisit::whereNull('date')->get();
            $visited = ReqVisit::whereNotNull('date')->whereNull('visit_date')->get();
            return view(
                "pages.sales.dashboard",
                compact(
                    'user',
                    'newCount',
                    'listCount',
                    'deliveryCount',
                    'notulens',
                    'commodity',
                    'dproduct',
                    'sproduct',
                    'visits',
                    'visited'
                )
            );
        }

        // dd($leveledProspect);
    }

    public function overviewIndex()
    {
        $sales = User::where('role', 'Sales')->get();
        $totalPO = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $yearNow = $dateNow->year;
            return $sale->quotation()->whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        });
        $totalForecast = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $yearNow = $dateNow->year;
            $total = $sale->quotation()->whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->whereIn('status', ['20', '30', '40', '60', '80'])->where('level', '1')->where('is_primary', '1')->sum('nett');
            return number_format($total, 2, ',', '.');
        });
        $filteredPO = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $yearNow = $dateNow->year;
            return $sale->quotation()->whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        });
        $filteredLeads = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $yearNow = $dateNow->year;
            return $sale->client()->whereYear('created_at', $yearNow)->whereMonth('created_at', $monthNow)->count();
        });
        $filteredQuote = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $yearNow = $dateNow->year;
            return $sale->quotation()->whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('level', '1')->where('is_primary', '1')->count();
        });
        $filteredDC = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $yearNow = $dateNow->year;
            return $sale->clients->flatMap(function ($client) use ($monthNow, $yearNow) {
                return $client->activities()->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('status', 'Responded')->whereIn('name', ['Daily Call', 'Follow Up'])->distinct('client.id')->get();
            })->count();
        });
        $filteredCRM = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $yearNow = $dateNow->year;
            return $sale->clients->flatMap(function ($client) use ($monthNow, $yearNow) {
                return $client->activities()->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('status', 'Responded')->where('name', 'CRM')->groupBy('client.id')->get();
            })->count();
        });
        $filteredVisit = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $yearNow = $dateNow->year;
            return $sale->clients->flatMap(function ($client) use ($monthNow, $yearNow) {
                return $client->activities()->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('status', 'Responded')->where('name', 'Visit')->get();
            })->count();
        });
        $targett = $sales->map(function ($sale) {
            return $sale->target()->pluck('total')->sum();
        });
        // dd($targett);
        return view('pages.admin.overview', compact('visit', 'dailyCall', 'quotation', 'po', 'customers', 'sales', 'totalPO', 'totalForecast', 'filteredLeads', 'filteredPO', 'filteredQuote', 'filteredDC', 'filteredVisit', 'filteredCRM', 'targett'));
    }
    public function notifIndex()
    {
        $before60 = Carbon::now()->subDays(60);
        $now = Carbon::now();
        $authId = Auth::id();

        // =======================
        // 1. Ambil comment admin
        // =======================
        $firstComments = Comment::where('id_user', $authId)
            ->select('id_status', DB::raw('MIN(created_at) as first_created_at'))
            ->groupBy('id_status')
            ->pluck('first_created_at', 'id_status');

        $statusIds = $firstComments->keys();

        $commentsQueryBase = Comment::join('change_status as c', 'c.id', '=', 'comment.id_status')
            ->join('quotation as q', 'q.id', '=', 'c.id_quotation')
            ->join('users as u', 'u.id', '=', 'comment.id_user')
            ->whereIn('comment.id_status', $statusIds)
            ->where('comment.id_user', '!=', $authId)
            ->orderByDesc('comment.date')
            ->whereBetween('comment.created_at', [$before60, $now])
            ->where(function ($query) use ($firstComments) {
                foreach ($firstComments as $statusId => $createdAt) {
                    $query->orWhere(function ($subQuery) use ($statusId, $createdAt) {
                        $subQuery->where('comment.id_status', $statusId)
                            ->where('comment.created_at', '>', $createdAt);
                    });
                }
            });

        $commentAdmin = (clone $commentsQueryBase)
            ->orderBy('comment.id_status')
            ->orderByDesc('comment.date')
            ->get([
                'q.id as idQ',
                'comment.id as idC',
                'comment.id_user',
                'comment.level',
                'comment.comment',
                'comment.date',
                'q.no_quote',
                'u.name',
                'u.image'
            ]);

        $unreadCommentAdmin = (clone $commentsQueryBase)
            ->where('comment.level', '1')
            ->orderBy('comment.id_status')
            ->orderByDesc('comment.date')
            ->get([
                'q.id as idQ',
                'comment.id as idC',
                'comment.id_user',
                'comment.level',
                'comment.comment',
                'comment.date',
                'q.no_quote',
                'u.name',
                'u.image'
            ]);

        $notifAdmin = (clone $commentsQueryBase)
            ->orderBy('comment.id_status')
            ->orderByDesc('comment.date')
            ->get([
                'q.id as idQ',
                'comment.id as idC',
                'comment.id_user',
                'comment.level',
                'comment.comment',
                'comment.date',
                'q.no_quote',
                'u.name',
                'u.image'
            ]);
        // =======================
        // 2. Unread comment (sales)
        // =======================
        $unreadComment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', 'o.id_status', '=', 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            ->where('quotation.id_sales', $authId)
            ->where('o.id_user', '!=', $authId)
            ->where('o.level', '1')
            ->whereBetween('o.created_at', [$before60, $now])
            ->orderBy('o.date', 'DESC')
            ->get([
                'quotation.id as idQ',
                'o.id as idC',
                'o.id_user',
                'o.level',
                'o.comment',
                'o.date',
                'quotation.no_quote',
                'u.name',
                'u.image'
            ]);

        // =======================
        // 3. Comment quotation & prospect
        // =======================
        $quotationComment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', 'o.id_status', '=', 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            // ->where('quotation.id_sales', $authId)
            ->where('o.type', 'quotation')
            ->where('o.id_user', '!=', $authId)
            ->whereBetween('o.created_at', [$before60, $now])
            ->orderBy('o.date', 'DESC')
            ->select([
                'quotation.id as idQ',
                'o.id as idC',
                'o.id_user',
                'o.level',
                'o.comment',
                'o.date',
                'o.type',
                'quotation.no_quote',
                'u.name',
                'u.image'
            ]);

        $prospectComment = Comment::join('prospect as p', 'comment.id_prospect', '=', 'p.id')
            ->join('users as u', 'u.id', '=', 'comment.id_user')
            ->join('pic as pi', 'pi.id', '=', 'p.id_pic')
            ->join('client as c', 'c.id', '=', 'pi.id_client')
            // ->where('p.id_sales', $authId)
            ->where('comment.type', 'prospect')
            ->where('comment.id_user', '!=', $authId)
            ->whereBetween('comment.created_at', [$before60, $now])
            ->orderBy('comment.date', 'DESC')
            ->select([
                'p.id as idP',
                'comment.id as idC',
                'comment.id_user',
                'comment.level',
                'comment.comment',
                'comment.date',
                'comment.type',
                'c.company',
                'u.name',
                'u.image'
            ]);

        $comment = $quotationComment
            ->union($prospectComment)
            ->orderBy('date', 'DESC')
            ->get();

        // dd($comment[1]);

        // =======================
        // 4. Activities
        // =======================
        $activities = DB::table('quotation')
            ->select(
                'id',
                'created_at',
                DB::raw("'quotation' as type"),
                'no_quote as detail',
                'num_rev as vers',
                DB::raw("'-' as status")
            )
            ->whereBetween('created_at', [$before60, $now])
            ->where('id_sales', $authId)
            ->unionAll(
                DB::table('activities')
                    ->select(
                        'activities.id',
                        'activities.created_at',
                        DB::raw("'activities' as type"),
                        'client.company as detail',
                        'status as vers',
                        'name as status'
                    )
                    ->join('client', 'client.id', '=', 'activities.id_client')
                    ->where('id_sales', $authId)
                    ->whereBetween('activities.created_at', [$before60, $now])
            )
            ->unionAll(
                DB::table('comment')
                    ->select(
                        'q.id',
                        'comment.created_at',
                        DB::raw("'comment' as type"),
                        'comment.comment as detail',
                        'no_quote as vers',
                        'name as status'
                    )
                    ->join('change_status as c', 'c.id', '=', 'comment.id_status')
                    ->join('quotation as q', 'q.id', '=', 'c.id_quotation')
                    ->join('users as u', 'u.id', '=', 'q.id_sales')
                    ->where('id_user', $authId)
                    ->whereBetween('comment.created_at', [$before60, $now])
            )
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.activity', compact(
            'unreadComment',
            'unreadCommentAdmin',
            'comment',
            'commentAdmin',
            'notifAdmin',
            'activities'
        ));
    }
    // public function notifIndex()
    // {
    //     // Comment Buat Admin
    //     $firstComments = Comment::where('id_user', Auth::id())
    //         ->groupBy('id_status')
    //         ->get();

    //     $statusIds = $firstComments->pluck('id_status')->toArray();
    //     $dates = $firstComments->pluck('created_at', 'id_status');

    //     $commentsQuery = Comment::join('change_status as c', 'c.id', '=', 'comment.id_status')
    //         ->join('quotation as q', 'q.id', '=', 'c.id_quotation')
    //         ->join('users as u', 'u.id', '=', 'comment.id_user')
    //         ->whereIn('comment.id_status', $statusIds)
    //         ->where(function ($query) use ($dates) {
    //             foreach ($dates as $statusId => $createdAt) {
    //                 $query->orWhere(function ($subQuery) use ($statusId, $createdAt) {
    //                     $subQuery->where('comment.id_status', $statusId)
    //                         ->whereRaw('TIMESTAMPDIFF(SECOND, ?, comment.created_at) > 0', [$createdAt]);
    //                 });
    //             }
    //         })
    //         ->where('comment.id_user', '!=', Auth::id());

    //     // Ambil semua komentar yang relevan
    //     $commentAdmin = $commentsQuery->orderBy('comment.id_status')
    //         ->orderByDesc('comment.created_at')
    //         ->get(['q.id as idQ', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'q.no_quote', 'u.name', 'u.image']);

    //     // Filter untuk komentar dengan level '1'
    //     $unreadCommentAdmin = $commentsQuery->where('comment.level', '1')
    //         ->orderBy('comment.id_status')
    //         ->orderByDesc('comment.created_at')
    //         ->get(['q.id as idQ', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'q.no_quote', 'u.name', 'u.image']);

    //     $before60 = Carbon::now()->subDays(60);
    //     $notifAdmin = $commentsQuery->orderBy('comment.id_status')
    //         ->orderBy('comment.created_at')
    //         ->whereBetween('comment.created_at', [$before60, Carbon::now()])
    //         ->get(['q.id as idQ', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'q.no_quote', 'u.name', 'u.image']);
    //     // End Comment Admin

    //     $unreadComment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
    //         ->join('comment as o', first: 'o.id_status', operator: '=', second: 'c.id')
    //         ->join('users as u', 'u.id', '=', 'o.id_user')
    //         ->where('quotation.id_sales', Auth::id())
    //         ->whereNot('id_user', Auth::id())
    //         ->where('o.level', '1')
    //         ->orderBy('o.date', 'DESC')
    //         ->get(['quotation.id as idQ', 'o.id as idC', 'o.id_user', 'o.level', 'o.comment', 'o.date', 'quotation.no_quote', 'u.name', 'u.image']);


    //     $quotationComment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
    //         ->join('comment as o', 'o.id_status', '=', 'c.id')
    //         ->join('users as u', 'u.id', '=', 'o.id_user')
    //         ->where('quotation.id_sales', Auth::id())
    //         ->where('o.type', 'quotation')  // Pastikan filter type di sini
    //         ->whereBetween('o.created_at', [$before60, Carbon::now()])
    //         ->where('o.id_user', '!=', Auth::id())
    //         ->orderBy('o.date', 'DESC')
    //         ->select(['quotation.id as idQ', 'o.id as idC', 'o.id_user', 'o.level', 'o.comment', 'o.date', 'o.type', 'quotation.no_quote', 'u.name', 'u.image']);

    //     // Query untuk mengambil data dengan type "prospect"
    //     $prospectComment = Comment::join('prospect as p', 'comment.id_prospect', '=', 'p.id')
    //         ->join('users as u', 'u.id', '=', 'comment.id_user')
    //         ->join('pic as pi', 'pi.id', '=', 'p.id_pic')
    //         ->join('client as c', 'c.id', '=', 'pi.id_client')
    //         ->where('p.id_sales', Auth::id())
    //         ->where('comment.type', 'prospect')  // Pastikan filter type di sini
    //         ->where('comment.id_user', '!=', Auth::id())
    //         ->whereBetween('comment.created_at', [$before60, Carbon::now()])
    //         ->orderBy('comment.date', 'DESC')
    //         ->select(['p.id as idP', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'comment.type', 'c.company', 'u.name', 'u.image']);

    //     // Menggabungkan kedua query menggunakan union
    //     $comment = $quotationComment->union($prospectComment)
    //         ->orderBy('date', 'DESC')
    //         ->get();

    //     // dd($comment->first()->type);
    //     // dd($unreadCommentAdmin);
    //     $activities = DB::table('quotation')
    //         ->select('id', 'created_at', DB::raw("'quotation' as type"), 'no_quote as detail', 'num_rev as vers', DB::raw("'-' as status"))
    //         ->whereBetween('created_at', [$before60, Carbon::now()]) // Mengambil data 7 hari ke belakang
    //         ->where('id_sales', Auth::id())
    //         ->unionAll(
    //             DB::table('activities')
    //                 ->select('activities.id', 'activities.created_at', DB::raw("'activities' as type"), 'client.company as detail', 'status as vers', 'name as status')
    //                 ->join('client', 'client.id', '=', 'activities.id_client')
    //                 ->where('id_sales', Auth::id())
    //                 ->whereBetween('activities.created_at', [$before60, Carbon::now()])
    //         )
    //         ->unionAll(
    //             DB::table('comment')
    //                 ->select('q.id', 'comment.created_at', DB::raw("'comment' as type"), 'comment.comment as detail', 'no_quote as vers', 'name as status')
    //                 ->join('change_status as c', 'c.id', '=', 'comment.id_status')
    //                 ->join('quotation as q', 'q.id', '=', 'c.id_quotation')
    //                 ->join('users as u', 'u.id', '=', 'q.id_sales')
    //                 ->where('id_user', Auth::id())
    //                 ->whereBetween('comment.created_at', [$before60, Carbon::now()])
    //         )
    //         ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan created_at
    //         ->get();
    //     // dd($activities);
    //     return view('pages.activity', compact('unreadComment', 'unreadCommentAdmin', 'comment', 'commentAdmin', 'notifAdmin', 'activities'));
    // }
    public function dateNotif($date)
    {
        $quotationComment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', 'o.id_status', '=', 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            ->where('quotation.id_sales', Auth::id())
            ->where('o.type', 'quotation')  // Pastikan filter type di sini
            ->whereDate('o.created_at', $date)
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
            ->whereDate('comment.created_at', $date)
            ->orderBy('comment.date', 'DESC')
            ->select(['p.id as idP', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'comment.type', 'c.company', 'u.name', 'u.image']);

        // Menggabungkan kedua query menggunakan union
        $comment = $quotationComment->union($prospectComment)
            ->orderBy('date', 'DESC')
            ->get();
        return $comment;
    }
    public function dateActivity($date)
    {
        $activities = DB::table('quotation')
            ->select('id', 'created_at', DB::raw("'quotation' as type"), 'no_quote as detail', 'num_rev as vers', DB::raw("'-' as status"))
            ->whereDate('created_at', $date) // Mengambil data 7 hari ke belakang
            ->where('id_sales', Auth::id())
            ->unionAll(
                DB::table('activities')
                    ->select('activities.id', 'activities.created_at', DB::raw("'activities' as type"), 'client.company as detail', 'status as vers', 'name as status')
                    ->join('client', 'client.id', '=', 'activities.id_client')
                    ->where('id_sales', Auth::id())
                    ->whereDate('activities.created_at', $date)
            )
            ->unionAll(
                DB::table('comment')
                    ->select('q.id', 'comment.created_at', DB::raw("'comment' as type"), 'comment.comment as detail', 'no_quote as vers', 'name as status')
                    ->join('change_status as c', 'c.id', '=', 'comment.id_status')
                    ->join('quotation as q', 'q.id', '=', 'c.id_quotation')
                    ->join('users as u', 'u.id', '=', 'q.id_sales')
                    ->where('id_user', Auth::id())
                    ->whereDate('comment.created_at', $date)
            )
            ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan created_at
            ->get();

        return $activities;
    }
    public function dateNotifAdmin($date)
    {

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

        $adminNotif = $commentsQuery->orderBy('comment.id_status')
            ->orderBy('comment.created_at')
            ->whereDate('comment.created_at', $date)
            ->get(['q.id as idQ', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'q.no_quote', 'u.name', 'u.image']);

        return $adminNotif;
    }
    // Ajax Sales Kanan
    public function totalQuotationAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalQuotation = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        return $totalQuotation;
    }
    public function totalProspectAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalProspect = Quotation::join('prospect as p', 'quotation.id', '=', 'p.id_quotation')->whereNotNull('id_quotation')->whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $sales)->whereIn('status', ['80', '90'])->where('level', '1')->where('is_primary', '1')->sum('nett');
        return $totalProspect;
    }
    public function totalHotProspectAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalHotProspect = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $sales)->whereIn('status', ['80', '90'])->where('level', '1')->where('is_primary', '1')->sum('nett');
        return $totalHotProspect;
    }
    
    public function totalLossAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalProspect = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $sales)->where('status', '0')->where('level', '1')->where('is_primary', '1')->sum('nett');
        return $totalProspect;
    }
    public function totalPoAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_sales', $sales)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        return $totalPO;
    }
    public function totalTargetPoAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;

        $totalPO = Quotation::whereYear('po_date', $yearNow)
            ->whereMonth('po_date', $monthNow)
            ->where('id_sales', $sales)
            ->where('status', '100')
            ->where('level', '1')
            ->where('is_primary', '1')
            ->sum('nett');

        $target = Target::where('id_sales', $sales)->first('total');

        $totalTarget = ($totalPO / $target->total) * 100;

        return round($totalTarget, 2);
    }

    public function filteredPOAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_sales', $sales)->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        return $filteredPO;
    }
    // Ajax Kiri Sales
    public function filteredLeadsAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredLeads = Client::whereYear('created_at', $yearNow)->whereMonth('created_at', $monthNow)->where('id_sales', $sales)->count();
        return $filteredLeads;
    }
    public function filteredPercentLeadsAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredLeads = Client::whereYear('created_at', $yearNow)->whereMonth('created_at', $monthNow)->where('id_sales', $sales)->count();
        $target = Target::where('id_sales', $sales)->first('leads');
        $leadsTarget = ($filteredLeads / $target->leads) * 100;
        return round($leadsTarget);
    }
    public function filteredTargetLeadsAdmin($sales)
    {
        $target = Target::where('id_sales', $sales)->first('leads');
        return $target;
    }
    public function filteredDcAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredDC = Activities::join('client as c', 'activities.id_client', '=', 'c.id')
            ->whereYear('date', $yearNow)
            ->whereMonth('date', $monthNow)
            ->where('c.id_sales', $sales)
            ->where('status', 'Responded')
            ->whereIn('name', ['Daily Call', 'Follow Up'])
            ->distinct('c.id')
            ->count();
        return $filteredDC;
    }
    public function filteredTargetDcAdmin($sales)
    {
        $target = Target::where('id_sales', $sales)->first('dc');
        return $target;
    }
    public function filteredPercentDcAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredDC = Activities::join('client as c', 'activities.id_client', '=', 'c.id')
            ->whereYear('date', $yearNow)
            ->whereMonth('date', $monthNow)
            ->where('c.id_sales', $sales)
            ->where('status', 'Responded')
            ->whereIn('name', ['Daily Call', 'Follow Up'])
            ->distinct('c.id')
            ->count();
        $target = Target::where('id_sales', $sales)->first('dc');
        $dcTarget = ($filteredDC / $target->dc) * 100;
        return round($dcTarget);
    }
    public function filteredCrmAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredCRM = Activities::join('client as c', 'activities.id_client', '=', 'c.id')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('c.id_sales', $sales)->where('status', 'Responded')->where('name', 'CRM')->distinct('c.id')->count();
        return $filteredCRM;
    }
    public function filteredTargetCRMAdmin($sales)
    {
        $target = Client::where('role', 'Customers')->where('id_sales', $sales)->count();
        return $target;
    }
    public function filteredPercentCRMAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredCRM = Activities::join('client as c', 'activities.id_client', '=', 'c.id')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('c.id_sales', $sales)->where('status', 'Responded')->where('name', 'CRM')->distinct('c.id')->count();
        $target = Client::where('role', 'Customers')->where('id_sales', $sales)->count();
        $crmTarget = ($filteredCRM / $target) * 100;
        return round($crmTarget);
    }
    public function filteredQuoteAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredQuote = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->count();
        return $filteredQuote;
    }
    public function filteredTargetQuoteAdmin($sales)
    {
        $target = Target::where('id_sales', $sales)->first('quote');
        return $target;
    }
    public function filteredPercentQuoteAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredQuote = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->count();
        $target = Target::where('id_sales', $sales)->first('quote');
        $quoteTarget = ($filteredQuote / $target->quote) * 100;
        return round($quoteTarget);
    }
    public function filteredProspectAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredProspect = Prospect::whereNotNull('id_quotation')->whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('id_sales', $sales)->count();
        return $filteredProspect;
    }
    public function filteredAllProspectAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $allProspect = Prospect::whereNotNull('id_quotation')->whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('id_sales', $sales)->count();
        return $allProspect;
    }
    public function filteredPercentProspectAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredProspect = Prospect::whereNotNull('id_quotation')->whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('id_sales', $sales)->count();
        $allProspect = Prospect::whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('id_sales', $sales)->count();
        $prospectTarget = ($filteredProspect / $allProspect ?? 0) * 100;
        return round($prospectTarget);
    }

    // Ajax Kiri Online
    public function filteredProductAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $product = SalesOnline::where('type', 'Product')->whereMonth('date', $monthNow)->whereYear('date', $yearNow)->where('id_sales', $sales)->count();
        return $product;
    }
    public function filteredSWAdmin($sales)
    {
        $monthNow = now()->month;
        $yearNow = now()->year;

        $airendSum = SalesOnline::where('type', 'SW')
            ->whereMonth('date', $monthNow)
            ->whereYear('date', $yearNow)
            ->where('id_sales', $sales)
            ->sum('airend');

        $kojishaSum = SalesOnline::where('type', 'SW')
            ->whereMonth('date', $monthNow)
            ->whereYear('date', $yearNow)
            ->where('id_sales', $sales)
            ->sum('kojisha');

        return $airendSum + $kojishaSum;
    }
    public function filteredVideoAdmin($sales)
    {
        $monthNow = now()->month;
        $yearNow = now()->year;

        $totalVideo = 0;

        $video = SalesOnline::where('type', 'Video')
            ->whereMonth('date', $monthNow)
            ->whereYear('date', $yearNow)
            ->where('id_sales', $sales)
            ->get();

        if ($video->isEmpty()) {
            return 0; // Hindari pembagian nol
        }

        foreach ($video as $item) {
            if (!empty($item->ig)) {
                $totalVideo += 30;
            }
            if (!empty($item->tiktok)) {
                $totalVideo += 30;
            }
            if (!empty($item->tokped)) {
                $totalVideo += 30;
            }
        }

        return $totalVideo / $video->count();
    }
    public function filteredStatAdmin($sales)
    {
        $monthNow = now()->month;
        $yearNow = now()->year;

        $stat = SalesOnline::where('type', 'Akurasi')
            ->whereMonth('date', $monthNow)
            ->whereYear('date', $yearNow)
            ->where('id_sales', $sales)
            ->get();

        if ($stat->isEmpty()) {
            return 0; // atau null, tergantung kebutuhan
        }

        return $stat->sum('average') / $stat->count();
    }
    public function filteredDeliveryAdmin($sales)
    {
        $monthNow = now()->month;
        $yearNow = now()->year;

        $delivery = SalesOnline::where('type', 'Delivery')
            ->whereMonth('date', $monthNow)
            ->whereYear('date', $yearNow)
            ->where('id_sales', $sales)
            ->get();

        if ($delivery->isEmpty()) {
            return 0; // atau null, tergantung kebutuhan
        }

        return $delivery->sum('average') / $delivery->count();
    }
    public function filteredCustomerAdmin($sales)
    {
        $monthNow = now()->month;
        $yearNow = now()->year;

        $customer = SalesOnline::where('type', 'Customer')
            ->whereMonth('date', $monthNow)
            ->whereYear('date', $yearNow)
            ->where('id_sales', $sales)
            ->get();

        if ($customer->isEmpty()) {
            return 0; // atau null, tergantung kebutuhan
        }

        return $customer->sum('average') / $customer->count();
    }
    public function filteredResponseAdmin($sales)
    {
        $monthNow = now()->month;
        $yearNow = now()->year;

        $response = SalesOnline::where('type', 'Response')
            ->whereMonth('date', $monthNow)
            ->whereYear('date', $yearNow)
            ->where('id_sales', $sales)
            ->get();

        if ($response->isEmpty()) {
            return 0; // atau null, tergantung kebutuhan
        }

        return $response->sum('average') / $response->count();
    }
    public function filteredRatingAdmin($sales)
    {
        $monthNow = now()->month;
        $yearNow = now()->year;

        $rating = SalesOnline::where('type', 'Rating')
            ->whereMonth('date', $monthNow)
            ->whereYear('date', $yearNow)
            ->where('id_sales', $sales)
            ->get();

        if ($rating->isEmpty()) {
            return 0; // atau null, tergantung kebutuhan
        }

        return $rating->sum('average') / $rating->count();
    }

    public function filteredVisitAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredVisit = Activities::join('client as c', 'activities.id_client', '=', 'c.id')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('c.id_sales', $sales)->where('status', 'Responded')->where('name', 'Visit')->count();
        return $filteredVisit;
    }

    // Ajax Support
    public function filteredProspect($support)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredprospect = Prospect::whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('id_support', $support)->count();
        return $filteredprospect;
    }
    public function filteredProvide($support)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredprospect = Prospect::whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('id_support', $support)->count();
        $filteredProvide = Prospect::whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('provide', '!=', '0')->where('id_support', $support)->count();
        $percentedProvide = $filteredprospect > 0
            ? round(($filteredProvide / $filteredprospect) * 100, 2)
            : 0;
        return response()->json([
            'prospect' => $filteredprospect,
            'provide' => $filteredProvide,
            'percent' => $percentedProvide
        ]);
    }
    public function filteredProspectedQuotation($support)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredProspectQuote = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_support', $support)->where('level', '1')->where('is_primary', '1')->count();
        $filteredProvide = Prospect::whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('provide', '!=', '0')->where('id_support', $support)->count();
        $percentedQuotation = $filteredProvide > 0
            ? round(($filteredProspectQuote / $filteredProvide) * 100, 2)
            : 0;
        return response()->json([
            'quotation' => $filteredProspectQuote,
            'provide' => $filteredProvide,
            'percent' => $percentedQuotation
        ]);
    }
    public function filteredNotProvide($support)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredprospect = Prospect::whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('id_support', $support)->count();
        $filteredNotProvide = Prospect::whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('provide', '==', '0')->where('id_support', $support)->count();
        $percentedProvide = $filteredprospect > 0
            ? round(($filteredNotProvide / $filteredprospect) * 100, 2)
            : 0;
        return response()->json([
            'prospect' => $filteredprospect,
            'provide' => $filteredNotProvide,
            'percent' => $percentedProvide
        ]);
    }
    public function filteredProspectedPO($support)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredProspectPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_support', $support)->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        $filteredProspectQuote = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_support', $support)->where('level', '1')->where('is_primary', '1')->count();
        $percentedQuotation = $filteredProspectQuote > 0
            ? round(($filteredProspectPO / $filteredProspectQuote) * 100, 2)
            : 0;
        return response()->json([
            'quotation' => $filteredProspectQuote,
            'po' => $filteredProspectPO,
            'percent' => $percentedQuotation
        ]);
    }
    public function totalProspectedQuotation($support)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalProspectQuote = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_support', $support)->where('status', '!=', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $formattedQuote = number_format($totalProspectQuote, 0, ",", ".");
        return $formattedQuote;
    }
    public function totalProspectedProspect($support)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalProspectProspect = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_support', $support)->whereIn('status', ['80', '90'])->where('level', '1')->where('is_primary', '1')->sum('nett');
        $formattedProspect = number_format($totalProspectProspect, 0, ",", ".");
        return $formattedProspect;
    }
    public function totalProspectedPO($support)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalProspectPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_support', $support)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $formattedPO = number_format($totalProspectPO, 0, ",", ".");
        return $formattedPO;
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
                        ->distinct('c.id')
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
                        ->distinct('c.id')
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
    protected function getWeekDataLeads()
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
                    $dCallPerWeek = Client::select(DB::raw('COUNT(*) as total'))
                        ->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
                        ->where(DB::raw('WEEK(created_at, 4)'), $weekKey)
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

    protected function getQuotationSales()
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $quotation = Quotation::whereYear('estimated_date', $yearNow)->whereMonth("estimated_date", $monthNow)->where("id_sales", Auth::user()->id)->where('level', '1')->where('is_primary', '1')->get();
        return $quotation;
    }
    protected function getVisitSales()
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $visit = Activities::select('activities.*')
            ->join('client as c', 'activities.id_client', '=', 'c.id')
            ->join('users as u', 'c.id_sales', '=', 'u.id')
            ->whereYear('date', $yearNow)
            ->whereMonth("date", $monthNow)
            ->where('u.id', Auth::user()->id)
            ->where('status', 'Responded')
            ->where('activities.name', 'Visit')
            ->count();
        return $visit;
    }
    protected function getDailyCallSales()
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $dailyCall = Activities::select('activities.*')
            ->join('client as c', 'activities.id_client', '=', 'c.id')
            ->join('users as u', 'c.id_sales', '=', 'u.id')
            ->whereYear('date', $yearNow)
            ->whereMonth("date", $monthNow)
            ->where('u.id', Auth::user()->id)
            ->where('status', 'Responded')
            ->whereIn('activities.name', ['Daily Call', 'Follow Up'])
            ->distinct('c.id')
            ->count();
        return $dailyCall;
    }
    protected function getPoSales()
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $po = Quotation::whereYear('po_date', $yearNow)->whereMonth("po_date", $monthNow)->where("id_sales", Auth::user()->id)->where("status", "100")->where('level', '1')->where('is_primary', '1')->get();

        return $po;
    }
    protected function getLeadsSales()
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $leads = Client::whereYear('created_at', $yearNow)->whereMonth("created_at", $monthNow)->where("id_sales", Auth::user()->id)->get();

        return $leads;
    }
    protected function getCustomersSales()
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $customers = Activities::select('activities.*')
            ->join('client as c', 'activities.id_client', '=', 'c.id')
            ->join('users as u', 'c.id_sales', '=', 'u.id')
            ->whereYear('date', $yearNow)
            ->whereMonth("date", $monthNow)
            ->where('u.id', Auth::user()->id)
            ->where('status', 'Responded')
            ->where('activities.name', 'CRM')
            ->distinct('c.id')
            ->count();
        return $customers;
    }
    // protected function getDataOverview()
    // {
    //     $users = User::where('role', 'Sales')->get();

    //     $data = [];
    //     $month = Carbon::now()->month;
    //     $year = Carbon::now()->year;

    //     foreach ($users as $user) {
    //         $leadCounts = collect([
    //             1 => 0,
    //             2 => 0,
    //             3 => 0,
    //             4 => 0,
    //             5 => 0,
    //         ]);

    //         $crmCounts = collect([
    //             1 => 0,
    //             2 => 0,
    //             3 => 0,
    //             4 => 0,
    //             5 => 0,
    //         ]);

    //         $quoteCounts = collect([
    //             1 => 0,
    //             2 => 0,
    //             3 => 0,
    //             4 => 0,
    //             5 => 0,
    //         ]);

    //         $poCounts = collect([
    //             1 => 0,
    //             2 => 0,
    //             3 => 0,
    //             4 => 0,
    //             5 => 0,
    //         ]);

    //         // Ambil semua clients milik user
    //         foreach ($user->clients as $client) {
    //             $activities = $client->activities()
    //                 ->where('name', 'CRM')
    //                 ->whereMonth('date', $month)
    //                 ->whereYear('date', $year)
    //                 ->get();

    //             foreach ($activities as $activity) {
    //                 $week = (int) $activity->week;

    //                 if (isset($crmCounts[$week])) {
    //                     $crmCounts->put($week, $crmCounts->get($week, 0) + 1);
    //                 }
    //             }
    //         }

    //         $leads = $user->clients()
    //             ->whereMonth('created_at', $month)
    //             ->whereYear('created_at', $year)
    //             ->get();

    //         foreach ($leads as $lead) {
    //             $week = (int) $lead->week;

    //             if (isset($leadCounts[$week])) {
    //                 $leadCounts->put($week, $leadCounts->get($week, 0) + 1);
    //             }
    //         }
    //         $quotations = $user->quotation()
    //             ->whereMonth('estimated_date', $month)
    //             ->whereYear('estimated_date', $year)
    //             ->where('level', '1')
    //             ->where('is_primary', '1')
    //             ->get();

    //         foreach ($quotations as $quote) {
    //             $week = (int) $quote->week;

    //             if (isset($quoteCounts[$week])) {
    //                 $quoteCounts->put($week, $quoteCounts->get($week, 0) + 1);
    //             }
    //         }

    //         $POs = $user->quotation()
    //             ->whereMonth('estimated_date', $month)
    //             ->whereYear('estimated_date', $year)
    //             ->where('status', '100')
    //             ->where('level', '1')
    //             ->where('is_primary', '1')
    //             ->get();

    //         foreach ($POs as $po) {
    //             $week = (int) $po->week;

    //             if (isset($poCounts[$week])) {
    //                 $poCounts->put($week, $poCounts->get($week, 0) + 1);
    //             }
    //         }

    //         $data[] = [
    //             'salesId' => $user->id,
    //             'sales' => $user->name,
    //             'lead' => $leadCounts,
    //             'crm' => $crmCounts,
    //             'quote' => $quoteCounts,
    //             'po' => $poCounts,
    //         ];
    //     }
    //     return $data;
    // }

    protected function getDataOverview()
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        $users = User::with('clients')->where('role', 'Sales')->get();

        // Ambil semua data sekaligus
        $allDC = Activities::join('client as c', 'activities.id_client', '=', 'c.id')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'Responded')
            ->whereIn('name', ['Daily Call', 'Follow Up'])
            ->groupBy('c.id')
            ->get();

        $allActivities = Activities::join('client as c', 'activities.id_client', '=', 'c.id')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'Responded')
            ->where('name', 'CRM')
            ->groupBy('c.id')
            ->get();

        $allLeads = Client::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        $allQuotes = Quotation::whereMonth('estimated_date', $month)
            ->whereYear('estimated_date', $year)
            ->where('level', '1')
            ->where('is_primary', '1')
            ->get();

        $allPOs = Quotation::whereMonth('po_date', $month)
            ->whereYear('po_date', $year)
            ->where('status', '100')
            ->where('level', '1')
            ->where('is_primary', '1')
            ->get();

        $data = [];

        foreach ($users as $user) {
            // inisialisasi counter minggu (1–5)
            $leadCounts = collect([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]);
            $dcCounts = collect([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]);
            $crmCounts = collect([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]);
            $quoteCounts = collect([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]);
            $poCounts = collect([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]);

            // Filter data yang sesuai sales
            $clientIds = $user->clients->pluck('id');
            $userDC = $allDC->where('id_sales', $user->id);
            $userCRM = $allActivities->whereIn('id_client', $clientIds);
            $userLeads = $allLeads->where('id_sales', $user->id);
            $userQuotes = $allQuotes->where('id_sales', $user->id);
            $userPOs = $allPOs->where('id_sales', $user->id);

            // Hitung CRM
            foreach ($userCRM as $activity) {
                $week = Carbon::parse($activity->date)->weekOfMonth;
                $crmCounts->put($week, $crmCounts->get($week) + 1);
            }

            // Hitung DC
            foreach ($userDC as $dc) {
                $week = Carbon::parse($dc->date)->weekOfMonth;
                $dcCounts->put($week, $dcCounts->get($week) + 1);
            }

            // Hitung Leads
            foreach ($userLeads as $lead) {
                $week = Carbon::parse($lead->created_at)->weekOfMonth;
                $leadCounts->put($week, $leadCounts->get($week) + 1);
            }

            // Hitung Quotes
            foreach ($userQuotes as $quote) {
                $week = Carbon::parse($quote->estimated_date)->weekOfMonth;
                $quoteCounts->put($week, $quoteCounts->get($week) + 1);
            }

            // Hitung POs
            foreach ($userPOs as $po) {
                $week = Carbon::parse($po->po_date)->weekOfMonth;
                $poCounts->put($week, $poCounts->get($week) + 1);
            }

            $data[] = [
                'salesId' => $user->id,
                'sales' => $user->name,
                'leads' => $leadCounts,
                'dc' => $dcCounts,
                'crm' => $crmCounts,
                'quote' => $quoteCounts,
                'po' => $poCounts,
            ];
        }

        return $data;
    }
}
