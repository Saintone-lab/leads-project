<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bank;
use App\Models\DetailExpense;
use App\Models\DetailProduct;
use App\Models\Expense;
use App\Models\FixedAsset;
use App\Models\LabaRugi;
use App\Models\Payment;
use App\Models\ProductIn;
use App\Models\Quotation;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class Expensecontroller extends Controller
{
    public function indexAccount()
    {
        $account = Account::where('level', 1)->get();
        $prim = Account::where('level', 1)->get();
        return view('pages.finance.account.index', compact('account', 'prim'));
    }
    public function getAccount($id)
    {
        $account = Account::find($id);

        if (!$account) {
            return response()->json([
                'message' => 'Data stock account tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'id' => $account->id ?? 1,
            'code' => $account->code ?? '',
            'name' => $account->name ?? '',
            'category' => $account->category ?? '',
            'currency' => $account->currency ?? '',
            'saldo' => $account->saldo ?? '',
            'parent' => $account->id_parents ?? '',
        ]);
    }
    public function storeAccount(Request $request)
    {
        $account = new Account();
        $account->id_parents = $request->parent ?? 0 ;
        $account->code = $request->code;
        $account->name = $request->name;
        $account->category = $request->category;
        $account->currency = $request->currency;
        $account->saldo = $request->saldo;
        $account->level = @$request->parent ? 2 : 1;
        $accountSave = $account->save();
        if ($accountSave) {
            return redirect('/expense-acount')->with('success', 'data telah dibuat');
        }
    }
    public function updateAccount(Request $request, $id)
    {
        $account = Account::find($id);
        $account->id_parents = $request->parent ?? 0 ;
        $account->code = $request->code;
        $account->name = $request->name;
        $account->category = $request->category;
        $account->currency = $request->currency;
        $account->saldo = $request->saldo;
        $account->level = @$request->parent ? 2 : 1;
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
        return view('pages.finance.income.print', compact(
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
        return view('pages.finance.income.print', compact(
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

    public function indexBalance()
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
        return view('pages.finance.balance.index', compact('years', 'months'));
    }
    public function printBulanBalance($year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::today();
        $grandTotalPenyusutan = 0;

        $piutang = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')->join('client as c', 'p.id_client', '=', 'c.id')
            ->whereBetween('po_date', [$startDate, $endDate])
            ->where('payment.type', 'Tempo')
            ->where('payment.level', 0)
            ->whereNotNULL('payment.due_date')
            ->groupBy('payment.id')
            ->sum('payment.amount');
        $replace = DetailProduct::all();
        $asset = $replace->sum(function ($replacement) {
            return $replacement->modal * $replacement->stock;
        });
        $pIn = ProductIn::where('tax', '11')->whereBetween('date', [$startDate, $endDate])->sum('total');
        $ppnMas = $pIn * 11 / 100;
        $totalFixed = FixedAsset::sum('total');
        $fixedAsset = FixedAsset::select('type', DB::raw('SUM(total) as total_amount'))
            ->groupBy('type')
            ->get();
        $penyusutan = FixedAsset::all()->groupBy('type')->map(function ($assets, $type) {
            $total = 0;
            foreach ($assets as $asset) {
                $bulan = min(
                    Carbon::parse($asset->beli)->diffInMonths(now()),
                    $asset->umur
                );

                $total += (($asset->total * 0.25) / 12) * $bulan;
            }
            return [
                'type' => $type,
                'total_penyusutan' => $total
            ];
        });
        $grandTotalPenyusutan = $penyusutan->sum('total_penyusutan');
        $quotation = Quotation::whereBetween('po_date', [$startDate, $endDate])->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $ppnKel = $quotation * 11 / 100;
        $prive = DetailExpense::where('id_account', 51)->sum('amount');

        $labaBulanIni = $this->hitungLabaBulanan($year, $month);
        $labaTahunLalu = $this->hitungLabaTahunan($year - 1);
        $labaTahunTahun = $this->hitungLabaTahunSebelumnya($year, $month);
        // $labaBulanBulan = $this->hitungLabaBulanSebelumnya($year, month: $month);

        $startStringYear = $start->translatedFormat('j M');
        $startString = $start->translatedFormat('j M Y');
        $endString = $end->translatedFormat('j M Y');
        return view('pages.finance.balance.print', compact(
            'startDate',
            'endDate',
            'startString',
            'startStringYear',
            'endString',
            'piutang',
            'asset',
            'ppnMas',
            'ppnKel',
            'totalFixed',
            'fixedAsset',
            'penyusutan',
            'quotation',
            'prive',
            'labaBulanIni',
            'labaTahunLalu',
            'labaTahunTahun',
            'grandTotalPenyusutan',
            'month'
        ));
    }
    public function printTahunBalance($year)
    {
        $startDate = Carbon::create($year, 1, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::create($year, 12, 1)->endOfMonth()->toDateString();
        $start = Carbon::create($year, 1, 1)->startOfMonth();
        $end = Carbon::today();
        $grandTotalPenyusutan = 0;

        $piutang = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')->join('client as c', 'p.id_client', '=', 'c.id')
            ->whereBetween('po_date', [$startDate, $endDate])
            ->where('payment.type', 'Tempo')
            ->where('payment.level', 0)
            ->whereNotNULL('payment.due_date')
            ->groupBy('payment.id')
            ->sum('payment.amount');
        $replace = DetailProduct::all();
        $asset = $replace->sum(function ($replacement) {
            return $replacement->modal * $replacement->stock;
        });
        $pIn = ProductIn::where('tax', '11')->whereBetween('date', [$startDate, $endDate])->sum('total');
        $ppnMas = $pIn * 11 / 100;
        $totalFixed = FixedAsset::sum('total');
        $fixedAsset = FixedAsset::select('type', DB::raw('SUM(total) as total_amount'))
            ->groupBy('type')
            ->get();
        $penyusutan = FixedAsset::all()->groupBy('type')->map(function ($assets, $type) {
            $total = 0;
            foreach ($assets as $asset) {
                $bulan = min(
                    Carbon::parse($asset->beli)->diffInMonths(now()),
                    $asset->umur
                );

                $total += (($asset->total * 0.25) / 12) * $bulan;
            }
            return [
                'type' => $type,
                'total_penyusutan' => $total
            ];
        });
        $grandTotalPenyusutan = $penyusutan->sum('total_penyusutan');
        $quotation = Quotation::where('status', '100')->whereBetween('po_date', [$startDate, $endDate])->where('level', '1')->where('is_primary', '1')->sum('nett');
        // dd($endDate);
        $ppnKel = $quotation * 11 / 100;
        $prive = DetailExpense::where('id_account', 51)->sum('amount');

        $labaTahunIni = $this->hitungLabaTahunan($year);
        $labaTahunLalu = $this->hitungLabaTahunan($year - 1);
        $labaTahunTahun = $this->hitungLabaTahunSebelumnya($year, month: 12);

        $startStringYear = $start->translatedFormat('j M');
        $startString = $start->translatedFormat('j M Y');
        $endString = $end->translatedFormat('j M Y');
        return view('pages.finance.balance.print', compact(
            'startDate',
            'endDate',
            'startString',
            'startStringYear',
            'endString',
            'piutang',
            'asset',
            'ppnMas',
            'ppnKel',
            'totalFixed',
            'fixedAsset',
            'penyusutan',
            'quotation',
            'prive',
            'labaTahunIni',
            'labaTahunLalu',
            'labaTahunTahun',
            'grandTotalPenyusutan'
        ));
    }
    public function indexEquity()
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
        return view('pages.finance.equity.index', compact('years', 'months'));
    }
    public function printBulanEquity($year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::today();

        $prive = DetailExpense::where('id_account', 51)->sum('amount');
        $labaBulanIni = $this->hitungLabaBulanan($year, $month);
        $labaTahunLalu = $this->hitungLabaTahunan($year - 1);
        $labaTahunTahun = $this->hitungLabaTahunSebelumnya($year, $month);
        // $labaBulanBulan = $this->hitungLabaBulanSebelumnya($year, month: $month);

        $startStringYear = $start->translatedFormat('j M');
        $startString = $start->translatedFormat('j M Y');
        $endString = $end->translatedFormat('j M Y');
        return view('pages.finance.equity.print', compact(
            'startDate',
            'endDate',
            'startString',
            'startStringYear',
            'endString',
            'prive',
            'labaBulanIni',
            'labaTahunLalu',
            'labaTahunTahun',
            'month'
        ));
    }
    public function printTahunEquity($year)
    {
        $startDate = Carbon::create($year, 1, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::create($year, 12, 1)->endOfMonth()->toDateString();
        $start = Carbon::create($year, 1, 1)->startOfMonth();
        $end = Carbon::today();

        $prive = DetailExpense::where('id_account', 51)->sum('amount');
        $labaTahunIni = $this->hitungLabaTahunan($year);
        $labaTahunLalu = $this->hitungLabaTahunan($year - 1);
        $labaTahunTahun = $this->hitungLabaTahunSebelumnya($year, month: 12);

        $startStringYear = $start->translatedFormat('j M');
        $startString = $start->translatedFormat('j M Y');
        $endString = $end->translatedFormat('j M Y');
        return view('pages.finance.equity.print', compact(
            'startDate',
            'endDate',
            'startString',
            'startStringYear',
            'endString',
            'prive',
            'labaTahunIni',
            'labaTahunLalu',
            'labaTahunTahun',
        ));
    }
    public function indexCashflow()
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
        return view('pages.finance.cashflow.index', compact('years', 'months'));
    }
    public function printBulanCashflow($year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::today();
        $grandTotalPenyusutan = 0;

        $quotation = Quotation::whereBetween('po_date', [$startDate, $endDate])->where('status', '100')->where('level', '1')->where('is_primary', '1')->sum('nett');
        $pendapatan = LabaRugi::whereBetween('date', [$start, $end])
            ->where('type', 'Pendapatan Lain')
            ->get();
        $income = $pendapatan->sum('amount');
        $biaya = LabaRugi::whereBetween('date', [$start, $end])
            ->where('type', 'Biaya Lain')
            ->get();
        $outcome = $biaya->sum('amount');
        $modal = Quotation::join('detail_quotation', 'quotation.id', '=', 'detail_quotation.id_quotation')
            ->join('serial_product', 'detail_quotation.id_equivalent', '=', 'serial_product.id')
            ->whereBetween('quotation.po_date', [$start, $end])
            ->where('quotation.status', '100')
            ->where('quotation.level', '1')
            ->where('quotation.is_primary', '1')
            ->sum('serial_product.price');
        $expensePerAccount = DB::table('detail_expense')
            ->join('expense as e', 'e.id', '=', 'detail_expense.id_expense')
            ->join('account', 'account.id', '=', 'detail_expense.id_account')
            ->whereBetween('e.date', [$startDate, $endDate])
            ->select(
                'account.name',
                DB::raw('SUM(detail_expense.amount) as total_amount')
            )
            ->groupBy('detail_expense.id_account', 'account.name')
            ->get();
        $expenseSum = $expensePerAccount->sum('total_amount');
        $piutang = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')->join('client as c', 'p.id_client', '=', 'c.id')
            ->whereBetween('po_date', [$startDate, $endDate])
            ->where('payment.type', 'Tempo')
            ->where('payment.level', 0)
            ->whereNotNULL('payment.due_date')
            ->groupBy('payment.id')
            ->sum('payment.amount');
        $replace = DetailProduct::all();
        $asset = $replace->sum(function ($replacement) {
            return $replacement->modal * $replacement->stock;
        });
        $pIn = ProductIn::where('tax', '11')->whereBetween('date', [$startDate, $endDate])->sum('total');
        $ppnMas = $pIn * 11 / 100;
        $totalFixed = FixedAsset::sum('total');
        $fixedAsset = FixedAsset::select('type', DB::raw('SUM(total) as total_amount'))
            ->groupBy('type')
            ->get();
        $penyusutan = FixedAsset::all()->groupBy('type')->map(function ($assets, $type) {
            $total = 0;
            foreach ($assets as $asset) {
                $bulan = min(
                    Carbon::parse($asset->beli)->diffInMonths(now()),
                    $asset->umur
                );

                $total += (($asset->total * 0.25) / 12) * $bulan;
            }
            return [
                'type' => $type,
                'total_penyusutan' => $total
            ];
        });
        $grandTotalPenyusutan = $penyusutan->sum('total_penyusutan');
        $ppnKel = $quotation * 11 / 100;
        $prive = DetailExpense::where('id_account', 51)->sum('amount');

        $startStringYear = $start->translatedFormat('j M');
        $startString = $start->translatedFormat('j M Y');
        $endString = $end->translatedFormat('j M Y');
        return view('pages.finance.cashflow.print', compact(
            'startDate',
            'endDate',
            'startString',
            'startStringYear',
            'endString',
            'piutang',
            'asset',
            'income',
            'ppnMas',
            'ppnKel',
            'totalFixed',
            'fixedAsset',
            'penyusutan',
            'quotation',
            'pendapatan',
            'expensePerAccount',
            'biaya',
            'prive',
            // 'labaBulanIni',
            // 'labaTahunLalu',
            // 'labaTahunTahun',
            'grandTotalPenyusutan',
            'month'
        ));
    }
    public function printTahunCashflow($year)
    {
        $startDate = Carbon::create($year, 1, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::create($year, 12, 1)->endOfMonth()->toDateString();
        $start = Carbon::create($year, 1, 1)->startOfMonth();
        $end = Carbon::today();
        $grandTotalPenyusutan = 0;

        $piutang = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')->join('client as c', 'p.id_client', '=', 'c.id')
            ->whereBetween('po_date', [$startDate, $endDate])
            ->where('payment.type', 'Tempo')
            ->where('payment.level', 0)
            ->whereNotNULL('payment.due_date')
            ->groupBy('payment.id')
            ->sum('payment.amount');
        $replace = DetailProduct::all();
        $asset = $replace->sum(function ($replacement) {
            return $replacement->modal * $replacement->stock;
        });
        $pIn = ProductIn::where('tax', '11')->whereBetween('date', [$startDate, $endDate])->sum('total');
        $ppnMas = $pIn * 11 / 100;
        $totalFixed = FixedAsset::sum('total');
        $fixedAsset = FixedAsset::select('type', DB::raw('SUM(total) as total_amount'))
            ->groupBy('type')
            ->get();
        $penyusutan = FixedAsset::all()->groupBy('type')->map(function ($assets, $type) {
            $total = 0;
            foreach ($assets as $asset) {
                $bulan = min(
                    Carbon::parse($asset->beli)->diffInMonths(now()),
                    $asset->umur
                );

                $total += (($asset->total * 0.25) / 12) * $bulan;
            }
            return [
                'type' => $type,
                'total_penyusutan' => $total
            ];
        });
        $grandTotalPenyusutan = $penyusutan->sum('total_penyusutan');
        $quotation = Quotation::where('status', '100')->whereBetween('po_date', [$startDate, $endDate])->where('level', '1')->where('is_primary', '1')->sum('nett');
        $pendapatan = LabaRugi::whereBetween('date', [$start, $end])
            ->where('type', 'Pendapatan Lain')
            ->get();
        $income = $pendapatan->sum('amount');
        // dd($endDate);
        $ppnKel = $quotation * 11 / 100;
        $prive = DetailExpense::where('id_account', 51)->sum('amount');
        $expensePerAccount = DB::table('detail_expense')
            ->join('expense as e', 'e.id', '=', 'detail_expense.id_expense')
            ->join('account', 'account.id', '=', 'detail_expense.id_account')
            ->whereBetween('e.date', [$startDate, $endDate])
            ->select(
                'account.name',
                DB::raw('SUM(detail_expense.amount) as total_amount')
            )
            ->groupBy('detail_expense.id_account', 'account.name')
            ->get();
        $expenseSum = $expensePerAccount->sum('total_amount');

        $labaTahunIni = $this->hitungLabaTahunan($year);
        $labaTahunLalu = $this->hitungLabaTahunan($year - 1);
        $labaTahunTahun = $this->hitungLabaTahunSebelumnya($year, month: 12);

        $startStringYear = $start->translatedFormat('j M');
        $startString = $start->translatedFormat('j M Y');
        $endString = $end->translatedFormat('j M Y');
        return view('pages.finance.cashflow.print', compact(
            'startDate',
            'endDate',
            'startString',
            'startStringYear',
            'endString',
            'piutang',
            'asset',
            'income',
            'pendapatan',
            'expensePerAccount  ',
            'ppnMas',
            'ppnKel',
            'totalFixed',
            'fixedAsset',
            'penyusutan',
            'quotation',
            'prive',
            'labaTahunIni',
            'labaTahunLalu',
            'labaTahunTahun',
            'grandTotalPenyusutan'
        ));
    }

    private function hitungLabaTahunan($year)
    {
        $start = Carbon::create($year, 1, 1)->startOfYear();
        $end = Carbon::create($year, 12, 31)->endOfYear();

        $po = Quotation::whereBetween('po_date', [$start, $end])
            ->where('status', '100')
            ->where('level', '1')
            ->where('is_primary', '1')
            ->sum('nett');

        $modal = Quotation::join('detail_quotation', 'quotation.id', '=', 'detail_quotation.id_quotation')
            ->join('serial_product', 'detail_quotation.id_equivalent', '=', 'serial_product.id')
            ->whereBetween('quotation.po_date', [$start, $end])
            ->where('quotation.status', '100')
            ->where('quotation.level', '1')
            ->where('quotation.is_primary', '1')
            ->sum('serial_product.price');

        $expense = detailExpense::join('expense as e', 'e.id', '=', 'detail_expense.id_expense')
            ->whereBetween('e.date', [$start, $end])
            ->sum('detail_expense.amount');

        $income = LabaRugi::whereBetween('date', [$start, $end])
            ->where('type', 'Pendapatan Lain')
            ->sum('amount');

        $charge = LabaRugi::whereBetween('date', [$start, $end])
            ->where('type', 'Beban Lain')
            ->sum('amount');

        return $po - $modal - $expense + $income - $charge;
    }
    private function hitungLabaBulanan($year, $month)
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 31)->endOfMonth();

        $po = Quotation::whereBetween('po_date', [$start, $end])
            ->where('status', '100')
            ->where('level', '1')
            ->where('is_primary', '1')
            ->sum('nett');

        $modal = Quotation::join('detail_quotation', 'quotation.id', '=', 'detail_quotation.id_quotation')
            ->join('serial_product', 'detail_quotation.id_equivalent', '=', 'serial_product.id')
            ->whereBetween('quotation.po_date', [$start, $end])
            ->where('quotation.status', '100')
            ->where('quotation.level', '1')
            ->where('quotation.is_primary', '1')
            ->sum('serial_product.price');

        $expense = detailExpense::join('expense as e', 'e.id', '=', 'detail_expense.id_expense')
            ->whereBetween('e.date', [$start, $end])
            ->sum('detail_expense.amount');

        $income = LabaRugi::whereBetween('date', [$start, $end])
            ->where('type', 'Pendapatan Lain')
            ->sum('amount');

        $charge = LabaRugi::whereBetween('date', [$start, $end])
            ->where('type', 'Beban Lain')
            ->sum('amount');

        return $po - $modal - $expense + $income - $charge;
    }
    private function hitungLabaTahunSebelumnya($year, $month)
    {
        $start = Carbon::create(2020, 1, 1)->startOfYear();
        $end = Carbon::create($year, $month, 31)->endOfYear();

        $po = Quotation::whereBetween('po_date', [$start, $end])
            ->where('status', '100')
            ->where('level', '1')
            ->where('is_primary', '1')
            ->sum('nett');

        $modal = Quotation::join('detail_quotation', 'quotation.id', '=', 'detail_quotation.id_quotation')
            ->join('serial_product', 'detail_quotation.id_equivalent', '=', 'serial_product.id')
            ->whereBetween('quotation.po_date', [$start, $end])
            ->where('quotation.status', '100')
            ->where('quotation.level', '1')
            ->where('quotation.is_primary', '1')
            ->sum('serial_product.price');

        $expense = detailExpense::join('expense as e', 'e.id', '=', 'detail_expense.id_expense')
            ->whereBetween('e.date', [$start, $end])
            ->sum('detail_expense.amount');

        $income = LabaRugi::whereBetween('date', [$start, $end])
            ->where('type', 'Pendapatan Lain')
            ->sum('amount');

        $charge = LabaRugi::whereBetween('date', [$start, $end])
            ->where('type', 'Beban Lain')
            ->sum('amount');

        return $po - $modal - $expense + $income - $charge;
    }
    private function hitungLabaBulanSebelumnya($year, $month)
    {
        $start = Carbon::create($year, 1, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 31)->endOfMonth();

        $po = Quotation::whereBetween('po_date', [$start, $end])
            ->where('status', '100')
            ->where('level', '1')
            ->where('is_primary', '1')
            ->sum('nett');

        $modal = Quotation::join('detail_quotation', 'quotation.id', '=', 'detail_quotation.id_quotation')
            ->join('serial_product', 'detail_quotation.id_equivalent', '=', 'serial_product.id')
            ->whereBetween('quotation.po_date', [$start, $end])
            ->where('quotation.status', '100')
            ->where('quotation.level', '1')
            ->where('quotation.is_primary', '1')
            ->sum('serial_product.price');

        $expense = detailExpense::join('expense as e', 'e.id', '=', 'detail_expense.id_expense')
            ->whereBetween('e.date', [$start, $end])
            ->sum('detail_expense.amount');

        $income = LabaRugi::whereBetween('date', [$start, $end])
            ->where('type', 'Pendapatan Lain')
            ->sum('amount');

        $charge = LabaRugi::whereBetween('date', [$start, $end])
            ->where('type', 'Beban Lain')
            ->sum('amount');

        return $po - $modal - $expense + $income - $charge;
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
