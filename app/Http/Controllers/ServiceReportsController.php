<?php

namespace App\Http\Controllers;

use App\Models\Pic;
use App\Models\Reports;
use App\Models\ReportsPict;
use App\Models\SignPict;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as Image;

class ServiceReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.technician.service-reports.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dateNow = Carbon::now();
        $numberS = Reports::whereYear('date', $dateNow)->where('id_technician', Auth::user()->id)->count();
        $formattedNumberS = str_pad($numberS + 1, 3, '0', STR_PAD_LEFT);
        $monthNow = $dateNow->month;
        $formattedMonthNow = $this->convertToRoman($monthNow);
        $pic = Pic::all();
        return view('pages.technician.service-reports.form', compact('pic', 'formattedNumberS', 'formattedMonthNow'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule = [
            'no_service => required',
            'unit => required',
            'serial_number => required',
            'running => required',
            'load => required',
            'jobdesc => required',
            'desc => required',
        ];
        $customMessages = [
            'no_service.required' => 'Field No Service Wajib Diisi!',
            'unit.required' => 'Field Unit Wajib Diisi',
            'serial_number.required' => 'Field Serial Number Wajib Diisi',
            'running.required' => 'Field Running Wajib Diisi!',
            'load.required' => 'Field Load Wajib Diisi!',
            'jobdesc.required' => 'Field Jobdesc Wajib Diisi!',
            'desc.required' => 'Field desc Wajib Diisi!',
        ];

        $this->validate($request, $rule, $customMessages);
        // dd($request);
        // Masukan Data ke Service Reports
        $reports = new Reports();
        $reports->id_technician = $request->technician;
        $reports->id_pic = $request->id_pic;
        $reports->no_service = $request->no_service;
        $reports->serial_number = $request->serial_number;
        $reports->running = $request->running;
        $reports->unit = $request->unit;
        $reports->load = $request->load;
        $reports->jobdesc = $request->jobdesc;
        $reports->desc = $request->desc;
        $reports->recomendation = $request->recomendation;
        $reports->date = $request->date;
        $reports->sign_client = NULL;
        $status = $reports->save();
        // dd($reports);

        // masukan data ke image reports
        foreach ($request->description as $item => $value) {
            $photo = new ReportsPict();
            $photo->id_reports = $reports->id;
            $photo->keterangan = $value; // Gunakan $value, bukan $request->description

            if ($request->hasFile('image')) {
                $foto = $request->file('image')[$item]; // Akses file sesuai dengan iterasi saat ini
                // Proses setiap file gambar
                $image_ext = $foto->getClientOriginalExtension();
                $image_name = Str::random(8);

                $upload_path = 'asset/reports';
                $imagename = $upload_path . '/' . $image_name . '.' . $image_ext;

                // Pemrosesan gambar
                $img = Image::make($foto->path());
                $img->fit(500, 800, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($imagename);

                $photo['picture'] = $imagename;
            }

            $status = $photo->save(); // Simpan objek setelah pemrosesan setiap file
        }
        if ($status) {
            return redirect('service-reports')->with('success', 'Data Has been created');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Reports::where('id', $id)->first();
        $pict = ReportsPict::where('id_reports', $id)->get();
        // dd($service);
        return view('pages.technician.service-reports.detail', compact('service', 'pict'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Reports::find($id);
        $pic = ReportsPict::where('id_reports', $id)->get();

        $delService = $service->delete();
        foreach ($pic as $pict) {
            $delPict = $pict->delete();
        }

        if ($delService && $delPict) {
            return 1;
        } else {
            return 0;
        }
    }

    public function print_reports($id)
    {
        $service = Reports::where('id', $id)->first();
        $pict = ReportsPict::where('id_reports', $id)->get();
        return view('pages.technician.service-reports.detail-print', compact('service', 'pict'));
    }

    public function hand_sign(Request $request, $id)
    {
        $photo = Reports::find($id);

        if ($request->hasFile('sign_client')) {
            $foto = $request->file('sign_client'); // Akses file sesuai dengan iterasi saat ini
            // Proses setiap file gambar
            $image_ext = $foto->getClientOriginalExtension();
            $image_name = Str::random(8);

            $upload_path = 'asset/reports';
            $imagename = $upload_path . '/' . $image_name . '.' . $image_ext;

            // Pemrosesan gambar
            $img = Image::make($foto->path());
            $img->fit(800, 500, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($imagename);

            $photo['sign_client'] = $imagename;
        }
        // dd($photo);
        $status = $photo->save();

        if($status){
            return redirect('/service-reports/'.$id)->with('massage', 'Data telah terkirim');
        }
    }
    public function delete_hand_sign($id)
    {
        $reports = Reports::find($id);

        $delsign = File::delete($reports->sign_client);
        if($delsign){
            $reports->sign_client = NULL;
        }
        // dd($photo);
        $status = $reports->save();

        if($status){
            return 1;
        }else{
            return 0;
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
}
