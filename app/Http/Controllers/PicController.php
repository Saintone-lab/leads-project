<?php

namespace App\Http\Controllers;

use App\Models\Pic;
use App\Models\Quotation;
use Illuminate\Http\Request;

class PicController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {

        $rule = [
            'namePic' =>
                'required',

            'emailPic' =>
                'required',

            'phonePic' =>
                'required',

            'position' =>
                'required',
        ];

        $message = [
            'namePic.required' => 'Field Nama PIC Wajib Diisi',
            'emailPic.required' => 'Field Email PIC Wajib Diisi',
            'phonePic.required' => 'Field Nomor PIC Wajib Diisi',
            'position.required' => 'Field Posisi PIC Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);

        // masukan data ke table PIC
        $pic = new Pic;
        $pic->id_client = $id;
        $pic->name_pic = $request->namePic;
        $pic->position = $request->position;
        $pic->email_pic = $request->emailPic;
        $pic->phone_pic = $request->phonePic;
        $picsave = $pic->save();

        if ($picsave) {
            return redirect('/customers/'.$id)->with('message', 'data telah ditambahkan');
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
        $rule = [
            'namePic' =>
                'required',

            'emailPic' =>
                'required',

            'phonePic' =>
                'required',

            'position' =>
                'required',
        ];

        $message = [
            'namePic.required' => 'Field Nama PIC Wajib Diisi',
            'emailPic.required' => 'Field Email PIC Wajib Diisi',
            'phonePic.required' => 'Field Nomor PIC Wajib Diisi',
            'position.required' => 'Field Posisi PIC Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);

        $pic = Pic::find($id);
        $pic->name_pic = $request->namePic;
        $pic->position = $request->position;
        $pic->email_pic = $request->emailPic;
        $pic->phone_pic = $request->phonePic;
        $picsave = $pic->save();

        if ($picsave) {
            return redirect('/customers/'.$id)->with('message','data telah diubah');
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
        $pic = Pic::find($id);
        $quoteD = Quotation::where('id_pic', $id)->get();

        $delPic = $pic->delete();
        if($quoteD != NULL) {
            foreach ($quoteD as $quote) {
                $delQuote = $quote->delete();
            }
        }

        if($delPic || $delQuote){
            return 1;
        }else{
            return 0;
        }
    }
}
