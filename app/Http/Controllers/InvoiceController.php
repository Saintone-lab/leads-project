<?php

namespace App\Http\Controllers;

use App\Models\DetailQuotation;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Quotation;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.accounting.invoice.index');
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
        $totalAmount = 0;
        $invoice = Invoice::find($id);
        $quote = Quotation::where('id', $invoice->id_quotation)->first();
        $dquote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $payments = Payment::where('id_quotation', $quote->id)->get();

        foreach ($payments as $payment) {
            $totalAmount += $payment->amount;
        }

        $remaining = $quote->harga_total - $totalAmount;
        $price = $this->terbilang($remaining);
        $tax = $quote->total_no_tax * $quote->tax / 100;

        return view('pages.accounting.invoice.detail', compact('quote', 'dquote', 'price', 'tax', 'invoice', 'payments', 'remaining'));
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
            'invoice' => 'required',
            'payment' => 'required',
        ];
        $message = [
            'invoice.required' => 'Field invoice Wajib Diisi',
            'payment.required' => 'Field payment Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        $invoice = Invoice::find($id);
        $invoice->no_invoice = $request->invoice;
        $invoice->term = $request->payment;
        $invoiceSave = $invoice->save();
        if ($invoiceSave) {
            return redirect('/invoice/' . $id)->with('message', 'Invoice has been accepted');
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
    public function request()
    {
        return view('pages.accounting.invoice.index-request');
    }
    public function before_accept($id)
    {
        
        $totalAmount = 0;
        $quote = Quotation::find($id);
        $dquote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $payments = Payment::where('id_quotation', $quote->id)->get();

        foreach ($payments as $payment) {
            $totalAmount += $payment->amount;
        }

        $remaining = $quote->harga_total - $totalAmount;
        $price = $this->terbilang($remaining);
        $tax = $quote->total_no_tax * $quote->tax / 100;
        $invoice = Invoice::where('id_quotation', $id)->first();
        // dd($price);
        return view('pages.accounting.invoice.before-accept', compact('quote', 'dquote', 'price', 'tax', 'invoice', 'payments', 'remaining'));
    }

    public function print_invoice($id)
    {
        $totalAmount = 0;
        $invoice = Invoice::find($id);
        $quote = Quotation::where('id', $invoice->id_quotation)->first();
        $dquote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $payments = Payment::where('id_quotation', $quote->id)->get();

        foreach ($payments as $payment) {
            $totalAmount += $payment->amount;
        }

        $remaining = $quote->harga_total - $totalAmount;
        $price = $this->terbilang($remaining);
        $tax = $quote->subtotal * $quote->tax / 100;
        // dd($termncon);
        return view("pages.accounting.invoice.detail-print", compact('quote', 'dquote', 'tax', 'invoice', 'price', 'payments', 'remaining'));
    }

    private function terbilang($number)
    {
        $number = abs($number);
        $words = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $result = "";

        if ($number < 12) {
            $result = " " . $words[$number];
        } elseif ($number < 20) {
            $result = $this->terbilang($number - 10) . " belas";
        } elseif ($number < 100) {
            $result = $this->terbilang((int) ($number / 10)) . " puluh " . $this->terbilang($number % 10);
        } elseif ($number < 200) {
            $result = " seratus" . $this->terbilang($number - 100);
        } elseif ($number < 1000) {
            $result = $this->terbilang((int) ($number / 100)) . " ratus " . $this->terbilang($number % 100);
        } elseif ($number < 2000) {
            $result = " seribu" . $this->terbilang($number - 1000);
        } elseif ($number < 1000000) {
            $result = $this->terbilang((int) ($number / 1000)) . " ribu " . $this->terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            $result = $this->terbilang((int) ($number / 1000000)) . " juta " . $this->terbilang($number % 1000000);
        } elseif ($number < 1000000000000) {
            $result = $this->terbilang((int) ($number / 1000000000)) . " milyar " . $this->terbilang($number % 1000000000);
        } elseif ($number < 1000000000000000) {
            $result = $this->terbilang((int) ($number / 1000000000000)) . " trilyun " . $this->terbilang($number % 1000000000000);
        }

        return ucwords(trim($result));
    }
}
