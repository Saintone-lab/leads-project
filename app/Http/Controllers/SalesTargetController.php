<?php

namespace App\Http\Controllers;

use App\Models\SalesReports;
use App\Models\SalesTargetHistory;
use App\Models\Target;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesTargetController extends Controller
{
    public function index(Request $request)
    {
        $years = SalesReports::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $currentYear = (int) ($request->year ?? ($years->first() ?? date('Y')));

        $salesUsers = User::where('role', 'Sales')
            ->where('active', '1')
            ->orderBy('name')
            ->get();

        // Target tahunan per sales untuk tahun yang dipilih
        $yearTargets = SalesTargetHistory::where('year', $currentYear)
            ->pluck('target_annual', 'user_id');

        // Semester records untuk aggregate target (S1 & S2)
        $semesterRecords = SalesReports::where('year', $currentYear)
            ->orderBy('semester')
            ->get()
            ->keyBy('semester');

        // Semua histori per sales (untuk panel histori)
        $allHistories = SalesTargetHistory::whereIn('user_id', $salesUsers->pluck('id'))
            ->orderBy('year', 'asc')
            ->get()
            ->groupBy('user_id');

        // Total target tim per tahun (untuk hitung % kontribusi di histori)
        $teamTargetByYear = SalesTargetHistory::selectRaw('year, SUM(target_annual) as total')
            ->groupBy('year')
            ->pluck('total', 'year');

        $teamTargetThisYear = $teamTargetByYear[$currentYear] ?? 0;

        return view('pages.admin.sales-target', compact(
            'years', 'currentYear', 'salesUsers',
            'yearTargets', 'allHistories',
            'teamTargetThisYear', 'teamTargetByYear',
            'semesterRecords'
        ));
    }

    public function saveYearTargets(Request $request, $year)
    {
        $request->validate([
            'targets'   => 'required|array',
            'targets.*' => 'nullable|numeric|min:0',
        ]);

        $adminId = Auth::id();

        foreach ($request->targets as $userId => $annual) {
            $annual = (int) $annual;
            if ($annual <= 0) continue;

            SalesTargetHistory::updateOrCreate(
                ['user_id' => $userId, 'year' => $year],
                ['target_annual' => $annual, 'set_by' => $adminId]
            );

            // Sync target bulanan aktif di tabel target (untuk dashboard per-sales)
            Target::where('id_sales', $userId)->update(['total' => intval($annual / 12)]);
        }

        // Auto-update aggregate semester di sales_reports
        $totalAnnual = SalesTargetHistory::where('year', $year)->sum('target_annual');
        if ($totalAnnual > 0) {
            $semTarget = intval($totalAnnual / 2);
            foreach (['1', '2'] as $sem) {
                SalesReports::updateOrCreate(
                    ['year' => $year, 'semester' => $sem],
                    ['target' => $semTarget]
                );
            }
        }

        return back()->with('success', "Target tim tahun {$year} berhasil disimpan.");
    }

    public function saveAggregateTarget(Request $request, $year)
    {
        $request->validate([
            'target_annual' => 'required|numeric|min:1',
        ]);

        $annual    = (int) $request->target_annual;
        $semTarget = intval($annual / 2);

        foreach (['1', '2'] as $sem) {
            SalesReports::updateOrCreate(
                ['year' => $year, 'semester' => $sem],
                ['target' => $semTarget]
            );
        }

        return back()->with('success', "Target agregat tim tahun {$year} berhasil disimpan.");
    }

    public function addYear(Request $request)
    {
        $request->validate(['year' => 'required|integer|min:2020|max:2099']);
        $year = (int) $request->year;

        foreach (['1', '2'] as $sem) {
            SalesReports::firstOrCreate(['year' => $year, 'semester' => $sem]);
        }

        return redirect()->route('sales-target.index', ['year' => $year])
            ->with('success', "Tahun {$year} berhasil ditambahkan.");
    }
}
