<?php

namespace App\Http\Controllers;

use App\Models\ChangeStatus;
use App\Models\Comment;
use App\Models\Contract;
use App\Models\DetailProduct;
use App\Models\DetailQuotation;
use App\Models\Invoice;
use App\Models\Machine;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Prospect;
use App\Models\Quotation;
use App\Models\SerialProduct;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unit = SerialProduct::whereNotNull('detail')->get();
        return view('pages.warehouse.unit.index', compact('unit'));
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
    {$rule = [
        'desc' =>
            'required',

        'serial' =>
            'required',
    ];

    $message = [
        'desc.required' => 'Field desc Wajib Diisi',
        'serial.required' => 'Field serial Wajib Diisi',
    ];
    $this->validate($request, $rule, $message);
    $machine = new Machine();
    $machine->id_client = 5508;
    $machine->id_unit = $request->unit;
    $machine->serial = $request->serial;
    $machine->status = $request->status;
    $machine->desc = $request->desc;
    $machine->tag = $request->tag;
    $machine->location = $request->location;
    $unitSave = $machine->save();
        if ($unitSave) {
            return redirect('/unit')->with('message', 'data telah di tambahkan');
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
        $product = Unit::find($id);
        $allStock = $product->stock + $product->warehouse_stock;
        $details = DetailProduct::where('id_product', $id)->get();
        $serials = SerialProduct::where('id_product', $id)->get();
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
        return view('pages.warehouse.unit.detail', compact('product', 'comment', 'unreadComment', 'commentAdmin', 'unreadCommentAdmin', 'details', 'leveledProspect', 'noSaleProspect', 'serials', 'allStock'));
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
        // Rules for validation
        $rule = [
            'sku' => 'required',
            'desc' => 'required',
            'sn' => 'required',
            'note' => 'required',
            'bar' => 'required',
        ];

        // Custom validation messages
        $message = [
            'sku.required' => 'Field sku Wajib Diisi',
            'desc.required' => 'Field description Wajib Diisi',
            'sn.required' => 'Field serial Number Wajib Diisi',
            'note.required' => 'Field note Wajib Diisi',
            'bar.required' => 'Field bar Wajib Diisi',
        ];

        $this->validate($request, $rule, $message);
        // dd($request);
        $unit = Unit::find($id);
        $unit->sku = $request->sku;
        $unit->status = $request->status;
        $unit->desc = $request->desc;
        $unit->sn = $request->sn;
        $unit->bar = $request->bar;
        $unit->power = $request->power;
        $unit->connect = $request->connect;
        $unit->air_cap = $request->air_cap;
        $unit->dimension = $request->dimension;
        $unit->weight = $request->weight;
        $unit->note = $request->note;
        $unit->unit = $request->unit;
        $unitSave = $unit->save();
        if ($unitSave) {
            return redirect('/unit/' . $unit->id)->with('message', 'data telah di tambahkan');
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
        $unit = Unit::find($id);

        if (!$unit) {
            return redirect('/unit/' . $id)->with('error', 'Produk tidak ditemukan');
        }

        $replacement = DetailProduct::where('id_product', $id)->get();
        $equivalents = SerialProduct::where('id_product', $id)->get();

        $delUnit = $unit->delete();

        foreach ($replacement as $replace) {
            $delReplace = $replace->delete();
        }

        foreach ($equivalents as $equivalent) {
            $delEqui = $equivalent->delete();
        }

        if ($delUnit || $delReplace || $delEqui) {
            return 1;
        } else {
            return 0;
        }
    }
    public function quotationDetail($id)
    {
        $totalAmount = 0;
        $dateNow = Carbon::now();
        $numberSP = Contract::join('quotation as q', 'contract.id_quotation', '=', 'q.id')->whereYear('contract.date', $dateNow)->where('q.tax', '11')->where('contract.type', 'Selling')->groupBy('contract.id')->get('contract.id');
        $numberSNP = Contract::join('quotation as q', 'contract.id_quotation', '=', 'q.id')->whereYear('contract.date', $dateNow)->where('q.tax', '0')->where('contract.type', 'Selling')->groupBy('contract.id')->get('contract.id');
        $numberCP = Contract::join('quotation as q', 'contract.id_quotation', '=', 'q.id')->whereYear('contract.date', $dateNow)->where('q.tax', '11')->where('contract.type', 'Order')->groupBy('contract.id')->get('contract.id');
        $numberCNP = Contract::join('quotation as q', 'contract.id_quotation', '=', 'q.id')->whereYear('contract.date', $dateNow)->where('q.tax', '0')->where('contract.type', 'Order')->groupBy('contract.id')->get('contract.id');
        $formattedNumberSP = str_pad($numberSP->count() + 1, 3, '0', STR_PAD_LEFT);
        $formattedNumberSNP = str_pad($numberSNP->count() + 1, 3, '0', STR_PAD_LEFT);
        $formattedNumberCP = str_pad($numberCP->count() + 1, 3, '0', STR_PAD_LEFT);
        $formattedNumberCNP = str_pad($numberCNP->count() + 1, 3, '0', STR_PAD_LEFT);
        $quote = Quotation::find($id);
        $quotations = Quotation::where('primary_id', $quote->primary_id)->get();
        $lastQuote = Quotation::where('primary_id', $quote->primary_id)->orderByDesc('num_rev')->first();
        $primQuote = Quotation::where('primary_id', $quote->primary_id)->where('is_primary', '1')->first();
        $invoice = Invoice::where('id_quotation', $id)->get();
        $dquote = DetailQuotation::where('id_quotation', $id)->get();
        $payments = Payment::where('id_quotation', $id)->get();
        $product = Product::join('serial_product as s', 's.id_product', '=', 'product.id')->get(['s.id', 'product.go', 's.pn']);
        $admin = User::where('role', 'Admin')->get();
        $noQuote = substr($quote->no_quote, 0, 3);
        $today = Carbon::now();
        $tax = ($quote->subtotal - $quote->diskon) * $quote->tax / 100;
        $afterDisc = $quote->subtotal - $quote->diskon;
        // dd($invoice[0]->no_invoice);
        $thisYear = $today->year;
        foreach ($payments as $payment) {
            $totalAmount += $payment->amount;
        }
        $status = ChangeStatus::where('id_quotation', $quote->primary_id)->with('comment')->get();


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
        // dd($comment);
        $remaining = $quote->harga_total - $totalAmount;
        // dd($formattedNumberSP);
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();
        return view("pages.sales.quotation.unit.detail", compact('quote', 'status', 'comment', 'unreadComment', 'commentAdmin', 'unreadCommentAdmin', 'leveledProspect', 'noSaleProspect', 'lastQuote', 'primQuote', 'quotations', 'dquote', 'admin', 'noQuote', 'thisYear', 'tax', 'formattedNumberSP', 'formattedNumberSNP', 'formattedNumberCP', 'formattedNumberCNP', 'invoice', 'payments', 'remaining', 'afterDisc'));
    }
    
    public function indexGlobal()
    {
        return view('pages.warehouse.unit.index-global');
    }

    public function storeGlobal(Request $request)
    {
        // Rules for validation
        $rule = [
            'sku' => 'required',
            'desc' => 'required',
            'sn' => 'required',
            'note' => 'required',
            'bar' => 'required',
        ];

        // Custom validation messages
        $message = [
            'sku.required' => 'Field sku Wajib Diisi',
            'desc.required' => 'Field description Wajib Diisi',
            'sn.required' => 'Field serial Number Wajib Diisi',
            'note.required' => 'Field note Wajib Diisi',
            'bar.required' => 'Field bar Wajib Diisi',
        ];

        $this->validate($request, $rule, $message);
        $lastUnit = Unit::orderBy('id', 'desc')->first();
        $lastProduct = Product::orderBy('id', 'desc')->first();
        $unit = new Unit;
        if (@$lastUnit) {
            if ($lastUnit->id > $lastProduct->id) {
                $unit->id = $lastUnit->id + 1;
            } else {
                $unit->id = $lastProduct->id + 1;
            }
        } else {
            $unit->id = $lastProduct->id + 1;
        }
        $unit->sku = $request->sku;
        $unit->status = 'global';
        $unit->desc = $request->desc;
        $unit->sn = $request->sn;
        $unit->bar = $request->bar;
        $unit->power = $request->power;
        $unit->connect = $request->connect;
        $unit->air_cap = $request->air_cap;
        $unit->dimension = $request->dimension;
        $unit->weight = $request->weight;
        $unit->note = $request->note;
        $unit->unit = $request->unit;
        $unit->stock = 0;
        $unit->first_stock = 0;
        $unit->warehouse_stock = 0;
        $unit->type = 'global';
        // foreach ($request->unit as $key => $value) {
        //     if ($value == 'rental') {
        //         $unit->rental = '1';
        //     }
        //     if ($value == 'second') {
        //         $unit->second = '1';
        //     }
        //     if ($value == 'new') {
        //         $unit->new = '1';
        //     }
        // }
        $unitSave = $unit->save();
        if ($unitSave) {
            return redirect('/unit-global/' . $unit->id)->with('message', 'data telah di tambahkan');
        }
    }
    public function showGlobal($id)
    {
        $product = Unit::find($id);
        $allStock = $product->stock + $product->warehouse_stock;
        $details = DetailProduct::where('id_product', $id)->get();
        $serials = SerialProduct::where('id_product', $id)->get();
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
        return view('pages.warehouse.unit.detail-global', compact('product', 'comment', 'unreadComment', 'commentAdmin', 'unreadCommentAdmin', 'details', 'leveledProspect', 'noSaleProspect', 'serials', 'allStock'));
    }

    public function corfac()
    {
        return view('pages.warehouse.unit.cor-factor');

    }
}
