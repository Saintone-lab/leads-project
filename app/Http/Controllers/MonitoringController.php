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
        $monitoring = new Monitoring();
        $monitoring->id_machine = $id;
        $monitoring->id_pic = Auth::user()->id;
        $monitoring->runing = $request->running . ' Hour';
        $monitoring->pressure = $request->pressure . ' Bar';
        $monitoring->temp = $request->temperature . " °C";
        $monitoring->condition = $request->condition;
        $monitoring->oil_level = $request->oil;
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

            $machine_brand = $machine->brand;
            $machine_type = $machine->type;
            $upload_dir = public_path('asset/machines/' . $machine_brand . '-' . $machine_type);
            $upload_path = 'asset/machines';
            $imagename = $upload_path . '/' . $machine_brand . '-' . $machine_type . '/' . $foto_name . '.' . $foto_ext;

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $img = Image::make($foto->path());
            $img->fit(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($imagename);
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

        // mengambil dara monitoring bulan ini
        $today = Carbon::today();

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
                'running' => $monitoringIndexed[$date]['runing'] ?? '-',
                'load' => $monitoringIndexed[$date]['load'] ?? '-',
                'pressure' => $monitoringIndexed[$date]['pressure'] ?? '-',
                'temp' => $monitoringIndexed[$date]['temp'] ?? '-',
                'condition' => $monitoringIndexed[$date]['condition'] ?? '-',
                'oil_level' => $monitoringIndexed[$date]['oil_level'] ?? '-',
                'desc' => $monitoringIndexed[$date]['desc'] ?? '-',
            ];
        }

        // Return data
        $monitoringThisMonth = response()->json($result);
        // dd($result);   
        return view('pages.monitoring.visitor', compact('machine', 'client', 'result'));
    }
}
