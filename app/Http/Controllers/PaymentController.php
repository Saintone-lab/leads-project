<?php

namespace App\Http\Controllers;

use App\Models\ChangeStatus;
use App\Models\DetailQuotation;
use App\Models\Expanse;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Quotation;
use App\Models\Reminder;
use App\Models\Resi;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index_invoice()
    {
        $fullInvoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->where('status', '100')
            ->where('invoice.flag', 'Reftech')
            ->where('quotation.tax', '11')
            ->sum('harga_total');
        $fullPayment = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->leftJoin(
                DB::raw('(SELECT id_quotation, SUM(amount) as total_payment 
                  FROM payment 
                  GROUP BY id_quotation) as pay'),
                'quotation.id',
                '=',
                'pay.id_quotation'
            )
            ->where('status', '100')
            ->where('invoice.flag', 'Reftech')
            ->where('quotation.tax', '11')
            ->sum(DB::raw('IFNULL(pay.total_payment, 0)'));
        $sisa = $fullInvoice - $fullPayment;

        $lastPaymentSub = DB::table('payment as p1')
            ->select('p1.id', 'p1.id_quotation', 'p1.amount', 'p1.type', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment GROUP BY id_quotation) as p2'), 'p1.id', '=', DB::raw('p2.max_id'));
        // dd($lastPaymentSub);
        // dd(DB::select('SELECT p1.* FROM payment p1 INNER JOIN (SELECT id_quotation, MAX(id) as max_id FROM payment GROUP BY id_quotation) p2 ON p1.id = p2.max_id'));
        // dd(DB::select('SELECT id_quotation, SUM(amount) as total_payment FROM payment GROUP BY id_quotation'));
        return view('pages.accounting.payment.index-invoice', compact('fullInvoice', 'fullPayment', 'sisa'));
    }
    public function index_invoice_ahmad()
    {
        $fullInvoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->where('status', '100')
            ->where('invoice.flag', 'Reftech')
            ->where('quotation.tax', '11')
            ->sum('harga_total');
        $fullPayment = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->leftJoin(
                DB::raw('(SELECT id_quotation, SUM(amount) as total_payment 
                  FROM payment 
                  GROUP BY id_quotation) as pay'),
                'quotation.id',
                '=',
                'pay.id_quotation'
            )
            ->where('status', '100')
            ->where('invoice.flag', 'Reftech')
            ->where('quotation.tax', '11')
            ->sum(DB::raw('IFNULL(pay.total_payment, 0)'));
        $sisa = $fullInvoice - $fullPayment;

        $lastPaymentSub = DB::table('payment as p1')
            ->select('p1.id', 'p1.id_quotation', 'p1.amount', 'p1.type', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment GROUP BY id_quotation) as p2'), 'p1.id', '=', DB::raw('p2.max_id'));
        // dd($lastPaymentSub);
        // dd(DB::select('SELECT p1.* FROM payment p1 INNER JOIN (SELECT id_quotation, MAX(id) as max_id FROM payment GROUP BY id_quotation) p2 ON p1.id = p2.max_id'));
        // dd(DB::select('SELECT id_quotation, SUM(amount) as total_payment FROM payment GROUP BY id_quotation'));
        return view('pages.accounting.payment.ahmad.index-invoice', compact('fullInvoice', 'fullPayment', 'sisa'));
    }
    public function index_invoice_rayi()
    {
        $fullInvoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->where('status', '100')
            ->where('invoice.flag', 'Reftech')
            ->where('quotation.tax', '11')
            ->sum('harga_total');
        $fullPayment = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->leftJoin(
                DB::raw('(SELECT id_quotation, SUM(amount) as total_payment 
                  FROM payment 
                  GROUP BY id_quotation) as pay'),
                'quotation.id',
                '=',
                'pay.id_quotation'
            )
            ->where('status', '100')
            ->where('invoice.flag', 'Reftech')
            ->where('quotation.tax', '11')
            ->sum(DB::raw('IFNULL(pay.total_payment, 0)'));
        $sisa = $fullInvoice - $fullPayment;

        $lastPaymentSub = DB::table('payment as p1')
            ->select('p1.id', 'p1.id_quotation', 'p1.amount', 'p1.type', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment GROUP BY id_quotation) as p2'), 'p1.id', '=', DB::raw('p2.max_id'));
        // dd($lastPaymentSub);
        // dd(DB::select('SELECT p1.* FROM payment p1 INNER JOIN (SELECT id_quotation, MAX(id) as max_id FROM payment GROUP BY id_quotation) p2 ON p1.id = p2.max_id'));
        // dd(DB::select('SELECT id_quotation, SUM(amount) as total_payment FROM payment GROUP BY id_quotation'));
        return view('pages.accounting.payment.rayi.index-invoice', compact('fullInvoice', 'fullPayment', 'sisa'));
    }
    public function detail_invoice($id)
    {
        $invoice = Invoice::find($id);
        $quote = Quotation::find($invoice->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $quote->id)->get();
        $payment = Payment::where('id_quotation', $quote->id)->get();
        // dd($payment);
        return view('pages.accounting.payment.detail-invoice', compact('invoice', 'quote', 'dQuote', 'payment'));
    }
    public function index_payment()
    {
        $receipt = Payment::all()->sum('amount');
        $confirm = Payment::where('level', 1)->sum('amount');
        $unconfirm = Payment::where('level', 0)->sum('amount');
        return view('pages.accounting.payment.index-payment', compact('receipt', 'confirm', 'unconfirm'));
    }
    public function detail_payment($id)
    {
        $payment = Payment::find($id);
        $quote = Quotation::find($payment->id_quotation);
        $activity = ChangeStatus::where('id_payment', $id)->get();

        if ($payment->type === 'BP') {
            $invoice = Invoice::where('id_quotation', $quote->id)->where('type', 'BP')->first();
        } else {
            $invoice = Invoice::where('id_quotation', $quote->id)->first();
        }
        return view('pages.accounting.payment.detail-payment', compact('activity', 'invoice', 'quote', 'payment'));
    }
    public function index_aging()
    {
        $invoice = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')->join('client as c', 'p.id_client', '=', 'c.id')
            ->where('payment.type', 'Tempo')
            ->where('payment.level', 0)
            ->whereNotNULL('payment.due_date')
            ->groupBy('payment.id')
            ->select('payment.*', 'q.harga_total', 'c.info', 'u.id as id_sales') // ambil kolom penting
            ->get();
        $confirm = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->join('invoice as i', 'i.id_quotation', '=', 'q.id')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')->join('client as c', 'p.id_client', '=', 'c.id')
            ->where('payment.type', 'Tempo')
            ->where('payment.level', 1)
            ->groupBy('payment.id')->sum('payment.amount');
        $unconfirm = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->join('invoice as i', 'i.id_quotation', '=', 'q.id')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')->join('client as c', 'p.id_client', '=', 'c.id')
            ->where('payment.type', 'Tempo')
            ->where('payment.level', 0)
            ->groupBy('payment.id')
            ->select('payment.*', 'q.harga_total', 'c.info', 'u.id as id_sales') // ambil kolom penting
            ->get();
        $overdue = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->where('payment.type', 'Tempo')
            ->where('payment.level', 0)
            ->whereNotNULL('payment.due_date')
            ->whereDate('payment.due_date', '<=', Carbon::today())
            ->groupBy('payment.id')
            ->select('payment.*', 'q.harga_total', 'c.info', 'u.id as id_sales') // ambil kolom penting
            ->get();
        $ondue = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->where('payment.type', 'Tempo')
            ->where('payment.level', 0)
            ->whereNotNULL('payment.due_date')
            ->whereDate('payment.due_date', '>', Carbon::today())
            ->groupBy('payment.id')
            ->select('payment.*', 'q.harga_total', 'c.info', 'u.id as id_sales') // ambil kolom penting
            ->get();
        $nodueCount = Payment::where('type', 'Tempo')->whereNull('due_date')->count();
        return view('pages.accounting.payment.index-aging', compact('invoice', 'confirm','nodueCount', 'unconfirm', 'overdue', 'ondue'));
    }
    public function detail_aging($id)
    {
        $payment = Payment::find($id);
        $quote = Quotation::find($payment->id_quotation);
        $invoice = Invoice::where('id_quotation', $quote->id)->first();
        $today = Carbon::today();
        $diffDue = $today->diffInDays($payment->due_date, false);
        $reminder = Reminder::where('id_payment', $id)->get();
        // dd($diffDue);
        return view('pages.accounting.payment.detail-aging', compact('reminder', 'diffDue', 'invoice', 'payment', 'quote'));

    }
    public function confirm_payment($id)
    {
        $payment = Payment::find($id);
        $payment->level = 1;
        $paymentSave = $payment->save();

        $activity = new ChangeStatus();
        $activity->id_user = Auth::user()->id;
        $activity->id_payment = $payment->id;
        $activity->note = "Payment Verif By ";
        $activity->status = 2;
        $activity->date = Carbon::now();
        $activity->save();
        if ($paymentSave) {
            return 1;
        } else {
            return 0;
        }
    }
    public function unconfirm_payment($id)
    {
        $payment = Payment::find($id);
        $payment->level = 0;
        $paymentSave = $payment->save();

        $activity = new ChangeStatus();
        $activity->id_user = Auth::user()->id;
        $activity->id_payment = $payment->id;
        $activity->note = "Unconfirmed By ";
        $activity->status = 3;
        $activity->date = Carbon::now();
        $activity->save();
        if ($paymentSave) {
            return 1;
        } else {
            return 0;
        }
    }
    public function view_payment($id)
    {
        $payment = Payment::findOrFail($id);

        // cek apakah user sudah pernah view dengan kombinasi payment + user + status
        $status = ChangeStatus::where('id_payment', $id)
            ->where('id_user', Auth::id())
            ->where('status', 1)
            ->first();

        if (!$status) {
            // hanya bikin baru kalau belum ada
            ChangeStatus::create([
                'id_user' => Auth::id(),
                'id_payment' => $id,
                'note' => "Payment View By ",
                'status' => 1,
                'date' => Carbon::now(),
            ]);
        }

        // redirect ke file aslinya
        return redirect(url($payment->file));
    }
    public function reminder_payment(Request $request, $id)
    {
        $reminder = new Reminder;
        $remCount = Reminder::where('id_payment', $id)->get()->count();
        $reminder->id_user = Auth::user()->id;
        $reminder->id_payment = $id;
        $reminder->reminder = $request->reminder;
        $reminder->date_fu = $request->date_fu;
        $reminder->date = Carbon::now();
        $reminder->status = $remCount + 1;
        $reminderSave = $reminder->save();
        if ($reminderSave) {
            return redirect('/payment-detail/aging/' . $id)->with("success", "data telah di buat");
        }
    }
}
