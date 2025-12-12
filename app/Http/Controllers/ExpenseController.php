<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bank;
use App\Models\DetailExpense;
use App\Models\Expense;
use Illuminate\Http\Request;

class Expensecontroller extends Controller
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
            return redirect('/expense-acount')->with('success', 'data telah dibuat');
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

    public function indexExpense()
    {
        return view('pages.finance.expense.index');
    }
    public function indexExpenseUmum()
    {
        return view('pages.finance.expense.index-umum');
    }

    public function createExpense()
    {
        $bank = Bank::all();
        $expense = Expense::all();
        $account = Account::all();
        return view('pages.finance.expense.form', compact('bank', 'expense', 'account'));
    }
    public function createExpenseUmum()
    {
        $expense = Expense::all();
        $account = Account::all();
        return view('pages.finance.expense.form-umum', compact('expense', 'account'));
    }
    public function storeExpense(Request $request)
    {
        // dd($request->all());
        if (@$request->bank) {
            # code...
            $bank = Bank::find($request->bank);
            $bank->saldo -= $request->total;
            $bank->save();
        }
        $expense = new Expense;
        $expense->id_bank = $request->bank ?? null;
        $expense->no_invoice = $request->no_invoice;
        $expense->no_cheque = $request->no_cheque;
        $expense->memo = $request->detail;
        $expense->date = $request->date;
        $expense->amount = $request->total;
        $expenseSave = $expense->save();
        if ($expenseSave) {
            foreach ($request->account as $item => $value) {
                $dExpense = new DetailExpense();
                $dExpense->id_Expense = $expense->id;
                $dExpense->id_account = $request->account[$item];
                $dExpense->memo = $request->memo[$item];
                $dExpense->amount = $request->amount[$item];
                $dExpenseSave = $dExpense->save();
            }
        }
        if ($expenseSave && $dExpenseSave) {
            if (@$request->bank) {
                return redirect('expense')->with('success', 'Data berhasil disimpan');
            } else {
                return redirect('expense-umum')->with('success', 'Data berhasil disimpan');
            }
            
        }
    }
    public function showExpense($id)
    {
        $expense = Expense::find($id);
        $detailExpense = DetailExpense::where('id_expense', $id)->get();
        $terbilang = $this->capitalizeWords(
            trim($this->terbilang($expense->amount))
        );
        return view('pages.finance.expense.detail', compact('detailExpense', 'expense', 'terbilang'));
    }
    public function showExpensePrint($id)
    {
        $expense = Expense::find($id);
        $detailExpense = DetailExpense::where('id_expense', $id)->get();
        $terbilang = $this->capitalizeWords(
            trim($this->terbilang($expense->amount))
        );
        return view('pages.finance.expense.detail-print', compact('detailExpense', 'expense', 'terbilang'));
    }
    public function deleteExpense($id)
    {
        $expense = Expense::find($id);
        $bank = Bank::find($expense->id_bank);
        $detailExpense = DetailExpense::where('id_expense', $id)->get();
        foreach ($detailExpense as $key) {
            $key->delete();
        }
        $bank->saldo += $expense->amount;
        $bank->save();
        $expenseDel = $expense->delete();
        if ($expenseDel) {
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
