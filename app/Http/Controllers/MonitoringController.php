<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Machine;
use App\Models\Monitoring;
use Carbon\Carbon;
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
        return view('pages.monitoring.form', compact('machine'));
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
            $monitoring->running = $request->running . ' Hour';
            $monitoring->loading = $request->loading . ' Hour';
            $monitoring->pressure = $request->pressure . ' Bar';
            $monitoring->temp = $request->temperature . " °C";
            $monitoring->oil_level = $request->oil;
        } else {
            $monitoring->dew = $request->dew;
            $monitoring->drain = $request->drain;
            $monitoring->cleaning = $request->cleaning;
            $monitoring->temp = $request->temperature_in . " °C";
            $monitoring->temp_out = $request->temperature_out . " °C";
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
            return redirect('/monitoring/daily/' . $id)->with('message', 'Data telah diibuat');
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

    // Monitoring Weekly
    public function createWeekly($id)
    {
        $machine = Machine::find($id);
        return view('pages.monitoring.form-weekly', compact('machine'));
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
        return view('pages.monitoring.service-index');
    }
    public function showServiceM($id)
    {
        $machine = Machine::find($id);
        return view('pages.monitoring.service-detail', compact('machine'));
    }
    public function visitorDailyService($id, $month)
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

        return view('pages.monitoring.service-visitor', compact('machine', 'client', 'compressor', 'dryer'));
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

        return view('pages.monitoring.service-visitor-print', compact('machine', 'client', 'compressor', 'dryer'));
    }
}
