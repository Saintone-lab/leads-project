<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\Prospect;
use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $library = new Library();
        $library->name = $request->name;
        $library->link = $request->link;
        $library->type = $request->type;
        $library->models = $request->model;
        $library->date = Carbon::now();
        $libSave = $library->save();
        switch ($library->type) {
            case 'Marketing Tools':
                $code = 'marktool';
                break;

            case 'Brosur':
                $code = 'brosur';
                break;

            case 'Partlist':
                $code = 'partlist';
                break;

            case 'Manual Book':
                $code = 'manbook';
                break;
            default:
                $code = '';
                break;
        }
        if ($libSave) {
            return redirect('/library/index/' . $code)->with('massage', 'Data telah terkirim');
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
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
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
        $library = Library::find($id);
        $library->name = $request->name;
        $library->link = $request->link;
        $library->models = $request->model;
        $library->date = Carbon::now();
        $libSave = $library->save();

        switch ($library->type) {
            case 'Marketing Tools':
                $code = 'marktool';
                break;

            case 'Brosur':
                $code = 'brosur';
                break;

            case 'Partlist':
                $code = 'partlist';
                break;

            case 'Manual Book':
                $code = 'manbook';
                break;
            default:
                $code = 'manual';
                break;
        }
        if ($libSave) {
            return redirect('/library/index/' . $code)->with('massage', 'Data telah terkirim');
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
        $library = Library::find($id);
        switch ($library->type) {
            case 'Marketing Tools':
                $code = 'maketing';
                break;

            case 'Brosur':
                $code = 'brosur';
                break;

            case 'Partlist':
                $code = 'partlist';
                break;

            case 'Manual Book':
                $code = '';
                break;
            default:
                $code = 'manual';
                break;
        }
        $libDel = $library->delete();
        if ($libDel) {
            return 1;
        } else {
            return 0;
        }
    }
    // public function store_marktool(Request $request)
    // {
    //     $library = new Library();
    //     $library->name = $request->name;
    //     $library->link = $request->link;
    //     $library->type = 'Marketing Tools';
    //     $library->models = $request->models;
    //     $library->date = Carbon::now();
    //     $libSave = $library->save();
    //     switch ($library->type) {
    //         case 'Marketing Tools':
    //             $code = 'maketing';
    //             break;

    //         case 'Brosur':
    //             $code = 'brosur';
    //             break;

    //         case 'Partlist':
    //             $code = 'partlist';
    //             break;

    //         case 'Manual Book':
    //             $code = '';
    //             break;
    //         default:
    //             $code = 'manual';
    //             break;
    //     }
    //     if ($libSave) {
    //         return redirect('/library/' . $code)->with('massage', 'Data telah terkirim');
    //     }
    // }
    // public function store_brosur(Request $request)
    // {
    //     $library = new Library();
    //     $library->name = $request->name;
    //     $library->link = $request->link;
    //     $library->type = 'Brosur';
    //     $library->models = $request->models;
    //     $library->date = Carbon::now();
    //     $libSave = $library->save();
    //     switch ($library->type) {
    //         case 'Marketing Tools':
    //             $code = 'maketing';
    //             break;

    //         case 'Brosur':
    //             $code = 'brosur';
    //             break;

    //         case 'Partlist':
    //             $code = 'partlist';
    //             break;

    //         case 'Manual Book':
    //             $code = '';
    //             break;
    //         default:
    //             $code = 'manual';
    //             break;
    //     }
    //     if ($libSave) {
    //         return redirect('/library/' . $code)->with('massage', 'Data telah terkirim');
    //     }
    // }
    // public function store_partlist(Request $request)
    // {
    //     $library = new Library();
    //     $library->name = $request->name;
    //     $library->link = $request->link;
    //     $library->type = 'Partlist';
    //     $library->models = $request->models;
    //     $library->date = Carbon::now();
    //     $libSave = $library->save();
    //     switch ($library->type) {
    //         case 'Marketing Tools':
    //             $code = 'maketing';
    //             break;

    //         case 'Brosur':
    //             $code = 'brosur';
    //             break;

    //         case 'Partlist':
    //             $code = 'partlist';
    //             break;

    //         case 'Manual Book':
    //             $code = '';
    //             break;
    //         default:
    //             $code = 'manual';
    //             break;
    //     }
    //     if ($libSave) {
    //         return redirect('/library/' . $code)->with('massage', 'Data telah terkirim');
    //     }
    // }
    // public function store_manbook(Request $request)
    // {
    //     $library = new Library();
    //     $library->name = $request->name;
    //     $library->link = $request->link;
    //     $library->type = 'Manual Book';
    //     $library->models = $request->models;
    //     $library->date = Carbon::now();
    //     $libSave = $library->save();
    //     switch ($library->type) {
    //         case 'Marketing Tools':
    //             $code = 'maketing';
    //             break;

    //         case 'Brosur':
    //             $code = 'brosur';
    //             break;

    //         case 'Partlist':
    //             $code = 'partlist';
    //             break;

    //         case 'Manual Book':
    //             $code = '';
    //             break;
    //         default:
    //             $code = 'manual';
    //             break;
    //     }
    //     if ($libSave) {
    //         return redirect('/library/' . $code)->with('massage', 'Data telah terkirim');
    //     }
    // }

    public function index_marktool()
    {
        $type = 'Marketing Tools';
        $library = Library::where('type', 'Marketing Tools')->get();
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();
        $comment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', first: 'o.id_status', operator: '=', second: 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            ->where('quotation.id_sales', Auth::id())
            ->where('o.level', '1')
            ->orderBy('o.date', 'DESC')
            ->get(['quotation.id as idQ', 'o.id as idC', 'o.id_user', 'o.comment', 'o.date', 'quotation.no_quote', 'u.name', 'u.image']);
        return view('pages.library.index-marktool', compact('type', 'comment', 'noSaleProspect', 'leveledProspect', 'library'));
    }
    public function index_brosur()
    {
        $type = 'Brosur';
        $library = Library::where('type', 'Brosur')->get();
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();
        $comment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', first: 'o.id_status', operator: '=', second: 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            ->where('quotation.id_sales', Auth::id())
            ->where('o.level', '1')
            ->orderBy('o.date', 'DESC')
            ->get(['quotation.id as idQ', 'o.id as idC', 'o.id_user', 'o.comment', 'o.date', 'quotation.no_quote', 'u.name', 'u.image']);
        return view('pages.library.index-brosur', compact('type', 'comment', 'noSaleProspect', 'leveledProspect', 'library'));
    }
    public function index_partlist()
    {
        $type = 'Partlist';
        $library = Library::where('type', 'Partlist')->get();
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();
        $comment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', first: 'o.id_status', operator: '=', second: 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            ->where('quotation.id_sales', Auth::id())
            ->where('o.level', '1')
            ->orderBy('o.date', 'DESC')
            ->get(['quotation.id as idQ', 'o.id as idC', 'o.id_user', 'o.comment', 'o.date', 'quotation.no_quote', 'u.name', 'u.image']);
        return view('pages.library.index-partlist', compact('type', 'comment', 'noSaleProspect', 'leveledProspect', 'library'));
    }
    public function index_manbook()
    {
        $type = 'Manual Book';
        $library = Library::where('type', 'Manual Book')->get();
        $noSaleProspect = Prospect::whereNULL('id_sales')->count();
        $leveledProspect = Prospect::whereNULL('level')->where('id_sales', Auth::id())->count();
        $comment = Quotation::join('change_status as c', 'c.id_quotation', '=', 'quotation.id')
            ->join('comment as o', first: 'o.id_status', operator: '=', second: 'c.id')
            ->join('users as u', 'u.id', '=', 'o.id_user')
            ->where('quotation.id_sales', Auth::id())
            ->where('o.level', '1')
            ->orderBy('o.date', 'DESC')
            ->get(['quotation.id as idQ', 'o.id as idC', 'o.id_user', 'o.comment', 'o.date', 'quotation.no_quote', 'u.name', 'u.image']);
        return view('pages.library.index-manbook', compact('type', 'comment', 'noSaleProspect', 'leveledProspect', 'library'));
    }
}
