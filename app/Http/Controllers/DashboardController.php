<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Client;
use App\Models\Comment;
use App\Models\DetailProduct;
use App\Models\Issues;
use App\Models\Notulen;
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
            return view(
                "pages.sales.dashboard",
                compact(
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
                    'issue',
                    'clients',
                    'customers',
                    'unreadComment',
                    'comment'
                )
            );
        } elseif (Auth::user()->role == 'Admin') {
            $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
            $poTotalPriceAdmin = Quotation::whereYear('po_date', $yearNow)->whereMonth("po_date", $monthNow)->where("status", "100")->where('level', '1')->where('is_primary', '1')->sum('nett');
            $formattedTotalPriceAdmin = $this->formatNumber($poTotalPriceAdmin);
            $sales = User::whereIn('role', ['Sales', 'Support'])->where('active', '1')->get();
            $firstSales = User::where('role', 'Sales')->first();
            $targett = Target::where('id_sales', $firstSales->id)->first('total');
            $targetAllSales = Target::join('users as u', 'u.id', '=', 'target.id_sales')->where('u.role', 'Sales')->where('u.active', '1')->sum('target.total');
            // dd($targetAllSales);
            $targetSales = $sales->map(function ($sale) {
                return $sale->target()->groupBy('id_sales')->get();
            });
            $totalPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_sales', $firstSales->id)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
            // dd($totalPO);
            // $totalProspectQuote = Quotation::whereYear('date', $yearNow)->whereMonth('date', $monthNow)->whereNotNull('id_support')->where('status', '!=', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
            // $prospectedQuotation = Prospect::join('quotation as q', 'q.id', '=', 'prospect.id_quotation')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('provide', '!=', '0')->where('status', '!=', '100')->where('q.level', '1')->where('is_primary', '1')->count();
            // $prospectedPO = Prospect::join('quotation as q', 'q.id', '=', 'prospect.id_quotation')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('provide', '!=', '0')->where('status', '100')->where('q.level', '1')->where('is_primary', '1')->count();
            // $prospectedQuotationTotal = Prospect::join('quotation as q', 'q.id', '=', 'prospect.id_quotation')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('provide', '!=', '0')->where('status', '!=', '100')->where('q.level', '1')->where('is_primary', '1')->sum('q.nett');
            // $prospectedPOTotal = Prospect::join('quotation as q', 'q.id', '=', 'prospect.id_quotation')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('provide', '!=', '0')->where('status', '100')->where('q.level', '1')->where('is_primary', '1')->sum('q.nett');
            // dd($totalProspectQuote);
            $totalProspect = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $firstSales->id)->whereIn('status', ['20', '30', '40', '60', '80'])->where('level', '1')->where('is_primary', '1')->sum('nett');
            $totalForecast = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $firstSales->id)->where('status', '80')->where('level', '1')->where('is_primary', '1')->sum('nett');
            $filteredPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_sales', $firstSales->id)->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
            $filteredDC = Activities::join('client as c', 'activities.id_client', '=', 'c.id')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('c.id_sales', $firstSales->id)->where('status', 'Responded')->count();
            $filteredCRM = Activities::join('client as c', 'activities.id_client', '=', 'c.id')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('c.id_sales', $firstSales->id)->where('status', 'Responded')->where('name', 'CRM')->count();
            $filteredQuote = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $firstSales->id)->where('level', '1')->where('is_primary', '1')->count();
            $filteredVisit = Activities::join('client as c', 'activities.id_client', '=', 'c.id')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('c.id_sales', $firstSales->id)->where('status', 'Responded')->where('name', 'Visit')->count();
            $dataDc = $this->getWeekDataDC();
            $dataCRM = $this->getWeekDataCRM();
            $dataVisit = $this->getWeekDataVisit();
            $dataQuote = $this->getWeekDataQuote();
            $dataPO = $this->getWeekDataPO();

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

            return view(
                "pages.sales.dashboard",
                compact(
                    'noSaleProspect',
                    'notulens',
                    'targetSales',
                    'sales',
                    'totalPO',
                    'filteredPO',
                    'filteredCRM',
                    'filteredVisit',
                    'filteredDC',
                    'filteredQuote',
                    'poTotalPriceAdmin',
                    'formattedTotalPriceAdmin',
                    'totalForecast',
                    'totalProspect',
                    'dataQuote',
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
        } else {
            $commodity = Product::count();
            $dproduct = DetailProduct::count();
            $sproduct = SerialProduct::count();

            $visits = ReqVisit::whereNull('date')->get();
            $visited = ReqVisit::whereNotNull('date')->whereNull('visit_date')->get();
            return view(
                "pages.sales.dashboard",
                compact(
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
                return $client->activities()->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('status', 'Responded')->whereIn('name', ['Daily Call', 'Follow Up'])->get();
            })->count();
        });
        $filteredCRM = $sales->map(function ($sale) {
            $dateNow = Carbon::now();
            $monthNow = $dateNow->month;
            $yearNow = $dateNow->year;
            return $sale->clients->flatMap(function ($client) use ($monthNow, $yearNow) {
                return $client->activities()->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('status', 'Responded')->where('name', 'CRM')->get();
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
        return view('pages.admin.overview', compact('visit', 'dailyCall', 'quotation', 'po', 'customers', 'sales', 'totalPO', 'totalForecast', 'filteredPO', 'filteredQuote', 'filteredDC', 'filteredVisit', 'filteredCRM', 'targett'));
    }

    public function notifIndex()
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

        // Ambil semua komentar yang relevan
        $commentAdmin = $commentsQuery->orderBy('comment.id_status')
            ->orderByDesc('comment.created_at')
            ->get(['q.id as idQ', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'q.no_quote', 'u.name', 'u.image']);

        // Filter untuk komentar dengan level '1'
        $unreadCommentAdmin = $commentsQuery->where('comment.level', '1')
            ->orderBy('comment.id_status')
            ->orderByDesc('comment.created_at')
            ->get(['q.id as idQ', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'q.no_quote', 'u.name', 'u.image']);


        $notifAdmin = $commentsQuery->orderBy('comment.id_status')
            ->orderBy('comment.created_at')
            ->whereDate('comment.created_at', Carbon::now())
            ->get(['q.id as idQ', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'q.no_quote', 'u.name', 'u.image']);
        // End Comment Admin

        $unreadComment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', first: 'o.id_status', operator: '=', second: 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            ->where('quotation.id_sales', Auth::id())
            ->whereNot('id_user', Auth::id())
            ->where('o.level', '1')
            ->orderBy('o.date', 'DESC')
            ->get(['quotation.id as idQ', 'o.id as idC', 'o.id_user', 'o.level', 'o.comment', 'o.date', 'quotation.no_quote', 'u.name', 'u.image']);


        $quotationComment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', 'o.id_status', '=', 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            ->where('quotation.id_sales', Auth::id())
            ->where('o.type', 'quotation')  // Pastikan filter type di sini
            ->whereDate('o.created_at', Carbon::now())
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
            ->whereDate('comment.created_at', Carbon::now())
            ->orderBy('comment.date', 'DESC')
            ->select(['p.id as idP', 'comment.id as idC', 'comment.id_user', 'comment.level', 'comment.comment', 'comment.date', 'comment.type', 'c.company', 'u.name', 'u.image']);

        // Menggabungkan kedua query menggunakan union
        $comment = $quotationComment->union($prospectComment)
            ->orderBy('date', 'DESC')
            ->get();

        // dd($comment->first()->type);
        // dd($unreadCommentAdmin);
        $activities = DB::table('quotation')
            ->select('id', 'created_at', DB::raw("'quotation' as type"), 'no_quote as detail', 'num_rev as vers', DB::raw("'-' as status"))
            ->whereDate('created_at', Carbon::now()) // Mengambil data 7 hari ke belakang
            ->where('id_sales', Auth::id())
            ->unionAll(
                DB::table('activities')
                    ->select('activities.id', 'activities.created_at', DB::raw("'activities' as type"), 'client.company as detail', 'status as vers', 'name as status')
                    ->join('client', 'client.id', '=', 'activities.id_client')
                    ->where('id_sales', Auth::id())
                    ->whereDate('activities.created_at', Carbon::now())
            )
            ->unionAll(
                DB::table('comment')
                    ->select('q.id', 'comment.created_at', DB::raw("'comment' as type"), 'comment.comment as detail', 'no_quote as vers', 'name as status')
                    ->join('change_status as c', 'c.id', '=', 'comment.id_status')
                    ->join('quotation as q', 'q.id', '=', 'c.id_quotation')
                    ->join('users as u', 'u.id', '=', 'q.id_sales')
                    ->where('id_user', Auth::id())
                    ->whereDate('comment.created_at', Carbon::now())
            )
            ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan created_at
            ->get();
        // dd($activities);
        return view('pages.activity', compact('unreadComment', 'unreadCommentAdmin', 'comment', 'commentAdmin', 'notifAdmin', 'activities'));
    }
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
    public function totalPoAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_sales', $sales)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        return $totalPO;
    }
    public function totalForecastAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalProspect = Quotation::whereYear('po_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $sales)->whereIn('status', ['20', '30', '40', '60', '80'])->where('level', '1')->where('is_primary', '1')->sum('nett');
        return $totalProspect;
    }
    public function totalProspectAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalForecast = Quotation::whereYear('po_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $sales)->where('status', '80')->where('level', '1')->where('is_primary', '1')->sum('nett');
        return $totalForecast;
    }
    public function filteredPoAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_sales', $sales)->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        return $filteredPO;
    }
    public function filteredQuoteAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredQuote = Quotation::whereYear('po_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->count();
        return $filteredQuote;
    }
    public function filteredDcAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredDC = Activities::join('client as c', 'activities.id_client', '=', 'c.id')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('c.id_sales', $sales)->where('status', 'Responded')->count();
        return $filteredDC;
    }
    public function filteredCrmAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredCRM = Activities::join('client as c', 'activities.id_client', '=', 'c.id')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('c.id_sales', $sales)->where('status', 'Responded')->where('name', 'CRM')->count();
        return $filteredCRM;
    }
    public function filteredVisitAdmin($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredVisit = Activities::join('client as c', 'activities.id_client', '=', 'c.id')->whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('c.id_sales', $sales)->where('status', 'Responded')->where('name', 'Visit')->count();
        return $filteredVisit;
    }
    public function target($sales)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_sales', $sales)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $target = Target::where('id_sales', $sales)->first('total');
        $totalTarget = ($totalPO / $target->total) * 10000;
        return $totalTarget;
    }
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
        $filteredProvide = Prospect::whereYear('date', $yearNow)->whereMonth('date', $monthNow)->where('provide', '!=', '0')->where('id_support', $support)->count();
        return $filteredProvide;
    }
    public function filteredProspectedQuotation($support)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredProspectQuote = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_support', $support)->where('level', '1')->where('is_primary', '1')->count();
        return $filteredProspectQuote;
    }
    public function filteredProspectedPO($support)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $filteredProspectPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_support', $support)->where('status', '100')->where('level', '1')->where('is_primary', '1')->count();
        return $filteredProspectPO;
    }
    public function totalProspectedQuotation($support)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalProspectQuote = Quotation::whereYear('estimated_date', $yearNow)->whereMonth('estimated_date', $monthNow)->where('id_support', $support)->where('status', '!=', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $formattedQuote = number_format($totalProspectQuote,0,",",".");
        return $formattedQuote;
    }
    public function totalProspectedPO($support)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearNow = $dateNow->year;
        $totalProspectPO = Quotation::whereYear('po_date', $yearNow)->whereMonth('po_date', $monthNow)->where('id_support', $support)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $formattedPO = number_format($totalProspectPO,0,",",".");
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
            ->count();
        return $customers;
    }
}
