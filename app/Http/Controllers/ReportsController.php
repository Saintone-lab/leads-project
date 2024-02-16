<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Quotation;
use App\Models\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        $dataDc = $this->getWeekDataDC();
        $dataQuote = $this->getWeekDataQuote();
        $dataPo = $this->getWeekDataPo();
        $target = Target::where('id_sales', Auth::user()->id)->first();
        // dd($dataQuote);
        $quotation = Quotation::where('status', '100')->where('id_sales', Auth::user()->id)->get();
        return view("pages.sales.report.index", compact("quotation", "dataDc", "dataQuote", "dataPo", "target"));
    }

    protected function getWeekDataDC()
    {
        $dateNow = Carbon::now();
        $yearNow = $dateNow->year;
        $monthNow = $dateNow->month;
        $firstDayOfMonth = "{$yearNow}-{$monthNow}-01";
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

        $firstDayOfWeek = date('N', strtotime($firstDayOfMonth));
        $weekEnd = date('W', strtotime($firstDayOfMonth));
        $endWeek = date('W', strtotime($lastDayOfMonth));
        $weekStart = $firstDayOfWeek > 1 ? $weekEnd + 1 : $weekEnd;

        $dCallPerWeek = Activities::select(DB::raw('CONCAT(YEAR(date), "-", MONTH(date), "-W", WEEK(date, 4)) as date'), DB::raw('WEEK(date, 4) as week'), DB::raw('COUNT(*) as total'))
            ->join('client as c', 'activities.id_client', '=', 'c.id')
            ->join('users as u', 'c.id_sales', '=', 'u.id')
            ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
            ->where('id_sales', Auth::user()->id)
            ->where('status', 'Responded')
            ->groupBy('week')
            ->orderBy('week')
            ->pluck('total', 'week');

        $fullMonthData = [];
        for ($week = $weekStart; $week <= $endWeek; $week++) {
            $weekKey = "{$week}";

            $weekDays = date('t', strtotime($weekKey));
            if ($weekDays >= 4) {
                $fullMonthData[$weekKey] = [
                    'week' => $weekKey,
                    'total' => isset($dCallPerWeek[$weekKey]) ? $dCallPerWeek[$weekKey] : 0,
                ];
            }
        }
        // dd($fullMonthData);

        return $fullMonthData;
    }
    protected function getWeekDataQuote()
    {
        $dateNow = Carbon::now();
        $yearNow = $dateNow->year;
        $monthNow = $dateNow->month;
        $firstDayOfMonth = "{$yearNow}-{$monthNow}-01";
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

        $firstDayOfWeek = date('N', strtotime($firstDayOfMonth));
        $weekEnd = date('W', strtotime($firstDayOfMonth));
        $endWeek = date('W', strtotime($lastDayOfMonth));
        $weekStart = $firstDayOfWeek > 1 ? $weekEnd + 1 : $weekEnd;

        $dCallPerWeek = Quotation::select(DB::raw('CONCAT(YEAR(estimated_date), "-", MONTH(estimated_date), "-W", WEEK(estimated_date, 4)) as estimated_date'), DB::raw('WEEK(estimated_date, 4) as week'), DB::raw('COUNT(*) as total'))
            ->whereBetween('estimated_date', [$firstDayOfMonth, $lastDayOfMonth])
            ->where('id_sales', Auth::user()->id)
            ->groupBy('week')
            ->orderBy('week')
            ->pluck('total', 'week');
        $fullMonthData = [];
        for ($week = $weekStart; $week <= $endWeek; $week++) {
            $weekKey = "{$week}";

            $weekDays = date('t', strtotime($weekKey));
            if ($weekDays >= 4) {
                $fullMonthData[$weekKey] = [
                    'week' => $weekKey,
                    'total' => isset($dCallPerWeek[$weekKey]) ? $dCallPerWeek[$weekKey] : 0,
                ];
            }
        }
        return $fullMonthData;
    }
    protected function getWeekDataPo()
    {
        $dateNow = Carbon::now();
        $yearNow = $dateNow->year;
        $monthNow = $dateNow->month;
        $firstDayOfMonth = "{$yearNow}-{$monthNow}-01";
        $lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

        $firstDayOfWeek = date('N', strtotime($firstDayOfMonth));
        $weekEnd = date('W', strtotime($firstDayOfMonth));
        $endWeek = date('W', strtotime($lastDayOfMonth));
        $weekStart = $firstDayOfWeek > 1 ? $weekEnd + 1 : $weekEnd;

        $dCallPerWeek = Quotation::select(DB::raw('CONCAT(YEAR(po_date), "-", MONTH(po_date), "-W", WEEK(po_date, 4)) as po_date'), DB::raw('WEEK(po_date, 4) as week'), DB::raw('COUNT(*) as total'))
            ->whereBetween('po_date', [$firstDayOfMonth, $lastDayOfMonth])
            ->where('id_sales', Auth::user()->id)
            ->where('status', '100')
            ->groupBy('week')
            ->orderBy('week')
            ->pluck('total', 'week');
        $fullMonthData = [];
        for ($week = $weekStart; $week <= $endWeek; $week++) {
            $weekKey = "{$week}";

            $weekDays = date('t', strtotime($weekKey));
            if ($weekDays >= 4) {
                $fullMonthData[$weekKey] = [
                    'week' => $weekKey,
                    'total' => isset($dCallPerWeek[$weekKey]) ? $dCallPerWeek[$weekKey] : 0,
                ];
            }
        }
        return $fullMonthData;
    }
}
