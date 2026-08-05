@extends('layouts.sales.app')
@section('title', $quote->no_quote)
<div class="invoice-print p-4">
    <div class="container-fluid flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column mb-0">
            <div class="mb-xl-0 pb-1">
                <div class="d-flex svg-illustration align-items-center gap-2 mb-3">
                    <span class="app-brand-logo demo">
                        <span style="color: var(--bs-primary)">
                            <img src="{{ asset('/asset') }}/logo/Reftech-Log.png" alt="" width="60%">
                        </span>
                    </span>
                </div>
                <p class="mb-1 fw-bolder" style="font-size: 15px">PT Reftech Jaya Optima</p>
                <div style="font-size: 12px; color: #555;">
                    <p class="mb-0">Taman Kopo Indah V, Soho Sommerville No. 31</p>
                    <p class="mb-0">Bandung – Jawa Barat 40218</p>
                    <p class="mb-0"><i class="mdi mdi-phone-outline me-1" style="font-size:11px;"></i>022 54417653 &nbsp;|&nbsp; <i class="mdi mdi-email-outline me-1" style="font-size:11px;"></i>info@reftech.id &nbsp;|&nbsp; <i class="mdi mdi-web me-1" style="font-size:11px;"></i>www.reftech.id</p>
                    <p class="mb-0 mt-1" style="font-size:10.5px; color:#444; font-weight:500;">
                        <i class="mdi mdi-certificate-outline me-1 text-primary"></i><span class="fw-bold" style="color:#696cff;">ISO Certified:</span> ISO 9001:2015 &nbsp;|&nbsp; ISO 14001:2015 &nbsp;|&nbsp; ISO 45001:2018
                    </p>
                </div>
            </div>
            <div class="text-end">
                <h3 class="fw-bold mb-1" style="letter-spacing:2px; color:#696cff;">QUOTATION</h3>
                <p class="mb-1 fw-semibold" style="font-size:14px;">#{{ $quote->no_quote }}</p>
                <p class="mb-0 text-muted" style="font-size:12px;">{{ $quote->date?->format('d F Y') }}</p>
                @if ($quote->no_pr)
                    <p class="mb-0" style="font-size:11px; color:#888;">No. PR: {{ $quote->no_pr }}</p>
                @endif
            </div>
        </div>

        {{-- Accent Divider --}}
        <div style="height:3px; background:linear-gradient(90deg,#696cff 0%,#9c9eff 60%,#e0e0e0 100%); border-radius:2px; margin:12px 0 16px;"></div>

        {{-- Quote To + Quotation Info (2 box berdampingan seimbang sempurna) --}}
        <div style="display:flex !important; display:-webkit-flex !important; align-items:stretch !important; gap:12px; margin-bottom:16px; font-size:12px;">
            <div style="flex:1; display:flex; flex-direction:column; align-self:stretch; border:1px solid #dcdcdc; border-radius:6px; padding:10px 14px; background:#fafafa;">
                <p class="mb-1 fw-bold text-uppercase" style="font-size:10px; letter-spacing:.5px; color:#555;">Quote To</p>
                <p class="mb-1 fw-bold" style="font-size:13.5px; color:#111;">
                    {{ $quote->client?->company ?? '-' }}
                    @if ($quote->plant)
                        <span class="badge bg-label-primary ms-1" style="font-size:9.5px; vertical-align:middle;">{{ strtoupper($quote->plant->name) }}</span>
                    @endif
                </p>
                @php
                    $contactParts = [];
                    if ($quote->pic?->name_pic) {
                        $contactParts[] = '<i class="mdi mdi-account-outline me-1" style="font-size:11px; color:#444;"></i><span style="color:#222; font-weight:500;">' . e($quote->pic->name_pic) . '</span>';
                    }
                    if ($quote->pic?->phone_pic) {
                        $contactParts[] = '<i class="mdi mdi-phone-outline me-1" style="font-size:11px; color:#444;"></i><span style="color:#222; font-weight:500;">' . e($quote->pic->phone_pic) . '</span>';
                    }
                    if ($quote->client?->email) {
                        $contactParts[] = '<i class="mdi mdi-email-outline me-1" style="font-size:11px; color:#444;"></i><span style="color:#222; font-weight:500;">' . e($quote->client->email) . '</span>';
                    }
                @endphp

                @if (count($contactParts) > 0)
                    <p class="mb-1" style="font-size:11.5px; color:#333;">
                        {!! implode(' &nbsp;|&nbsp; ', $contactParts) !!}
                    </p>
                @endif
                @if ($quote->address || $quote->plant)
                    <p class="mb-0" style="font-size:11.5px; color:#222;">
                        <i class="mdi mdi-map-marker-outline me-1" style="font-size:11px; color:#444;"></i><span style="font-weight:500;">{{ $quote->address ?? $quote->plant?->address }} {{ $quote->plant ? '(' . $quote->plant->name . ')' : '' }}</span>
                    </p>
                @endif
            </div>
            <div style="min-width:240px; display:flex; flex-direction:column; align-self:stretch; border:1px solid #dcdcdc; border-radius:6px; padding:10px 14px; background:#fafafa;">
                <p class="mb-1 fw-bold text-uppercase" style="font-size:10px; letter-spacing:.5px; color:#555;">Prepared By</p>
                <p class="mb-1 fw-bold" style="font-size:13.5px; color:#111;">{{ $quote->sales?->name ?? 'Alifya Syahrani' }}</p>
                <p class="mb-1 fw-medium" style="font-size:11.5px; color:#444;">
                    <i class="mdi mdi-briefcase-outline me-1" style="font-size:11px; color:#444;"></i>{{ $quote->sales?->title ?? 'Sales Engineer' }}
                </p>
                @if ($quote->sales?->email || $quote->sales?->phone)
                    <p class="mb-0" style="font-size:11.5px; color:#222;">
                        @if ($quote->sales?->phone)
                            <i class="mdi mdi-phone-outline me-1" style="font-size:11px; color:#444;"></i><span style="font-weight:500;">{{ $quote->sales->phone }}</span>
                        @endif
                        @if ($quote->sales?->phone && $quote->sales?->email) &nbsp;|&nbsp; @endif
                        @if ($quote->sales?->email)
                            <i class="mdi mdi-email-outline me-1" style="font-size:11px; color:#444;"></i><span style="font-weight:500;">{{ $quote->sales->email }}</span>
                        @endif
                    </p>
                @endif
            </div>
        </div>

        <p class="mb-3" style="font-size:12px; color:#777; font-style:italic;">
            Sir/Madam, We are pleased to offer the under-mentioned as per conditions and details described as following:
        </p>

        {{-- Items Table --}}
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
                'AIR RECEIVER TANK' => [
                    'bar'     => 'Max. Pressure',
                    'grade'   => 'T Plate',
                    'cooling' => 'Certification',
                ],
                'FILTRATION SYSTEM' => [
                    'air_cap'  => 'Flowrate',
                    'material' => 'Element',
                    'connect'  => 'Drain',
                ],
            ];
            $hasDisc = $quote->details->where('disc', '>', 0)->count() > 0;
        @endphp

        <table class="table table-bordered m-0 mb-0" style="width:100%; font-size:12px;">
            <thead style="font-size:11px; background:#eeeeff; color:#3d3d8f;">
                <tr>
                    <th class="text-center" style="width:3%; padding:11px 6px; font-weight:700; border-color:#d0d0ff;">No.</th>
                    <th class="text-center" style="width:{{ $hasDisc ? '44%' : '49%' }}; padding:11px 6px; font-weight:700; border-color:#d0d0ff;">Item Description</th>
                    <th class="text-center" style="width:9%; padding:11px 6px; font-weight:700; border-color:#d0d0ff;">Qty</th>
                    <th class="text-center" style="width:18%; padding:11px 6px; font-weight:700; border-color:#d0d0ff;">Price (IDR)</th>
                    @if ($hasDisc)
                        <th class="text-center" style="width:6%; padding:11px 6px; font-weight:700; border-color:#d0d0ff;">Disc</th>
                    @endif
                    <th class="text-center" style="width:{{ $hasDisc ? '20%' : '21%' }}; padding:11px 6px; font-weight:700; border-color:#d0d0ff;">Total (IDR)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $itemNo = 1;
                    $headerCount = 0;
                @endphp
                @foreach ($quote->details as $i => $item)
                    @if ($item->type === 'header' || $item->type === 'heading')
                        @php
                            $lbl = trim($item->label ?? '');
                            if (!preg_match('/^[A-Z0-9][\.\)]/i', $lbl)) {
                                $lbl = chr(65 + ($headerCount % 26)) . '. ' . $lbl;
                            }
                            $headerCount++;
                        @endphp
                        <tr style="background:#f4f4fe;">
                            <td colspan="{{ $hasDisc ? 6 : 5 }}" style="padding:5px 10px; font-weight:700; font-size:11px; color:#3d3d8f; border-color:#d0d0ff; text-transform:uppercase; letter-spacing:.5px;">
                                <i class="mdi mdi-bookmark-outline me-1"></i>{{ $lbl }}
                            </td>
                        </tr>
                    @else
                        <tr style="{{ $i % 2 === 1 ? 'background:#f9f9fd;' : 'background:#fff;' }}">
                            <td class="align-top text-center" style="padding:8px 6px;">{{ $itemNo++ }}</td>
                            <td class="align-top" style="padding:8px 6px;">
                                @if ($item->type === 'unit' && $item->unit)
                                    <p class="mb-1 fw-semibold" style="font-size:12px;">
                                        {{ $item->label ?: ($item->unit->brand . ' ' . $item->unit->sku . ($item->unit->model ? ' — ' . $item->unit->model : '')) }}
                                    </p>
                                    @php
                                        $specs = $item->getSpecVisibleArray();
                                        $category = $item->unit->unit ?? '';
                                        $catOverride = $specLabelsOverride[$category] ?? [];
                                    @endphp
                                    @if (!empty($specs))
                                        <div style="font-size:10px; color:#555; margin-top:3px;">
                                            @foreach ($specs as $field)
                                                @if ($field === 'unit') @continue @endif
                                                @php
                                                    $val = $item->unit->$field ?? null;
                                                    $label = $catOverride[$field] ?? $specLabels[$field] ?? $field;
                                                @endphp
                                                @if ($val && isset($specLabels[$field]))
                                                    <div style="display:flex; padding:1px 0;">
                                                        <span style="color:#888; min-width:110px; flex-shrink:0;">{{ $label }}</span>
                                                        <span>: {{ $val }}{{ $specUnits[$field] ?? '' }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @elseif ($item->id_equivalent && $item->equivalent)
                                    <p class="mb-1 fw-bold" style="font-size:12px;">
                                        {{ trim(($item->equivalent->brand ?? '') . ($item->equivalent->pn ? ' — ' . $item->equivalent->pn : '')) ?: $item->label }}
                                    </p>
                                    @if (optional($item->equivalent->product)->description)
                                        <p class="mb-0 text-muted" style="font-size:10px;">{{ $item->equivalent->product->description }}</p>
                                    @endif
                                @else
                                    <p class="mb-0 fw-semibold" style="font-size:12px;">{{ $item->label }}</p>
                                    @if ($item->description)
                                        <div class="text-muted" style="font-size:10px; white-space:pre-line; margin-top:2px;">{{ $item->description }}</div>
                                    @endif
                                @endif
                            </td>
                            <td class="align-top text-center" style="padding:8px 6px;">{{ (int) $item->qty }} {{ $item->info_qty ?? 'Unit' }}</td>
                            <td class="align-top text-end" style="padding:8px 6px;">{{ number_format($item->price, 0, '', '.') }}</td>
                            @if ($hasDisc)
                                <td class="align-top text-center" style="padding:8px 6px;">{{ $item->disc > 0 ? (int) $item->disc . '%' : '-' }}</td>
                            @endif
                            <td class="align-top text-end" style="padding:8px 6px; font-weight:500;">{{ number_format($item->amount, 0, '', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        {{-- Financial Summary (rata kanan) --}}
        @php
            $afterDisc = $quote->diskon > 0
                ? $quote->subtotal - $quote->discount_amount
                : $quote->subtotal;
        @endphp
        <div class="d-flex justify-content-end mt-3" style="margin-bottom:12px;">
            <div style="min-width:270px; font-size:12px; border:1px solid #d0d0ff; border-left:4px solid #696cff; border-radius:6px; overflow:hidden; background:#fff;">
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="padding:6px 16px 6px 14px; color:#555;">Subtotal</td>
                        <td style="padding:6px 14px 6px 0; text-align:right; font-weight:500; color:#333;">Rp {{ number_format($quote->subtotal, 0, '', '.') }}</td>
                    </tr>
                    @if ($quote->diskon > 0)
                        <tr style="border-top:1px solid #eeeeff;">
                            <td style="padding:6px 16px 6px 14px; color:#555;">Discount{{ $quote->discount_label ? ' ' . $quote->discount_label : '' }}</td>
                            <td style="padding:6px 14px 6px 0; text-align:right; font-weight:500; color:#dc3545;">- Rp {{ number_format($quote->discount_amount, 0, '', '.') }}</td>
                        </tr>
                        <tr style="border-top:1px solid #eeeeff;">
                            <td style="padding:6px 16px 6px 14px; color:#555;">After Discount</td>
                            <td style="padding:6px 14px 6px 0; text-align:right; font-weight:500; color:#333;">Rp {{ number_format($afterDisc, 0, '', '.') }}</td>
                        </tr>
                    @endif
                    <tr style="border-top:1px solid #eeeeff;">
                        <td style="padding:6px 16px 6px 14px; color:#555;">Tax {{ $quote->tax ? '(12%)' : '' }}</td>
                        <td style="padding:6px 14px 6px 0; text-align:right; font-weight:500; color:#333;">
                            {{ $quote->tax ? 'Rp ' . number_format($quote->tax_amount, 0, '', '.') : '-' }}
                        </td>
                    </tr>
                    @if ($quote->shipping > 0)
                        <tr style="border-top:1px solid #eeeeff;">
                            <td style="padding:6px 16px 6px 14px; color:#555;">Shipping Cost</td>
                            <td style="padding:6px 14px 6px 0; text-align:right; font-weight:500; color:#333;">Rp {{ number_format($quote->shipping, 0, '', '.') }}</td>
                        </tr>
                    @endif
                    <tr style="border-top:2px solid #d0d0ff; background:#f0f0ff;">
                        <td style="padding:9px 16px 9px 14px; font-weight:700; font-size:13px; color:#3d3d8f;">TOTAL PRICE</td>
                        <td style="padding:9px 14px 9px 0; text-align:right; font-weight:700; font-size:13px; color:#696cff;">Rp {{ number_format($quote->total, 0, '', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Note (full-width, di bawah financial summary) --}}
        @if ($quote->note)
        <div style="border:1px solid #e0e0e0; border-left:3px solid #696cff; border-radius:4px; padding:10px 14px; font-size:12px; color:#333; margin-bottom:12px; background:#fafafa;">
            <p class="mb-1 fw-semibold" style="font-size:10px; color:#888; text-transform:uppercase; letter-spacing:.5px;">Remarks</p>
            <p class="mb-0" style="white-space:pre-wrap; line-height:1.6;">{{ $quote->note }}</p>
        </div>
        @endif

        {{-- T&C --}}
        <div style="border:1px solid #e0e0e0; border-radius:6px; padding:12px 16px; font-size:12px; background:#fff; margin-bottom:16px;">
            <p class="mb-2 fw-semibold" style="font-size:10px; text-transform:uppercase; letter-spacing:.5px; color:#888;">Term &amp; Condition</p>
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:160px; padding:3px 0; color:#555; vertical-align:top;">Validity of Quotation</td>
                    <td style="padding:3px 0; vertical-align:top;">: {{ $quote->validity ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:3px 0; color:#555; vertical-align:top;">Price</td>
                    <td style="padding:3px 0; vertical-align:top;">: {{ $quote->pricing ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:3px 0; color:#555; vertical-align:top;">Delivery Process</td>
                    <td style="padding:3px 0; vertical-align:top;">: {{ $quote->delivery_process ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:3px 0; color:#555; vertical-align:top;">Payment</td>
                    <td style="padding:3px 0; vertical-align:top;">: {{ $quote->payment ?? '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- Footer --}}
        <div style="border-top:2px solid #eeeeff; padding-top:12px; margin-top:4px;">
            <p class="text-center mb-3" style="font-size:11px; color:#aaa; font-style:italic;">
                Thank you for your business. We look forward to your continued partnership.
            </p>
            <div class="d-flex justify-content-between align-items-end" style="font-size:12px; color:#555;">
                <div>
                    <p class="mb-0 fw-bold" style="font-size:11px; color:#696cff; text-transform:uppercase; letter-spacing:.5px;">Compressed Air Solution :</p>
                    <p class="mb-0 fw-medium" style="font-size:11px; color:#444;">
                        Sales &nbsp;|&nbsp; Rental &nbsp;|&nbsp; Maintenance &nbsp;|&nbsp; Air Audit &nbsp;|&nbsp; Installation
                    </p>
                </div>
                <div class="text-end" style="font-size:11px; color:#aaa;">
                    <p class="mb-0 fw-semibold" style="color:#696cff; font-size:12px;">PT Reftech Jaya Optima</p>
                    <p class="mb-0">{{ $quote->date?->format('d F Y') }}</p>
                </div>
            </div>
        </div>

    </div>
</div>

@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice-print.css" />
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/js/app-invoice-print.js"></script>
@endpush
