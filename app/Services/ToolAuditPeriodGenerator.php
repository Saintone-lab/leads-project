<?php

namespace App\Services;

use App\Models\FixedAsset;
use App\Models\ToolAudit;
use App\Models\ToolAuditItem;
use App\Models\ToolAuditPeriod;
use App\Models\User;
use Carbon\Carbon;

class ToolAuditPeriodGenerator
{
    /**
     * Window audit: 10 hari terakhir bulan Juni (semester 1) & Desember (semester 2).
     * Return null kalau tanggal hari ini di luar kedua window itu.
     */
    public function activeWindow(?Carbon $date = null)
    {
        $date = $date ?? Carbon::today();
        $tahun = $date->year;

        $windows = [
            1 => Carbon::create($tahun, 6, 1)->endOfMonth(),
            2 => Carbon::create($tahun, 12, 1)->endOfMonth(),
        ];

        foreach ($windows as $semester => $endOfMonth) {
            $start = $endOfMonth->copy()->subDays(9)->startOfDay();
            $end = $endOfMonth->copy()->endOfDay();
            if ($date->between($start, $end)) {
                return [
                    'tahun' => $tahun,
                    'semester' => $semester,
                    'tanggal_mulai' => $start->toDateString(),
                    'tanggal_selesai' => $end->toDateString(),
                ];
            }
        }

        return null;
    }

    /**
     * Idempotent — aman dipanggil berkali-kali (dari cron ataupun lazy trigger
     * saat teknisi/admin buka halaman Audit Tools). Bikin tool_audit_period +
     * tool_audit (Draft) + tool_audit_item kalau belum ada, untuk tiap teknisi
     * yang punya minimal 1 tools Aktif.
     */
    public function generateIfNeeded(?Carbon $date = null): ?ToolAuditPeriod
    {
        $window = $this->activeWindow($date);
        if (!$window) {
            return null;
        }

        $period = ToolAuditPeriod::firstOrCreate(
            ['tahun' => $window['tahun'], 'semester' => $window['semester']],
            [
                'tanggal_mulai' => $window['tanggal_mulai'],
                'tanggal_selesai' => $window['tanggal_selesai'],
                'status' => 'Open',
            ]
        );

        $romawi = $window['semester'] == 1 ? 'I' : 'II';
        $technicians = User::where('role', 'Technician')->get();

        foreach ($technicians as $technician) {
            $activeTools = FixedAsset::where('type', 'Tools')
                ->where('id_pic', $technician->id)
                ->where('status_tools', 'Aktif')
                ->get();

            if ($activeTools->isEmpty()) {
                continue;
            }

            $audit = ToolAudit::firstOrCreate(
                ['id_audit_period' => $period->id, 'id_technician' => $technician->id],
                [
                    'no_audit' => ($technician->code ?? $technician->id) . '/' . $romawi . '/' . $window['tahun'],
                    'status_submit' => 'Draft',
                    'total_tools' => $activeTools->count(),
                ]
            );

            foreach ($activeTools as $tool) {
                ToolAuditItem::firstOrCreate(
                    ['id_tool_audit' => $audit->id, 'id_fixed_asset' => $tool->id],
                    ['qty_actual' => $tool->qty]
                );
            }
        }

        return $period;
    }
}
