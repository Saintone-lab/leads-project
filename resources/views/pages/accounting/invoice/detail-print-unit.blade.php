@extends('layouts.sales.app')
@section('title', $invoice->no_invoice ?? 'Invoice Unit')
<div class="invoice-print p-4">
    <div class="container-fluid flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex justify-content-{{ !$quote->tax ? 'end' : 'between' }} flex-xl-row flex-md-column flex-sm-row flex-column">
            @if ($quote->tax)
                <div class="mb-xl-0 pb-1">
                    <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                        <span class="app-brand-logo demo">
                            <span style="color: var(--bs-primary)">
                                <img class="text-md" src="{{ asset('/asset') }}/logo/Reftech-Log.png" alt="" srcset="" width="60%">
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
                                    <i class="mdi mdi-phone-outline scaleX-n1-rtl me-1 mdi-14px"></i>022 54417653
                                    &nbsp;&nbsp;<i class="mdi mdi-email-outline scaleX-n1-rtl me-1 mdi-14px"></i>info@reftech.id
                                </p>
                            </div>
                        </div>
                        <div class="npwp_add">
                            <p class="mb-1 fw-bolder">NPWP Address :</p>
                            <pre style="font-size: 10px; font-family: Inter, sans-serif; max-width: 100%; white-space: pre-wrap; word-break: break-word;">Komp. Negia Kencana Residence Blok B, No.2 Pasanggrahan, Ujung Berung Kota Bandung - Jawa Barat 40199</pre>
                            <p class="mb-1 text-black fw-medium p-1" style="background-color: rgb(224, 221, 255); font-size: 10px;">
                                NPWP : 73.728.571.8-429.000
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="text-end">
                <h1 class="fw-bold" style="color: blue;">INVOICE</h1>
                <div>
                    <span class="fw-bolder" style="font-size: 18px">{{ $invoice->no_invoice }}</span>
                </div>
                <div class="mt-1">
                    <span class="fw-medium">{{ $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d-m-Y') : '-' }}</span>
                </div>
            </div>
        </div>

        <hr>

        {{-- Invoice To --}}
        <h5>Invoice To</h5>
        <div>
            <table class="table table-bordered" style="border: 1px solid black; width: 100%;">
                <tr>
                    <td rowspan="3" style="vertical-align: top; width: 60%;">
                        <div class="row">
                            <div class="col-2 fw-medium"><p class="mb-1">Bill To</p></div>
                            <div class="col-10">
                                <p class="mb-1 fw-bolder">: {{ $quote->client?->company }}</p>
                            </div>
                            <div class="col-2 fw-medium"><p class="mb-1">PIC</p></div>
                            <div class="col-10">
                                <p class="mb-1 fw-bolder">: {{ $quote->pic?->name_pic ?? $quote->attn }}</p>
                            </div>
                            <div class="col-2 fw-medium"><p class="mb-1">NPWP</p></div>
                            <div class="col-10">
                                <p class="mb-1">: {{ $quote->client?->npwp }}</p>
                            </div>
                            <div class="col-2 fw-medium"><p class="mb-1">Phone</p></div>
                            <div class="col-10">
                                <p class="mb-1">: {{ $quote->client?->phone }}</p>
                            </div>
                            <div class="col-2 fw-medium"><p class="mb-1">Address</p></div>
                            <div class="col-10">
                                @if ($invoice->invoiceTo == '1')
                                    <pre style="font-size: 13px; font-family: Inter, sans-serif; max-width: 100%; white-space: pre-wrap; word-break: break-word;">: {{ $quote->client?->address }}</pre>
                                @else
                                    <pre style="font-size: 13px; font-family: Inter, sans-serif; max-width: 100%; white-space: pre-wrap; word-break: break-word;">: {{ $quote->client?->subAddress }}</pre>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td><p>Purchase Order</p></td>
                    <td><p class="fs-6 text-black fw-bold m-0">{{ $quote->po_number }}</p></td>
                </tr>
                <tr>
                    <td colspan="2" style="background-color: #F9F9F9;" class="text-center">
                        <p class="fs-5 text-black fw-medium m-0">Term Of Payment:</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <pre style="font-size: 14px; font-family: Inter;">{{ $invoice->term ?? $quote->payment_method }}</pre>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Items --}}
        @php
            $afterDisc  = $quote->subtotal - $quote->discount_amount;
            $bgColor    = 'rgb(224, 248, 248)';
            $hasDisc    = $quote->details->where('disc', '>', 0)->count() > 0;
            $labelSpan  = $quote->tax ? ($hasDisc ? 3 : 2) : 3;
            $amountSpan = ($quote->tax || $hasDisc) ? 2 : 1;
        @endphp
        <div>
            <table class="table table-bordered m-0" style="border: 1px solid rgb(60,60,60); width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width:1%">No.</th>
                        <th class="text-center" style="width:{{ $quote->tax ? '38%' : '45%' }}">Item</th>
                        <th class="text-center">Price</th>
                        <th class="text-center" style="width:1%; white-space:nowrap">Qty</th>
                        @if ($hasDisc)
                            <th class="text-center">Disc</th>
                        @endif
                        @if ($quote->tax)
                            <th class="text-center">DPP</th>
                        @endif
                        <th class="text-center">Amount</th>
                    </tr>
                </thead>
                @php
                    $specLabels = [
                        'brand'=>'Brand','model'=>'Model','type_unit'=>'Type',
                        'bar'=>'Max Pressure','air_cap'=>'Air Capacity','power'=>'Motor Power',
                        'voltage'=>'Voltage','connect'=>'Drive','cooling'=>'Cooling Method',
                        'exhaust'=>'Connection','refrigerant_type'=>'Refrigerant Type','pdp'=>'PDP',
                        'filtration'=>'Filtration','oil_content'=>'Oil Content','grade'=>'Grade',
                        'capacity'=>'Capacity','material'=>'Material','test_pressure'=>'Test Pressure',
                        'inlet_pressure'=>'Inlet Pressure','outlet_pressure'=>'Outlet Pressure',
                        'inlet_cap'=>'Inlet Capacity (LP)','outlet_cap'=>'Outlet Capacity (HP)',
                        'dimension'=>'Dimension','weight'=>'Weight',
                    ];
                    $specUnits = [
                        'bar'=>' Bar','air_cap'=>' m³/min',
                        'filtration'=>' µm','oil_content'=>' ppm',
                        'test_pressure'=>' Bar','inlet_pressure'=>' Bar','outlet_pressure'=>' Bar',
                        'inlet_cap'=>' m³/min','outlet_cap'=>' m³/min',
                        'weight'=>' Kg','capacity'=>' Liter',
                    ];
                    $specLabelsOverride = [
                        'AIR RECEIVER TANK' => ['bar'=>'Max. Pressure','grade'=>'T Plate','cooling'=>'Certification'],
                        'FILTRATION SYSTEM'  => ['air_cap'=>'Flowrate','material'=>'Element','connect'=>'Drain'],
                    ];
                @endphp
                <tbody>
                    @foreach ($quote->details as $i => $detail)
                        @php $dpp = $quote->tax ? ($detail->amount * 11 / 12) : 0; @endphp
                        <tr style="font-size: 13px">
                            <td class="align-top">{{ $i + 1 }}</td>
                            <td class="align-top">
                                @if ($detail->type === 'unit' && $detail->unit)
                                    <p class="mb-1 fw-medium" style="font-size: 12px">{{ $detail->label ?: ($detail->unit->brand . ' ' . $detail->unit->model) }}</p>
                                    @php
                                        $specs       = $detail->getSpecVisibleArray();
                                        $category    = $detail->unit->unit ?? '';
                                        $catOverride = $specLabelsOverride[$category] ?? [];
                                    @endphp
                                    @if (!empty($specs) && $invoice->show_spec)
                                        <div style="font-size:10px; color:#555; margin-top:3px;">
                                            @foreach ($specs as $field)
                                                @if ($field === 'unit') @continue @endif
                                                @php $val = $detail->unit->$field ?? null; @endphp
                                                @if ($val && isset($specLabels[$field]))
                                                    <div style="display:flex; padding:1px 0;">
                                                        <span style="color:#888; min-width:110px; flex-shrink:0;">{{ $catOverride[$field] ?? $specLabels[$field] }}</span>
                                                        <span>: {{ $val }}{{ $specUnits[$field] ?? '' }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <p class="mb-0 fw-medium" style="font-size: 12px">{{ $detail->label }}</p>
                                    @if ($detail->description)
                                        <pre class="mb-0" style="font-size: 10px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $detail->description }}</pre>
                                    @endif
                                @endif
                            </td>
                            <td class="align-top text-end">{{ number_format($detail->price, 0, '', '.') }}</td>
                            <td class="align-top text-center" style="white-space:nowrap">{{ intval($detail->qty) }} {{ $detail->info_qty }}</td>
                            @if ($hasDisc)
                                <td class="align-top text-center">{{ $detail->disc > 0 ? intval($detail->disc) . '%' : '-' }}</td>
                            @endif
                            @if ($quote->tax)
                                <td class="align-top text-end">{{ number_format($dpp, 0, '', '.') }}</td>
                            @endif
                            <td class="align-top text-end">{{ number_format($detail->amount, 0, '', '.') }}</td>
                        </tr>
                    @endforeach

                    {{-- Finance Summary --}}
                    <tr class="fw-medium" style="font-size: 13px">
                        <td colspan="{{ $quote->tax ? 2 : 1 }}" rowspan="9" style="border: none !important;"></td>
                        <td colspan="{{ $labelSpan }}" class="text-end py-0" style="padding-right: 10px !important;">
                            <p class="m-0">{{ ($quote->tax || $totalPph > 0) ? 'Subtotal' : 'Total' }}</p>
                        </td>
                        <td colspan="{{ $amountSpan }}" class="py-0 text-end" style="padding-right: 10px !important;">
                            <p class="m-0">Rp {{ number_format($quote->subtotal, 0, '', '.') }}</p>
                        </td>
                    </tr>
                    @if ($quote->diskon > 0)
                        <tr class="fw-medium" style="font-size: 13px">
                            <td colspan="{{ $labelSpan }}" class="text-end py-0" style="padding-right: 10px !important;">
                                <p class="m-0">Discount ({{ $quote->discount_label }})</p>
                            </td>
                            <td colspan="{{ $amountSpan }}" class="py-0 text-end" style="padding-right: 10px !important;">
                                <p class="m-0">- Rp {{ number_format($quote->discount_amount, 0, '', '.') }}</p>
                            </td>
                        </tr>
                        <tr class="fw-medium" style="font-size: 13px">
                            <td colspan="{{ $labelSpan }}" class="text-end py-0" style="padding-right: 10px !important;">
                                <p class="m-0">Total After Discount</p>
                            </td>
                            <td colspan="{{ $amountSpan }}" class="py-0 text-end" style="padding-right: 10px !important;">
                                <p class="m-0">Rp {{ number_format($afterDisc, 0, '', '.') }}</p>
                            </td>
                        </tr>
                    @endif
                    @if ($quote->tax)
                        <tr class="fw-medium" style="font-size: 13px">
                            <td colspan="{{ $labelSpan }}" class="text-end py-0" style="padding-right: 10px !important;">
                                <p class="m-0">DPP Atas PPN</p>
                            </td>
                            <td colspan="{{ $amountSpan }}" class="py-0 text-end" style="padding-right: 10px !important;">
                                <p class="m-0">Rp {{ number_format($afterDisc * 11 / 12, 0, '', '.') }}</p>
                            </td>
                        </tr>
                        <tr class="fw-medium" style="font-size: 13px">
                            <td colspan="{{ $labelSpan }}" class="text-end py-0" style="padding-right: 10px !important;">
                                <p class="m-0">VAT 12%</p>
                            </td>
                            <td colspan="{{ $amountSpan }}" class="py-0 text-end" style="padding-right: 10px !important;">
                                <p class="m-0">Rp {{ number_format($quote->tax_amount, 0, '', '.') }}</p>
                            </td>
                        </tr>
                    @endif
                    @if ($totalPph > 0)
                        <tr class="fw-medium" style="font-size: 13px">
                            <td colspan="{{ $labelSpan }}" class="text-end py-0" style="padding-right: 10px !important;">
                                <p class="m-0">PPH</p>
                            </td>
                            <td colspan="{{ $amountSpan }}" class="py-0 text-end" style="padding-right: 10px !important;">
                                <p class="m-0">Rp {{ number_format($totalPph, 0, '', '.') }}</p>
                            </td>
                        </tr>
                    @endif
                    @if ($quote->tax || $totalPph > 0)
                        <tr class="fw-medium" style="font-size: 13px">
                            <td colspan="{{ $labelSpan }}" class="text-end py-0" style="background-color:{{ $bgColor }}; padding-right: 10px !important;">
                                <p class="m-0 fw-bold">Total</p>
                            </td>
                            <td colspan="{{ $amountSpan }}" class="py-0 text-end" style="background-color:{{ $bgColor }}; padding-right: 10px !important;">
                                <p class="m-0 fw-bold">Rp {{ number_format($quote->total, 0, '', '.') }}</p>
                            </td>
                        </tr>
                    @endif
                    @if (floatval($invoice->percent) < 100)
                        <tr style="font-size: 13px; background:#fff3cd;">
                            <td colspan="{{ $labelSpan }}" class="text-end py-1 fw-bold" style="padding-right: 10px !important;">
                                <p class="m-0">{{ $invoice->type }} ({{ floatval($invoice->percent) }}% dari total)</p>
                            </td>
                            <td colspan="{{ $amountSpan }}" class="py-1 fw-bold text-end" style="padding-right: 10px !important;">
                                <p class="m-0">Rp {{ number_format($totalAfterPph, 0, '', '.') }}</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Terbilang --}}
        <p class="fw-medium mt-2 p-2" style="background-color: rgb(248,248,248); width: 70%;">
            Say amount: # {{ $terbilang }} Rupiah
        </p>

        {{-- Bank & TTD --}}
        <div class="row mt-3 mx-0">
            <div class="col-6 px-0">
                <h5 class="mb-2 mt-2">Payment by Transfer or Giro shall be made in Full amount to :</h5>
                <div class="row">
                    <div class="col-3 fw-medium">
                        <p class="mb-1">Payable to</p>
                        <p class="mb-1">Acc Name</p>
                        <p class="mb-1">Acc No.</p>
                        <p class="mb-1">Swift Code</p>
                    </div>
                    @if ($quote->tax)
                        <div class="col">
                            <p class="mb-1">: Bank BCA (IDR)</p>
                            <p class="mb-1">: PT. REFTECH JAYA OPTIMA</p>
                            <p class="mb-1">: 008 - 6289 - 789</p>
                            <p class="mb-1">: CENAIDJA</p>
                        </div>
                    @else
                        <div class="col">
                            <p class="mb-1">: Bank BCA (IDR)</p>
                            <p class="mb-1">: ARIEP RACHMAN</p>
                            <p class="mb-1">: 166 - 2242 - 271</p>
                            <p class="mb-1">: -</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col"></div>
            <div class="col-4 my-5 text-center">
                <p>Bandung, {{ $invoice->date ? \Carbon\Carbon::parse($invoice->date)->locale('ID')->translatedFormat('d F Y') : \Carbon\Carbon::now()->locale('ID')->translatedFormat('d F Y') }}</p>
                @if ($quote->tax)
                    <p class="fs-normal fw-bolder">PT. Reftech Jaya Optima</p>
                @endif
                @if (isset($invoice->sign))
                    <img src="{{ url('') . '/' . $invoice->sign }}" alt="" height="77">
                @else
                    <div style="padding: 40px 0;"></div>
                @endif
                <p class="pt-3 fw-bolder">Ariep Rachman</p>
                <p>Director</p>
            </div>
        </div>

    </div>
</div>
@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice-print-header.css" />
    <style>
        @media print {
            @page { size: A4 portrait; margin: 10mm 12mm 10mm 12mm; }
            .invoice-print div { overflow: visible !important; }
            .invoice-print table { width: 100% !important; }
            .invoice-print td, .invoice-print th { overflow-wrap: break-word !important; }
            .invoice-print table td { color: #333 !important; }
            .invoice-print pre { white-space: pre-wrap !important; word-break: break-word !important; overflow: visible !important; max-width: 100% !important; }
        }
        @media screen {
            .invoice-print table td { color: #333 !important; }
            .invoice-print pre { white-space: pre-wrap; word-break: break-word; overflow: visible; max-width: 100%; }
        }
    </style>
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/js/app-invoice-print.js"></script>
@endpush
