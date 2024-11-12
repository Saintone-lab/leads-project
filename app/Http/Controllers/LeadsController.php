<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreLeadsRequest;

use App\Models\Client;
use App\Models\Comment;
use App\Models\CrmStatus;
use App\Models\Issues;
use App\Models\Prospect;
use App\Models\User;
use App\Models\Activities;
use App\Models\Visit;
use App\Models\Quotation;
use App\Models\Pic;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = Client::where("role", "Leads")->get();
        $issue = Issues::get();
        $sales = User::where('role', 'sales')->get();
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
        return view('pages.sales.clients.leads.index', compact('noSaleProspect', 'comment', 'unreadComment', 'commentAdmin', 'unreadCommentAdmin', 'leveledProspect', 'client', 'sales', 'issue'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
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

            'email' =>
                'required',

            'phone' =>
                'required',

            'ru' =>
                'required',

            'web' =>
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
            'email.required' => 'Field Email Wajib Diisi',
            'phone.required' => 'Field Phone Wajib Diisi',
            'ru.required' => 'Wajib Pilih Reseller atau User',
            'web.required' => 'Field Web Wajib Diisi',
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
        $leads = new Client;
        $leads->id_sales = Auth::id();
        $leads->id_support = NULL;
        $leads->id_issues = 1;
        $leads->company = $request->company;
        $leads->email = $request->email;
        $leads->phone = $request->phone;
        $leads->ru = $request->ru;
        $leads->web = $request->web;
        $leads->image = 'profile.jpg';
        $leads->source = $request->source;
        $leads->created_date = Carbon::today()->toDateString();
        $leads->role = 'Leads';
        if ($request->machine != NULL) {
            $leads->machine = $request->machine;
        } else {
            $leads->machine = NULL;
        }
        $leads->mobile = $request->mobile;
        $leads->address = $request->address;
        $leads->subAddress = $request->subAddress;
        $leads->area = $request->area;
        $leadsave = $leads->save();

        // masukan data ke table PIC
        // $pic = new Pic;
        // $pic->id_client = $leads->id;
        // $pic->name_pic = $request->namePic;
        // $pic->position = $request->position;
        // $pic->email_pic = $request->emailPic;
        // $pic->phone_pic = $request->phonePic;
        // $picsave = $pic->save();

        if ($leadsave) {
            return redirect('leads')->with('message', 'data telah ditambahkan');
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
        $leads = Client::where('id', $id)->first();
        $charge = PIC::where('id_client', $id)->get();
        $callhis = Activities::where('id_client', $id)->whereIn('name', ['Daily Call', 'Follow Up'])->get();
        $visit = Activities::where('id_client', $id)->where('name', 'Visit')->get();
        $quote = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')->where('pic.id_client', $id)->where('level', '1')->get('quotation.*');
        $sales = User::where('role', 'sales')->get();
        $issue = Issues::all();
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
        return view('pages.sales.clients.leads.detail', compact('noSaleProspect', 'comment', 'unreadComment', 'commentAdmin', 'unreadCommentAdmin', 'leveledProspect', 'leads', 'callhis', 'quote', 'sales', 'charge', 'issue', 'visit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
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

            'machine' =>
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
            'machine.required' => 'Field Machine Wajib Diisi',
        ];

        $this->validate($request, $rule, $message);

        //masukan data ke table leads(client)
        $leads = Client::find($id);
        $leads->company = $request->company;
        $leads->email = $request->email;
        $leads->phone = $request->phone;
        $leads->ru = $request->ru;
        $leads->web = $request->web;
        $leads->source = $request->source;
        $leads->machine = $request->machine;
        $leads->mobile = $request->mobile;
        $leads->address = $request->address;
        $leads->subAddress = $request->subAddress;
        $leads->area = $request->area;
        $leadsave = $leads->save();

        if ($leadsave) {
            return redirect('/leads/detail/' . $id)->with('message', 'data telah diUpdate');
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
        $leadsD = Client::find($id);
        $picD = Pic::where('id_client', $id)->get();
        $activitiesD = Activities::where('id_client', $id)->get();
        $visitD = Visit::where('id_client', $id)->get();
        $quoteD = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')->where('pic.id_client', $id)->get();

        $delLeads = $leadsD->delete();
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

        if ($delLeads || $delActivities || $delVisits || $delQuote || $delpic) {
            return 1;
        } else {
            return 0;
        }
    }

    public function storeActionWithLeads(Request $request, $id)
    {
        $leads = Client::where("id", $id)->first();
        $leads->id_issues = $request->issues;
        if ($request->issues == '5') {
            $leads->role = 'Customers';
            $status = new CrmStatus;
            $status->id_client = $id;
            $status->status = 2;
            $statSave = $status->save();
        }
        $isuSave = $leads->save();

        $action = new Activities;
        $action->id_client = $id;
        if ($leads->activities != Null) {
            $action->name = "Follow Up";
        } else {
            $action->name = "Daily Call";
        }
        $action->status = $request->status;
        $action->action = $request->action;
        $action->note = $request->note;
        $action->date = \Carbon\Carbon::today();
        $action->follow_up = $request->follow_up;
        $activitiesSave = $action->save();
        if ($isuSave && $activitiesSave || $statSave) {
            if ($request->issues == '5') {
                return redirect("/existing/" . $id)->with("success", "Data telah ditambahkan");
            } else {
                return redirect("/leads/detail/" . $id)->with("success", "Data telah ditambahkan");
            }
        }
    }
    public function storeVisitWithLeads(Request $request, $id)
    {
        $leads = Client::where("id", $id)->first();
        $leads->id_issues = $request->issues;
        if ($request->issues == '5') {
            $leads->role = 'Customers';
            $status = new CrmStatus;
            $status->id_client = $id;
            $status->status = 2;
            $statSave = $status->save();
        }
        $isuSave = $leads->save();

        $action = new Activities;
        $action->id_client = $id;
        $action->name = 'Visit';
        $action->status = $request->status;
        $action->action = 'Visit';
        $action->note = $request->note;
        $action->date = \Carbon\Carbon::today();
        $action->follow_up = $request->follow_up;
        $activitiesSave = $action->save();
        if ($isuSave && $activitiesSave || $statSave) {
            if ($request->issues == '5') {
                return redirect("/existing/" . $id)->with("success", "Data telah ditambahkan");
            } else {
                return redirect("/leads/detail/" . $id)->with("success", "Data telah ditambahkan");
            }
        }
    }

    public function convertToCustomers(Request $request, $id)
    {
        $leads = Client::where("id", $id)->first();
        $leads->role = 'Customers';
        $leadsSave = $leads->save();
        $status = new CrmStatus;
        $status->id_client = $id;
        $status->status = 2;
        $statSave = $status->save();
        if ($leadsSave && $statSave) {
            return 1;
        } else {
            return 0;
        }

    }
}
