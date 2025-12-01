<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bank;
use App\Models\DetailPayable;
use App\Models\Payable;
use Illuminate\Http\Request;

class PayableController extends Controller
{
    public function indexAccount()
    {
        return view('pages.finance.account.index');
    }
    public function storeAccount(Request $request)
    {
        $account = new Account();
        $account->code = $request->code;
        $account->name = $request->name;
        $account->category = $request->category;
        $accountSave = $account->save();
        if ($accountSave) {
            return redirect('/payable-acount')->with('success', 'data telah dibuat');
        }
    }
    public function deleteAccount($id)
    {
        $account = Account::find($id);
        $delAccount = $account->delete();
        if ($delAccount) {
            return 1;
        } else {
            return 0;
        }
    }

    public function indexPayable()
    {
        return view('pages.finance.payable.index');
    }

    public function createpayable()
    {
        $bank = Bank::all();
        $payable = Payable::all();
        $account = Account::all();
        return view('pages.finance.payable.form', compact('bank', 'payable', 'account'));
    }
    public function storePayable(Request $request)
    {
        // dd($request->all());
        $bank = Bank::find($request->bank);
        $payable = new Payable;
        $payable->id_bank = $request->bank;
        $payable->no_voucher = $request->no_voucher;
        $payable->no_cheque = $request->no_cheque;
        $payable->memo = $request->detail;
        $payable->payee = $request->payee;
        $payable->date = $request->date;
        $payable->amount = $request->total;
        $payableSave = $payable->save();
        if ($payableSave) {
            foreach ($request->account as $item => $value) {
                $dpayable = new DetailPayable();
                $dpayable->id_payable = $payable->id;
                $dpayable->id_account = $request->account[$item];
                $dpayable->memo = $request->memo[$item];
                $dpayable->amount = $request->amount[$item];
                $dpayableSave = $dpayable->save();
            }
        }
        $bank->saldo -= $request->total;
        $bank->save();
        if ($payableSave && $dpayableSave) {
            return redirect('payable')->with('success', 'Data berhasil disimpan');
        }
    }
    public function showPayable($id)
    {
        $payable = Payable::find($id);
        $detailPayable = DetailPayable::where('id_payable', $id)->get();
        $terbilang = $this->capitalizeWords(
            trim($this->terbilang($payable->amount))
        );
        return view('pages.finance.payable.detail', compact('detailPayable', 'payable', 'terbilang'));
    }
    public function showPayablePrint($id)
    {
        $payable = Payable::find($id);
        $detailPayable = DetailPayable::where('id_payable', $id)->get();
        $terbilang = $this->capitalizeWords(
            trim($this->terbilang($payable->amount))
        );
        return view('pages.finance.payable.detail-print', compact('detailPayable', 'payable', 'terbilang'));
    }
    public function deletePayable($id)
    {
        $payable = Payable::find($id);
        $bank = Bank::find($payable->id_bank);
        $detailPayable = DetailPayable::where('id_payable', $id)->get();
        foreach ($detailPayable as $key) {
            $key->delete();
        }
        $bank->saldo += $payable->amount;
        $bank->save();
        $payableDel = $payable->delete();
        if ($payableDel) {
            return 1;
        } else {
            return 0;
        }
    }

    private function terbilang($number)
    {
        $number = abs($number);
        $words = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

        if ($number < 12)
            return " " . $words[$number];
        if ($number < 20)
            return $this->terbilang($number - 10) . " belas";
        if ($number < 100)
            return $this->terbilang(floor($number / 10)) . " puluh" . $this->terbilang($number % 10);
        if ($number < 200)
            return " seratus" . $this->terbilang($number - 100);
        if ($number < 1000)
            return $this->terbilang(floor($number / 100)) . " ratus" . $this->terbilang($number % 100);
        if ($number < 2000)
            return " seribu" . $this->terbilang($number - 1000);
        if ($number < 1000000)
            return $this->terbilang(floor($number / 1000)) . " ribu" . $this->terbilang($number % 1000);
        if ($number < 1000000000)
            return $this->terbilang(floor($number / 1000000)) . " juta" . $this->terbilang($number % 1000000);
        if ($number < 1000000000000)
            return $this->terbilang(floor($number / 1000000000)) . " miliar" . $this->terbilang($number % 1000000000);

        return "";
    }

    private function capitalizeWords($str)
    {
        return ucwords($str);
    }
}
