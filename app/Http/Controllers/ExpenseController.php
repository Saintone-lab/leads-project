<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bank;
use App\Models\DetailExpense;
use App\Models\Expense;
use App\Models\LabaRugi;
use App\Models\Quotation;
use Carbon\Carbon;
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

    public function indexIncome()
    {

        $currentYear = date('Y');

        $years = [];
        for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
            $years[] = $i;
        }
        $start = Carbon::now()->subYear()->startOfYear();
        $end = Carbon::now()->endOfYear();

        $months = collect();
        $cursor = $start->copy();

        while ($cursor <= $end) {
            $months->push([
                'month' => $cursor->month,      // 1–12
                'year' => $cursor->year,
                'label' => $cursor->translatedFormat('F Y'), // Januari 2024
            ]);

            $cursor->addMonth();
        }
        // dd($months);
        return view('pages.finance.income.index', compact('years', 'months'));
    }
    public function storeIncome(Request $request)
    {
        $income = new LabaRugi;
        $income->desc = $request->desc;
        $income->type = $request->type;
        $income->amount = $request->price;
        $income->date = Carbon::today();
        $incomeSave = $income->save();
        if ($incomeSave) {
            return redirect()->back()->with('success', 'berhasil ditambahkan!');
        }
    }
    public function printBulan($month, $year)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::today();
        $quotation = Quotation::whereBetween('po_date', [$startDate, $endDate])->where('status', '100')->where('level', '1')->where('is_primary', '1')->get();
        $poSum = $quotation->sum('nett');
        $modalSum = Quotation::join('detail_quotation', 'quotation.id', '=', 'detail_quotation.id_quotation')
            ->join('serial_product', 'detail_quotation.id_equivalent', '=', 'serial_product.id')
            ->whereBetween('quotation.po_date', [$startDate, $endDate])
            ->where('quotation.status', '100')
            ->where('quotation.level', '1')
            ->where('quotation.is_primary', '1')
            ->sum('serial_product.price');
        $allExpense = detailExpense::join('expense as e', 'e.id', '=', 'detail_expense.id_expense')->whereBetween('e.date', [$startDate, $endDate])->groupBy('detail_expense.id')->get();
        $expenseSum = $allExpense->sum('amount');
        $allIncome = LabaRugi::whereBetween('date', [$startDate, $endDate])->where('type', 'Pendapatan Lain')->get();
        $incomeSum = $allIncome->sum('amount');
        $allCharge = LabaRugi::whereBetween('date', [$startDate, $endDate])->where('type', 'Beban Lain')->get();
        $chargeSum = $allCharge->sum('amount');
        $startStringYear = $start->translatedFormat('j M');
        $startString = $start->translatedFormat('j M Y');
        $endString = $end->translatedFormat('j M Y');
        return view('pages.finance.income.print',compact(
            'startDate',
            'endDate',
            'startString',
            'startStringYear',
            'endString',
            'poSum',
            'modalSum',
            'allExpense',
            'allCharge',
            'allIncome',
            'expenseSum',
            'incomeSum',
            'chargeSum'
        ));
    }
    public function printTahun($year)
    {
        $startDate = Carbon::create($year, 1, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::create($year, 12, 1)->endOfMonth()->toDateString();
        $start = Carbon::create($year, 1, 1)->startOfMonth();
        $end = Carbon::today();
        $quotation = Quotation::whereBetween('po_date', [$startDate, $endDate])->where('status', '100')->where('level', '1')->where('is_primary', '1')->get();
        $poSum = $quotation->sum('nett');
        $modalSum = Quotation::join('detail_quotation', 'quotation.id', '=', 'detail_quotation.id_quotation')
            ->join('serial_product', 'detail_quotation.id_equivalent', '=', 'serial_product.id')
            ->whereBetween('quotation.po_date', [$startDate, $endDate])
            ->where('quotation.status', '100')
            ->where('quotation.level', '1')
            ->where('quotation.is_primary', '1')
            ->sum('serial_product.price');
        $allExpense = detailExpense::join('expense as e', 'e.id', '=', 'detail_expense.id_expense')->whereBetween('e.date', [$startDate, $endDate])->groupBy('detail_expense.id')->get();
        $expenseSum = $allExpense->sum('amount');
        $allIncome = LabaRugi::whereBetween('date', [$startDate, $endDate])->where('type', 'Pendapatan Lain')->get();
        $incomeSum = $allIncome->sum('amount');
        $allCharge = LabaRugi::whereBetween('date', [$startDate, $endDate])->where('type', 'Beban Lain')->get();
        $chargeSum = $allCharge->sum('amount');
        $startStringYear = $start->translatedFormat('j M');
        $startString = $start->translatedFormat('j M Y');
        $endString = $end->translatedFormat('j M Y');
        return view('pages.finance.income.print',compact(
            'startDate',
            'endDate',
            'startString',
            'startStringYear',
            'endString',
            'poSum',
            'modalSum',
            'allExpense',
            'allCharge',
            'allIncome',
            'expenseSum',
            'incomeSum',
            'chargeSum'
        ));
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
