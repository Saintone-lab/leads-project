<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Delivery;
use App\Models\DetailQuotation;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Quotation;
use App\Models\ReturnQ;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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
        $return = ReturnQ::where('id_quotation', $invoice->id_quotation)->first();
        $dquote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $payments = Payment::where('id_quotation', $quote->id)->get();
        // dd($return);

        foreach ($payments as $payment) {
            $totalAmount += $payment->amount;
        }

        $remaining = $quote->harga_total - $totalAmount;
        $harga = Payment::where('id_quotation', $quote->id)->orderBy('created_at', 'DESC')->first();
        $price = $this->terbilang(@$harga->amount);
        $fullPrice = $this->terbilang($quote->harga_total);
        $tax = ($quote->subtotal - $quote->diskon) * $quote->tax / 100;
        $afterDisc = $quote->subtotal - $quote->diskon;

        $doTek = Delivery::where('id_invoice', $id)->where('type','teknisi')->get();
        $doEks = Delivery::where('id_invoice', $id)->where('type','ekspedisi')->get();

        return view('pages.accounting.invoice.detail', compact('return', 'quote', 'harga', 'dquote', 'price', 'fullPrice', 'tax', 'invoice', 'payments', 'remaining', 'afterDisc', 'doTek', 'doEks'));
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
        $quote = Quotation::find($invoice->id_quotation);
        $invoice->no_invoice = $request->invoice;
        $invoice->term = $request->payment;
        $invoice->invoiceTo = $quote->destination;
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
        $invoice = Invoice::find($id);
        if ($invoice->type == "DP") {
            $allInvoice = Invoice::where('id_quotation', $invoice->id_quotation)->get();
            foreach ($allInvoice as $item) {
                $invoiceDel = $item->delete();
            }
        } else {
            $invoiceDel = $invoice->delete();
        }

        if ($invoiceDel) {
            return 1;
        } else {
            return 0;
        }
    }
    public function index_kojisha()
    {
        return view('pages.accounting.invoice.index-kojisha');
    }
    public function request()
    {
        return view('pages.accounting.invoice.index-request');
    }
    public function before_accept($id)
    {
        $dateNow = Carbon::now();
        $year = $dateNow->year;
        $month = $dateNow->month;
        $monthCode = $this->convertToRoman($month);
        $lastInvoiceP = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')->where('quotation.tax', '11')->whereNotNull('no_invoice')->whereYear('invoice.created_at', $year)->orderBy('invoice.created_at', 'desc')->first(['invoice.*', 'quotation.tax']);
        $lastInvoiceNP = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')->where('quotation.tax', '0')->whereNotNull('no_invoice')->whereYear('invoice.created_at', $year)->orderBy('invoice.created_at', 'desc')->first(['invoice.*', 'quotation.tax']);
        // dd($lastInvoiceNP);
        function generateNextInvoiceNumber($lastInvoice, $defaultCode)
        {
            if ($lastInvoice) {
                // Ekstrak 3 digit numerik pertama dari no_invoice
                preg_match('/^\d{3}/', $lastInvoice->no_invoice, $matches);

                if (!empty($matches)) {
                    $lastNumber = $matches[0]; // Bagian numerik yang diekstrak, misal "004"
                    $newNumber = str_pad((int) $lastNumber + 1, 3, '0', STR_PAD_LEFT); // Increment dan pad angka

                    return $newNumber;
                } else {
                    // Jika tidak ada bagian numerik yang ditemukan, gunakan default
                    return $defaultCode;
                }
            } else {
                // Jika tidak ada invoice sebelumnya, mulai dari awal
                return $defaultCode;
            }
        }

        // Generate next invoice numbers
        $nextCodeP = generateNextInvoiceNumber($lastInvoiceP, '001');
        $nextCodeNP = generateNextInvoiceNumber($lastInvoiceNP, '001');

        $totalAmount = 0;
        $quote = Quotation::find($id);
        $dquote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $payments = Payment::where('id_quotation', $quote->id)->get();

        foreach ($payments as $payment) {
            $totalAmount += $payment->amount;
        }

        $remaining = $quote->harga_total - $totalAmount;
        $price = $this->terbilang($remaining);
        $tax = ($quote->subtotal - $quote->diskon) * $quote->tax / 100;
        $invoice = Invoice::where('id_quotation', $id)->orderBy('created_at', 'desc')->first();
        // dd($price);
        return view('pages.accounting.invoice.before-accept', compact('quote', 'dquote', 'price', 'tax', 'invoice', 'payments', 'remaining', 'lastInvoiceP', 'lastInvoiceNP', 'nextCodeP', 'nextCodeNP', 'year', 'monthCode'));
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
        $harga = Payment::where('id_quotation', $quote->id)->orderBy('created_at', 'DESC')->first();
        $price = $this->terbilang(@$harga->amount);
        $fullPrice = $this->terbilang($quote->harga_total);
        $tax = ($quote->subtotal - $quote->diskon) * $quote->tax / 100;
        $afterDisc = $quote->subtotal - $quote->diskon;
        // dd($termncon);
        return view("pages.accounting.invoice.detail-print", compact('quote', 'dquote', 'tax', 'invoice', 'price', 'fullPrice', 'payments', 'remaining', 'afterDisc'));
    }

    public function hand_sign(Request $request, $id)
    {
        $photo = Invoice::find($id);
        $quote = Quotation::where('id', $photo->id_quotation)->first();
        $harga = Payment::where('id_quotation', $quote->id)->orderBy('created_at', 'DESC')->first();
        $jumlah = isset($harga) ? $harga->amount : $quote->harga_total;

        if ($photo->flag == "Reftech") {
            $photo->sign = $jumlah >= 5000000 ? 'asset/sign/reftech-m.jpeg' : 'asset/sign/reftech-nm.jpeg';
        } elseif ($photo->flag == "Kojisha") {
            $photo->sign = $jumlah >= 5000000 ? 'asset/sign/kojisha-m.jpeg' : 'asset/sign/kojisha-nm.jpeg';
        }

        // if ($request->hasFile('sign')) {
        //     $foto = $request->file('sign'); // Akses file sesuai dengan iterasi saat ini
        //     // Proses setiap file gambar
        //     $image_ext = $foto->getClientOriginalExtension();
        //     $image_name = Str::random(8);

        //     $upload_path = 'asset/invoice';
        //     $imagename = $upload_path . '/' . $image_name . '.' . $image_ext;

        //     // Pemrosesan gambar
        //     $img = Image::make($foto->path());
        //     $img->fit(800, 500, function ($constraint) {
        //         $constraint->aspectRatio();
        //     });
        //     $img->save($imagename);

        //     $photo['sign'] = $imagename;
        // }
        // dd($photo);

        $status = $photo->save();

        if ($status) {
            return 1;
        } else {
            0;
        }
    }
    public function delete_hand_sign($id)
    {
        $invoice = Invoice::find($id);

        // $delsign = File::delete($invoice->sign);
        // if ($delsign) {
        $invoice->sign = NULL;
        // }
        // dd($photo);
        $status = $invoice->save();

        if ($status) {
            return 1;
        } else {
            return 0;
        }
    }
    public function change_date(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        $invoice->date = $request->date;
        $invoice->invoiceTo = $request->destination;
        $invoiceSave = $invoice->save();

        if ($invoiceSave) {
            return redirect('/invoice/' . $id)->with('massage', 'Data telah terkirim');
        }
    }

    public function do_ekspedisi($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();
        $client = Client::where('id', $quote->pic->id_client)->first();
        // dd($client);

        return view("pages.accounting.delivery.ekspedisi", compact('quote', 'invoice', 'dQuote', 'client'));
    }
    public function print_ekspedisi($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();

        return view("pages.accounting.delivery.ekspedisi-print", compact('quote', 'invoice', 'dQuote'));
    }

    public function do_teknisi($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();

        return view("pages.accounting.delivery.teknisi", compact('quote', 'invoice', 'dQuote'));
    }
    public function print_teknisi($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();

        return view("pages.accounting.delivery.teknisi-print", compact('quote', 'invoice', 'dQuote'));
    }
    public function form_ekspedisi(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        $invoice->dateDo = $request->date;
        $invoice->doTo = $request->destination;
        $status = $invoice->save();

        if ($status) {
            return redirect('/invoice/do_ekspedisi/' . $id)->with('massage', 'Data telah terkirim');
        }
    }
    public function form_teknisi(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        if ($request->check == '1') {
            $invoice->dateDo = NULL;
        } else {
            $invoice->dateDo = $request->date;
        }

        $invoice->doTo = $request->destination;
        $status = $invoice->save();

        if ($status) {
            return redirect('/invoice/do_teknisi/' . $id)->with('massage', 'Data telah terkirim');
        }
    }

    public function label_detail($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);

        return view("pages.accounting.label.detail", compact('quote', 'invoice'));
    }
    public function label_print($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);

        return view("pages.accounting.label.detail-print", compact('quote', 'invoice'));
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
            $result = " seratus " . $this->terbilang($number - 100);
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
