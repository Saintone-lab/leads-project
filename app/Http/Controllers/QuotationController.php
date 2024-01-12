<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Client;
use App\Models\DetailQuotation;
use App\Models\Pic;
use App\Models\Quotation;
use App\Models\Termncon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotation = Quotation::all();
        return view('pages.sales.quotation.index', compact('quotation'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dateNow = Carbon::now();
        $numberQ = Quotation::whereYear('estimated_date', $dateNow)->where('id_sales', Auth::user()->id)->count();
        $formattedNumberQ = str_pad($numberQ + 1, 3, '0', STR_PAD_LEFT);
        $monthNow = $dateNow->month;
        $formattedMonthNow = $this->convertToRoman($monthNow);
        $pic = Pic::all();
        $sales = User::where('role', 'sales')->get();
        return view('pages.sales.quotation.form', compact('pic', 'sales', 'formattedNumberQ', 'formattedMonthNow'));
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
        // dd($request->all());
        // Masukan Data ke Tabel Quotataion
        $quotation = new Quotation();
        $quotation->id_pic = $request->id_pic;
        $quotation->id_sales = $request->id_sales;
        $quotation->id_service = NULL;
        $quotation->no_pr = NULL;
        $quotation->status = "20";
        $quotation->note = "-";
        $quotation->expired_date = $request->expired_date;
        $quotation->po_date = NULL;
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
        $quotation->harga_total = $request->harga_total;
        $status = $quotation->save();

        // Masukan Data Ke Tabel Detail Quotataion
        foreach ($request->product as $item => $value) {
            $dQuote = new DetailQuotation;
            $dQuote->id_quotation = $quotation->id;
            $dQuote->product = $request->product[$item];
            $dQuote->detail_product = $request->detail_product[$item];
            $dQuote->price = $request->price[$item];
            $dQuote->qty = $request->qty[$item];
            $dQuote->disc = $request->disc[$item];
            $dQuote->amount = $request->amount[$item];
            $status = $dQuote->save();
        }

        // Masukan Data ke dalam Tabel Term n Condition
        $termncon = new Termncon;
        $termncon->id_quotation = $quotation->id;
        $termncon->validity = $request->validity;
        $termncon->pricing = $request->pricing;
        $termncon->delivery_process = $request->delivery_process;
        $termncon->payment = $request->payment;
        $termncon->note = $request->note;
        $status = $termncon->save();

        if ($status) {
            return redirect('quotation')->with("success", "Data Quotation Telah Ditambahkan");
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
        $quote = Quotation::where('id', $id)->first();
        $dquote = DetailQuotation::where('id_quotation', $id)->get();
        // dd($quote);
        return view("pages.sales.quotation.detail", compact('quote', 'dquote'));
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
        $quote = Quotation::where('id', $id)->first();
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
        // dd($request->all());
        // Masukan Data ke Tabel Quotataion
        $quotation = new Quotation();
        $quotation->id_pic = $request->id_pic;
        $quotation->id_sales = $request->id_sales;
        $quotation->id_service = NULL;
        if ($request->no_pr != NULL) {
            $quotation->no_pr = $request->no_pr;
        } else {
            $quotation->no_pr = NULL;
        }
        $quotation->status = $quote->status;
        $quotation->note = $quote->note;
        $quotation->po_date = $quote->po_date;
        $quotation->expired_date = $request->expired_date;
        $quotation->estimated_date = $request->estimated_date;
        $quotation->tax = $request->tax;
        $quotation->shipping = $request->shipping;
        $quotation->no_quote = $request->no_quote;
        $quotation->title = $request->title;
        $quotation->subtotal = $request->subtotal;
        if ($request->diskon != NULL) {
            $quotation->diskon = $request->diskon;
        } else {
            $quotation->diskon = 0;
        }
        $quotation->harga_total = $request->harga_total;
        $status = $quotation->save();

        // Masukan Data Ke Tabel Detail Quotataion
        foreach ($request->product as $item => $value) {
            $dQuote = new DetailQuotation;
            $dQuote->id_quotation = $quotation->id;
            $dQuote->product = $request->product[$item];
            $dQuote->detail_product = $request->detail_product[$item];
            $dQuote->price = $request->price[$item];
            $dQuote->qty = $request->qty[$item];
            $dQuote->disc = $request->disc[$item];
            $dQuote->amount = $request->amount[$item];
            $status = $dQuote->save();
        }

        // Masukan Data ke dalam Tabel Term n Condition
        $termncon = new Termncon;
        $termncon->id_quotation = $quotation->id;
        $termncon->validity = $request->validity;
        $termncon->pricing = $request->pricing;
        $termncon->delivery_process = $request->delivery_process;
        $termncon->payment = $request->payment;
        $termncon->note = $request->note;
        $status = $termncon->save();

        if ($status) {
            return redirect('quotation')->with("success", "Data Revisi Quotation Telah Ditambahkan");
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
        //
    }
    public function print_quote($id)
    {
        $quote = Quotation::where('id', $id)->first();
        $dquote = DetailQuotation::where('id_quotation', $id)->get();
        // dd($termncon);
        return view("pages.sales.quotation.detail-print", compact('quote', 'dquote'));
    }

    public function pdf_quote($id)
    {
        $quote = Quotation::where('id', $id)->first();
        $dquote = DetailQuotation::where('id_quotation', $id)->get();
        // return view("pages.sales.quotation.detail-pdf", compact('quote', 'dquote'));
        $pdf = PDF::loadView("pages.sales.quotation.detail-pdf", compact('quote', 'dquote'));
        return $pdf->stream();
        // return $pdf->download('invoice-'.$quote->client->company.'-'.$quote->no_quote.'.pdf');
    }

    public function edit_revisi($id)
    {
        $quotation = Quotation::where('id', $id)->first();
        $dquotation = DetailQuotation::where('id_quotation', $id)->get();
        $client = Client::all();
        $pic = Pic::all();
        $sales = User::where('role', 'sales')->get();
        return view('pages.sales.quotation.form', compact('quotation', 'dquotation', 'client', 'sales', 'pic'));
    }

    public function change_status($id, Request $request)
    {
        $rule = [
            'status' => 'required',
            'note' => 'required',
        ];
        $message = [
            'status.required' => 'Field Status Wajib Diisi',
            'note.required' => 'Field note Wajib Diisi',
        ];

        $this->validate($request, $rule, $message);

        $quotation = Quotation::find($id);
        $pic = Pic::where('id', $quotation->id_pic)->first();
        $client = Client::where('id', $pic->id_client)->first();
        $quotation->status = $request->status;
        $quotation->note = $request->note;
        if ($request->status == "100") {
            $action = new Activities;
            $action->id_client = $pic->id_client;
            $action->name = 'Follow Up';
            $action->status = 'Responded';
            $action->date = Carbon::now();
            $action->follow_up = Carbon::now()->addDays(14);
            $action->action = 'Phone Office';
            $action->note = 'Done PO';
            $activitiesSave = $action->save();

            $client->id_issues = '5';
            $client->role = 'Customers';
            $isuSave = $client->save();

            $quotation->po_date = Carbon::now();
        }
        $stats = $quotation->save();
        if ($stats || $activitiesSave || $isuSave) {
            return redirect('quotation')->with("success", "Data Status Quotation Telah Diubah");
        }
    }

    public function po_quote()
    {
        $quotation = Quotation::all();
        return view('pages.sales.quotation.po.index', compact('quotation'));
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
