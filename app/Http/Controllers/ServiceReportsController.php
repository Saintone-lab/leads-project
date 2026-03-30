<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DetailProduct;
use App\Models\ImageService;
use App\Models\MonitoringActivities;
use App\Models\Notulen;
use App\Models\PendingPO;
use App\Models\Pic;
use App\Models\Product;
use App\Models\Prospect;
use App\Models\Reports;
use App\Models\ReportsPict;
use App\Models\ReqVisit;
use App\Models\SerialProduct;
use App\Models\Service;
use App\Models\SignPict;
use App\Models\User;
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
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        $reportsCount = Reports::join('machine as m', 'm.id', '=', 'reports.id_machine')
            ->join('client as c', 'c.id', '=', 'm.id_client')
            ->join('users as u', 'u.id', '=', 'c.id_sales')
            ->where('u.id', Auth::user()->id)
            ->where('reports.viewed', 0)->count();
        return view('pages.technician.service-reports.index', compact('reportsCount', 'noSaleProspect', ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sales = User::where('role', 'Sales')->get();
        $clients = Client::all();
        $dateNow = Carbon::now();
        $numberS = Reports::whereYear('date', $dateNow)->where('id_technician', Auth::user()->id)->count();
        $formattedNumberS = str_pad($numberS + 1, 3, '0', STR_PAD_LEFT);
        $monthNow = $dateNow->month;
        $formattedMonthNow = $this->convertToRoman($monthNow);
        $pic = Pic::join('client as c', 'c.id', '=', 'pic.id_client')->select('pic.*')->get();
        return view('pages.technician.service-reports.form', compact('sales', 'pic', 'formattedNumberS', 'formattedMonthNow', 'clients'));
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
        $reports->id_technician = $request->technician;
        $reports->id_pic = $request->id_pic;
        $reports->id_machine = $request->machine;
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
        // dd($pict);
        $noSaleProspect = Prospect::whereNULL('id_sales')->whereNull('provide')->count();
        return view('pages.technician.service-reports.detail', compact('noSaleProspect', 'service', 'pict'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sales = User::where('role', 'Sales')->get();
        $report = Reports::find($id);
        $image = ReportsPict::where('id_reports', $id)->get();
        $dateNow = Carbon::now();
        $numberS = Reports::whereYear('date', $dateNow)->where('id_technician', Auth::user()->id)->count();
        $formattedNumberS = str_pad($numberS + 1, 3, '0', STR_PAD_LEFT);
        $monthNow = $dateNow->month;
        $formattedMonthNow = $this->convertToRoman($monthNow);
        $clients = Client::all();
        $pic = Pic::all();
        // dd($image);
        return view('pages.technician.service-reports.form', compact('sales','pic', 'formattedNumberS', 'formattedMonthNow', 'report', 'image', 'clients'));
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
        $rule = [
            'running => required',
            'load => required',
            'jobdesc => required',
            'desc => required',
        ];
        $customMessages = [
            'running.required' => 'Field Running Wajib Diisi!',
            'load.required' => 'Field Load Wajib Diisi!',
            'jobdesc.required' => 'Field Jobdesc Wajib Diisi!',
            'desc.required' => 'Field desc Wajib Diisi!',
        ];

        $this->validate($request, $rule, $customMessages);
        // dd($request);
        // Masukan Data ke Service Reports
        $reports = Reports::find($id);
        // $reports->id_technician = $request->technician;
        // $reports->id_pic = $request->id_pic;
        $reports->type = $request->type;
        // $reports->id_machine = $request->machine;
        // $reports->no_service = $request->no_service;
        $reports->running = $request->running;
        $reports->load = $request->load;
        $reports->jobdesc = $request->jobdesc;
        $reports->date = $request->date;
        $reports->desc = $request->desc;
        $reports->recomendation = $request->recomendation;
        $status = $reports->save();
        // dd($reports);
        if ($status) {
            return redirect('service-reports/' . $reports->id)->with('success', 'Data Has been updated');
        }
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

        if ($status) {
            return redirect('/service-reports/' . $id)->with('massage', 'Data telah terkirim');
        }
    }
    public function delete_hand_sign($id)
    {
        $reports = Reports::find($id);

        $delsign = File::delete($reports->sign_client);
        if ($delsign) {
            $reports->sign_client = NULL;
        }
        // dd($photo);
        $status = $reports->save();

        if ($status) {
            return 1;
        } else {
            return 0;
        }
    }

    public function inputImage(Request $request, $id)
    {
        foreach ($request->description as $item => $value) {
            $photo = new ReportsPict();
            $photo->id_reports = $id;
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
            return redirect('service-reports/' . $id)->with('success', 'Data Has been created');
        }
    }

    public function deleteImage($id)
    {
        $photos = ReportsPict::where('id_reports', $id)->get();
        foreach ($photos as $picture) {
            $status = $picture->delete();
        }
        if ($status) {
            return 1;
        } else {
            return 0;
        }
    }

    public function markViewed(Request $request)
    {
        $report = Reports::findOrFail($request->id);
        $report->viewed += 1;
        $report->save();

        return response()->json(['success' => true]);
    }
    public function serviceMer()
    {
        $today = Carbon::now()->toDateString();
        $commodity = Product::count();
        $dproduct = DetailProduct::count();
        $sproduct = SerialProduct::count();
        $user = User::find('25');
        $monitoring = MonitoringActivities::whereDate('date', $today)->first();

        $newCount = PendingPO::where('status', operator: 0)
            ->where('type', 'Non Project')
            ->count();
        // dd($newCount);  
        $listCount = PendingPO::whereIn('pending_po.status', [1, 2, 3, 4])
            ->where('type', 'Non Project')
            ->count();
        $deliveryCount = PendingPO::where('pending_po.status', 5)
            ->where('type', 'Non Project')
            ->count();
        $doneCount = PendingPO::where('pending_po.status', 6)
            ->where('type', 'Non Project')
            ->count();

        $visits = ReqVisit::whereNull('date')->get();
        $notulens = Notulen::join('mention_notulen as m', 'm.id_notulen', '=', 'notulen.id')->join('users as u', 'm.id_mention', '=', 'u.id')->get(['notulen.*', 'u.name', 'm.level']);
        $visited = ReqVisit::whereNotNull('date')->whereNull('visit_date')->get();
        return view(
            "pages.support.serviceM.reports",
            compact(
                'user',
                'newCount',
                'listCount',
                'deliveryCount',
                'notulens',
                'commodity',
                'dproduct',
                'sproduct',
                'visits',
                'visited'
            )
        );
    }

    public function get_client($id)
    {
        $client = Client::where('id_status',$id)->get();

        return response()->json([
            'data' => $client
        ]);
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
