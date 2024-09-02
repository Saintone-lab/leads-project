<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Delivery;
use App\Models\DetailDelivery;
use App\Models\DetailQuotation;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Pic;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\SerialProduct;
use App\Models\Termncon;
use App\Models\User;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $quotation = Quotation::where('id_sales', Auth::user()->id)->where('level', '1')->where('is_primary', '1')->get();
        $forecast = Quotation::where('id_sales', Auth::user()->id)->where('level', '1')->where('is_primary', '1')->whereIn('status', ['20', '30', '40', '60', '80'])->sum('nett');
        $prospect = Quotation::where('id_sales', Auth::user()->id)->where('level', '1')->where('is_primary', '1')->where('status', '80')->sum('nett');
        $po = Quotation::where('id_sales', Auth::user()->id)->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $loss = Quotation::where('id_sales', Auth::user()->id)->where('status', '0')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $quotationAdmin = Quotation::get();
        $forecastAdmin = Quotation::whereIn('status', ['20', '30', '40', '60', '80'])->where('level', '1')->where('is_primary', '1')->sum('nett');
        $prospectAdmin = Quotation::where('status', '80')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $poAdmin = Quotation::where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $lossAdmin = Quotation::where('status', '0')->where('level', '1')->where('is_primary', '1')->sum('nett');
        // dd();
        return view('pages.sales.quotation.index', compact('quotation', 'forecast', 'prospect', 'po', 'loss', 'quotationAdmin', 'forecastAdmin', 'prospectAdmin', 'poAdmin', 'lossAdmin'));
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
        $pic = Pic::join('client', 'client.id', '=', 'id_client')->where('client.id_sales', Auth::user()->id)->get('pic.*');
        $sales = User::where('role', 'sales')->get();
        $product = Product::join('serial_product as s', 's.id_product', '=', 'product.id')->get(['product.id as comId', 's.id', 'product.go', 's.pn', 's.brand', 'product.detail_desc']);
        // dd($product);
        return view('pages.sales.quotation.form', compact('pic', 'sales', 'formattedNumberQ', 'formattedMonthNow', 'product'));
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
        // dd($request);
        // Masukan Data ke Tabel Quotataion
        $quotation = new Quotation();
        $quotation->id_pic = $request->id_pic;
        $quotation->id_sales = $request->id_sales;
        $quotation->id_service = NULL;
        $quotation->is_primary = "1";
        $quotation->num_rev = 0;
        $quotation->destination = $request->destination;
        $quotation->no_pr = NULL;
        $quotation->status = "20";
        $quotation->status_date = Carbon::today();
        $quotation->note = "-";
        $quotation->expired_date = $request->expired_date;
        $quotation->po_date = NULL;
        $quotation->po_file = NULL;
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
                $dQuote = new DetailQuotation;
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
            if ($dQuoteSave) {
                // Masukan Data ke dalam Tabel Term n Condition
                $termncon = new Termncon;
                $termncon->id_quotation = $quotation->id;
                $termncon->validity = $request->validity;
                $termncon->pricing = $request->pricing;
                $termncon->delivery_process = $request->delivery_process;
                $termncon->payment = $request->payment;
                $termncon->note = $request->note;
                $termnconSave = $termncon->save();
            }
        }
        if ($termnconSave) {
            return redirect('/quotation/' . $quotation->id)->with('message', 'data telah di tambahkan');
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

        $remaining = $quote->harga_total - $totalAmount;
        // dd($formattedNumberSP);
        return view("pages.sales.quotation.detail", compact('quote', 'lastQuote', 'primQuote', 'quotations', 'dquote', 'admin', 'noQuote', 'thisYear', 'tax', 'formattedNumberSP', 'formattedNumberSNP', 'formattedNumberCP', 'formattedNumberCNP', 'invoice', 'payments', 'remaining', 'afterDisc'));
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
        $lastQuote = Quotation::where('primary_id', $quote->primary_id)->orderByDesc('num_rev')->first();
        $detquote = DetailQuotation::where('id_quotation', $id)->get();
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
        $quote->is_primary = '0';
        $quote->save();

        $quotation = new Quotation();
        $quotation->id_pic = $request->id_pic;
        $quotation->id_sales = $request->id_sales;
        $quotation->id_service = NULL;
        $quotation->primary_id = $quote->primary_id;
        $quotation->is_primary = '1';
        $quotation->num_rev = $lastQuote->num_rev + 1;
        $quotation->destination = $quote->destination;
        if ($request->no_pr != NULL) {
            $quotation->no_pr = $request->no_pr;
        } else {
            $quotation->no_pr = NULL;
        }
        $quotation->status = $quote->status;
        $quotation->status_date = $quote->status_date;
        $quotation->note = $quote->note;
        $quotation->po_date = $quote->po_date;
        $quotation->po_file = $quote->po_file;
        $quotation->level = $quote->level;
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
        $quotation->fee = $quote->fee;
        $quotation->nett = $request->subtotal - $request->diskon;
        $quotation->total_no_tax = $request->total_no_tax;
        $quotation->harga_total = $request->harga_total;
        $quoteSave = $quotation->save();
        if ($quoteSave) {
            // Masukan Data Ke Tabel Detail Quotataion
            foreach ($request->product as $item => $value) {
                $dQuote = new DetailQuotation;
                $dQuote->id_quotation = $quotation->id;
                $dQuote->id_equivalent = $request->product[$item];
                $dQuote->detail_product = $request->detail_product[$item];
                $dQuote->price = $request->price[$item];
                $dQuote->qty = $request->qty[$item];
                $dQuote->fee = 0;
                $dQuote->info_qty = $request->info_qty[$item];
                $dQuote->disc = $request->disc[$item];
                $dQuote->amount = $request->amount[$item];
                $dQuote->pph = 0;
                $dQuoteSave = $dQuote->save();
            }
            if ($dQuoteSave) {
                // Masukan Data ke dalam Tabel Term n Condition
                $termncon = new Termncon;
                $termncon->id_quotation = $quotation->id;
                $termncon->validity = $request->validity;
                $termncon->pricing = $request->pricing;
                $termncon->delivery_process = $request->delivery_process;
                $termncon->payment = $request->payment;
                $termncon->note = $request->note;
                $termnconSave = $termncon->save();
            }
        }
        if ($termnconSave) {
            return redirect('/quotation/' . $quotation->id)->with("success", "Data Revisi Quotation Telah Ditambahkan");
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
        $quotation = Quotation::find($id);
        $quote = Quotation::where('primary_id', $quotation->primary_id)->where('num_rev', $quotation->num_rev - 1)->first();
        $quotes = Quotation::where('primary_id', $quotation->primary_id)->get();

        if (count($quotes) > 1) {
            $quote->is_primary = '1';
            $quote->save();
        }

        $quotation->level = '0';
        $quote->is_primary = '0';
        $delQuote = $quotation->save();

        if ($delQuote) {
            return 1;
        } else {
            return 0;
        }
    }
    public function print_quote($id)
    {
        $quote = Quotation::where('id', $id)->first();
        $dquote = DetailQuotation::where('id_quotation', $id)->get();
        $tax = ($quote->subtotal - $quote->diskon) * $quote->tax / 100;
        $afterDisc = $quote->subtotal - $quote->diskon;
        // dd($termncon);
        return view("pages.sales.quotation.detail-print", compact('quote', 'dquote', 'tax', 'afterDisc'));
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
        $dateNow = Carbon::now();
        $numberQ = Quotation::whereYear('estimated_date', $dateNow)->where('id_sales', Auth::user()->id)->count();
        $formattedNumberQ = str_pad($numberQ + 1, 3, '0', STR_PAD_LEFT);
        $monthNow = $dateNow->month;
        $formattedMonthNow = $this->convertToRoman($monthNow);
        $pic = Pic::all();
        $product = Product::join('serial_product as s', 's.id_product', '=', 'product.id')->get(['s.id', 'product.go', 's.pn', 's.brand', 'product.detail_desc']);
        $sales = User::where('role', 'sales')->get();
        // dd($dquotation);
        return view('pages.sales.quotation.form', compact('quotation', 'dquotation', 'sales', 'pic', 'formattedNumberQ', 'formattedMonthNow', 'product'));
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
        $quotation->status_date = Carbon::today();
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
    public function loss_quote()
    {
        return view('pages.sales.quotation.loss.index');
    }

    public function sales_quotation($id)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $quotation = Quotation::where('id_sales', $id)->where('level', '1')->where('is_primary', '1')->whereMonth('estimated_date', $monthNow)->get();
        $forecast = Quotation::where('id_sales', $id)->where('level', '1')->where('is_primary', '1')->whereMonth('estimated_date', $monthNow)->whereIn('status', ['20', '30', '40', '60', '80'])->sum('nett');
        $prospect = Quotation::where('id_sales', $id)->where('level', '1')->where('is_primary', '1')->whereMonth('estimated_date', $monthNow)->where('status', '80')->sum('nett');
        $po = Quotation::where('id_sales', $id)->where('level', '1')->where('is_primary', '1')->whereMonth('po_date', $monthNow)->where('status', '100')->sum('nett');
        $loss = Quotation::where('id_sales', $id)->where('level', '1')->where('is_primary', '1')->whereMonth('estimated_date', $monthNow)->where('status', '0')->sum('nett');
        return view('pages.sales.quotation.sales', compact('quotation', 'forecast', 'prospect', 'po', 'loss'));
    }

    public function sales_po($id)
    {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $quotation = Quotation::where('id_sales', $id)->where('level', '1')->where('is_primary', '1')->whereMonth('po_date', $monthNow)->get();
        return view('pages.sales.quotation.po.sales', compact('quotation'));
    }

    public function convert_po(Request $request, $id)
    {
        $rule = [
            // 'poDate' => 'required',
            'note' => 'required',
        ];
        $message = [
            // 'poDate.required' => 'Field Date Wajib Diisi',
            'note.required' => 'Field note Wajib Diisi',
        ];

        $this->validate($request, $rule, $message);
        // dd($request);
        $quotation = Quotation::find($id);
        $pic = Pic::where('id', $quotation->id_pic)->first();
        $client = Client::where('id', $pic->id_client)->first();
        $quotation->status = "100";
        $quotation->note = $request->note;
        $quotation->po_date = $request->po_date;
        $quoteSave = $quotation->save();
        if ($client->id_issues != "5") {
            $client->id_issues = '5';
            $client->role = 'Customers';
            $client->save();
        }
        if ($quoteSave) {
            return redirect('/quotation/' . $id)->with("success", "data telah ditambahkan");
        }
    }
    public function convert_flag(Request $request, $id)
    {
        $quote = Quotation::find($id);
        if ($quote->flag == 'Reftech') {
            $quote->flag = 'Kojisha';
        } elseif ($quote->flag == 'Kojisha') {
            $quote->flag = 'Reftech';
        }
        $quoteSave = $quote->save();
        if ($quoteSave) {
            return 1;
        } else {
            return 0;
        }
    }

    public function insert_fee(Request $request, $id)
    {
        $quote = Quotation::find($id);
        if (!$quote) {
            return redirect()->back()->with('error', 'Quotation not found');
        }
        $subtotal = $quote->subtotal;

        $dQuotes = DetailQuotation::where('id_quotation', $id)->get();
        $quote->fee = $request->total;
        $quote->nett = $subtotal - $request->total;

        foreach ($dQuotes as $index => $dQuote) {
            if (isset($request->fee[$index])) {
                $dQuote->fee = $request->fee[$index];
                $dQuote->save();
            }
        }

        $quoteSave = $quote->save();
        if ($quoteSave) {
            return redirect('/quotation/' . $quote->id)->with('message', 'Fees telah di tambahkan');
        } else {
            return redirect()->back()->with('error', 'Failed to save quotation');
        }
    }

    public function delete_fee(Request $request, $id)
    {
        $quote = Quotation::find($id);
        $dQuote = DetailQuotation::where('id_quotation', $id)->get();
        foreach ($dQuote as $detail) {
            $detail->fee = 0;
            $detail->save();
        }
        $quote->fee = 0;
        $quote->nett = $quote->subtotal;
        $quoteSave = $quote->save();
        if ($quoteSave) {
            return 1;
        } else {
            return 0;
        }
    }

    public function request_bp(Request $request, $id)
    {
        $quote = Quotation::find($id);
        $invoice = new Invoice;
        $invoice->id_quotation = $id;
        $invoice->no_po = $request->po;
        $invoice->no_invoice = NULL;
        $invoice->flag = $quote->flag;
        $invoice->type = 'BP';
        $invoice->date = Carbon::today();
        $invoice->term = NULL;
        $invoice->invoiceTo = NULL;
        $invoice->sign = NULL;
        $invoiceSave = $invoice->save();
        if ($invoiceSave) {
            return redirect('/quotation/' . $id)->with('message', 'File has Uploaded');
        } else {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }

    }
    public function upload_po(Request $request, $id)
    {
        $quote = Quotation::find($id);

        if (!$quote) {
            return response()->json(['error' => 'Quotation not found.'], 404);
        }
        if ($request->hasFile('uploadPO')) {
            $foto = $request->file('uploadPO');

            // Validate the file to ensure it's a PDF
            $request->validate([
                'uploadPO' => 'required|file|mimes:pdf|max:2048',
            ]);

            // Get file extension
            $file_ext = $foto->getClientOriginalExtension();

            // Sanitize the quote number to create a valid filename
            $sanitized_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $quote->no_quote);

            // Construct file name
            $file_name = $sanitized_file_name . '.' . $file_ext;

            // Set upload path
            $upload_path = 'asset/po';

            // Move the file to the upload path
            $foto->move(public_path($upload_path), $file_name);

            // Update the quote with the new file path
            $quote->po_file = $upload_path . '/' . $file_name;
            $quote->upload_date = Carbon::today();
            $quote->save();
            // create invoice quote
            if (Auth::user()->id != '16') {
                $invoice = new Invoice;
                $invoice->id_quotation = $id;
                $invoice->no_po = $request->po;
                $invoice->flag = $quote->flag;
                $invoice->no_invoice = NULL;
                $invoice->type = 'CT';
                $invoice->date = Carbon::today();
                $invoice->term = NULL;
                $invoice->invoiceTo = NULL;
                $invoice->sign = NULL;
                $invoice->save();
            }

            return redirect('/quotation/' . $id)->with('message', 'File has Uploaded');
        } else {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }
    }
    public function download_po($id)
    {
        $quote = Quotation::find($id);

        if (!$quote) {
            return response()->json(['error' => 'Quotation not found.'], 404);
        }

        $file_path = public_path($quote->po_file);

        if (!file_exists($file_path)) {
            return response()->json(['error' => 'File not found at path: ' . $file_path], 404);
        }

        return response()->download($file_path);
    }
    public function delete_po($id)
    {
        $quote = Quotation::find($id);
        $invoice = Invoice::where('id_quotation', $id)->get();

        if (!$quote) {
            return response()->json(['error' => 'Quotation not found.'], 404);
        }

        $file_path = public_path($quote->po_file);

        if (file_exists($file_path)) {
            foreach ($invoice as $key => $value) {
                $invoice->delete();
            }
            unlink($file_path);
            $quote->po_file = null;
            $quote->upload_date = null;
            $quote->save();
            return 1;
        } else {
            return 0;
        }
    }

    public function add_payment(Request $request, $id)
    {
        $quote = Quotation::find($id);
        if (!$quote) {
            return redirect('/quotation/' . $id)->with('error', 'Quotation not found.');
        }

        // $paymentCount = Payment::where('id_quotation', $id)->count();
        $invoice = Invoice::where('id_quotation', $id)->get();
        $payment = new Payment;

        // if ($request->hasFile('file')) {
        //     $foto = $request->file('file');

        //     // Validate the file to ensure it's a PDF, JPG, JPEG, or PNG
        //     $request->validate([
        //         'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        //     ]);

        //     // Get file extension
        //     $file_ext = $foto->getClientOriginalExtension();

        //     // Sanitize the quote number to create a valid filename
        //     $sanitized_file_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $quote->no_quote);

        //     // Construct file name
        //     $file_name = $sanitized_file_name . '-' . ($paymentCount + 1) . '.' . $file_ext;

        //     // Set upload path
        //     $upload_path = 'asset/payment';

        //     // Move the file to the upload path
        //     $foto->move(public_path($upload_path), $file_name);

        //     // Update the payment with the new file path
        //     $payment->id_quotation = $id;
        //     $payment->file = $upload_path . '/' . $file_name;
        //     $payment->amount = $request->amount;
        //     $payment->percent = $request->percent;
        //     $payment->note = $request->note;
        //     $payment->save();

        //     return redirect('/quotation/' . $id)->with('message', 'File has been uploaded.');
        // } else {
        //     return response()->json(['error' => 'No file uploaded.'], 400);
        // }
        $targetInvoice = $invoice->count() - 1;
        if ($invoice->count() == 1) {
            $invoice[$targetInvoice]->type = 'DP';
            $invoice[$targetInvoice]->save();
        } else {
            $invoice[$targetInvoice]->type = 'BP';
            $invoice[$targetInvoice]->save();
        }
        $payment->id_quotation = $id;
        $payment->file = 'photo';
        $payment->amount = $request->amount;
        $payment->percent = $request->percent;
        $payment->note = $request->note;
        $payment->save();

        return redirect('/quotation/' . $id)->with('message', 'File has been uploaded.');
    }
    public function download_payment($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['error' => 'Quotation not found.'], 404);
        }

        $file_path = public_path($payment->file);

        if (!file_exists($file_path)) {
            return response()->json(['error' => 'File not found at path: ' . $file_path], 404);
        }

        return response()->download($file_path);
    }

    public function delete_payment($id)
    {
        $payment = Payment::find($id);
        $invoice = Invoice::where('id_quotation', $payment->id_quotation)->first();

        if (!$payment) {
            return response()->json(['error' => 'Quotation not found.'], 404);
        }
        $paymentDel = $payment->delete();
        $invoice->type = 'CT';
        $invoice->save();

        // $file_path = public_path($payment->file);

        // if (file_exists($file_path)) {
        //     unlink($file_path);
        //     $payment->delete();
        //     return 1;
        if ($paymentDel) {
            return 1;
        } else {
            return 0;
        }
    }

    public function cancel_po($id)
    {
        $quote = Quotation::find($id);
        $invoices = Invoice::where('id_quotation', $id)->get();
        $deliveries = Delivery::whereIn('id_invoice', $invoices->pluck('id'))->get();
        $detDeliveries = DetailDelivery::whereIn('id_delivery', $deliveries->pluck('id'))->get();

        // Edit Quotation
        $quote->status = '80';
        $quote->po_date = NULL;
        $file_path = public_path($quote->po_file);
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        $quote->po_file = NULL;
        $status = $quote->save();

        // Hapus Detail Delivery
        foreach ($detDeliveries as $detDelivery) {
            $detDelivery->delete();
        }

        // Hapus Delivery
        foreach ($deliveries as $delivery) {
            $delivery->delete();
        }

        // Hapus Invoice
        foreach ($invoices as $invoice) {
            $invoice->delete();
        }


        if ($status) {
            return 1;
        } else {
            return 0;
        }
    }

    public function add_mention(Request $request, $id){
        $quote = Quotation::find($id);
        $quote->mention = $request->mention;
        $quote->note = $request->note;
        $status = $quote->save();
        if ($status) {
            return redirect('/quotation/'. $id)->with('massage','Data berhasil di buat');
        }
    }

    public function change_primary(Request $request, $id){
        $quote = Quotation::find($id);
        $quotations = Quotation::where("primary_id", $quote->primary_id)->get();
        foreach ($quotations as $quotation) {
            $quotation->is_primary = '0';
            $quotation->save();
        }
        $quote->is_primary = '1';
        $quoteSave = $quote->save();
        if ($quoteSave) {
            return response()->json(['success' => 'Status berhasil diperbarui']);
        } else {
            return response()->json(['error' => 'Gagal menyimpan perubahan status'], 500);
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
