<?php

namespace App\Http\Controllers;

use App\Models\ChangeStatus;
use App\Models\Client;
use App\Models\Comment;
use App\Models\DetailQuotation;
use App\Models\MentionComment;
use App\Models\Pic;
use App\Models\Product;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\Termncon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ProspectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotation = Quotation::where('id_support', Auth::user()->id)->where('level', '1')->where('is_primary', '1')->get();
        $forecast = Quotation::where('id_support', Auth::user()->id)->where('level', '1')->where('is_primary', '1')->whereIn('status', ['20', '30', '40', '60', '80'])->sum('nett');
        $prospect = Quotation::where('id_support', Auth::user()->id)->where('level', '1')->where('is_primary', '1')->where('status', '80')->sum('nett');
        $po = Quotation::where('id_support', Auth::user()->id)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $loss = Quotation::where('id_support', Auth::user()->id)->where('status', '0')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quotationAdmin = Quotation::whereNotNull('id_support')->where('level', '1')->get();
        $forecastAdmin = Quotation::whereIn('status', ['20', '30', '40', '60', '80'])->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $prospectAdmin = Quotation::where('status', '80')->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $poAdmin = Quotation::where('status', '100')->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $lossAdmin = Quotation::where('status', '0')->whereNotNull('id_support')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $prospects = Prospect::where('id_sales', Auth::id())->whereNull('level')->get();
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
        return view('pages.support.prospect.index', compact('prospects', 'comment', 'unreadComment', 'commentAdmin', 'unreadCommentAdmin', 'quotation', 'leveledProspect', 'noSaleProspect', 'quotationAdmin', 'forecast', 'prospect', 'po', 'loss', 'forecastAdmin', 'prospectAdmin', 'poAdmin', 'lossAdmin'));

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
        $rule = [
            'company' =>
                'required',

            'phone' =>
                'required',

            'ru' =>
                'required',

            'source' =>
                'required',

            'mobile' =>
                'required',

            'address' =>
                'required',

            'subAddress' =>
                'required',

            'area' =>
                'required',

            // 'namePic' =>
            //     'required',

            // 'emailPic' =>
            //     'required',

            // 'phonePic' =>
            //     'required',

            // 'position' =>
            //     'required',
        ];

        $message = [
            'company.required' => 'Field company Wajib Diisi',
            'phone.required' => 'Field Phone Wajib Diisi',
            'ru.required' => 'Wajib Pilih Reseller atau User',
            'source.required' => 'Field Source Wajib Diisi',
            'mobile.required' => 'Field Mobile Wajib Diisi',
            'address.required' => 'Field Address Wajib Diisi',
            'subAddress.required' => 'Field Sub Address Wajib Diisi',
            'area.required' => 'Field Area Wajib Diisi',
            // 'namePic.required'=> 'Field Nama PIC Wajib Diisi',
            // 'emailPic.required'=> 'Field Email PIC Wajib Diisi',
            // 'phonePic.required'=> 'Field Nomor PIC Wajib Diisi',
            // 'position.required'=> 'Field Posisi PIC Wajib Diisi',
        ];

        $this->validate($request, $rule, $message);
        // dd($request);
        //masukan data ke table leads(client)
        $client = new Client();
        $client->id_sales = Auth::id();
        $client->id_support = Auth::id();
        $client->id_issues = 1;
        $client->company = $request->company;
        $client->email = '-';
        $client->phone = $request->phone;
        $client->ru = $request->ru;
        $client->web = '-';
        $client->image = 'profile.jpg';
        $client->source = $request->source;
        $client->created_date = Carbon::today()->toDateString();
        $client->role = 'Leads';
        $client->npwp = '0';
        $client->mobile = $request->mobile;
        $client->address = $request->address;
        $client->subAddress = $request->subAddress;
        $client->area = $request->area;
        $clientSave = $client->save();

        // masukan data ke table PIC
        $pic = new Pic();
        $pic->id_client = $client->id;
        $pic->name_pic = $request->namePic;
        $pic->position = $request->position;
        $pic->email_pic = $request->emailPic;
        $pic->phone_pic = $request->phonePic;
        $picsave = $pic->save();

        $prospect = new Prospect();
        $prospect->id_sales = NULL;
        $prospect->id_quotation = NULL;
        $prospect->id_pic = $pic->id;
        $prospect->id_support = Auth::id();
        $prospect->kebutuhan = $request->prospect;
        $prospect->date = Carbon::now();
        $prospect->level = NULL;
        $prospect->provide = NULL;
        $prospectSave = $prospect->save();


        if ($prospectSave) {
            return redirect('prospect')->with('message', 'data telah ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $prospect = Prospect::find($id);
        $quotation = Quotation::find($prospect->id_quotation);
        $allQuotation = Quotation::where('id_pic', $prospect->id_pic)->get();
        $pic = Pic::where('id', $prospect->id_pic)->first();
        $client = Client::where('id', $pic->id_client)->first();
        $sales = User::where('role', 'Sales')->where('active', '1')->get();
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();
        $prospectComments = Comment::where('id_prospect', $id)->where('type', 'prospect')->with('mention')->get();
        $user = User::whereNot('id', Auth::user()->id)->get();
        // dd($prospectComments);


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
        return view('pages.support.prospect.detail', compact('allQuotation', 'prospect', 'comment', 'prospectComments', 'unreadComment', 'commentAdmin', 'quotation', 'unreadCommentAdmin', 'leveledProspect', 'noSaleProspect', 'pic', 'client', 'sales', 'user'));
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
        $prospect = Prospect::find($id);
        $prospect->id_sales = $request->sales;
        $prospectSave = $prospect->save();
        if ($prospectSave) {
            return redirect('prospect')->with('message', 'data telah ditambahkan');
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
        $prospect = Prospect::find($id);
        $pic = Pic::find($prospect->id_pic);
        $client = Client::find($pic->id_client);

        $prospectDel = $prospect->delete();
        $picDel = $pic->delete();
        $clientDel = $client->delete();
        if ($prospectDel && $picDel && $clientDel) {
            return 1;
        } else {
            return 0;
        }

    }

    public function add_sales(Request $request, $id)
    {
        // dd($request->all());
        $prospect = Prospect::find($id);
        $pic = Pic::find($prospect->id_pic);
        $client = Client::find($pic->id_client);
        if ($request->provideCheck == 1) {
            $prospect->provide = '1';
            $prospect->id_sales = $request->sales;
            $client->id_sales = $request->sales;
        } else {
            $prospect->provide = '0';
            $prospect->id_sales = NULL;
        }
        $prospectSave = $prospect->save();
        $client->save();
        if ($prospectSave) {
            return redirect('prospect')->with('message', 'data telah ditambahkan');
        }
    }

    public function onProcessFU($id)
    {
        $prospect = Prospect::find($id);
        $prospect->level = '9';
        $prospectSave = $prospect->save();
        if ($prospectSave) {
            return 1;
        } else {
            return 0;
        }
    }
    public function without_quotation($id)
    {
        $prospect = Prospect::find($id);
        $prospect->level = '0';
        $prospectSave = $prospect->save();
        if ($prospectSave) {
            return 1;
        } else {
            return 0;
        }
    }
    public function with_quotation($id)
    {
        $prospect = Prospect::find($id);
        $prospect->level = '1';
        $prospectSave = $prospect->save();
        if ($prospectSave) {
            return 1;
        } else {
            return 0;
        }
    }

    public function no_respond($id)
    {
        $prospect = Prospect::find(id: $id);
        $prospect->level = '2';
        $prospectSave = $prospect->save();
        if ($prospectSave) {
            return 1;
        } else {
            return 0;
        }
    }
    public function no_provide($id)
    {
        $prospect = Prospect::find($id);
        $prospect->provide = '0';
        $prospect->level = NULL;

        $prospectSave = $prospect->save();
        if ($prospectSave) {
            return 1;
        } else {
            return 0;
        }
    }

    public function create_quotation($id)
    {
        $prospect = Prospect::find($id);
        $dateNow = Carbon::now();
        $numberQ = Quotation::whereYear('estimated_date', $dateNow)->where('id_sales', Auth::user()->id)->count();
        $formattedNumberQ = str_pad($numberQ + 1, 3, '0', STR_PAD_LEFT);
        $monthNow = $dateNow->month;
        $formattedMonthNow = $this->convertToRoman($monthNow);
        $product = Product::join('serial_product as s', 's.id_product', '=', 'product.id')->get(['product.id as comId', 's.id', 'product.go', 's.pn', 's.brand', 'product.detail_desc']);
        return view('pages.support.prospect.quotation', compact('prospect', 'numberQ', 'formattedNumberQ', 'formattedMonthNow', 'product'));
    }

    public function store_quotation(Request $request, $id)
    {
        $prospect = Prospect::find($id);
        $rule = [
            'no_quote' => 'required',
            'title' => 'required',
            'product' => 'required',
            'detail_product' => 'required',
            'expired_date' => 'required',
            'validity' => 'required',
            'pricing' => 'required',
            'delivery_process' => 'required',
            'payment' => 'required',
            'shipping' => 'required',
        ];
        $message = [
            'no_quote.required' => 'Field No Quote Wajib Diisi',
            'title.required' => 'Field Title Wajib Diisi',
            'product.required' => 'Field Product Wajib Diisi',
            'detail_product.required' => 'Field Detail Product Wajib Diisi',
            'expired_date.required' => 'Wajib isi Expired Date',
            'termcon.required' => 'Field Term and Conditions Wajib Diisi',
            'shipping.required' => 'Quotation Wajib memiliki harga Antar',
        ];
        $this->validate($request, $rule, $message);
        // dd($request);
        // Masukan Data ke Tabel Quotataion
        $quotation = new Quotation();
        $quotation->id_pic = $prospect->id_pic;
        $quotation->id_sales = $prospect->id_sales;
        $quotation->id_service = NULL;
        $quotation->id_support = $prospect->id_support;
        $quotation->is_primary = "1";
        $quotation->primary_id = 0;
        $quotation->num_rev = 0;
        $quotation->destination = $request->destination;
        $quotation->no_pr = NULL;
        $quotation->status = "20";
        $quotation->status_date = Carbon::today();
        $quotation->note = "-";
        $quotation->expired_date = $request->expired_date;
        $quotation->po_date = NULL;
        $quotation->po_file = NULL;
        $quotation->quote_for = $request->type;
        $quotation->type = 'Sparepart';
        $quotation->level = '1';
        $quotation->estimated_date = $request->estimated_date;
        if ($request->tax != NULL) {
            $quotation->tax = $request->tax;
        } else {
            $quotation->tax = 0;
        }
        $quotation->shipping = $request->shipping;
        $quotation->no_quote = $request->no_quote;
        $quotation->title = $request->title;
        $quotation->subtotal = $request->subtotal;
        if ($request->diskon != NULL) {
            $quotation->diskon = $request->diskon;
        } else {
            $quotation->diskon = 0;
        }
        $quotation->fee = 0;
        $quotation->nett = $request->subtotal - $request->diskon;
        $quotation->total_no_tax = $request->total_no_tax;
        $quotation->harga_total = $request->harga_total;
        $quoteSave = $quotation->save();
        $quotation->primary_id = $quotation->id;
        $quotation->save();
        if ($quoteSave) {
            // Masukan Data Ke Tabel Detail Quotataion
            foreach ($request->product as $item => $value) {
                $dQuote = new DetailQuotation();
                $dQuote->id_quotation = $quotation->id;
                $dQuote->id_equivalent = $request->product[$item];
                $dQuote->detail_product = $request->detail_product[$item];
                $dQuote->price = $request->price[$item];
                $dQuote->fee = 0;
                $dQuote->qty = $request->qty[$item];
                $dQuote->info_qty = $request->info_qty[$item];
                $dQuote->disc = $request->disc[$item];
                $dQuote->amount = $request->amount[$item];
                $dQuote->pph = 0;
                $dQuoteSave = $dQuote->save();
            }
            $stats = new ChangeStatus;
            $stats->id_quotation = $quotation->id;
            $stats->date = Carbon::now();
            $stats->note = 'Quotation has been created';
            $stats->status = "10";
            $stats->save();
            if ($dQuoteSave) {
                // Masukan Data ke dalam Tabel Term n Condition
                $termncon = new Termncon();
                $termncon->id_quotation = $quotation->id;
                $termncon->validity = $request->validity;
                $termncon->pricing = $request->pricing;
                $termncon->delivery_process = $request->delivery_process;
                $termncon->payment = $request->payment;
                $termncon->note = $request->note;
                $termnconSave = $termncon->save();
            }
        }
        $prospect->id_quotation = $quotation->id;
        $prospectSave = $prospect->save();
        if ($prospectSave) {
            return redirect('/quotation/' . $quotation->id)->with('message', 'data telah di tambahkan');
        }
    }

    public function choose_quotation(Request $request, $id)
    {
        $prospect = Prospect::find($id);
        $prospect->id_quotation = $request->id_quotation;
        $prospect->level = '1';
        $prospectSave = $prospect->save();
        if ($prospectSave) {
            return redirect('/quotation/' . $request->id_quotation);
        }
    }

    public function add_comment(Request $request, $id)
    {
        // dd($request->all());
        $comment = new Comment;
        $comment->id_status = NULL;
        $comment->id_prospect = $id;
        $comment->id_user = Auth::user()->id;
        $comment->date = Carbon::now();
        $comment->comment = $request->comment;
        $comment->level = '1';
        $comment->type = 'prospect';
        $commentSave = $comment->save();
        if (@$request->mention) {
            foreach ($request->mention as $key => $value) {
                $mention = new MentionComment();
                $mention->id_comment = $comment->id;
                $mention->id_mention = $value;
                $mention->level = '0';
                // dd($mention);
                $mention->save();
            }
        }
        if ($commentSave) {
            return redirect('/prospect/' . $id . '#viewComment')->with('massage', 'Data berhasil di buat');
        }
    }
    public function view_comment($id)
    {
        $comment = Comment::find($id);

        if ($comment) {
            $comment->level = '2';
            $comment->save();

            // Return response JSON
            return response()->json(['message' => 'Comment updated successfully!']);
        } else {
            // Return error jika tidak ditemukan
            return response()->json(['message' => 'Comment not found'], 404);
        }
    }

    protected function convertToRoman($month)
    {
        $romanMonth = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        return $romanMonth[$month];
    }
}
