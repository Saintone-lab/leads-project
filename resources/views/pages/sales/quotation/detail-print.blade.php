@extends('layouts.sales.app')
@section('title', 'Detail Quotation')
<div class="invoice-print p-4">
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
            <div class="mb-xl-0 pb-1">
                <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                    <span class="app-brand-logo demo">
                        <span style="color: var(--bs-primary)">
                            <img class="text-md"
                                src="{{ url('https://reftech.id/wp-content/uploads/2021/10/Reftech-Logo-Hitam.png') }}"
                                alt="" srcset="" width="60%">
                        </span>
                    </span>
                </div>
                <p class="mb-1 fw-bolder">PT Reftech Jaya Optima</p>
                <div style="font-size: 10px">
                    <p class="mb-1">Taman Kopo Indah V, Ruko Sommerville No. 31</p>
                    <p class="mb-1">Bandung – Jawa Barat 40218</p>
                    <p class="mb-1">
                        <i class="mdi mdi-phone-outline scaleX-n1-rtl me-1"></i>022 54417653
                    </p>
                </div>
            </div>
            <div class="text-end">
                <h5 class="fw-bold">QUOTATION</h5>
                <div>
                    <span class="fw-bolder">#{{ $quote->no_quote }}</span>
                </div>
                <div class="mt-1">
                    <span
                        class="text-muted">{{ $quote->status == '25' ? 'DRAFT' : ($quote->status == '50' ? 'SEND' : ($quote->status == '75' ? 'NEGOTIATION' : ($quote->status == '100' ? 'DONE PO' : ($quote->status == '0' ? 'LOSS' : '')))) }}</span>
                </div>
                <div class="mt-1">
                    <span class="text-muted">{{Carbon\Carbon::parse($quote->estimated_date)->format('d-m-Y')}}</span>
                </div>
            </div>
        </div>

        <hr>

        <div class="mb-4">
            <div class="row">
                <div class="col-6">
                    <h6 class="fw-semibold fs-4 mb-3">Quote To:</h6>
                </div>
                <div class="col-6 mb-2">
                </div>
            </div>
            <div class="row">
                <div class="col-2 fw-medium">
                    <p class="mb-1">Company </p>
                    <p class="mb-1">Name PIC</p>
                    <p class="mb-1">Phone </p>
                </div>
                <div class="col-4">
                    <p class="mb-1">: {{ $quote->pic->client->company }}</p>
                    <p class="mb-1">: {{ $quote->pic->name_pic }}</p>
                    <p class="mb-1">: {{ $quote->pic->client->phone }}</p>
                </div>
                <div class="col-2 fw-medium text-end">
                    <p class="mb-1">Sales :</p>
                    <p class="mb-1">No PR :</p>
                    <p class="mb-1">Email :</p>
                </div>
                <div class="col-4 text-end">
                    <p class="mb-1"> PT Reftech Jaya Optima</p>
                    <p class="mb-1"> {{ $quote->no_pr ?? '-' }}</p>
                    <p class="mb-1"> {{ $quote->pic->client->email }}</p>
                </div>
            </div>
        </div>

        <div class="mb-2">
            <table class="table table-borderless m-0" style="width: 100%">
                <thead class="table-light border-top">
                    <tr>
                        <th class="no">No.</th>
                        <th class="item text-nowrap">Item</th>
                        <th class="price">Price</th>
                        <th class="qty">Qty</th>
                        <th class="disc">Disc</th>
                        <th class="amount">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 0;
                    @endphp
                    @foreach ($dquote as $product)
                        @php
                            $no++;
                        @endphp
                        <tr style="font-size: 13px">
                            <td>{{ $no }}</td>
                            <td class="text-nowrap">
                                <p class="mb-0 fw-semibold" style="font-size: 12px">
                                    {{ $product->product }}
                                </p>
                                <pre class="mb-0" style="font-size: 10px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $product->detail_product }}</pre>
                            </td>
                            <td>RP {{ number_format($product->price, 0, '', '.') }}</td>
                            <td>{{ $product->qty }} {{ $product->info_qty }}</td>
                            <td>{{ $product->disc }}%</td>
                            <td>RP {{ number_format($product->amount, 0, '', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="">
                        <td colspan="3" rowspan="2" class="align-top pt-4">

                        </td>
                        <td colspan="2" class="text-end pt-4 pb-0">
                            <p class="mb-2">Subtotal:</p>
                            <p class="mb-2">Tax:</p>
                            <p class="mb-2">Shipping Cost:</p>
                        </td>
                        <td colspan="2" class="pt-4 pb-0">
                            <p class="fw-semibold mb-2 text-end">Rp
                                {{ number_format($quote->subtotal, 0, '', '.') }}</p>
                            <p class="fw-semibold mb-2 text-end">{{ $quote->tax }}%</p>
                            <p class="fw-semibold mb-2 text-end">Rp
                                {{ number_format($quote->shipping, 0, '', '.') }}</p>
                        </td>
                    </tr>
                    <tr class="total">
                        <td colspan="2" class="">
                            <p class="mb-0 text-end">Total:</p>
                        </td>
                        <td colspan="2" class="">
                            <p class="fw-semibold mb-0 text-end">Rp
                                {{ number_format($quote->harga_total, 0, '', '.') }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mb-4">
            <h5 class="mt-4 mb-3">Term & Condition</h5>
            <div class="row">
                <div class="col-3 fw-medium termc p-3">
                    <p class="mb-1">Validity Of Quotation</p>
                    <p class="mb-1">Price </p>
                    <p class="mb-1">Delivery Process </p>
                    <p class="mb-1">Payment </p>
                    <p class="mb-1">Note </p>
                </div>
                <div class="col termc p-3">
                    <p class="mb-1">: {{ $quote->termncon[0]->validity }}</p>
                    <p class="mb-1">: {{ $quote->termncon[0]->pricing }}</p>
                    <p class="mb-1">: {{ $quote->termncon[0]->delivery_process }}</p>
                    <p class="mb-1">: {{ $quote->termncon[0]->payment }}</p>
                    <p class="mb-1">: {{ $quote->termncon[0]->note }}</p>
                </div>
            </div>
        </div>
        <div class="mb-0">
            <p class="text-center mb-0">if you have any questions about this quotation, please contact :</p>
            <p class="text-center">{{ $quote->sales->name }} {{ $quote->sales->phone }}</p>
        </div>
    </div>
</div>
@push('after-style')
<!-- Page CSS -->
<link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice-print.css" />
<link rel="stylesheet" href="style.css">
@endpush
@push('after-script')
<script src="{{ asset('assets') }}/js/app-invoice-print.js"></script>
@endpush
