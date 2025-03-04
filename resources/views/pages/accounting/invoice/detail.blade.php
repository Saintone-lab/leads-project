@extends('layouts.sales.app')
@section('title', 'Invoice')
@section('content')
    <div class="row invoice-preview">
        {{-- Invoice --}}
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    @if ($invoice->flag == 'Reftech')
                        <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                            <div class="mb-xl-0 pb-1">
                                <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                                    <span class="app-brand-logo demo">
                                        <span style="color: var(--bs-primary)">
                                            <img class="text-md" src="{{ asset('/asset') }}/logo/Reftech-Log.png"
                                                alt="" srcset="" width="60%">
                                        </span>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="info">
                                        <p class="mb-1 fw-bolder">Office Address :</p>
                                        <div style="font-size: 10px">
                                            <p class="mb-1">Taman Kopo Indah V, Ruko Sommerville No. 31</p>
                                            <p class="mb-1">Bandung – Jawa Barat 40218</p>
                                            <p class="mb-1">
                                                <i class="mdi mdi-phone-outline scaleX-n1-rtl me-1 mdi-14px"></i>022
                                                54417653
                                                {{ '   ' }}<i
                                                    class="mdi mdi-email-outline scaleX-n1-rtl me-1 mdi-14px"></i>info@reftech.id
                                            </p>
                                            <p class="mb-1">
                                            </p>
                                        </div>
                                    </div>
                                    <div class="npwp_add">
                                        <p class="mb-1 fw-bolder">NPWP Address :</p>
                                        <pre
                                            style="font-size: 10px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 250px; overflow-x: auto; white-space: pre-wrap;">Komp. Negia Kencana Residence Blok B, No.2 Pasanggrahan, Ujung Berung Kota Bandung - Jawa Barat 40199</pre>
                                        <p class="mb-1 text-black fw-medium p-1"
                                            style="background-color: rgb(224, 221, 255); font-size: 10px">NPWP :
                                            73.728.571.8-429.000</p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <h1 class="fw-bold" style="color: blue;">INVOICE</h1>
                                <div>
                                    <span class="fw-bolder">#{{ $invoice->no_invoice }}</span>
                                </div>
                                <div class="mt-1">
                                    <span
                                        class="text-black">{{ Carbon\Carbon::parse($invoice->date)->format('d-m-Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                            <div class="mb-xl-0 pb-1">
                                <div class="d-flex svg-illustration align-items-center gap-2 mb-2">
                                    <span class="app-brand-logo demo">
                                        <span style="color: var(--bs-primary)">
                                            <img class="text-md" src="{{ asset('/asset') }}/logo/Logo-update-size.png"
                                                alt="" srcset="" width="60%">
                                        </span>
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <div class="info">
                                        <p class="mb-1 fw-bolder">Office Address :</p>
                                        <div style="font-size: 10px">
                                            <p class="mb-1">Jl. Nancep No. 45A, Setu</p>
                                            <p class="mb-1">Cibitung - Kab. Bekasi 17320</p>
                                            <p class="mb-1">
                                                <i class="mdi mdi-phone-outline scaleX-n1-rtl me-1 mdi-14px"></i>+62
                                                812-1000-0997
                                                {{ ' | ' }}<i
                                                    class="mdi mdi-email-outline scaleX-n1-rtl me-1 mdi-14px"></i>admin@kojisha.com
                                            </p>
                                        </div>
                                    </div>
                                    <div class="npwp_add">
                                        <p class="mb-1 fw-bolder">NPWP Address :</p>
                                        <pre
                                            style="font-size: 10px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 250px; overflow-x: auto; white-space: pre-wrap;">Jl. Nancep No. 45, Setu Cisaat RT. 001 RW. 003 Cibening, Setu</pre>
                                        <p class="mb-1 text-black fw-medium p-1"
                                            style="background-color: rgb(255, 235, 221)">NPWP : 96.484.859.2-413.000</p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <h1 class="fw-bold" style="color: rgb(175, 29, 23);">INVOICE</h1>
                                <div>
                                    <span class="fw-bolder">#{{ $invoice->no_invoice }}</span>
                                </div>
                                <div class="mt-1">
                                    <span
                                        class="text-muted">{{ Carbon\Carbon::parse($invoice->date)->format('d-m-Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <hr class="my-0">
                <div class="card-body mb-3">
                    @php

                        if ($invoice->invoiceTo == '1') {
                            $address = $quote->pic->client->address;
                        } else {
                            $address = $quote->pic->client->subAddress;
                        }
                    @endphp
                    <h5>Invoice To</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered" style="border: 1px solid black;">
                            <tr>
                                <td rowspan="3" style="vertical-align: top; width: 50%;">
                                    <div class="row">
                                        <div class="col-4 fw-medium">
                                            <p class="mb-1">Bill To </p>
                                        </div>
                                        <div class="col-8">
                                            <pre class="mb-1"
                                                style="font-size: 16px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">: {{ $quote->pic->client->company }}</pre>
                                        </div>
                                        <div class="col-4 fw-medium">
                                            <p class="mb-1">PIC </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="mb-1">: {{ $quote->pic->name_pic }}</p>
                                        </div>
                                        <div class="col-4 fw-medium">
                                            <p class="mb-1">NPWP </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="mb-1">: {{ $quote->pic->client->npwp }}</p>
                                        </div>
                                        <div class="col-4 fw-medium">
                                            <p class="mb-1">Phone </p>
                                        </div>
                                        <div class="col-8">
                                            <p class="mb-1">: {{ $quote->pic->client->phone }}</p>
                                        </div>
                                        <div class="col-4 fw-medium">
                                            <p class="mb-1">Address</p>
                                        </div>
                                        <div class="col-8">
                                            @if ($invoice->invoiceTo == '1')
                                                <pre
                                                    style="font-size: 16px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">: {{ $quote->pic->client->address }}</pre>
                                            @else
                                                <pre
                                                    style="font-size: 16px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">: {{ $quote->pic->client->subAddress }}</pre>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- <p>Bill To : {{ $quote->pic->client->company }}</p>
                                    <p>Phone : {{ $quote->pic->client->phone }}</p>
                                    <p>Address : {{ $address }}</p> --}}
                                </td>
                                <td>
                                    <p>Purchase Order :</p>
                                </td>
                                <td>
                                    <p>{{ $invoice->no_po }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style=" background-color: #F9F9F9;" class="text-center">
                                    <p class="fs-5 text-black fw-medium m-0">Term Of Payment:</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <pre
                                        style="font-size: 16px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $invoice->term }}</pre>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered m-0" style="border: 1px solid rgb(60, 60, 60)">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th style="width: 5%">Disc</th>
                                @if ($quote->tax != 0)
                                    <th style="width: 15%">DPP</th>
                                @endif
                                <th style="width:20%">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalPph = 0;
                                $no = 0;
                            @endphp
                            @foreach ($dquote as $product)
                                @php
                                    $no++;
                                    $pph = ($product->amount * $product->pph) / 100;
                                    $totalPph += $pph;
                                    $dpp = ($product->amount * 11) / 12;
                                @endphp
                                <tr style="font-size: 13px">
                                    <td class="align-top">{{ $no }}</td>
                                    <td class="text-nowrap align-top">
                                        <p class="mb-0 fw-medium" style="font-size: 12px">
                                            {{ $product->equivalent->brand }} {{ $product->equivalent->pn }}
                                        </p>
                                        <pre class="mb-0"
                                            style="font-size: 10px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $product->detail_product }}</pre>
                                    </td>
                                    <td class="align-top text-end">RP {{ number_format($product->price, 0, '', '.') }}
                                    </td>
                                    <td class="align-top">{{ $product->qty }} {{ $product->info_qty }} </td>
                                    <td class="align-top">{{ $product->disc }}%</td>
                                    @if ($quote->tax != 0)
                                        <td class="align-top text-end">RP {{ number_format($dpp, 0, '', '.') }}
                                    @endif
                                    <td class="align-top text-end">RP {{ number_format($product->amount, 0, '', '.') }}

                                    </td>
                                </tr>
                            @endforeach
                            <tr class="fw-medium" style="font-size: 13px">
                                <td colspan="{{ $quote->tax != 0 ? '3' : '2' }}" rowspan="9" id="dynamicRows"
                                    style="border-bottom :none !important;">
                                </td>
                                <td colspan="3" id="price" class="text-end pl-4 py-0"
                                    style="padding-right: 10px !important;">
                                    <p class="m-0">
                                        {{ $quote->tax != 0 || $invoice->pph != 0 || $quote->shipping != 0 ? 'Subtotal' : 'Total' }}
                                    </p>
                                    {{-- <p class="m-0">Total</p> --}}
                                </td>
                                <td id="price" class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="text-end m-0">RP
                                        {{ number_format($quote->subtotal, 0, '', '.') }}</p>
                                </td>
                            </tr>
                            @php
                                if ($invoice->flag == 'Reftech') {
                                    $bgColor = 'rgb(224, 248, 248)';
                                } else {
                                    $bgColor = 'rgb(255, 232, 210)';
                                }
                            @endphp
                            @if ($invoice->type == 'CT')
                                @if ($quote->diskon != 0)
                                    <tr class="fw-medium" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0" style="padding-right: 10px !important;">
                                            <p class="m-0">Discount</p>
                                        </td>
                                        <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                            <p class="m-0 text-end">RP
                                                {{ number_format($quote->diskon, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="fw-medium" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0" style="padding-right: 10px !important;">
                                            <p class="m-0">Total After Discount</p>
                                        </td>
                                        <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                            <p class="m-0 text-end">RP
                                                {{ number_format($afterDisc, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                    @if ($quote->tax != 0)
                                        <tr class="fw-medium" style="font-size: 13px">
                                            <td colspan="3" id="price" class="text-end pl-4 py-0"
                                                style="padding-right: 10px !important;">
                                                <p class="m-0">
                                                    DPP Atas PPN
                                                </p>
                                            </td>
                                            <td id="price" class="pr-4 py-0" style="padding-left: 0 !important;">
                                                @php
                                                    $dpp = ($afterDisc * 11) / 12;
                                                @endphp
                                                <p class="text-end m-0">RP
                                                    {{ number_format($dpp, 0, '', '.') }}</p>
                                            </td>
                                        </tr>
                                    @else
                                    @endif
                                @endif
                                @if ($quote->tax != 0 || $totalPph > 0)
                                    @if ($quote->tax != 0)
                                        <tr class="fw-medium" style="font-size: 13px">
                                            <td colspan="3" id="price" class="text-end pl-4 py-0"
                                                style="padding-right: 10px !important;">
                                                <p class="m-0">
                                                    DPP Atas PPN
                                                </p>
                                            </td>
                                            <td id="price" class="pr-4 py-0" style="padding-left: 0 !important;">
                                                @php
                                                    $dpp = ($quote->subtotal * 11) / 12;
                                                @endphp
                                                <p class="text-end m-0">RP
                                                    {{ number_format($dpp, 0, '', '.') }}</p>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr class="fw-medium py-0" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0" style="padding-right: 10px !important;">
                                            <p class="m-0">VAT {{ $quote->tax == '11' ? '12%' : '' }}</p>
                                        </td>
                                        <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                            <p class="m-0 text-end">
                                                {{ $tax == '0' ? '0' : 'RP ' . number_format($tax, 0, '', '.') }}</p>
                                        </td>
                                    </tr>
                                    @if ($totalPph > 0)
                                        <tr class="fw-medium py-0" style="font-size: 13px">
                                            <td colspan="3" class="text-end py-0"
                                                style="padding-right: 10px !important;">
                                                <p class="m-0">PPH</p>
                                            </td>
                                            <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                                <p class="m-0 text-end">
                                                    {{ $totalPph == '0' ? '0' : 'RP ' . number_format($totalPph, 0, '', '.') }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                                @if ($quote->shipping != 0)
                                    <tr class="fw-medium" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0" style="padding-right: 10px !important;">
                                            <p class="m-0">Shipping Cost</p>
                                        </td>
                                        <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                            <p class="m-0 text-end">RP
                                                {{ number_format($quote->shipping, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                @endif
                                @if ($quote->tax != 0 || $totalPph > 0 || $quote->shipping != 0)
                                    <tr class="fw-medium py-0" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0"
                                            style="background-color: {{ $bgColor }}; padding-left:20px; padding-right:10px;">
                                            <p class="m-0 fw-bold">Total</p>
                                        </td>
                                        <td class="pr-4 py-0"
                                            style="background-color: {{ $bgColor }}; padding-right:20px;">
                                            <p class="m-0 text-end fw-bold">
                                                {{ 'RP ' . number_format($quote->harga_total - $totalPph, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                @endif
                            @elseif ($invoice->type == 'DP')
                                @php
                                    $amount1 = $payments[0]->amount / (1 + $quote->tax / 100);
                                    $vat = $amount1 * ($quote->tax / 100);
                                @endphp
                                @if ($quote->diskon != 0)
                                    <tr class="fw-medium" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0" style="padding-right: 10px !important;">
                                            <p class="m-0">Discount</p>
                                        </td>
                                        <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                            <p class="m-0 text-end">RP
                                                {{ number_format($quote->diskon, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="fw-medium" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0"
                                            `style="padding-right: 10px !important;">
                                            <p class="m-0">Total After Discount</p>
                                        </td>
                                        <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                            <p class="m-0 text-end">RP
                                                {{ number_format($afterDisc, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                @endif
                                <tr class="fw-medium" style="font-size: 13px">
                                    <td colspan="3" class="text-end py-0 px-0">
                                        <p class="m-0"
                                            style="background-color: yellow; padding-left:20px; padding-right:10px;">
                                            {{ $payments[0]->note }}
                                            {{ $payments[0]->percent }}%:</p>
                                    </td>
                                    <td class="px-0 py-0" style="padding-left: 0 !important;">
                                        <p class="fw-medium m-0 text-end"
                                            style="background-color: yellow; padding-right:20px;">
                                            RP
                                            {{ number_format($amount1, 0, '', '.') }}</p>
                                    </td>
                                </tr>
                                @if ($quote->tax != 0)
                                    <tr class="fw-medium" style="font-size: 13px">
                                        <td colspan="3" id="price" class="text-end pl-4 py-0"
                                            style="padding-right: 10px !important;">
                                            <p class="m-0">
                                                DPP Atas PPN
                                            </p>
                                        </td>
                                        <td id="price" class="pr-4 py-0" style="padding-left: 0 !important;">
                                            @php
                                                $dpp = ($amount1 * 11) / 12;
                                            @endphp
                                            <p class="text-end m-0">RP
                                                {{ number_format($dpp, 0, '', '.') }}</p>
                                        </td>
                                    </tr>
                                    <tr class="fw-medium py-0" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0" style="padding-right: 10px !important;">
                                            <p class="m-0">VAT {{ $quote->tax == '11' ? '12%' : '' }}</p>
                                        </td>
                                        <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                            <p class="m-0 text-end">
                                                {{ $vat == '0' ? '0' : 'RP ' . number_format($vat, 0, '', '.') }}</p>
                                        </td>
                                    </tr>
                                    @if ($totalPph > 0)
                                        <tr class="fw-medium py-0" style="font-size: 13px">
                                            <td colspan="3" class="text-end py-0"
                                                style="padding-right: 10px !important;">
                                                <p class="m-0">PPH</p>
                                            </td>
                                            <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                                <p class="m-0 text-end">
                                                    {{ $totalPph == '0' ? '0' : 'RP ' . number_format($totalPph, 0, '', '.') }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endif
                                    @php
                                        $totalwithpph = $payments[0]->amount - $totalPph;
                                    @endphp
                                    @if ($quote->shipping != 0)
                                        <tr class="fw-medium" style="font-size: 13px">
                                            <td colspan="3" class="text-end py-0"
                                                style="padding-right: 10px !important;">
                                                <p class="m-0">Shipping Cost</p>
                                            </td>
                                            <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                                <p class="m-0 text-end">RP
                                                    {{ number_format($quote->shipping, 0, '', '.') }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr class="fw-medium py-0" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0"
                                            style="background-color: {{ $bgColor }}; padding-left:20px; padding-right:10px;">
                                            <p class="m-0 fw-bold">Total Include VAT</p>
                                        </td>
                                        <td class="pr-4 py-0"
                                            style="background-color: {{ $bgColor }}; padding-right:20px;">
                                            <p class="m-0 text-end fw-bold">
                                                Rp {{ number_format($totalwithpph, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                @else
                                    @if ($quote->shipping != 0)
                                        <tr class="fw-medium" style="font-size: 13px">
                                            <td colspan="3" class="text-end py-0"
                                                style="padding-right: 10px !important;">
                                                <p class="m-0">Shipping Cost</p>
                                            </td>
                                            <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                                <p class="m-0 text-end">RP
                                                    {{ number_format($quote->shipping, 0, '', '.') }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr class="fw-medium py-0" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0"
                                            style="background-color: {{ $bgColor }}; padding-left:20px; padding-right:10px;">
                                            <p class="m-0">Total</p>
                                        </td>
                                        <td class="pr-4 py-0"
                                            style="background-color: {{ $bgColor }}; padding-right:20px;">
                                            <p class="m-0 text-end fw-bold">
                                                {{ number_format($payments[0]->amount, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                @endif
                            @elseif ($invoice->type == 'BP')
                                @php
                                    $amount1 = $payments[0]->amount / (1 + $quote->tax / 100);
                                    $amount2 = $payments[1]->amount / (1 + $quote->tax / 100);
                                    $vat = $amount2 * ($quote->tax / 100);
                                @endphp
                                @if ($quote->diskon != 0)
                                    <tr class="fw-medium" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0" style="padding-right: 10px !important;">
                                            <p class="m-0">Discount</p>
                                        </td>
                                        <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                            <p class="m-0 text-end">RP
                                                {{ number_format($quote->diskon, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr class="fw-medium" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0" style="padding-right: 10px !important;">
                                            <p class="m-0">Total After Discount</p>
                                        </td>
                                        <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                            <p class="m-0 text-end">RP
                                                {{ number_format($afterDisc, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                @endif
                                <tr class="fw-medium" style="font-size: 13px">
                                    <td colspan="3" class="text-end py-0" style="padding-right: 10px !important;">
                                        <p class="m-0">
                                            {{ $payments[0]->note }}
                                            {{ $payments[0]->percent }}%:</p>
                                    </td>
                                    <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                        <p class="m-0 text-end">
                                            RP
                                            {{ number_format($amount1, 0, '', '.') }}</p>
                                    </td>
                                </tr>
                                <tr class="fw-medium" style="font-size: 13px">
                                    <td colspan="3" class="text-end py-0 px-0">
                                        <p class="m-0"
                                            style="background-color: yellow; padding-left:20px; padding-right:10px;">
                                            {{ $payments[1]->note }}
                                            {{ $payments[1]->percent }}%:</p>
                                    </td>
                                    <td class="px-0 py-0" style="padding-left: 0 !important;">
                                        <p class="m-0 text-end" style="background-color: yellow; padding-right:20px;">
                                            RP
                                            {{ number_format($amount2, 0, '', '.') }}</p>
                                    </td>
                                </tr>
                                @if ($totalPph > 0)
                                    <tr class="fw-medium py-0" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0" style="padding-right: 10px !important;">
                                            <p class="m-0">PPH</p>
                                        </td>
                                        <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                            <p class="m-0 text-end">
                                                {{ $totalPph == '0' ? '0' : 'RP ' . number_format($totalPph, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                @endif
                                @php
                                    $totalwithpph = $payments[1]->amount - $totalPph;
                                @endphp
                                @if ($quote->tax != 0)
                                    <tr class="fw-medium" style="font-size: 13px">
                                        <td colspan="3" id="price" class="text-end pl-4 py-0"
                                            style="padding-right: 10px !important;">
                                            <p class="m-0">
                                                DPP Atas PPN
                                            </p>
                                        </td>
                                        <td id="price" class="pr-4 py-0" style="padding-left: 0 !important;">
                                            @php
                                                $dpp = ($amount2 * 11) / 12;
                                            @endphp
                                            <p class="text-end m-0">RP
                                                {{ number_format($dpp, 0, '', '.') }}</p>
                                        </td>
                                    </tr>
                                    <tr class="fw-medium py-0" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0" style="padding-right: 10px !important;">
                                            <p class="m-0">VAT {{ $quote->tax == '11' ? '12%' : '' }}</p>
                                        </td>
                                        <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                            <p class="m-0 text-end">
                                                {{ $vat == '0' ? '0' : 'RP ' . number_format($vat, 0, '', '.') }}</p>
                                        </td>
                                    </tr>
                                    @if ($quote->shipping != 0)
                                        <tr class="fw-medium" style="font-size: 13px">
                                            <td colspan="3" class="text-end py-0"
                                                style="padding-right: 10px !important;">
                                                <p class="m-0">Shipping Cost</p>
                                            </td>
                                            <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                                <p class="m-0 text-end">RP
                                                    {{ number_format($quote->shipping, 0, '', '.') }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr class="fw-medium py-0" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0"
                                            style="background-color: {{ $bgColor }}; padding-left:20px; padding-right:10px;">
                                            <p class="m-0">Total Include VAT</p>
                                        </td>
                                        <td class="pr-4 py-0"
                                            style="background-color: {{ $bgColor }}; padding-right:20px;">
                                            <p class="m-0 text-end fw-bold">
                                                RP {{ number_format($totalwithpph, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                @else
                                    @if ($quote->shipping != 0)
                                        <tr class="fw-medium" style="font-size: 13px">
                                            <td colspan="3" class="text-end py-0"
                                                style="padding-right: 10px !important;">
                                                <p class="m-0">Shipping Cost</p>
                                            </td>
                                            <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                                <p class="m-0 text-end">RP
                                                    {{ number_format($quote->shipping, 0, '', '.') }}
                                                </p>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr class="fw-medium py-0" style="font-size: 13px">
                                        <td colspan="3" class="text-end py-0"
                                            style="background-color: {{ $bgColor }}; padding-left:20px; padding-right:10px;">
                                            <p class="m-0">Total</p>
                                        </td>
                                        <td class="pr-4 py-0"
                                            style="background-color: {{ $bgColor }}; padding-right:20px;">
                                            <p class="m-0 text-end fw-bold">
                                                Rp {{ number_format($totalwithpph, 0, '', '.') }}
                                            </p>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        </tbody>
                    </table>
                </div>
                
                @if ($invoice->type == 'CT')
                <p class="fs-5 fw-medium mt-2 p-2" style="background-color: rgb(248, 248, 248); width:70%;"> Say
                    amount: #
                    {{ $fullPrice }} Rupiah</p>
                @elseif ($invoice->type == 'DP')
                    <p class="fs-5 fw-medium mt-2 p-2" style="background-color: rgb(248, 248, 248); width:70%;"> Say
                        amount: #
                        {{ $priceDp }} Rupiah</p>
                @elseif ($invoice->type == 'BP')
                    <p class="fs-5 fw-medium mt-2 p-2" style="background-color: rgb(248, 248, 248); width:70%;"> Say
                        amount: #
                        {{ $priceBp }} Rupiah</p>
                @endif
                <div class="row mt-5">
                    <div class="col-6 m-4">
                        <h5 class="my-4">Payment by Transfer or Giro shall be made in Full amount to :</h5>
                        <div class="row">
                            <div class="col-3 fw-medium">
                                <p class="mb-1">Payable to</p>
                                <p class="mb-1">Acc Name </p>
                                <p class="mb-1">Acc No. </p>
                                <p class="mb-1">Swift Code </p>
                            </div>
                            @if ($invoice->flag == 'Reftech' && $invoice->quote->tax == 0)
                                <div class="col">
                                    <p class="mb-1">: Bank BCA (IDR)</p>
                                    <p class="mb-1">: ARIEP RACHMAN</p>
                                    <p class="mb-1">: 166 - 2242 - 271</p>
                                    <p class="mb-1">: -</p>
                                </div>
                            @elseif ($invoice->flag == 'Reftech' && $invoice->quote->tax > 0)
                                <div class="col">
                                    <p class="mb-1">: Bank BCA (IDR)</p>
                                    <p class="mb-1">: PT. REFTECH JAYA OPTIMA</p>
                                    <p class="mb-1">: 008 - 6289 - 789</p>
                                    <p class="mb-1">: CENAIDJA</p>
                                </div>
                            @elseif ($invoice->flag == 'Kojisha' && $invoice->quote->tax == 0)
                                <div class="col">
                                    <p class="mb-1">: Bank BCA (IDR)</p>
                                    <p class="mb-1">: REGITA DWI MELINDA</p>
                                    <p class="mb-1">: 1560239137</p>
                                    <p class="mb-1">: - </p>
                                </div>
                            @elseif ($invoice->flag == 'Kojisha' && $invoice->quote->tax > 0)
                                <div class="col">
                                    <p class="mb-1">: Bank BCA (IDR)</p>
                                    <p class="mb-1">: KOJISHA INNOTIV INDONESIA PT</p>
                                    <p class="mb-1">: 5223876543</p>
                                    <p class="mb-1">: - </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col"></div>
                    @if ($invoice->flag == 'Reftech')
                        <div class="col-4 my-5 text-center">
                            <p>Bandung, {{ Carbon\Carbon::parse($invoice->date)->locale('ID')->translatedFormat('d F Y') }}
                            </p>
                            <p class="fs-normal fw-bolder">PT. Reftech Jaya Optima</p>
                            @if (isset($invoice->sign))
                                <img src="{{ url('') . '/' . $invoice->sign }}" alt="" srcset=""
                                    height="77">
                            @else
                                <div style="padding: 40px 0;"></div>
                            @endif
                            {{-- <div class="pb-5"></div> --}}
                            <p class="pt-3 fw-bolder">Ariep Rachman</p>
                            <p>Director</p>
                        </div>
                    @else
                        <div class="col-4 my-5 text-center">
                            <p>Bekasi, {{ Carbon\Carbon::parse($invoice->date)->format('d F Y') }}</p>
                            <p class="fs-normal fw-bolder">PT. Kojisha Innotiv Indonesia </p>
                            @if (isset($invoice->sign))
                                <img src="{{ url('') . '/' . $invoice->sign }}" alt="" srcset=""
                                    height="77">
                            @else
                                <div style="padding: 40px 0;"></div>
                            @endif
                            {{-- <div class="pb-5"></div> --}}
                            <p class="pt-3 fw-bolder">Dedeh Sulastri</p>
                            <p>Director</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- End: Invoice --}}
        {{-- Button Invocie --}}
        @if (Auth::user()->role != 'Logistic')
            <div class="col-xl-3 col-md-4 col-12 invoice-actions">
                <div class="card mb-3">
                    <div class="card-body">
                        <a class="btn btn-primary d-grid w-100 mb-3 waves-effect" target="_blank"
                            href="{{ route('print.invoice', $invoice->id) }}">
                            Download
                        </a>
                        <a type="button" data-bs-toggle="modal" data-bs-target="#descView"
                            class="d-grid w-100 waves-effect mb-3">
                            <button type="button" class="btn btn-outline-primary">
                                Change Description Product
                            </button>
                        </a>
                        @if (!isset($return) && Auth::user()->role == 'Sales')
                            <a class="btn btn-outline-secondary d-grid w-100 mb-3 waves-effect"
                                href="{{ route('return.edit', $invoice->id) }}">
                                Request Return Invoice
                            </a>
                        @elseif(@$return->lvl == '0' && Auth::user()->role == 'Sales')
                            <button type="button" class="btn btn-outline-primary d-grid w-100 waves-effect mb-3">
                                Waiting Warehouse Accept
                            </button>
                        @elseif(@$return->lvl == '1')
                            <a class="btn btn-instagram d-grid w-100 mb-3 waves-effect"
                                href="{{ route('return.show', $return->id) }}">
                                Return Invoice
                            </a>
                        @endif
                        <a type="button" data-bs-toggle="modal" data-bs-target="#changeDate"
                            class="d-grid w-100 waves-effect mb-3">
                            <button type="button" class="btn btn-secondary">
                                Change Date
                            </button>
                        </a>
                        <a href="#" class="btn btn-outline-danger d-grid w-100 waves-effect delete-invoice mb-3"
                            data-id="{{ $quote->id }}">Delete</a>
                        <button class="btn btn-outline-secondary d-grid w-100 mb-3 waves-effect" id="backButton">
                            Back
                        </button>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        @if ($totalPph > 0)
                            <a href="#" class="btn btn-danger d-grid w-100 waves-effect delete-pph mb-3"
                                data-id="{{ $invoice->id }}">Delete PPH</a>
                        @else
                            <a type="button" data-bs-toggle="modal" data-bs-target="#addPph"
                                class="d-grid w-100 waves-effect mb-3">
                                <button type="button" class="btn btn-twitter">
                                    Input PPH
                                </button>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        @if (isset($invoice->sign))
                            <a href="#" class="btn btn-danger d-grid w-100 waves-effect delete-hand-sign mb-3"
                                data-id="{{ $invoice->id }}">Delete Hand Sign</a>
                        @else
                            <a href="#" class="btn btn-secondary d-grid w-100 waves-effect input-hand-sign mb-3"
                                data-id="{{ $invoice->id }}">Input Hand Sign</a>
                        @endif
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <button type="button" class="btn btn-secondary w-100 waves-effect waves-light mb-3"
                            data-bs-toggle="modal" data-bs-target="#detailPayment"> Detail Payment </button>
                        <h5>Remaining : Rp {{ number_format($remaining, 0, '.', ',') }}</h5>
                    </div>
                </div>
                @if (Auth::user()->role == 'Admin')
                    <div class="card mb-3">
                        <div class="card-body">
                            <a type="button" data-bs-toggle="modal" data-bs-target="#doTeknisi"
                                class="d-grid w-100 waves-effect mb-3">
                                <button type="button" class="btn btn-success">
                                    Create Delivery Order Teknisi
                                </button>
                            </a>
                            <a type="button" data-bs-toggle="modal" data-bs-target="#doEkspedisi"
                                class="d-grid w-100 waves-effect mb-3">
                                <button type="button" class="btn btn-whatsapp">
                                    Create Delivery Order Ekspedisi
                                </button>
                            </a>
                            {{-- <a class="btn btn-whatsapp d-grid w-100 mb-3 waves-effect"
                        href="{{ route('invoice.do_ekspedisi', $invoice->id) }}">
                        Delivery Order Ekspedisi
                    </a> --}}
                        </div>
                    </div>
                    @php
                        $eks = 0;
                        $tek = 0;
                    @endphp
                    @if ($doTek->count() >= 1 || $doEks->count() >= 1)
                        <div class="card mb-3">
                            <div class="card-body">
                                @foreach ($doTek as $teknisi)
                                    @php
                                        $tek++;
                                    @endphp
                                    <a class="btn btn-whatsapp d-grid w-100 mb-3 waves-effect"
                                        href="{{ route('delivery.show', $teknisi->id) }}">
                                        Delivery Order Teknisi ({{ $tek }})
                                    </a>
                                @endforeach
                                @foreach ($doEks as $ekspedisi)
                                    @php
                                        $eks++;
                                    @endphp
                                    <a class="btn btn-whatsapp d-grid w-100 mb-3 waves-effect"
                                        href="{{ route('delivery.show', $ekspedisi->id) }}">
                                        Delivery Order Ekspedisi ({{ $eks }})
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="card mb-3">
                        <div class="card-body">
                            <a class="btn btn-primary d-grid w-100 mb-3 waves-effect"
                                href="{{ route('invoice.label_detail', $invoice->id) }}">
                                Cetak Sampul Surat
                            </a>
                        </div>
                    </div>
                @endif
                {{-- End : Button Invoice --}}
            </div>
        @else
            <div class="col-xl-3 col-md-4 col-12 invoice-actions">
                <div class="card">
                    <div class="card-body">
                        @if (@$pOut)
                            <a class="btn btn-primary d-grid w-100 mb-3 waves-effect"
                                href="{{ route('product-out.show', $pOut->id) }}">
                                Go To Product Out
                            </a>
                        @else
                            <a class="btn btn-primary d-grid w-100 mb-3 waves-effect"
                                href="{{ route('product-out.invoice', $invoice->id) }}">
                                Create Product Out
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        @include('components.modal.quotation.detail-payment')
        @include('components.modal.accounting.sign')
        @include('components.modal.invoice.date')
        @include('components.modal.invoice.desc')
        @include('components.modal.invoice.pph')
        @include('components.modal.accounting.delivery.create-teknisi')
        @include('components.modal.accounting.delivery.create-ekspedisi')
    @endsection
    @push('after-style')
        <!-- Page CSS -->
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
    @endpush
    @push('after-script')
        <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
    @endpush
    @push('page-script')
        <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
    @endpush
    @push('script')
        <script>
            // $(document).on('click', '.delete-contract', function() {
            //     var id = $(this).data('id');
            //     var quoteId = $(this).data('quote');
            //     Swal.fire({
            //         title: "Are you sure?",
            //         text: "You won't be able to revert this!",
            //         icon: "warning",
            //         showCancelButton: true,
            //         confirmButtonText: "Yes, delete it!",
            //         customClass: {
            //             confirmButton: "btn btn-primary me-3 waves-effect waves-light",
            //             cancelButton: "btn btn-label-secondary waves-effect",
            //         },
            //         buttonsStyling: false,
            //     }).then(function(result) {
            //         if (result.value) {
            //             $.ajax({
            //                 'url': '{{ url('contract') }}/' + id,
            //                 'type': 'POST',
            //                 'data': {
            //                     '_method': 'DELETE',
            //                     '_token': '{{ csrf_token() }}'
            //                 },
            //                 success: function(response) {
            //                     if (response == 1) {
            //                         Swal.fire({
            //                             icon: "success",
            //                             title: "Deleted!",
            //                             text: "Your file has been deleted.",
            //                             customClass: {
            //                                 confirmButton: "btn btn-success waves-effect",
            //                             },
            //                         })
            //                         window.setTimeout(function() {
            //                             window.location.href = '/quotation/' + quoteId;
            //                         }, 2000);
            //                     } else {
            //                         Swal.fire({
            //                             icon: 'error',
            //                             title: 'Oops...',
            //                             text: 'Data Failed to Delete!'
            //                         });
            //                     }
            //                 }
            //             });
            //         } else if (result.dismiss === Swal.DismissReason.cancel) {
            //             Swal.fire({
            //                 title: "Cancelled",
            //                 text: "Your imaginary file is safe :)",
            //                 icon: "error",
            //                 customClass: {
            //                     confirmButton: "btn btn-success waves-effect",
            //                 },
            //             });
            //         }
            //     });
            // });
            $('#backButton').click(function() {
                window.history.back();
            });
            $(() => {

                const dateInput = document.getElementById('dateInput');
                const resetCheckbox = document.getElementById('checkDate');

                // Saat checkbox di-check
                resetCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        dateInput.value = ''; // Hapus nilai date
                    }
                });

                // Saat input tanggal diisi
                dateInput.addEventListener('input', function() {
                    if (this.value) {
                        resetCheckbox.checked = false; // Uncheck checkbox
                    }
                });
                $('#formFileMultiple').on('change', function() {
                    var files = this.files;
                    var dynamicInputsContainer = $('#dynamicInputsContainer');
                    dynamicInputsContainer.empty();

                    // Hanya mengambil satu file (file pertama)
                    var file = files[0];
                    console.log(file);
                    const previewContainer = document.getElementById('image-preview');
                    previewContainer.innerHTML = '';

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const imageContainer = document.createElement('div');
                        const imageElement = document.createElement('img');
                        imageContainer.className = 'image-container'; // Tambahkan kelas sesuai kebutuhan

                        // Set maksimum lebar dan tinggi untuk gambar
                        imageElement.style.maxWidth =
                            '800px'; // Ganti dengan nilai maksimum lebar yang Anda inginkan
                        imageElement.style.maxHeight =
                            '500px'; // Ganti dengan nilai maksimum tinggi yang Anda inginkan

                        imageElement.src = e.target.result;

                        imageContainer.appendChild(imageElement);
                        previewContainer.appendChild(imageContainer);
                    };

                    reader.readAsDataURL(file);
                });
            });
            $(document).on('click', '.delete-hand-sign', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    customClass: {
                        confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                        cancelButton: "btn btn-label-secondary waves-effect",
                    },
                    buttonsStyling: false,
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            'url': '{{ url('invoice') }}/del-sign/' + id,
                            'type': 'POST',
                            'data': {
                                '_method': 'DELETE',
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response == 1) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        customClass: {
                                            confirmButton: "btn btn-success waves-effect",
                                        },
                                    })
                                    window.setTimeout(function() {
                                        window.location.href = '/invoice/' + id;
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Data Failed to Delete!'
                                    });
                                }
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: "Cancelled",
                            text: "Your imaginary file is safe :)",
                            icon: "error",
                            customClass: {
                                confirmButton: "btn btn-success waves-effect",
                            },
                        });
                    }
                });
            });
            $(document).on('click', '.input-hand-sign', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, input it!",
                    customClass: {
                        confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                        cancelButton: "btn btn-label-secondary waves-effect",
                    },
                    buttonsStyling: false,
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            'url': '{{ url('invoice') }}/sign/' + id,
                            'type': 'POST',
                            'data': {
                                '_method': 'POST',
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response == 1) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Inputed!",
                                        text: "Your image has been Inputed.",
                                        customClass: {
                                            confirmButton: "btn btn-success waves-effect",
                                        },
                                    })
                                    window.setTimeout(function() {
                                        window.location.href = '/invoice/' + id;
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Data Failed to Input!'
                                    });
                                }
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: "Cancelled",
                            text: "Yout Image cancelled to input :)",
                            icon: "error",
                            customClass: {
                                confirmButton: "btn btn-success waves-effect",
                            },
                        });
                    }
                });
            });
            $(document).on('click', '.delete-invoice', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    customClass: {
                        confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                        cancelButton: "btn btn-label-secondary waves-effect",
                    },
                    buttonsStyling: false,
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            'url': '{{ url('invoice') }}/' + id,
                            'type': 'POST',
                            'data': {
                                '_method': 'DELETE',
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response == 1) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        customClass: {
                                            confirmButton: "btn btn-success waves-effect",
                                        },
                                    })
                                    window.setTimeout(function() {
                                        window.location.href = '/quotation';
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Data Failed to Delete!'
                                    });
                                }
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: "Cancelled",
                            text: "Your imaginary file is safe :)",
                            icon: "error",
                            customClass: {
                                confirmButton: "btn btn-success waves-effect",
                            },
                        });
                    }
                });
            });
            $(document).on('click', '.delete-pph', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    customClass: {
                        confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                        cancelButton: "btn btn-label-secondary waves-effect",
                    },
                    buttonsStyling: false,
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            'url': '{{ url('invoice') }}/del-pph/' + id,
                            'type': 'POST',
                            'data': {
                                '_method': 'DELETE',
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response == 1) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        customClass: {
                                            confirmButton: "btn btn-success waves-effect",
                                        },
                                    })
                                    window.setTimeout(function() {
                                        window.location.href = '/invoice/' + id;
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Data Failed to Delete!'
                                    });
                                }
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: "Cancelled",
                            text: "Your imaginary file is safe :)",
                            icon: "error",
                            customClass: {
                                confirmButton: "btn btn-success waves-effect",
                            },
                        });
                    }
                });
            });
        </script>
    @endpush
