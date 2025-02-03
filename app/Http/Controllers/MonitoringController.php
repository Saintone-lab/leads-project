<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Machine;
use App\Models\Monitoring;
use App\Models\MonitoringWeekly;
use App\Models\Pic;
use App\Models\Reports;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Str;

class MonitoringController extends Controller
{
    // button
    public function button($id)
    {
        $machine = Machine::find($id);
        $hasMonitoringToday = Monitoring::where('id_machine', $id)
            ->whereDate('created_at', Carbon::today())
            ->exists();
        return view('pages.monitoring.button', compact('machine', 'hasMonitoringToday'));
    }

    // monitoring daily
    public function indexDaily($id)
    {
        $machine = Machine::find($id);
        // dd($machine->unit);
        return view('pages.monitoring.index', compact('machine'));
    }
    public function createDaily($id)
    {
        $machine = Machine::find($id);
        $monitoring = Monitoring::where('id_machine', $id)->orderByDesc('date')->first();
        // dd($monitoring);
        return view('pages.monitoring.form', compact('machine', 'monitoring'));
    }
    public function createDailyReports($monitoring, $id)
    {
        $machine = Machine::find($id);
        $monitoring = Monitoring::where('id_machine', $id)->orderByDesc('date')->first();

        $runningRaw = str_replace('.', '', $monitoring->running);
        $loadingRaw = str_replace('.', '', $monitoring->loading);

        // Ambil angka menggunakan regex
        preg_match('/\d+/', $runningRaw, $runningMatches);
        $runningNumericValue = $runningMatches[0] ?? ''; // Hasil: 1869

        preg_match('/\d+/', $loadingRaw, $loadingMatches);
        $loadingNumericValue = $loadingMatches[0] ?? ''; // Hasil: 6244
        $clients = Client::all();
        $dateNow = Carbon::now();
        $numberS = Reports::whereYear('date', $dateNow)->where('id_technician', Auth::user()->id)->count();
        $formattedNumberS = str_pad($numberS + 1, 3, '0', STR_PAD_LEFT);
        // dd($formattedNumberS);
        $monthNow = $dateNow->month;
        $formattedMonthNow = $this->convertToRoman($monthNow);
        $pic = Pic::join('client as c', 'c.id', '=', 'pic.id_client')->where('c.role', '=', 'Customers')->where('c.id', '=', '1277')->select('pic.*')->get();
        return view('pages.monitoring.form-service-reports', compact('machine', 'monitoring', 'pic', 'formattedNumberS', 'formattedMonthNow', 'runningNumericValue', 'loadingNumericValue'));
    }
    public function storeDaily(Request $request, $id)
    {
        // dd($request->all());
        $machine = Machine::find($id);
        // dd($machine->unit->unit->unit);
        $monitoring = new Monitoring();
        $monitoring->id_machine = $id;
        $monitoring->id_pic = Auth::user()->id;
        $monitoring->condition = $request->condition;
        if ($machine->unit->unit->unit != 'REFRIGERANT AIR DRYER') {
            $monitoring->running = number_format($request->running, 0, ',', '.') . ' Hour';
            $monitoring->loading = number_format($request->loading, 0, ',', '.') . ' Hour';
            if (is_numeric($request->pressure) && strpos($request->pressure, ',') === false) {
                $monitoring->pressure = $request->pressure . ',0' . ' Bar';
            } else {
                $monitoring->pressure = $request->pressure . ' Bar';
            }
            $monitoring->temp = $request->temperature . " °C";
            $monitoring->oil_level = $request->oil;
        } else {
            $monitoring->dew = $request->dew;
            $monitoring->drain = $request->drain;
            $monitoring->cleaning = $request->cleaning;
            $monitoring->temp = $request->temperature_in . " °C";
            $monitoring->temp_out = $request->temperature_out . " °C";
        }
        if ($request->main_desc == NULL) {
            $monitoring->main_desc = NULL;
            $monitoring->main_next = NULL;
            $monitoring->technician = NULL;
        } else {
            $monitoring->main_desc = $request->main_desc;
            $monitoring->main_next = $request->main_next;
            $monitoring->technician = $request->technician;
        }
        $monitoring->desc = $request->desc;
        $monitoring->date = Carbon::today();
        if ($request->hasFile('picture')) {
            // $foto = $request->file('picture'); // Akses file sesuai dengan iterasi saat ini
            // // Proses setiap file gambar
            // $picture_ext = $foto->getClientOriginalExtension();
            // $picture_name = Str::random(8);

            // $machine_brand = $machine->brand;
            // $machine_type = $machine->type;
            // $upload_dir = public_path('asset/machines/' . $machine_brand . '-' . $machine_type);

            // if (!file_exists($upload_dir)) {
            //     mkdir($upload_dir, 0777, true);
            // }

            // $picturename = $upload_dir . '/' . $picture_name . '.' . $picture_ext;

            // // Pemrosesan gambar
            // $img = Image::make($foto->path());
            // $img->fit(500, 800, function ($constraint) {
            //     $constraint->aspectRatio();
            // });
            // $img->save($picturename);

            // $monitoring['picture'] = $picturename;

            $foto = $request->file('picture');
            $foto_ext = $foto->getClientOriginalExtension();
            $foto_name = Str::random(8);

            $machine_brand = $machine->unit->brand;
            $machine_type = $machine->unit->type;

            // Direktori upload
            $upload_dir = public_path('asset/machines/' . $machine_brand . '-' . $machine_type);
            $upload_path = 'asset/machines';
            $imagename = $upload_path . '/' . $machine_brand . '-' . $machine_type . '/' . $foto_name . '.' . $foto_ext;

            // Cek apakah direktori sudah ada
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Proses manipulasi dan simpan gambar
            $img = Image::make($foto->path());
            $img->fit(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($upload_dir . '/' . $foto_name . '.' . $foto_ext);

            // Simpan path ke database atau variabel monitoring
            $monitoring['picture'] = $imagename;

        }
        $monitorSave = $monitoring->save();
        if ($monitorSave) {
            return redirect('/monitoring/daily/' . $id)->with('success', 'Data telah di buat');
        }
    }
    public function storeService(Request $request, $monitoring, $id)
    {
        $rule = [
            'no_service => required',
            'running => required',
            'load => required',
            'jobdesc => required',
            'desc => required',
        ];
        $customMessages = [
            'no_service.required' => 'Field No Service Wajib Diisi!',
            'running.required' => 'Field Running Wajib Diisi!',
            'load.required' => 'Field Load Wajib Diisi!',
            'jobdesc.required' => 'Field Jobdesc Wajib Diisi!',
            'desc.required' => 'Field desc Wajib Diisi!',
        ];

        $this->validate($request, $rule, $customMessages);
        // dd($request);
        // Masukan Data ke Service Reports
        $reports = new Reports();
        $reports->id_technician = Auth::user()->id;
        $reports->id_pic = $request->id_pic;
        $reports->id_machine = $id;
        $reports->id_monitoring = $monitoring;
        $reports->no_service = $request->no_service;
        $reports->type = $request->type;
        $reports->running = $request->running;
        $reports->load = $request->load;
        $reports->date = $request->date;
        $reports->jobdesc = $request->jobdesc;
        $reports->desc = $request->desc;
        $reports->recomendation = $request->recomendation;
        $reports->sign_client = NULL;
        $status = $reports->save();
        // dd($reports);
        if ($status) {
            return redirect('service-reports/' . $reports->id)->with('success', 'Data Has been created');
        }
    }

    public function storeMainLog(Request $request, $id)
    {
        $monitoring = Monitoring::find($id);
        $machine = Machine::find($monitoring->id_machine);
        // dd($monitoring);
        $monitoring->main_desc = $request->main_desc;
        $monitoring->main_next = $request->main_next;
        $monitoring->technician = $request->technician;
        $monitorSave = $monitoring->save();
        if ($monitorSave) {
            return redirect('/monitoring-service-create/' . $monitoring->id . '/' . $machine->id)->with('message', 'Data telah di save');
        }
    }
    public function visitorDaily($id)
    {
        $machine = Machine::find($id);
        $client = Client::find($machine->id_client);

        $today = Carbon::today();

        $startOfMonth = $today->copy()->startOfMonth();
        $startOfMonthDate = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $dates = [];
        for ($date = $startOfMonthDate; $date->lte($endOfMonth); $date->addDay()) {
            $dates[] = $date->format('d-m-Y');
        }
        // dd($dates);

        // Ambil data monitoring dari database
        $monitoringData = Monitoring::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('id_machine', $id)
            ->join('users as u', 'u.id', '=', 'monitoring.id_pic')
            ->get(['monitoring.*', 'u.name'])
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date)->format('d-m-Y'); // Format tanggal
                return $item;
            });

        $compressorIndexed = $monitoringData->keyBy('date')->map(function ($item) {
            return [
                'id' => $item->id ?? '-',
                'running' => $item->running ?? '-',
                'loading' => $item->loading ?? '-',
                'pressure' => $item->pressure ?? '-',
                'temp' => $item->temp ?? '-',
                'condition' => $item->condition ?? '-',
                'oil_level' => $item->oil_level ?? '-',
                'pic' => $item->name ?? '-',
            ];
        })->toArray();

        // Gabungkan data monitoring dengan daftar tanggal
        $compressor = [];
        foreach ($dates as $date) {
            $compressor[] = [
                'date' => $date,
                'id' => $compressorIndexed[$date]['id'] ?? '-',
                'running' => $compressorIndexed[$date]['running'] ?? '-',
                'loading' => $compressorIndexed[$date]['loading'] ?? '-',
                'pressure' => $compressorIndexed[$date]['pressure'] ?? '-',
                'temp' => $compressorIndexed[$date]['temp'] ?? '-',
                'condition' => $compressorIndexed[$date]['condition'] ?? '-',
                'oil_level' => $compressorIndexed[$date]['oil_level'] ?? '-',
                'pic' => $compressorIndexed[$date]['pic'] ?? '-',
            ];
        }

        $dryerIndexed = $monitoringData->keyBy('date')->map(function ($item) {
            return [
                'id' => $item->id ?? '-',
                'temp' => $item->temp ?? '-',
                'temp_out' => $item->temp_out ?? '-',
                'dew' => $item->dew ?? '-',
                'drain' => $item->drain ?? '-',
                'condition' => $item->condition ?? '-',
                'oil_level' => $item->oil_level ?? '-',
                'desc' => $item->desc ?? '-',
                'pic' => $item->name ?? '-',
            ];
        })->toArray();

        // Gabungkan data monitoring dengan daftar tanggal
        $dryer = [];
        foreach ($dates as $date) {
            $dryer[] = [
                'date' => $date,
                'id' => $dryerIndexed[$date]['id'] ?? '-',
                'temp' => $dryerIndexed[$date]['temp'] ?? '-',
                'temp_out' => $dryerIndexed[$date]['temp_out'] ?? '-',
                'dew' => $dryerIndexed[$date]['dew'] ?? '-',
                'drain' => $dryerIndexed[$date]['drain'] ?? '-',
                'condition' => $dryerIndexed[$date]['condition'] ?? '-',
                'oil_level' => $dryerIndexed[$date]['oil_level'] ?? '-',
                'desc' => $dryerIndexed[$date]['desc'] ?? '-',
                'pic' => $dryerIndexed[$date]['pic'] ?? '-',
            ];
        }
        // dd($compressor);

        // Return data
        $monitoringThisMonth = response()->json($compressor);

        return view('pages.monitoring.visitor', compact('machine', 'client', 'compressor', 'dryer'));
    }

    public function logDaily($id)
    {
        $machine = Machine::find($id);
        $client = Client::find($machine->id_client);

        $today = Carbon::today();

        $monitoring = Monitoring::whereNotNULL('main_desc')->where('main_desc', '!=', '-')->whereMonth('date', $today->month)->where('id_machine', $id)->get();
        $issue = Monitoring::whereNotNULL('desc')->where('desc', '!=', '-')->whereMonth('date', $today->month)->where('id_machine', $id)->get();
        // dd($monitoring);

        return view('pages.monitoring.maintenance-log', compact('machine', 'client', 'monitoring', 'issue'));
    }

    // monitoring daily
    public function indexWeekly($id)
    {
        $machine = Machine::find($id);
        // dd($machine->unit);
        return view('pages.monitoring.index-weekly', compact('machine'));
    }
    // Monitoring Weekly
    public function createWeekly($id)
    {
        $today = Carbon::now(); // Hari ini
        $weekNumber = intval($this->getWeekNumberFromDate($today));
        $machine = Machine::find($id);
        // dd($weekNumber);
        return view('pages.monitoring.form-weekly', compact('machine', 'weekNumber'));
    }
    public function storeWeekly(Request $request, $id)
    {
        // dd($request->all());
        $machine = Machine::find($id);
        // dd($machine->unit->unit->unit);
        $monitoring = new MonitoringWeekly();
        $monitoring->id_machine = $id;
        $monitoring->id_pic = Auth::user()->id;
        $monitoring->ampere = $request->ampere . ' A';
        $monitoring->voltage = $request->voltage . ' V';
        $monitoring->pm = $request->pm;
        $monitoring->week = $request->week;
        $monitoring->remark = $request->remark;
        if ($machine->unit->unit->unit != 'REFRIGERANT AIR DRYER') {
            $monitoring->idle = $request->idle . ' A';
            $monitoring->vibration = $request->vibration;
        } else {
            $monitoring->dew = $request->dew;
            $monitoring->drain = $request->drain;
            $monitoring->pre = $request->pre;
            $monitoring->after = $request->after;
        }
        $monitoring->desc = $request->desc;
        $monitoring->date = Carbon::today();
        $monitorSave = $monitoring->save();
        if ($monitorSave) {
            return redirect('/monitoring/weekly/' . $id)->with('message', 'Data telah diibuat');
        }
    }
    public function visitorWeekly($id)
    {
        $machine = Machine::find($id);
        $client = Client::find($machine->id_client);
        $machineC = Client::join('machine as m', 'client.id', '=', 'm.id_client')->groupBy('client.id')->get('m.*');
        $monitoringAC = Machine::leftJoin('monitoring_weekly as mw', 'mw.id_machine', '=', 'machine.id')
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'mw.id_pic')
            ->where('u.unit', 'AIR COMPRESSOR SCREW')
            ->where('machine.id_client', $client->id)
            ->select('machine.*', 'mw.*', 'us.name', 'machine.id as idM')
            ->groupBy('machine.id')
            ->get();
        $monitoringDRYER = Machine::leftJoin('monitoring_weekly as mw', 'mw.id_machine', '=', 'machine.id')
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'mw.id_pic')
            ->where('u.unit', 'REFRIGERANT AIR DRYER')
            ->where('machine.id_client', $client->id)
            ->select('machine.*', 'mw.*', 'us.name', 'machine.id as idM')
            ->groupBy('machine.id')
            ->get();
        // dd($monitoringDRYER);
        return view('pages.monitoring.visitor-weekly', compact('machine', 'client', 'monitoringAC', 'monitoringDRYER'));
    }

    // get data monitoring
    public function getMonitoringCompressorThisMonth($id)
    {
        $today = Carbon::today();
        $machine = Machine::find($id);

        $startOfMonth = $today->copy()->startOfMonth();
        $startOfMonthDate = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $dates = [];
        for ($date = $startOfMonthDate; $date->lte($endOfMonth); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }
        // dd($dates);

        // Ambil data monitoring dari database
        $monitoringData = Monitoring::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('id_machine', $id)
            ->get(); // Format: ['2024-11-01' => '100']

        $monitoringIndexed = $monitoringData->keyBy('date')->map(function ($item) {
            return [
                'id' => $item->id ?? '-',
                'runing' => $item->runing ?? '-',
                'load' => $item->load ?? '-',
                'pressure' => $item->pressure ?? '-',
                'temp' => $item->temp ?? '-',
                'condition' => $item->condition ?? '-',
                'oil_level' => $item->oil_level ?? '-',
                'desc' => $item->desc ?? '-',
            ];
        })->toArray();

        // Gabungkan data monitoring dengan daftar tanggal
        $result = [];
        foreach ($dates as $date) {
            $result[] = [
                'date' => $date,
                'id' => $monitoringIndexed[$date]['id'] ?? '-',
                'runing' => $monitoringIndexed[$date]['runing'] ?? '-',
                'load' => $monitoringIndexed[$date]['load'] ?? '-',
                'pressure' => $monitoringIndexed[$date]['pressure'] ?? '-',
                'temp' => $monitoringIndexed[$date]['temp'] ?? '-',
                'condition' => $monitoringIndexed[$date]['condition'] ?? '-',
                'oil_level' => $monitoringIndexed[$date]['oil_level'] ?? '-',
                'desc' => $monitoringIndexed[$date]['desc'] ?? '-',
            ];
        }

        // Return data
        return response()->json(['data' => $result]);
    }
    public function getMonitoringDryerThisMonth($id)
    {
        $today = Carbon::today();
        $machine = Machine::find($id);

        $startOfMonth = $today->copy()->startOfMonth();
        $startOfMonthDate = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $dates = [];
        for ($date = $startOfMonthDate; $date->lte($endOfMonth); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }
        // dd($dates);

        // Ambil data monitoring dari database
        $monitoringData = Monitoring::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('id_machine', $id)
            ->get(); // Format: ['2024-11-01' => '100']
        $monitoringIndexed = $monitoringData->keyBy('date')->map(function ($item) {
            return [
                'id' => $item->id ?? '-',
                'temp' => $item->temp ?? '-',
                'temp_out' => $item->temp_out ?? '-',
                'dew' => $item->dew ?? '-',
                'drain' => $item->drain ?? '-',
                'condition' => $item->condition ?? '-',
                'oil_level' => $item->oil_level ?? '-',
                'desc' => $item->desc ?? '-',
            ];
        })->toArray();

        // Gabungkan data monitoring dengan daftar tanggal
        $result = [];
        foreach ($dates as $date) {
            $result[] = [
                'date' => $date,
                'id' => $monitoringIndexed[$date]['id'] ?? '-',
                'temp' => $monitoringIndexed[$date]['temp'] ?? '-',
                'temp_out' => $monitoringIndexed[$date]['temp_out'] ?? '-',
                'dew' => $monitoringIndexed[$date]['dew'] ?? '-',
                'drain' => $monitoringIndexed[$date]['drain'] ?? '-',
                'condition' => $monitoringIndexed[$date]['condition'] ?? '-',
                'oil_level' => $monitoringIndexed[$date]['oil_level'] ?? '-',
                'desc' => $monitoringIndexed[$date]['desc'] ?? '-',
            ];
        }

        // Return data
        return response()->json(['data' => $result]);
    }

    // service Manager
    public function indexServiceM()
    {
        $allPlant = Machine::where('id_client', 1277)->count();
        $allPlantMonitoring = Machine::where('id_client', 1277)
            ->whereHas('monitoring', function ($query) {
                $query->whereDate('date', Carbon::today());
            })->count();
        $GT = Machine::where('id_client', 1277)->where('location', 'GT 1-2')->count();
        $GTMonitoring = Machine::where('id_client', 1277)->where('location', 'GT 1-2')
            ->whereHas('monitoring', function ($query) {
                $query->whereDate('date', Carbon::today());
            })->count();
        $GT3 = Machine::where('id_client', 1277)->where('location', 'GT3/BOILER')->count();
        $GT3Monitoring = Machine::where('id_client', 1277)->where('location', 'GT3/BOILER')
            ->whereHas('monitoring', function ($query) {
                $query->whereDate('date', Carbon::today());
            })->count();
        $INC = Machine::where('id_client', 1277)->where('location', 'INC')->count();
        $INCMonitoring = Machine::where('id_client', 1277)->where('location', 'INC')
            ->whereHas('monitoring', function ($query) {
                $query->whereDate('date', Carbon::today());
            })->count();
        $PM12 = Machine::where('id_client', 1277)->where('location', 'PM 1-2')->count();
        $PM12Monitoring = Machine::where('id_client', 1277)->where('location', 'PM 1-2')
            ->whereHas('monitoring', function ($query) {
                $query->whereDate('date', Carbon::today());
            })->count();
        $PM35 = Machine::where('id_client', 1277)->whereBetween('location', ['PM 3', 'PM 5'])->count();
        $PM35Monitoring = Machine::where('id_client', 1277)->whereBetween('location', ['PM 3', 'PM 5'])
            ->whereHas('monitoring', function ($query) {
                $query->whereDate('date', Carbon::today());
            })->count();
        $PM78 = Machine::where('id_client', 1277)->whereBetween('location', ['PM 7', 'PM 8'])->count();
        $PM78Monitoring = Machine::where('id_client', 1277)->whereBetween('location', ['PM 7', 'PM 8'])
            ->whereHas('monitoring', function ($query) {
                $query->whereDate('date', Carbon::today());
            })->count();


        $day = Carbon::today();
        $month = $day->month;
        $year = $day->year;

        $machines = Machine::with([
            'unit',
            'unit.unit',
            'monitoring' => function ($query) use ($month, $year) {
                $query->whereMonth('date', $month)
                    ->whereYear('date', $year);
            }
        ])->where('id_client', 1277)->get();

        $result = $machines->filter(function ($machine) {
            return $machine->monitoring->isNotEmpty();
        })->map(function ($machine) {
            return [
                'machine' => $machine->unit->brand . ' ' . $machine->unit->unit->sku . ' - ' . $machine->tag . ' - ' . $machine->location,
                'id' => $machine->id,
                'log' => $machine->monitoring->filter(function ($log) {
                    return !is_null($log->desc) && $log->desc !== '-' && $log->desc !== 'normal' && $log->desc !== 'Normal';
                })->map(function ($log) {
                    return [
                        'log' => $log->desc,
                        'date' => \Carbon\Carbon::parse($log->date)->format('d'),
                        'pic' => $log->pic->name
                    ];
                }),
                'mainlog' => $machine->monitoring->filter(function ($mainlog) {
                    return !is_null($mainlog->main_desc) && $mainlog->main_desc !== '-';
                })->map(function ($mainlog) {
                    return [
                        'id' => $mainlog->id,
                        'id_pic' => $mainlog->id_pic,
                        'id_machine' => $mainlog->machine->id,
                        'id_service' => $mainlog->reports->first()->id ?? NULL,
                        'log' => $mainlog->main_desc,
                        'date' => \Carbon\Carbon::parse($mainlog->date)->format('d'),
                        'technician' => $mainlog->technician
                    ];
                }),
            ];
        });

        return view('pages.monitoring.service-index', compact('month', 'year', 'allPlant', 'allPlantMonitoring', 'GT', 'GTMonitoring', 'GT3', 'GT3Monitoring', 'INC', 'INCMonitoring', 'PM12', 'PM12Monitoring', 'PM35', 'PM35Monitoring', 'PM78', 'PM78Monitoring', 'result'));
    }
    public function showServiceM($id)
    {
        $machine = Machine::find($id);
        return view('pages.monitoring.service-detail', compact('machine'));
    }
    public function visitorDailyService($id, $month)
    {
        $machine = Machine::find($id);
        $months = $month;
        // dd($months);
        $client = Client::find($machine->id_client);

        $setday = Carbon::today();
        $today = $setday->setMonth($month);
        $year = $today->year;

        $startOfMonth = $today->copy()->startOfMonth();
        $startOfMonthDate = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $dates = [];
        for ($date = $startOfMonthDate; $date->lte($endOfMonth); $date->addDay()) {
            $dates[] = $date->format('d-m-Y');
        }
        // dd($dates);

        // Ambil data monitoring dari database
        $monitoringData = Monitoring::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('id_machine', $id)
            ->join('users as u', 'u.id', '=', 'monitoring.id_pic')
            ->join('machine as m', 'm.id', '=', 'monitoring.id_machine')
            ->get(['monitoring.*', 'u.name', 'm.id as id_machine'])
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date)->format('d-m-Y'); // Format tanggal
                return $item;
            });
        // dd($monitoringData);

        $compressorIndexed = $monitoringData->keyBy('date')->map(function ($item) {
            return [
                'id' => $item->id ?? '-',
                'id_machine' => $item->id_machine ?? '-',
                'running' => $item->running ?? '-',
                'loading' => $item->loading ?? '-',
                'pressure' => $item->pressure ?? '-',
                'temp' => $item->temp ?? '-',
                'condition' => $item->condition ?? '-',
                'oil_level' => $item->oil_level ?? '-',
                'pic' => $item->name ?? '-',
                'desc' => $item->desc ?? '-',
                'main_desc' => $item->main_desc ?? '-',
            ];
        })->toArray();
        // dd($compressorIndexed);

        // Gabungkan data monitoring dengan daftar tanggal
        $compressor = [];
        foreach ($dates as $date) {
            $compressor[] = [
                'date' => $date,
                'id' => $compressorIndexed[$date]['id'] ?? '-',
                'id_machine' => $compressorIndexed[$date]['id_machine'] ?? '-',
                'running' => $compressorIndexed[$date]['running'] ?? '-',
                'loading' => $compressorIndexed[$date]['loading'] ?? '-',
                'pressure' => $compressorIndexed[$date]['pressure'] ?? '-',
                'temp' => $compressorIndexed[$date]['temp'] ?? '-',
                'condition' => $compressorIndexed[$date]['condition'] ?? '-',
                'oil_level' => $compressorIndexed[$date]['oil_level'] ?? '-',
                'pic' => $compressorIndexed[$date]['pic'] ?? '-',
                'desc' => $compressorIndexed[$date]['desc'] ?? '-',
                'main_desc' => $compressorIndexed[$date]['main_desc'] ?? '-',
            ];
        }
        // dd($compressor);

        $dryerIndexed = $monitoringData->keyBy('date')->map(function ($item) {
            return [
                'id' => $item->id ?? '-',
                'id_machine' => $item->id_machine ?? '-',
                'temp' => $item->temp ?? '-',
                'temp_out' => $item->temp_out ?? '-',
                'dew' => $item->dew ?? '-',
                'drain' => $item->drain ?? '-',
                'condition' => $item->condition ?? '-',
                'oil_level' => $item->oil_level ?? '-',
                'desc' => $item->desc ?? '-',
                'main_desc' => $item->main_desc ?? '-',
                'pic' => $item->name ?? '-',
            ];
        })->toArray();

        // Gabungkan data monitoring dengan daftar tanggal
        $dryer = [];
        foreach ($dates as $date) {
            $dryer[] = [
                'date' => $date,
                'id' => $dryerIndexed[$date]['id'] ?? '-',
                'id_machine' => $compressorIndexed[$date]['id_machine'] ?? '-',
                'temp' => $dryerIndexed[$date]['temp'] ?? '-',
                'temp_out' => $dryerIndexed[$date]['temp_out'] ?? '-',
                'dew' => $dryerIndexed[$date]['dew'] ?? '-',
                'drain' => $dryerIndexed[$date]['drain'] ?? '-',
                'condition' => $dryerIndexed[$date]['condition'] ?? '-',
                'oil_level' => $dryerIndexed[$date]['oil_level'] ?? '-',
                'desc' => $dryerIndexed[$date]['desc'] ?? '-',
                'main_desc' => $dryerIndexed[$date]['main_desc'] ?? '-',
                'pic' => $dryerIndexed[$date]['pic'] ?? '-',
            ];
        }
        // dd($compressor);

        // Return data
        $monitoringThisMonth = response()->json($compressor);

        $issue = Monitoring::join('users as u', 'u.id', '=', 'monitoring.id_pic')->where('id_machine', $id)->whereNot('desc', '-')->whereNot('desc', 'normal')->whereNot('desc', 'Normal')->whereMonth('date', $month)->whereNotNull('desc')->select('monitoring.*', 'u.name')->get();
        $mainlog = Monitoring::join('users as u', 'u.id', '=', 'monitoring.id_pic')->where('id_machine', $id)->whereMonth('date', $month)->whereNot('main_desc', '-')->whereNot('main_desc', 'normal')->whereNot('main_desc', 'Normal')->whereNotNull('main_desc')->select('monitoring.*', 'u.name')->get();
        // dd($mainlog);
        return view('pages.monitoring.service-visitor', compact('machine', 'client', 'compressor', 'dryer', 'months', 'issue', 'mainlog'));
    }
    public function visitorDailyServicePrint($id, $month)
    {
        $machine = Machine::find($id);
        $client = Client::find($machine->id_client);

        $setday = Carbon::today();
        $today = $setday->setMonth($month);

        $startOfMonth = $today->copy()->startOfMonth();
        $startOfMonthDate = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $dates = [];
        for ($date = $startOfMonthDate; $date->lte($endOfMonth); $date->addDay()) {
            $dates[] = $date->format('d-m-Y');
        }
        // dd($dates);

        // Ambil data monitoring dari database
        $monitoringData = Monitoring::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('id_machine', $id)
            ->join('users as u', 'u.id', '=', 'monitoring.id_pic')
            ->get(['monitoring.*', 'u.name'])
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date)->format('d-m-Y'); // Format tanggal
                return $item;
            });
        // dd($monitoringData);

        $compressorIndexed = $monitoringData->keyBy('date')->map(function ($item) {
            return [
                'id' => $item->id ?? '-',
                'running' => $item->running ?? '-',
                'loading' => $item->loading ?? '-',
                'pressure' => $item->pressure ?? '-',
                'temp' => $item->temp ?? '-',
                'condition' => $item->condition ?? '-',
                'oil_level' => $item->oil_level ?? '-',
                'pic' => $item->name ?? '-',
            ];
        })->toArray();
        // dd($compressorIndexed);

        // Gabungkan data monitoring dengan daftar tanggal
        $compressor = [];
        foreach ($dates as $date) {
            $compressor[] = [
                'date' => $date,
                'id' => $compressorIndexed[$date]['id'] ?? '-',
                'running' => $compressorIndexed[$date]['running'] ?? '-',
                'loading' => $compressorIndexed[$date]['loading'] ?? '-',
                'pressure' => $compressorIndexed[$date]['pressure'] ?? '-',
                'temp' => $compressorIndexed[$date]['temp'] ?? '-',
                'condition' => $compressorIndexed[$date]['condition'] ?? '-',
                'oil_level' => $compressorIndexed[$date]['oil_level'] ?? '-',
                'pic' => $compressorIndexed[$date]['pic'] ?? '-',
            ];
        }
        // dd($compressor);

        $dryerIndexed = $monitoringData->keyBy('date')->map(function ($item) {
            return [
                'id' => $item->id ?? '-',
                'temp' => $item->temp ?? '-',
                'temp_out' => $item->temp_out ?? '-',
                'dew' => $item->dew ?? '-',
                'drain' => $item->drain ?? '-',
                'condition' => $item->condition ?? '-',
                'oil_level' => $item->oil_level ?? '-',
                'desc' => $item->desc ?? '-',
                'pic' => $item->name ?? '-',
            ];
        })->toArray();

        // Gabungkan data monitoring dengan daftar tanggal
        $dryer = [];
        foreach ($dates as $date) {
            $dryer[] = [
                'date' => $date,
                'id' => $dryerIndexed[$date]['id'] ?? '-',
                'temp' => $dryerIndexed[$date]['temp'] ?? '-',
                'temp_out' => $dryerIndexed[$date]['temp_out'] ?? '-',
                'dew' => $dryerIndexed[$date]['dew'] ?? '-',
                'drain' => $dryerIndexed[$date]['drain'] ?? '-',
                'condition' => $dryerIndexed[$date]['condition'] ?? '-',
                'oil_level' => $dryerIndexed[$date]['oil_level'] ?? '-',
                'desc' => $dryerIndexed[$date]['desc'] ?? '-',
                'pic' => $dryerIndexed[$date]['pic'] ?? '-',
            ];
        }
        // dd($compressor);

        // Return data
        $monitoringThisMonth = response()->json($compressor);

        $issue = Monitoring::join('users as u', 'u.id', '=', 'monitoring.id_pic')->where('id_machine', $id)->whereNot('desc', '-')->whereNot('desc', 'normal')->whereNot('desc', 'Normal')->whereMonth('date', $month)->whereNotNull('desc')->select('monitoring.*', 'u.name')->get();
        $mainlog = Monitoring::join('users as u', 'u.id', '=', 'monitoring.id_pic')->where('id_machine', $id)->whereMonth('date', $month)->whereNot('main_desc', '-')->whereNot('main_desc', 'normal')->whereNot('main_desc', 'Normal')->whereNotNull('main_desc')->select('monitoring.*', 'u.name')->get();

        return view('pages.monitoring.service-visitor-print', compact('machine', 'client', 'compressor', 'dryer', 'issue', 'mainlog'));
    }
    public function visitorWeeklyService($id, $week)
    {
        $machine = Machine::find($id);
        $client = Client::find($machine->id_client);
        $machineC = Client::join('machine as m', 'client.id', '=', 'm.id_client')->groupBy('client.id')->get('m.*');
        $monitoringAC = Machine::leftJoin('monitoring_weekly as mw', 'mw.id_machine', '=', 'machine.id')
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'mw.id_pic')
            ->where('u.unit', 'AIR COMPRESSOR SCREW')
            ->where(function ($query) use ($week) {
                $query->where('mw.week', $week)
                    ->orWhereNull('mw.week'); // Tetap tampilkan jika mw.week kosong
            })
            ->where('machine.id_client', $client->id)
            ->select('machine.*', 'mw.*', 'us.name', 'machine.id as idM')
            ->groupBy('machine.id')
            ->get();
        $monitoringDRYER = Machine::leftJoin('monitoring_weekly as mw', 'mw.id_machine', '=', 'machine.id')
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'mw.id_pic')
            ->where('u.unit', 'REFRIGERANT AIR DRYER')
            ->where(function ($query) use ($week) {
                $query->where('mw.week', $week)
                    ->orWhereNull('mw.week'); // Tetap tampilkan jika mw.week kosong
            })
            ->where('machine.id_client', $client->id)
            ->select('machine.*', 'mw.*', 'us.name', 'machine.id as idM')
            ->groupBy('machine.id')
            ->get();
        // dd($monitoringAC);
        return view('pages.monitoring.service-visitor-weekly', compact('machine', 'client', 'monitoringAC', 'monitoringDRYER'));
    }
    public function visitorWeeklyServicePrint($id, $week)
    {
        $machine = Machine::find($id);
        $client = Client::find($machine->id_client);
        $machineC = Client::join('machine as m', 'client.id', '=', 'm.id_client')->groupBy('client.id')->get('m.*');
        $monitoringAC = Machine::leftJoin('monitoring_weekly as mw', 'mw.id_machine', '=', 'machine.id')
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'mw.id_pic')
            ->where('u.unit', 'AIR COMPRESSOR SCREW')
            ->where(function ($query) use ($week) {
                $query->where('mw.week', $week)
                    ->orWhereNull('mw.week'); // Tetap tampilkan jika mw.week kosong
            })
            ->where('machine.id_client', $client->id)
            ->select('machine.*', 'mw.*', 'us.name', 'machine.id as idM')
            ->groupBy('machine.id')
            ->get();
        $monitoringDRYER = Machine::leftJoin('monitoring_weekly as mw', 'mw.id_machine', '=', 'machine.id')
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'mw.id_pic')
            ->where('u.unit', 'REFRIGERANT AIR DRYER')
            ->where(function ($query) use ($week) {
                $query->where('mw.week', $week)
                    ->orWhereNull('mw.week'); // Tetap tampilkan jika mw.week kosong
            })
            ->where('machine.id_client', $client->id)
            ->select('machine.*', 'mw.*', 'us.name', 'machine.id as idM')
            ->groupBy('machine.id')
            ->get();
        // dd($monitoringAC);
        return view('pages.monitoring.service-visitor-print-weekly', compact('machine', 'client', 'monitoringAC', 'monitoringDRYER'));
    }
    public function issueMachine($date)
    {
        $day = Carbon::createFromFormat('d-m-Y', time: $date);
        $month = $day->month;
        $year = $day->year;

        $machines = Machine::with([
            'unit',
            'unit.unit',
            'monitoring' => function ($query) use ($month, $year) {
                $query->whereMonth('date', $month)
                    ->whereYear('date', $year);
            }
        ])->where('id_client', 1277)->get();

        $result = $machines->filter(function ($machine) {
            return $machine->monitoring->isNotEmpty();
        })->map(function ($machine) {
            return [
                'machine' => $machine->unit->brand . ' ' . $machine->unit->unit->sku . ' - ' . $machine->tag . ' - ' . $machine->location,
                'id' => $machine->id,
                'log' => $machine->monitoring->filter(function ($log) {
                    return !is_null($log->desc) && $log->desc !== '-' && $log->desc !== 'normal' && $log->desc !== 'Normal';
                })->map(function ($log) {
                    return [
                        'log' => $log->desc,
                        'date' => \Carbon\Carbon::parse($log->date)->format('d'),
                        'pic' => $log->pic->name
                    ];
                }),
                'mainlog' => $machine->monitoring->filter(function ($mainlog) {
                    return !is_null($mainlog->main_desc) && $mainlog->main_desc !== '-';
                })->map(function ($mainlog) {
                    return [
                        'id' => $mainlog->id,
                        'id_pic' => $mainlog->id_pic,
                        'id_machine' => $mainlog->machine->id,
                        'id_service' => $mainlog->reports->first()->id ?? NULL,
                        'log' => $mainlog->main_desc,
                        'date' => \Carbon\Carbon::parse($mainlog->date)->format('d'),
                        'technician' => $mainlog->technician
                    ];
                }),
            ];
        });

        // dd($day);

        // $mainlog = $machinesmain->filter(function ($machine) {
        //     return $machine->monitoring->isNotEmpty();
        // })->map(function ($machine) {
        //     return [
        //         'machine' => $machine->unit->brand . ' ' . $machine->unit->unit->sku . ' - ' . $machine->tag . ' - ' . $machine->location,
        //         'log' => $machine->monitoring->map(function ($log) {
        //             return ['mainlog' => $log->main_desc, 'date' => \Carbon\Carbon::parse($log->date)->format('d'), 'technician' => $log->technician];
        //         })
        //     ];
        // });
        // dd($result);

        return view('pages.monitoring.issue', compact('result', 'month', 'year', 'day'));
    }

    public function recapDay($month, $year)
    {
        $tanggal = $month . ' ' . $year;
        return view('pages.monitoring.recap-day', compact('tanggal'));
    }
    public function recapM($date)
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d', $date);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format, expected Y-m-d'], 400);
        }
        $date = $date ? Carbon::parse($date) : Carbon::today();
        $allPlant = Machine::where('id_client', 1277)->count();
        $allPlantMonitoring = Machine::where('id_client', 1277)
            ->whereHas('monitoring', function ($query) use ($date) {
                $query->whereDate('date', $date);
            })->count();
        $GT = Machine::where('id_client', 1277)->where('location', 'GT 1-2')->count();
        $GTMonitoring = Machine::where('id_client', 1277)->where('location', 'GT 1-2')
            ->whereHas('monitoring', function ($query) use ($date) {
                $query->whereDate('date', $date);
            })->count();
        $GT3 = Machine::where('id_client', 1277)->where('location', 'GT3/BOILER')->count();
        $GT3Monitoring = Machine::where('id_client', 1277)->where('location', 'GT3/BOILER')
            ->whereHas('monitoring', function ($query) use ($date) {
                $query->whereDate('date', $date);
            })->count();
        $INC = Machine::where('id_client', 1277)->where('location', 'INC')->count();
        $INCMonitoring = Machine::where('id_client', 1277)->where('location', 'INC')
            ->whereHas('monitoring', function ($query) use ($date) {
                $query->whereDate('date', $date);
            })->count();
        $PM12 = Machine::where('id_client', 1277)->where('location', 'PM 1-2')->count();
        $PM12Monitoring = Machine::where('id_client', 1277)->where('location', 'PM 1-2')
            ->whereHas('monitoring', function ($query) use ($date) {
                $query->whereDate('date', $date);
            })->count();
        $PM35 = Machine::where('id_client', 1277)->whereBetween('location', ['PM 3', 'PM 5'])->count();
        $PM35Monitoring = Machine::where('id_client', 1277)->whereBetween('location', ['PM 3', 'PM 5'])
            ->whereHas('monitoring', function ($query) use ($date) {
                $query->whereDate('date', $date);
            })->count();
        $PM78 = Machine::where('id_client', 1277)->whereBetween('location', ['PM 7', 'PM 8'])->count();
        $PM78Monitoring = Machine::where('id_client', 1277)->whereBetween('location', ['PM 7', 'PM 8'])
            ->whereHas('monitoring', function ($query) use ($date) {
                $query->whereDate('date', $date);
            })->count();
        // $mesin = Machine::where('id_client', 1277)->rightJoin('monitoring as m', 'm.id_machine', '=' ,'machine.id')->whereDate('m.date', $date)->get();
        $mesinCompressor = Machine::leftJoin('monitoring as m', function ($join) use ($date) {
            $join->on('machine.id', '=', 'm.id_machine')
                ->whereDate('m.date', '=', $date); // Menyaring berdasarkan tanggal monitoring
        })
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'm.id_pic')
            ->where('machine.id_client', 1277)
            ->where('u.unit', 'AIR COMPRESSOR SCREW')
            ->select(
                'machine.*',
                DB::raw("CONCAT(sp.brand, ' ', u.sku) as brand_type"),
                'm.*',
                'u.unit',
                'us.name'
            )
            ->get();
        $mesinDryer = Machine::leftJoin('monitoring as m', function ($join) use ($date) {
            $join->on('machine.id', '=', 'm.id_machine')
                ->whereDate('m.date', '=', $date); // Menyaring berdasarkan tanggal monitoring
        })
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'm.id_pic')
            ->where('machine.id_client', 1277)
            ->where('u.unit', 'REFRIGERANT AIR DRYER')
            ->select(
                'machine.*',
                DB::raw("CONCAT(sp.brand, ' ', u.sku) as brand_type"),
                'm.*',
                'us.name'
            )
            ->get();
        // dd($mesinDryer);
        return view('pages.monitoring.recap', compact('allPlant', 'allPlantMonitoring', 'GT', 'GTMonitoring', 'GT3', 'GT3Monitoring', 'INC', 'INCMonitoring', 'PM12', 'PM12Monitoring', 'PM35', 'PM35Monitoring', 'PM78', 'PM78Monitoring', ));
    }

    public function issueUpdate(Request $request, $id, $month)
    {
        $monitoring = Monitoring::find($id);
        // dd($monitoring->id_machine);
        $monitoring->desc = $request->desc;
        $monitoringSave = $monitoring->save();
        if ($monitoringSave) {
            return redirect('/service-manager-daily/' . $monitoring->id_machine . '/' . $month)->with('massage', 'data telah dibuat');
        }
    }
    public function mainlogUpdate(Request $request, $id, $month)
    {
        $monitoring = Monitoring::find($id);
        // dd($request->all());
        $monitoring->main_desc = $request->main_desc;
        $monitoringSave = $monitoring->save();
        if ($monitoringSave) {
            return redirect('/service-manager-daily/' . $monitoring->id_machine . '/' . $month)->with('massage', 'data telah dibuat');
        }
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
    protected function getWeekNumberFromDate($date)
    {
        // Ubah tanggal ke format Carbon
        $carbonDate = Carbon::parse($date);

        // Dapatkan hari pertama dalam bulan ini
        $firstDayOfMonth = $carbonDate->copy()->startOfMonth();

        // Hitung minggu keberapa
        $weekNumber = ceil($carbonDate->diffInDays($firstDayOfMonth) / 7);

        return $weekNumber;
    }
}
