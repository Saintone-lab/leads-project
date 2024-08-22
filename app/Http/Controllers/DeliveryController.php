<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\DetailDelivery;
use App\Models\DetailQuotation;
use App\Models\Invoice;
use App\Models\Quotation;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $rule = [
            'destination' =>
                'required',
        ];
        $message = [
            'destination.required' => 'Field Destination Wajib Diisi',
        ];
        $this->validate($request, $rule, $message);
        $invoice = Invoice::find($request->id_invoice);
        $quote = Quotation::find($invoice->id_quotation);
        $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();

        $delivery = new Delivery;
        $delivery->id_invoice = $request->id_invoice;
        $delivery->date = $request->date;
        $delivery->destination = $request->destination;
        $delivery->type = $request->type;
        $deliverySave = $delivery->save();

        foreach ($dQuote as $item => $value) {
            if ($request->qty[$item] >= 1) {
                $dDelivery = new DetailDelivery;
                $dDelivery->id_delivery = $delivery->id;
                $dDelivery->id_pn = $value->id_equivalent;
                $dDelivery->desc = $value->detail_product;
                $dDelivery->qty = $request->qty[$item];
                $dDelivery->info_qty = $value->info_qty;
                $status = $dDelivery->save();
            }
        }
        if ($deliverySave && $status) {
            return redirect('/delivery/' . $delivery->id)->with("success", "Data Delivery Telah Ditambahkan");
        }
        // else {
        //     return redirect('/delivery/' . $delivery->id)->with("success", "Data Delivery Telah Ditambahkan");
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $delivery = Delivery::find($id);
        $dDelivery = DetailDelivery::where('id_delivery', $id)->get();
        $invoice = invoice::find($delivery->id_invoice);
        $quote = Quotation::find($invoice->id_quotation);
        // $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();

        return view("pages.accounting.delivery.detail", compact('delivery', 'dDelivery', 'invoice', 'quote'));
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
        $delivery = Delivery::find($id);
        $detDelevery = DetailDelivery::where('id_delivery', $id)->get();

        $delDelivery = $delivery->delete();
        foreach ($detDelevery as $product) {
            $delDetDelivery = $product->delete();
        }
        if ($delDelivery && $delDetDelivery) {
            return 1;
        }else{
            return 0;
        }
    }

    public function print_delivery($id)
    {
        $delivery = Delivery::find($id);
        $dDelivery = DetailDelivery::where('id_delivery', $id)->get();
        $invoice = invoice::find($delivery->id_invoice);
        $quote = Quotation::find($invoice->id_quotation);
        // $dQuote = DetailQuotation::where('id_quotation', $invoice->id_quotation)->get();

        return view("pages.accounting.delivery.detail-print", compact('delivery', 'dDelivery', 'invoice', 'quote'));
    }

    public function change_date(Request $request, $id)
    {
        $delivery = Delivery::find($id);

        if (@$request->check == '1') {
            $delivery->date = NULL;
        } else {
            $delivery->date = $request->date;
        }

        $delivery->destination = $request->destination;
        $status = $delivery->save();

        if ($status) {
            return redirect('/delivery/' . $id)->with('massage', 'Data telah terkirim');
        }
    }
    public function change_date_label(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        if (@$request->check == '1') {
            $invoice->date = NULL;
        } else {
            $invoice->date = $request->date;
        }

        $invoice->invoiceTo = $request->destination;
        $status = $invoice->save();

        if ($status) {
            return redirect('/invoice/label_detail/' . $id)->with('massage', 'Data telah terkirim');
        }
    }
}
