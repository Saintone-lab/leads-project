<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Client;
use App\Models\Comment;
use App\Models\CrmStatus;
use App\Models\Issues;
use App\Models\Machine;
use App\Models\Pic;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\Reports;
use App\Models\SerialProduct;
use App\Models\Unit;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CrmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();



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

        return view("pages.sales.existing.index", compact('leveledProspect', 'comment', 'unreadComment', 'commentAdmin', 'unreadCommentAdmin', 'noSaleProspect'));
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
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $yearsNow = $dateNow->year;
        $existing = Client::find($id);
        $machines = Machine::where('id_client', $id)->get();
        $charge = PIC::where('id_client', $id)->get();
        $callhis = Activities::where('id_client', $id)->whereIn('name', ['Daily Call', 'Follow Up', 'CRM'])->get();
        $visit = Activities::where('id_client', $id)->where('name', 'Visit')->get();
        $quote = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')->where('pic.id_client', $id)->where('level', '1')->get('quotation.*');
        $sales = User::where('role', 'sales')->get();
        $issue = Issues::all();
        $unit = SerialProduct::whereNotNull('detail')->get();
        // dd($unit);
        $crmhis = $this->data($id);
        $machinehis = $this->getServicePerMonth($id);
        // dd($yearsNow);
        $service = Reports::join('pic', 'pic.id', '=', 'reports.id_pic')->where('pic.id_client', $id)->get('reports.*');
        // dd($quote);
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();


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
        return view(
            'pages.sales.existing.detail',
            compact(
                'existing',
                'callhis',
                'quote',
                'leveledProspect',
                'noSaleProspect',
                'comment',
                'unreadComment',
                'commentAdmin',
                'unreadCommentAdmin',
                'sales',
                'unit',
                'charge',
                'issue',
                'crmhis',
                'service',
                'visit',
                'machines',
                'monthNow',
                'yearsNow'
            )
        );
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
        $rule = [
            'company' =>
                'required',

            'email' =>
                'required',

            'phone' =>
                'required',

            'web' =>
                'required',

            'source' =>
                'required',

            'mobile' =>
                'required',

            'address' =>
                'required',

            'area' =>
                'required',

            'npwp' =>
                'required',
        ];

        $message = [
            'company.required' => 'Field company Wajib Diisi',
            'email.required' => 'Field Email Wajib Diisi',
            'phone.required' => 'Field Phone Wajib Diisi',
            'ru.required' => 'Wajib Pilih Reseller atau User',
            'web.required' => 'Field Web Wajib Diisi',
            'source.required' => 'Field Source Wajib Diisi',
            'mobile.required' => 'Field Mobile Wajib Diisi',
            'address.required' => 'Field Address Wajib Diisi',
            'area.required' => 'Field Area Wajib Diisi',
            'npwp.required' => 'Field npwp Wajib Diisi',
        ];

        $this->validate($request, $rule, $message);

        $existings = Client::find($id);
        $existings->company = $request->company;
        $existings->email = $request->email;
        $existings->phone = $request->phone;
        $existings->ru = $request->ru;
        $existings->web = $request->web;
        $existings->source = $request->source;
        $existings->npwp = $request->npwp;
        $existings->mobile = $request->mobile;
        $existings->info = $request->info;
        $existings->address = $request->address;
        $existings->subAddress = $request->subAddress;
        $existings->area = $request->area;
        $existingsave = $existings->save();

        if ($existingsave) {
            return redirect('/existing/' . $id)->with('message', 'data telah diUpdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existingD = Client::find($id);
        $picD = Pic::where('id_client', $id)->get();
        $activitiesD = Activities::where('id_client', $id)->get();
        $visitD = Visit::where('id_client', $id)->get();
        $quoteD = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')->where('pic.id_client', $id)->get();

        $delExisting = $existingD->delete();
        if ($picD != NULL) {
            foreach ($picD as $pic) {
                $delpic = $pic->delete();
            }
        }
        if ($activitiesD != NULL) {
            foreach ($activitiesD as $activities) {
                $delActivities = $activities->delete();
            }
        }
        if ($visitD != NULL) {
            foreach ($visitD as $visit) {
                $delVisits = $visit->delete();
            }
        }
        if ($quoteD != NULL) {
            foreach ($quoteD as $quote) {
                $delQuote = $quote->delete();
            }
        }

        if ($delExisting || $delActivities || $delVisits || $delQuote || $delpic) {
            return 1;
        } else {
            return 0;
        }
    }

    public function storeActionWithCrm(Request $request, $id)
    {
        $action = new Activities;
        $action->id_client = $id;
        $action->name = "CRM";
        $action->status = $request->status;
        $action->action = $request->action;
        $action->note = $request->note;
        $action->date = $request->date;
        $action->follow_up = $request->follow_up;
        $activitiesSave = $action->save();
        if ($activitiesSave) {
            return redirect("/existing/" . $id)->with("success", "Data telah ditambahkan");
        }
    }

    public function updateStatusAtDropdown(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $crmStat = CrmStatus::where('id_client', $id)->first();
            if (!$crmStat) {
                return response()->json(['error' => 'Data tidak ditemukan'], 404);
            }
            $crmStat->status = $request->status;
            $crmStatSave = $crmStat->save();
            if ($crmStatSave) {
                return response()->json(['success' => 'Status berhasil diperbarui']);
            } else {
                return response()->json(['error' => 'Gagal menyimpan perubahan status'], 500);
            }
        } else {
            return response()->json(['error' => 'Metode request tidak valid'], 405);
        }
    }
    
    public function ruIndex()
    {
        $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();



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

        return view("pages.sales.clients.ru.index", compact('leveledProspect', 'comment', 'unreadComment', 'commentAdmin', 'unreadCommentAdmin', 'noSaleProspect'));
    }
    protected function getDataPerMonth()
    {
        // Misalkan Anda ingin mengambil data untuk semester pertama tahun 2024
        $startSemester = Carbon::parse('2024-01-01');
        $endSemester = $startSemester->copy()->addMonths(6)->subDay(); // Akhir semester adalah 6 bulan setelah mulai

        $dataPerSixMonths = [];

        $startMonth = $startSemester->copy();
        $endMonth = $endSemester->copy();

        // Loop untuk setiap bulan dalam periode enam bulan
        while ($startMonth->lte($endMonth)) {
            $startDate = $startMonth->copy()->startOfMonth();
            $endDate = $startMonth->copy()->endOfMonth();

            // Penanganan untuk bulan-bulan dengan sedikit hari
            if ($startDate->daysInMonth < 4) {
                // Jika hari dalam bulan kurang dari 4, langsung lanjut ke bulan berikutnya
                $startMonth->addMonth();
                continue;
            }

            $dataPerMonth = [];

            // Ambil data untuk setiap minggu dalam bulan ini
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $startWeek = $currentDate->copy()->startOfWeek();
                $endWeek = $currentDate->copy()->endOfWeek();

                // Hitung jumlah hari dalam minggu ini
                $daysInWeek = $endWeek->diffInDays($startWeek) + 1;

                // Jika jumlah hari dalam minggu lebih dari 3, pertimbangkan sebagai satu minggu
                if ($daysInWeek >= 4) {
                    $data = Activities::whereBetween('created_at', [$startWeek, $endWeek])->get();

                    if ($data->isNotEmpty()) {
                        // Jika ada data, tambahkan ke array dataPerMonth
                        $dataPerMonth[] = [
                            'week_start' => $startWeek->format('Y-m-d'),
                            'week_end' => $endWeek->format('Y-m-d'),
                            'data' => $data->pluck('created_at')->toArray(),
                        ];
                    } else {
                        // Jika tidak ada data, tambahkan tanda "-"
                        $dataPerMonth[] = [
                            'week_start' => $startWeek->format('Y-m-d'),
                            'week_end' => $endWeek->format('Y-m-d'),
                            'data' => '-',
                        ];
                    }
                }

                // Pindahkan ke minggu berikutnya
                $currentDate->addWeek();
            }

            $dataPerSixMonths[] = [
                'month' => $startMonth->format('F Y'),
                'data_per_month' => $dataPerMonth,
            ];

            // Pindahkan ke bulan berikutnya
            $startMonth->addMonth();
        }

        return $dataPerSixMonths;
    }
    protected function data($id)
    {
        $currentMonth = date('n'); // 'n' returns the month without leading zeros
        $currentYear = date('Y');

        // Determine the start date based on the current month
        if ($currentMonth >= 1 && $currentMonth <= 6) {
            // January to June
            $startSemester = Carbon::parse($currentYear . '-01-01'); // 1 January of the current year
        } else {
            // July to December
            $startSemester = Carbon::parse($currentYear . '-07-01'); // 1 July of the current year
        }
        // Misalkan semester berlangsung selama 16 minggu
        $numOfWeeks = 26;

        $dataPerMonth = [];
        $dataPerSixMonth = [];

        for ($week = 0; $week < $numOfWeeks; $week++) {
            $currentWeek = $startSemester->copy()->addWeeks($week);
            $startDate = $currentWeek->copy()->startOfWeek();
            $endDate = $currentWeek->copy()->endOfWeek();

            // Hitung jumlah hari dalam minggu ini
            $daysInWeek = $endDate->diffInDays($startDate) + 1;

            // Jika jumlah hari dalam minggu lebih dari 4, pertimbangkan sebagai satu minggu
            if ($daysInWeek > 4) {
                $month = $currentWeek->format('F Y');
                $data = Activities::whereBetween('date', [$startDate, $endDate])->where('id_client', $id)->get();
                if ($data->isNotEmpty()) {
                    // dd($data);
                    // Jika ada data, tambahkan ke array dataPerMonth
                    $dataPerMonth[$month][] = [
                        'week_start' => $startDate->format('Y-m-d'),
                        'week_end' => $endDate->format('Y-m-d'),
                        'data' => $data->map(function ($item) {
                            $carbonDate = Carbon::parse($item->date);
                            return $carbonDate->format('m-d');
                        }),
                        'note' => $data->map(function ($item) {
                            return $item->note;
                        }),
                    ];
                } else {
                    // Jika tidak ada data, tambahkan tanda "-"
                    $dataPerMonth[$month][] = [
                        'week_start' => $startDate->format('Y-m-d'),
                        'week_end' => $endDate->format('Y-m-d'),
                        'data' => '-',
                        'note' => '-',
                    ];
                }
            }
        }
        $dataPerSixMonth[] = [
            'month' => $month,
            'data' => $dataPerMonth,
        ];

        return $dataPerMonth;
    }
    public function getServicePerMonth($id)
    {

        $machines = Machine::where('id_client', $id)->with('reports')->get();
        // dd($machines);
        $results = [];

        // Fungsi untuk mengubah angka bulan menjadi nama bulan menggunakan Carbon
        function getMonthName($monthNumber)
        {
            return Carbon::create()->month($monthNumber)->format('F'); // Menghasilkan nama bulan penuh seperti January, February, dll.
        }

        foreach ($machines as $machine) {
            $serviceReportsByMonth = [];

            // Inisialisasi array bulan
            for ($i = 1; $i <= 12; $i++) {
                $serviceReportsByMonth[getMonthName($i)] = [
                    'month' => getMonthName($i),
                    'service' => 'no service'
                ];
            }

            // Isi array bulan dengan data dari laporan servis yang ada
            foreach ($machine->reports as $report) {
                $month = Carbon::parse($report->date)->month; // Angka bulan (1-12)
                $monthName = getMonthName($month);
                $serviceReportsByMonth[$monthName] = [
                    'month' => $monthName,
                    'service' => $report->no_service // Ganti dengan field yang sesuai
                ];
            }

            $results[] = [
                'machine' => $machine->brand, // Ganti dengan field yang sesuai
                'Service' => array_values($serviceReportsByMonth)
            ];
        }

        return response()->json($results);
    }
}
