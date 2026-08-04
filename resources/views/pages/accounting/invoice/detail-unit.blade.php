@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts.sales.app')
@section('title', 'Invoice ' . ($invoice->no_invoice ?? '#' . $invoice->id))
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="{{ route('invoice.index') }}" class="text-muted">Accounting / Invoice</a> /
        </span>
        {{ $invoice->no_invoice ?? '#' . $invoice->id }}
    </h4>

    <div class="row invoice-preview">
        {{-- Invoice Card --}}
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card" style="position: relative; overflow: hidden;">

                {{-- Watermark --}}
                @if ($invoice->status_p)
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-35deg); font-size: 160px; font-weight: 900; color: rgba(40, 167, 69, 0.10); pointer-events: none; z-index: 0; letter-spacing: 12px; white-space: nowrap; user-select: none;">
                        PAID
                    </div>
                @else
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-35deg); font-size: 140px; font-weight: 900; color: rgba(220, 53, 69, 0.10); pointer-events: none; z-index: 0; letter-spacing: 12px; white-space: nowrap; user-select: none;">
                        UNPAID
                    </div>
                @endif

                {{-- Header --}}
                <div class="card-body p-4" style="position: relative; z-index: 1;">
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column {{ !$quote->tax ? 'justify-content-end' : '' }} gap-3 mb-0">
                        @if ($quote->tax)
                            <div class="mb-xl-0 pb-1">
                                <div class="d-flex svg-illustration align-items-center gap-2 mb-3">
                                    <span class="app-brand-logo demo">
                                        <img src="{{ asset('/asset') }}/logo/Reftech-Log.png" alt="Reftech Logo" width="180">
                                    </span>
                                </div>
                                <div class="d-flex flex-row align-items-start gap-4 mt-2" style="font-size: 11px;">
                                    <div class="info" style="max-width: 260px;">
                                        <p class="mb-1 fw-bold text-dark" style="font-size: 11.5px;">
                                            <i class="mdi mdi-office-building-outline me-1 text-primary"></i>Office Address :
                                        </p>
                                        <p class="mb-1 text-muted" style="line-height: 1.4;">Taman Kopo Indah V, Soho Sommerville No. 31, Bandung – Jawa Barat 40218</p>
                                        <p class="mb-0 text-muted">
                                            <i class="mdi mdi-phone-outline me-1 text-primary"></i>022 54417653 &nbsp;|&nbsp; <i class="mdi mdi-email-outline me-1 text-primary"></i>info@reftech.id
                                        </p>
                                    </div>
                                    <div class="npwp_add" style="max-width: 280px;">
                                        <p class="mb-1 fw-bold text-dark" style="font-size: 11.5px;">
                                            <i class="mdi mdi-file-document-outline me-1 text-primary"></i>NPWP Address :
                                        </p>
                                        <p class="mb-1 text-muted" style="line-height: 1.4;">Komp. Negia Kencana Residence Blok B, No.2 Pasanggrahan, Ujung Berung Kota Bandung - Jawa Barat 40199</p>
                                        <div class="px-2 py-0.5 rounded-0" style="background:#eef0ff; border:1px solid #d0d0ff; font-size:10.5px; font-weight:600; color:#3d3d8f; display:inline-block; border-radius:0 !important;">
                                            <i class="mdi mdi-card-account-details-outline me-1"></i>NPWP: 73.728.571.8-429.000
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-xl-0 pb-1">
                                <div class="d-flex svg-illustration align-items-center gap-2 mb-3">
                                    <span class="app-brand-logo demo">
                                        <img src="{{ asset('/asset') }}/logo/Reftech-Log.png" alt="Reftech Logo" width="180">
                                    </span>
                                </div>
                                <p class="mb-1 fw-bold text-dark" style="font-size:14px;">PT Reftech Jaya Optima</p>
                                <p class="mb-0 text-muted" style="font-size:11px;">Taman Kopo Indah V, Soho Sommerville No. 31, Bandung – Jawa Barat 40218</p>
                            </div>
                        @endif

                        <div class="text-end">
                            <h1 class="fw-bold" style="color: #2529fa; letter-spacing: 2px;">INVOICE</h1>
                            <p class="mb-1 fw-bold text-dark" style="font-size:14px;">#{{ $invoice->no_invoice }}</p>
                            <p class="mb-1 text-muted small">{{ $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d F Y') : '-' }}</p>
                            @php
                                $hasProof = $payments->whereNotNull('file')->where('level', 0)->isNotEmpty();
                                if ($invoice->status_p) {
                                    $warna = 'bg-label-success text-success';
                                    $text  = 'Verified';
                                } elseif ($hasProof) {
                                    $warna = 'bg-label-warning text-warning';
                                    $text  = 'Awaiting Verification';
                                } else {
                                    $warna = 'bg-label-dark text-dark';
                                    $text  = 'Waiting Payment';
                                }
                            @endphp
                            <div class="mt-1">
                                <span class="badge {{ $warna }} px-3 py-1 fs-6 fw-bold">{{ $text }}</span>
                            </div>
                        </div>
                    </div>

                    <div style="height:2px; background:linear-gradient(90deg,#696cff 0%,#9c9eff 60%,#e0e0e0 100%); border-radius:2px; margin:16px 0 18px;"></div>

                    {{-- Invoice To + Document Info Box --}}
                    <div style="display:flex !important; align-items:stretch !important; gap:12px; margin-bottom:16px; font-size:12px;">
                        <div style="flex:1; display:flex; flex-direction:column; align-self:stretch; border:1px solid #dcdcdc; border-radius:0 !important; padding:10px 14px; background:#fafafa;">
                            <p class="mb-1 fw-bold text-uppercase" style="font-size:10px; letter-spacing:.5px; color:#555;">Invoice To</p>
                            <p class="mb-1 fw-bold" style="font-size:13.5px; color:#111;">{{ $quote->client?->company ?? '-' }}</p>
                            @php
                                $contactParts = [];
                                $picName = $quote->pic?->name_pic ?? $quote->attn;
                                if ($picName) {
                                    $contactParts[] = '<i class="mdi mdi-account-outline me-1" style="font-size:11px; color:#444;"></i><span style="color:#222; font-weight:500;">' . e($picName) . '</span>';
                                }
                                if ($quote->pic?->phone_pic || $quote->client?->phone) {
                                    $contactParts[] = '<i class="mdi mdi-phone-outline me-1" style="font-size:11px; color:#444;"></i><span style="color:#222; font-weight:500;">' . e($quote->pic?->phone_pic ?: $quote->client?->phone) . '</span>';
                                }
                                if ($quote->client?->npwp) {
                                    $contactParts[] = '<i class="mdi mdi-card-account-details-outline me-1" style="font-size:11px; color:#444;"></i><span style="color:#222; font-weight:500;">NPWP: ' . e($quote->client->npwp) . '</span>';
                                }
                            @endphp
                            @if (count($contactParts) > 0)
                                <p class="mb-1" style="font-size:11.5px; color:#333;">
                                    {!! implode(' &nbsp;|&nbsp; ', $contactParts) !!}
                                </p>
                            @endif
                            @php
                                $targetAddress = $invoice->invoiceTo == '1' ? ($quote->client?->address ?? '-') : ($quote->client?->subAddress ?? '-');
                            @endphp
                            @if ($targetAddress)
                                <p class="mb-0" style="font-size:11.5px; color:#222;">
                                    <i class="mdi mdi-map-marker-outline me-1" style="font-size:11px; color:#444;"></i><span style="font-weight:500;">{{ $targetAddress }}</span>
                                </p>
                            @endif
                        </div>
                        <div style="min-width:240px; display:flex; flex-direction:column; align-self:stretch; border:1px solid #dcdcdc; border-radius:0 !important; padding:10px 14px; background:#fafafa;">
                            <p class="mb-1 fw-bold text-uppercase" style="font-size:10px; letter-spacing:.5px; color:#555;">Payment Terms &amp; Info</p>
                            <p class="mb-1 fw-semibold" style="font-size:12px; color:#222;">
                                <i class="mdi mdi-clipboard-text-outline me-1 text-primary"></i>PO No: <span class="fw-bold">{{ $quote->po_number ?? '-' }}</span>
                            </p>
                            <p class="mb-1 fw-semibold" style="font-size:12px; color:#222;">
                                <i class="mdi mdi-clock-outline me-1 text-primary"></i>Term: <span class="fw-bold">{{ $invoice->term ?? $quote->payment_method }}</span>
                            </p>
                            @php
                                $tempoPayRec = $payments->firstWhere('type', 'Tempo') ?? $payments->first();
                                $dueDateDisplay = $tempoPayRec?->due_date ? \Carbon\Carbon::parse($tempoPayRec->due_date) : null;
                            @endphp
                            @if ($dueDateDisplay)
                                <p class="mb-1 fw-semibold" style="font-size:12px; color:#d97706;">
                                    <i class="mdi mdi-calendar-clock me-1"></i>Jatuh Tempo: <span class="fw-bold">{{ $dueDateDisplay->format('d F Y') }}</span>
                                </p>
                            @endif
                            <p class="mb-0 text-muted" style="font-size:11.5px;">
                                <i class="mdi mdi-account-outline me-1 text-primary"></i>Sales: <span class="fw-medium text-dark">{{ $quote->sales?->name ?? '-' }}</span>
                            </p>
                        </div>
                    </div>

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
                            'bar'=>' Bar','air_cap'=>' m³/min','test_pressure'=>' Bar',
                            'inlet_pressure'=>' Bar','outlet_pressure'=>' Bar',
                            'inlet_cap'=>' m³/min','outlet_cap'=>' m³/min',
                            'weight'=>' Kg','capacity'=>' Liter',
                        ];
                        $hasDisc = $quote->details->where('disc', '>', 0)->count() > 0;
                        $colCount = 5 + ($hasDisc ? 1 : 0) + ($quote->tax ? 1 : 0);
                    @endphp
                    <div class="table-responsive rounded border mb-3">
                        <table class="table table-bordered m-0" style="width:100%; font-size:12px;">
                            <thead style="font-size:11px; background:#eeeeff; color:#3d3d8f;">
                                <tr>
                                    <th class="text-center py-2" style="width:4%; font-weight:700; border-color:#d0d0ff;">No.</th>
                                    <th class="text-center py-2" style="font-weight:700; border-color:#d0d0ff;">DESKRIPSI</th>
                                    <th class="text-center py-2" style="width:10%; font-weight:700; border-color:#d0d0ff;">Qty</th>
                                    <th class="text-center py-2" style="width:18%; font-weight:700; border-color:#d0d0ff;">HARGA (IDR)</th>
                                    @if ($hasDisc)
                                        <th class="text-center py-2" style="width:7%; font-weight:700; border-color:#d0d0ff;">Disc</th>
                                    @endif
                                    @if ($quote->tax)
                                        <th class="text-center py-2" style="width:15%; font-weight:700; border-color:#d0d0ff;">DPP (IDR)</th>
                                    @endif
                                    <th class="text-center py-2" style="width:18%; font-weight:700; border-color:#d0d0ff;">TOTAL HARGA (IDR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $itemNo = 1;
                                    $headerCount = 0;
                                @endphp
                                @foreach ($quote->details as $item)
                                    @if ($item->type === 'header' || $item->type === 'heading')
                                        @php
                                            $lbl = trim($item->label ?? '');
                                            if (!preg_match('/^[A-Z0-9][\.\)]/i', $lbl)) {
                                                $lbl = chr(65 + ($headerCount % 26)) . '. ' . $lbl;
                                            }
                                            $headerCount++;
                                        @endphp
                                        <tr style="background:#f0f0ff;">
                                            <td colspan="{{ $colCount }}" class="fw-bold text-primary text-uppercase px-3" style="padding: 5px 10px; font-size:11.5px; border-top:1px solid #d0d0ff; border-bottom:1px solid #d0d0ff;">
                                                <i class="mdi mdi-bookmark-outline me-1"></i>{{ $lbl }}
                                            </td>
                                        </tr>
                                    @else
                                        @php $dpp = $quote->tax ? ($item->amount * 11 / 12) : 0; @endphp
                                        <tr style="font-size: 12px">
                                            <td class="text-center align-top py-2">{{ $itemNo++ }}</td>
                                            <td class="align-top py-2">
                                                @if ($item->type === 'unit' && $item->unit)
                                                    <p class="mb-1 fw-semibold" style="font-size: 12px">
                                                        {{ $item->label ?: ($item->unit->brand . ' ' . $item->unit->sku . ($item->unit->model ? ' — ' . $item->unit->model : '')) }}
                                                    </p>
                                                    @php $specs = $item->getSpecVisibleArray(); @endphp
                                                    @if (!empty($specs))
                                                        <div class="spec-detail-rows" style="font-size:11px; color:#777; margin-top:4px; {{ $invoice->show_spec ? '' : 'display:none;' }}">
                                                            @foreach ($specs as $field)
                                                                @if ($field === 'unit') @continue @endif
                                                                @php $val = $item->unit->$field ?? null; @endphp
                                                                @if ($val && isset($specLabels[$field]))
                                                                    <div style="display:flex; padding:1px 0;">
                                                                        <span style="min-width:110px; flex-shrink:0;">{{ $specLabels[$field] }}</span>
                                                                        <span>: {{ $val }}{{ $specUnits[$field] ?? '' }}</span>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @elseif ($item->type === 'equivalent' || $item->type === 'sparepart' || $item->id_equivalent || $item->equivalent)
                                                     @if ($item->equivalent)
                                                         @php
                                                             $brandPn = trim(($item->equivalent->brand ?? '') . ($item->equivalent->pn ? ' - ' . $item->equivalent->pn : ''));
                                                             $subDesc = $item->label;
                                                             if (empty($subDesc) || $subDesc === $brandPn) {
                                                                 $subDesc = optional($item->equivalent->product)->description ?? optional($item->equivalent->product)->name;
                                                             }
                                                         @endphp
                                                         <p class="mb-0 fw-bold text-dark" style="font-size: 12px">{{ $brandPn ?: $item->label }}</p>
                                                         @if ($subDesc && $subDesc !== $brandPn)
                                                             <div style="font-size: 12px; color: #333333; font-weight: 500; margin-top: 2px; line-height: 1.4;">{{ $subDesc }}</div>
                                                         @endif
                                                     @else
                                                         <p class="mb-0 fw-bold text-dark" style="font-size: 12px">{{ $item->label }}</p>
                                                     @endif
                                                @else
                                                    <p class="mb-0 fw-bold text-dark" style="font-size: 12px">{{ $item->label }}</p>
                                                @endif
                                                @if ($item->description)
                                                     <div style="font-size: 11px; color: #444; white-space: pre-line; margin-top: 3px; line-height: 1.4;">{{ $item->description }}</div>
                                                @endif
                                            </td>
                                            <td class="text-center align-top py-2">
                                                {{ (float) $item->qty }} {{ $item->info_qty ?? 'Unit' }}
                                                @if ($item->remaining_qty <= 0)
                                                    <div><span class="badge bg-label-success mt-1" style="font-size:9.5px;">Terkirim Semua</span></div>
                                                @elseif ($item->delivered_qty > 0)
                                                    <div><span class="badge bg-label-warning mt-1" style="font-size:9.5px;">Sisa {{ $item->remaining_qty }}</span></div>
                                                @else
                                                    <div><span class="badge bg-label-secondary mt-1" style="font-size:9.5px;">Belum Dikirim</span></div>
                                                @endif
                                            </td>
                                            <td class="text-end align-top py-2">{{ number_format($item->price, 0, '', '.') }}</td>
                                            @if ($hasDisc)
                                                <td class="text-center align-top py-2">{{ $item->disc > 0 ? (float) $item->disc . '%' : '-' }}</td>
                                            @endif
                                            @if ($quote->tax)
                                                <td class="text-end align-top py-2">{{ number_format($dpp, 0, '', '.') }}</td>
                                            @endif
                                            <td class="text-end align-top py-2 fw-semibold">{{ number_format($item->amount, 0, '', '.') }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Financial Summary Box --}}
                    @php
                        $afterDisc = $quote->diskon > 0
                            ? $quote->subtotal - $quote->discount_amount
                            : $quote->subtotal;
                    @endphp
                    <div class="d-flex justify-content-end mb-3">
                        <div style="min-width:280px; font-size:12px; border:1px solid #d0d0ff; border-left:4px solid #696cff; border-radius:6px; overflow:hidden; background:#fff;">
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
                                @if ($quote->tax)
                                    <tr style="border-top:1px solid #eeeeff;">
                                        <td style="padding:6px 16px 6px 14px; color:#555;">DPP Atas PPN</td>
                                        <td style="padding:6px 14px 6px 0; text-align:right; font-weight:500; color:#333;">Rp {{ number_format($afterDisc * 11 / 12, 0, '', '.') }}</td>
                                    </tr>
                                    <tr style="border-top:1px solid #eeeeff;">
                                        <td style="padding:6px 16px 6px 14px; color:#555;">PPN 12%</td>
                                        <td style="padding:6px 14px 6px 0; text-align:right; font-weight:500; color:#333;">Rp {{ number_format($quote->tax_amount, 0, '', '.') }}</td>
                                    </tr>
                                @endif
                                @if ($quote->shipping > 0)
                                    <tr style="border-top:1px solid #eeeeff;">
                                        <td style="padding:6px 16px 6px 14px; color:#555;">Shipping Cost</td>
                                        <td style="padding:6px 14px 6px 0; text-align:right; font-weight:500; color:#333;">Rp {{ number_format($quote->shipping, 0, '', '.') }}</td>
                                    </tr>
                                @endif
                                @if ($totalPph > 0)
                                    <tr style="border-top:1px solid #eeeeff;">
                                        <td style="padding:6px 16px 6px 14px; color:#555;">PPH 23</td>
                                        <td style="padding:6px 14px 6px 0; text-align:right; font-weight:500; color:#dc3545;">- Rp {{ number_format($totalPph, 0, '', '.') }}</td>
                                    </tr>
                                @endif
                                @php
                                    $showTagihanBreakdown = in_array($invoice->type, ['DP', 'BP', 'Balance Payment', 'Down Payment']) || floatval($invoice->percent) < 100;
                                @endphp
                                <tr style="border-top:2px solid #d0d0ff; background:{{ !$showTagihanBreakdown ? 'yellow' : '#f0f0ff' }};">
                                    <td style="padding:9px 16px 9px 14px; font-weight:700; font-size:13px; color:{{ !$showTagihanBreakdown ? '#000' : '#3d3d8f' }};">TOTAL</td>
                                    <td style="padding:9px 14px 9px 0; text-align:right; font-weight:700; font-size:13px; color:{{ !$showTagihanBreakdown ? '#000' : '#696cff' }};">Rp {{ number_format($showTagihanBreakdown ? $quote->total : $totalAfterPph, 0, '', '.') }}</td>
                                </tr>
                                @if ($showTagihanBreakdown)
                                    @if (in_array($invoice->type, ['BP', 'Balance Payment']))
                                        @php
                                            $dpInvoices = isset($allInvoices) ? $allInvoices->reject(fn($i) => $i->id == $invoice->id) : collect();
                                            $dpPercent  = $dpInvoices->sum(fn($i) => floatval($i->percent));
                                            if ($dpPercent <= 0 && floatval($invoice->percent) < 100) {
                                                $dpPercent = 100 - floatval($invoice->percent);
                                            }
                                            $dpAmount = round($quote->total * $dpPercent / 100);
                                        @endphp
                                        @if ($dpAmount > 0)
                                            <tr style="border-top:1px solid #eeeeff;">
                                                <td style="padding:6px 16px 6px 14px; color:#555;">DP TELAH DIBAYAR ({{ $dpPercent }}%)</td>
                                                <td style="padding:6px 14px 6px 0; text-align:right; font-weight:500; color:#dc3545;">Rp {{ number_format($dpAmount, 0, '', '.') }}</td>
                                            </tr>
                                        @endif
                                    @endif
                                    <tr style="border-top:2px solid #e6c300; background:yellow;">
                                        <td style="padding:8px 16px 8px 14px; font-weight:800; font-size:12.5px; color:#000;">
                                            {{ in_array($invoice->type, ['DP', 'Down Payment']) ? 'TAGIHAN DP (' . floatval($invoice->percent) . '%)' : (in_array($invoice->type, ['BP', 'Balance Payment']) ? 'TAGIHAN BP (' . floatval($invoice->percent) . '%)' : 'TAGIHAN ' . strtoupper($invoice->type) . ' (' . floatval($invoice->percent) . '%)') }}
                                        </td>
                                        <td style="padding:8px 14px 8px 0; text-align:right; font-weight:800; font-size:13px; color:#000;">Rp {{ number_format($totalAfterPph, 0, '', '.') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    {{-- Terbilang Box --}}
                    <div class="p-3 rounded-0 mb-4" style="background:#f0f2ff; border: 1px dashed #696cff; border-radius:0 !important;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="mdi mdi-cash-multiple text-primary fs-5"></i>
                            <span class="fw-bold text-primary" style="font-size:12px;">Terbilang :</span>
                            <span class="fw-bold text-dark" style="font-size:12.5px;"># {{ $terbilang }} Rupiah</span>
                        </div>
                    </div>

                    {{-- Bank & TTD --}}
                    <div class="row pt-2 align-items-end">
                        <div class="col-md-7">
                            <div class="p-3 rounded-0 border" style="background:#fafafa; font-size:11.5px; border-radius:0 !important;">
                                <p class="fw-bold mb-2 text-dark" style="font-size:12px;">
                                    <i class="mdi mdi-bank-outline me-1 text-primary"></i>Pembayaran : Transfer / Giro
                                </p>
                                <table style="width:100%; border-collapse:collapse;">
                                    @if ($quote->tax)
                                        <tr>
                                            <td style="padding:2px 0; color:#555; width:90px;">Bank Name</td>
                                            <td style="padding:2px 0; font-weight:600; color:#111;">: Bank BCA (IDR)</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:2px 0; color:#555;">Acc Name</td>
                                            <td style="padding:2px 0; font-weight:700; color:#696cff;">: PT. REFTECH JAYA OPTIMA</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:2px 0; color:#555;">Acc No.</td>
                                            <td style="padding:2px 0; font-weight:700; color:#111;">: 008 - 6289 - 789</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:2px 0; color:#555;">Swift Code</td>
                                            <td style="padding:2px 0; font-weight:500; color:#333;">: CENAIDJA</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td style="padding:2px 0; color:#555; width:90px;">Bank Name</td>
                                            <td style="padding:2px 0; font-weight:600; color:#111;">: Bank BCA (IDR)</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:2px 0; color:#555;">Acc Name</td>
                                            <td style="padding:2px 0; font-weight:700; color:#696cff;">: ARIEP RACHMAN</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:2px 0; color:#555;">Acc No.</td>
                                            <td style="padding:2px 0; font-weight:700; color:#111;">: 166 - 2242 - 271</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5 text-center mt-3 mt-md-0">
                            <p class="mb-1 text-muted" style="font-size:11.5px;">Bandung, {{ $invoice->date ? \Carbon\Carbon::parse($invoice->date)->locale('ID')->translatedFormat('d F Y') : \Carbon\Carbon::now()->locale('ID')->translatedFormat('d F Y') }}</p>
                            @if ($quote->tax)
                                <p class="fw-bold mb-1 text-dark" style="font-size:12px;">PT. Reftech Jaya Optima</p>
                            @endif
                            @if (isset($invoice->sign))
                                <div class="my-2">
                                    <img src="{{ url('') . '/' . $invoice->sign }}" alt="Signature" height="70">
                                </div>
                            @else
                                <div style="padding: 30px 0;"></div>
                            @endif
                            <p class="mb-0 fw-bold text-dark" style="font-size:13px; border-bottom:1px solid #ddd; display:inline-block; padding-bottom:2px;">Ariep Rachman</p>
                            <p class="mb-0 text-muted" style="font-size:11px;">Director</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        {{-- End: Invoice --}}

        {{-- Sidebar Actions --}}
        <div class="col-xl-3 col-md-4 col-12 invoice-actions">

            {{-- 1. Primary Actions Card --}}
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body d-grid gap-2 p-3">
                    <div class="btn-group w-100">
                        <a href="{{ route('invoice.show_unit.print', $invoice->id) }}" target="_blank"
                           class="btn btn-primary waves-effect fw-medium">
                            <i class="mdi mdi-printer-outline me-1"></i> Print / Download
                        </a>
                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split waves-effect"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('invoice.show_unit.print', $invoice->id) }}" target="_blank">
                                    <i class="mdi mdi-file-document-outline me-1"></i> Invoice Print
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('invoice.unit.label_detail', $invoice->id) }}">
                                    <i class="mdi mdi-package-variant-closed me-1"></i> Label Sampul
                                </a>
                            </li>
                        </ul>
                    </div>

                    @if (Auth::user()->role !== 'Sales')
                    <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light border">
                        <label class="form-check-label text-dark small mb-0 fw-medium" for="toggle-spec">
                            <i class="mdi mdi-text-box-search-outline me-1 text-primary"></i>Tampilkan Spek
                        </label>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="toggle-spec"
                                data-id="{{ $invoice->id }}"
                                {{ $invoice->show_spec ? 'checked' : '' }}>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button class="btn btn-label-secondary flex-grow-1 waves-effect" id="backButton">
                            <i class="mdi mdi-arrow-left me-1"></i>Back
                        </button>
                        <a href="{{ route('unit-quotation.show', $quote->id) }}"
                           class="btn btn-label-info flex-grow-1 waves-effect">
                            <i class="mdi mdi-file-eye-outline me-1"></i>Quotation
                        </a>
                    </div>
                </div>
            </div>

            {{-- Invoice Settings Card --}}
            @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Accounting')
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-header py-2 px-3 bg-light border-bottom">
                        <small class="text-uppercase text-muted fw-bold" style="font-size:10px; letter-spacing:0.5px;">Invoice Settings</small>
                    </div>
                    <div class="card-body d-grid gap-2 p-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 waves-effect text-start"
                            data-bs-toggle="modal" data-bs-target="#changeDate">
                            <i class="mdi mdi-calendar-edit me-1 text-primary"></i> Change Date
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 waves-effect text-start"
                            data-bs-toggle="modal" data-bs-target="#editInvoiceModal">
                            <i class="mdi mdi-pencil-outline me-1 text-primary"></i> Edit No Invoice / Term
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm w-100 waves-effect text-start"
                            data-bs-toggle="modal" data-bs-target="#dueDate">
                            <i class="mdi mdi-calendar-clock me-1 text-warning"></i> Set / Edit Due Date
                        </button>
                    </div>
                </div>
            @endif

            {{-- 2. Invoice Info Card --}}
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-header py-2 px-3 bg-light border-bottom">
                    <small class="text-uppercase text-muted fw-bold" style="font-size:10px; letter-spacing:0.5px;">Invoice Information</small>
                </div>
                <div class="card-body d-grid gap-2 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">No. Invoice</span>
                        <span class="fw-bold small text-primary">#{{ $invoice->no_invoice }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Tanggal</span>
                        <span class="fw-semibold small">{{ $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Jatuh Tempo</span>
                        @if ($dueDateDisplay)
                            <span class="fw-bold small text-warning"><i class="mdi mdi-calendar-clock me-1"></i>{{ $dueDateDisplay->format('d M Y') }}</span>
                        @else
                            <span class="badge bg-label-secondary" style="font-size:10px;">Belum Di-set</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">No. PO</span>
                        <span class="fw-semibold small">{{ $quote->po_number ?? '-' }}</span>
                    </div>
                    @if ($quote->po_file)
                        <a href="{{ Storage::url($quote->po_file) }}" target="_blank"
                           class="btn btn-outline-success btn-sm w-100 waves-effect">
                            <i class="mdi mdi-file-pdf-box me-1"></i> Lihat File PO
                        </a>
                    @endif
                    @if ($allInvoices->count() > 1)
                        <hr class="my-1">
                        @foreach ($allInvoices as $inv)
                            <a href="{{ route('invoice.show_unit', $inv->id) }}"
                               class="btn btn-sm {{ $inv->id == $invoice->id ? 'btn-primary' : 'btn-outline-secondary' }} w-100 waves-effect">
                                <span class="badge {{ $inv->type === 'DP' ? 'bg-warning' : 'bg-info' }} me-1">{{ $inv->type }}</span>
                                {{ $inv->no_invoice ?? 'Pending' }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- 3. Tax / PPH --}}
            @if (Auth::user()->role != 'Sales')
            <div class="card mb-3">
                <div class="card-header py-2 px-3">
                    <small class="text-uppercase text-muted fw-semibold">Tax / PPH</small>
                </div>
                <div class="card-body d-grid gap-2">
                    @php $pphPerItem = $quote->details->sum(fn($d) => ($d->amount * $d->pph) / 100); @endphp
                    @if ($pphPerItem > 0)
                        <a href="#" class="btn btn-danger w-100 waves-effect delete-pph-unit"
                           data-id="{{ $invoice->id }}">Delete PPH 23</a>
                    @else
                        <button type="button" class="btn btn-outline-info w-100 waves-effect"
                            data-bs-toggle="modal" data-bs-target="#modalAddPph">Input PPH 23</button>
                    @endif
                    @if (($invoice->pph ?? 0) > 0)
                        <a href="#" class="btn btn-danger w-100 waves-effect delete-pph-manual-unit"
                           data-id="{{ $invoice->id }}">Delete PPH Manual</a>
                    @else
                        <button type="button" class="btn btn-outline-secondary w-100 waves-effect"
                            data-bs-toggle="modal" data-bs-target="#modalAddPphManual">Input PPH Manual</button>
                    @endif
                </div>
            </div>
            @endif

            {{-- 4. Hand Sign --}}
            @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Accounting')
                <div class="card mb-3">
                    <div class="card-header py-2 px-3">
                        <small class="text-uppercase text-muted fw-semibold">Hand Sign</small>
                    </div>
                    <div class="card-body">
                        @if (isset($invoice->sign))
                            <a href="#" class="btn btn-danger w-100 waves-effect delete-hand-sign-unit"
                               data-id="{{ $invoice->id }}">Delete Hand Sign</a>
                        @else
                            <a href="#" class="btn btn-outline-secondary w-100 waves-effect input-hand-sign-unit"
                               data-id="{{ $invoice->id }}">Input Hand Sign</a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- 5. Payment --}}
            <div class="card mb-3">
                <div class="card-header py-2 px-3 d-flex align-items-center justify-content-between">
                    <small class="text-uppercase text-muted fw-semibold">Payment</small>
                    @if ($payments->isNotEmpty())
                        <span class="badge bg-label-success small">Rp {{ number_format($payments->sum('amount'), 0, '', '.') }}</span>
                    @endif
                </div>

                {{-- Invoice Summary --}}
                <div class="card-body p-0">
                    @foreach ($allInvoices as $inv)
                        @php
                            $invTotal = $quote->total;
                            if ($inv->type === 'DP' && $inv->term) {
                                $pct      = floatval(filter_var($inv->term, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
                                $invTotal = round($quote->total * $pct / 100);
                            } elseif ($inv->type === 'BP') {
                                $dpInv    = $allInvoices->firstWhere('type', 'DP');
                                $pct      = $dpInv?->term ? floatval(filter_var($dpInv->term, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)) : 0;
                                $invTotal = $quote->total - round($quote->total * $pct / 100);
                            }
                        @endphp
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                            <div>
                                <p class="mb-0 small fw-semibold">
                                    <span class="badge {{ $inv->type === 'DP' ? 'bg-warning' : ($inv->type === 'BP' ? 'bg-info' : 'bg-primary') }} me-1" style="font-size:10px">{{ $inv->type }}</span>
                                    Rp {{ number_format($invTotal, 0, '', '.') }}
                                </p>
                                <p class="mb-0 text-muted" style="font-size:11px">{{ $inv->no_invoice ?? 'Belum diterbitkan' }}</p>
                            </div>
                            @if ($inv->status_p)
                                <span class="badge bg-label-success" style="font-size:10px">Verified</span>
                            @else
                                <span class="badge bg-label-warning" style="font-size:10px">Unpaid</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Payment Records --}}
                @if ($payments->isNotEmpty())
                <div class="border-top">
                    <div class="px-3 pt-2 pb-1">
                        <small class="text-uppercase text-muted fw-semibold" style="font-size:10px">Payment Received</small>
                    </div>
                    @foreach ($payments as $pay)
                    <div class="d-flex align-items-start justify-content-between px-3 py-2 border-bottom" id="pay-row-{{ $pay->id }}">
                        <div>
                            <p class="mb-0 fw-semibold small">
                                Rp {{ number_format($pay->amount, 0, '', '.') }}
                                @if ($pay->type)
                                    <span class="badge bg-label-primary ms-1" style="font-size:10px">{{ $pay->type }}</span>
                                @endif
                            </p>
                            @if ($pay->method)
                                <p class="mb-0 text-muted" style="font-size:11px">{{ $pay->method }}</p>
                            @endif
                            @if ($pay->note)
                                <p class="mb-0 text-muted" style="font-size:11px">{{ $pay->note }}</p>
                            @endif
                            <div class="mt-1 d-flex flex-wrap gap-1">
                                @if ($pay->file)
                                    <a href="{{ asset($pay->file) }}" target="_blank"
                                       class="badge bg-label-success text-decoration-none" style="font-size:10px">
                                        <i class="mdi mdi-file-check-outline"></i> Bukti Transfer
                                    </a>
                                @else
                                    <span class="badge bg-label-warning" style="font-size:10px">Belum ada bukti</span>
                                @endif
                                @if ($pay->level == 1)
                                    <span class="badge bg-label-success" style="font-size:10px">
                                        <i class="mdi mdi-check-circle-outline"></i> Paid
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex gap-1 ms-2">
                        @if (!$pay->file && Auth::user()->role === 'Sales')
                            <button type="button" class="btn btn-sm btn-icon btn-outline-success btn-upload-proof-inv"
                                data-id="{{ $pay->id }}" title="Upload Bukti">
                                <i class="mdi mdi-upload"></i>
                            </button>
                        @endif
                        @if ($pay->file && $pay->level == 0 && Auth::user()->role === 'Sales')
                            <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete-proof"
                                data-id="{{ $pay->id }}" title="Hapus Bukti Transfer">
                                <i class="mdi mdi-file-remove-outline"></i>
                            </button>
                        @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Actions --}}
                <div class="card-footer p-3 d-grid gap-2">
                    @if ($quote->status === 'po_received' && Auth::user()->role === 'Sales')
                        <button type="button" class="btn btn-outline-success w-100 waves-effect"
                            data-bs-toggle="modal" data-bs-target="#modalAddPayment">
                            <i class="mdi mdi-cash-plus me-1"></i> Tambah Payment
                        </button>
                    @endif
                    @if (in_array(Auth::user()->role, ['Admin', 'Accounting', 'Finance']))
                        @if (!$invoice->status_p)
                            @if ($payments->isNotEmpty())
                                <button type="button" class="btn btn-primary w-100 waves-effect"
                                    data-bs-toggle="modal" data-bs-target="#confirmPayment">Confirm Payment</button>
                            @else
                                <div class="alert alert-warning p-2 mb-0" style="font-size:11px; border-radius:0 !important;">
                                    <i class="mdi mdi-alert-circle-outline me-1"></i> Menunggu Sales menambahkan data Payment.
                                </div>
                            @endif
                        @else
                            <a href="#" class="btn btn-danger w-100 waves-effect undo-payment-unit"
                               data-id="{{ $invoice->id }}">Undo Confirm Payment</a>
                        @endif
                    @endif
                </div>
            </div>

            {{-- 6. Delivery Order --}}
            @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Accounting')
                <div class="card mb-3">
                    <div class="card-header py-2 px-3">
                        <small class="text-uppercase text-muted fw-semibold">Delivery Order</small>
                    </div>
                    <div class="card-body d-grid gap-2">
                        @if ($quote->deliveries->isNotEmpty())
                            @foreach ($quote->deliveries as $d)
                                <a href="{{ route('delivery.show', $d->id) }}"
                                   class="btn btn-outline-info btn-sm w-100 waves-effect">
                                    <i class="mdi mdi-file-document-outline me-1"></i> Lihat Surat Jalan #{{ $d->id }}
                                </a>
                            @endforeach
                        @endif
                        @php
                            $hasRemaining = $quote->details->where('type', '!=', 'header')->sum('remaining_qty') > 0;
                        @endphp
                        @if ($quote->status === 'po_received' && $hasRemaining)
                            <button type="button" class="btn btn-outline-success w-100 waves-effect"
                                data-bs-toggle="modal" data-bs-target="#modalSJUnit">
                                <i class="mdi mdi-truck-delivery-outline me-1"></i> Buat Surat Jalan
                            </button>
                        @elseif ($quote->status === 'po_received')
                            <span class="badge bg-label-success w-100 py-2">Semua Item Sudah Terkirim</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Buat Surat Jalan --}}
    @if (($quote->status === 'po_received') && (Auth::user()->role == 'Admin' || Auth::user()->role == 'Accounting'))
        <div class="modal fade" id="modalSJUnit" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('unit-quotation.storeDelivery', $quote->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_invoice" value="{{ $invoice->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Buat Surat Jalan — {{ $quote->no_quote }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" name="date"
                                        value="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tujuan / Alamat</label>
                                    <select class="form-select" name="destination" required>
                                        @if ($quote->client)
                                            <option value="1">{{ $quote->client->address }}</option>
                                            @if ($quote->client->subAddress)
                                                <option value="2">{{ $quote->client->subAddress }}</option>
                                            @endif
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Jenis Pengiriman</label>
                                    <select class="form-select" name="type">
                                        <option value="Ekspedisi">Ekspedisi</option>
                                        <option value="Teknisi">Teknisi</option>
                                    </select>
                                </div>
                            </div>

                            <label class="form-label fw-semibold">Item yang Dikirim</label>
                            <p class="text-muted mb-2" style="font-size:11.5px;">
                                Centang item yang dikirim kali ini. Qty default = sisa yang belum terkirim, bisa dikurangi kalau cuma kirim sebagian.
                            </p>
                            <div class="table-responsive border rounded">
                                <table class="table table-sm table-bordered m-0" style="font-size:12px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:5%"></th>
                                            <th>Description</th>
                                            <th class="text-center" style="width:15%">Sisa</th>
                                            <th class="text-center" style="width:20%">Qty Dikirim</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quote->details as $item)
                                            @if ($item->type === 'header')
                                                <tr style="background:#f0f0ff;">
                                                    <td colspan="4" class="fw-bold text-uppercase py-1 px-2 text-primary" style="font-size:11px;">{{ $item->label }}</td>
                                                </tr>
                                            @elseif ($item->remaining_qty > 0)
                                                <tr>
                                                    <td class="text-center align-middle">
                                                        <input class="form-check-input item-check" type="checkbox" name="item_ids[]"
                                                            value="{{ $item->id }}" data-target="qty-{{ $item->id }}" checked>
                                                    </td>
                                                    <td class="align-middle">{{ $item->label }}</td>
                                                    <td class="text-center align-middle">{{ $item->remaining_qty }} {{ $item->info_qty }}</td>
                                                    <td class="align-middle">
                                                        <input type="number" step="any" min="0" max="{{ $item->remaining_qty }}"
                                                            value="{{ $item->remaining_qty }}" name="qty[{{ $item->id }}]"
                                                            id="qty-{{ $item->id }}" class="form-control form-control-sm">
                                                    </td>
                                                </tr>
                                            @else
                                                <tr class="text-muted">
                                                    <td class="text-center align-middle">
                                                        <input type="checkbox" class="form-check-input" disabled>
                                                    </td>
                                                    <td class="align-middle text-decoration-line-through">{{ $item->label }}</td>
                                                    <td class="text-center align-middle">0</td>
                                                    <td class="align-middle"><span class="badge bg-label-success">Terkirim Semua</span></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Buat Surat Jalan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal PPH 23 per item --}}
    <div class="modal fade" id="modalAddPph" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('invoice.unit.pph', $invoice->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add PPH 23 — {{ $invoice->no_invoice }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @foreach ($quote->details as $i => $detail)
                            <div class="row g-2 mb-3 align-items-center">
                                <div class="col-8">
                                    <p class="mb-0 fw-medium" style="font-size: 13px">
                                        @if ($detail->type === 'unit' && $detail->unit)
                                            {{ $detail->label ?: ($detail->unit->brand . ' ' . $detail->unit->model) }}
                                        @else
                                            {{ $detail->label }}
                                        @endif
                                    </p>
                                </div>
                                <div class="col-4">
                                    <div class="input-group input-group-merge">
                                        <input type="number" class="form-control" name="pph[{{ $i }}]"
                                               value="{{ $detail->pph }}" placeholder="2" min="0" max="100" step="0.1">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal PPH Manual --}}
    <div class="modal-onboarding modal fade animate__animated" id="modalAddPphManual" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center">
                <form action="{{ route('invoice.unit.pph_manual', $invoice->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="onboarding-content mb-0">
                            <h4 class="onboarding-title text-body">Add PPH Manual</h4>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-floating form-floating-outline">
                                        <div class="input-group">
                                            <span class="input-group-text">Rp.</span>
                                            <input type="text" class="form-control invoice-item-pph-manual-label"
                                                id="pphManualLabel" name="pphLabel" placeholder="Put PPH Here"
                                                data-type="currency" value="{{ old('pph') }}">
                                            <input class="form-control invoice-item-pph-manual" type="number"
                                                name="pph" id="pphManual" value="{{ old('pph') }}" hidden>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Confirm Payment --}}
    <div class="modal fade" id="confirmPayment" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('invoice.confirm_payment_unit', $invoice->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label fw-semibold">Catatan</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Catatan pembayaran..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary waves-effect">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Add Payment --}}
    <div class="modal fade" id="modalAddPayment" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="mdi mdi-cash-plus me-1"></i> Tambah Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('unit-quotation.add-payment', $quote->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipe Payment <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" id="inv-add-payment-type" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="DP">DP (Down Payment)</option>
                                <option value="BP">BP (Balance Payment)</option>
                                <option value="CBD">CBD</option>
                                <option value="COD">COD</option>
                                <option value="Tempo">Tempo</option>
                            </select>
                        </div>
                        <div class="mb-3" id="inv-tempo-group" style="display:none">
                            <label class="form-label fw-semibold">Tempo (hari)</label>
                            <input type="number" class="form-control" name="tempo" min="1" placeholder="misal: 30">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Metode <span class="text-danger">*</span></label>
                            <select class="form-select" name="method" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="Transfer">Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="Giro">Giro</option>
                                <option value="Escrow">Escrow</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="amount" required min="1" placeholder="Masukkan jumlah yang diterima">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Persentase (%)</label>
                            <input type="number" class="form-control" name="percent" min="1" max="100" placeholder="opsional, misal: 50">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan</label>
                            <input type="text" class="form-control" name="note" placeholder="opsional">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Upload Bukti Payment --}}
    <div class="modal fade" id="modalUploadBukti" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Bukti Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formUploadBuktiInv" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="file" class="form-control" name="file" accept="image/*,.pdf" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Invoice Modal --}}
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Accounting')
        <div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editInvoiceModalLabel">Edit No Invoice & Term of Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('invoice.update', $invoice->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="invoiceNumber" class="form-label">No Invoice</label>
                                <input type="text" class="form-control" id="invoiceNumber" name="invoice" value="{{ old('invoice', $invoice->no_invoice) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="termPayment" class="form-label">Term of Payment</label>
                                <textarea class="form-control" id="termPayment" name="payment" rows="4" required>{{ old('payment', $invoice->term ?? $quote->payment_method) }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('components.modal.invoice.date')
        @include('components.modal.invoice.due-date')
    @endif

@endsection

@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
@endpush

@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
@endpush

@push('script')
<script>
    $('#backButton').click(function () { window.history.back(); });

    // Buat Surat Jalan: nonaktifkan qty saat item di-uncheck
    $(document).on('change', '.item-check', function () {
        var $qty = $('#' + $(this).data('target'));
        $qty.prop('disabled', !this.checked);
    });

    // Toggle spesifikasi
    $('#toggle-spec').on('change', function () {
        var id      = $(this).data('id');
        var showing = $(this).is(':checked');

        $.post('/invoice/unit/' + id + '/toggle-spec', { _token: '{{ csrf_token() }}' });

        if (showing) {
            $('.spec-detail-rows').show();
        } else {
            $('.spec-detail-rows').hide();
        }
    });

    // PPH Manual format
    $(".invoice-item-pph-manual-label").on('keyup', function () {
        var nomorInt = parseInt($(this).val().replace(/\./g, ''), 10);
        if (!isNaN(nomorInt)) {
            $(this).val(nomorInt.toLocaleString('id-ID'));
            $("#pphManual").val(nomorInt);
        }
    });

    // Delete PPH 23
    $(document).on('click', '.delete-pph-unit', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus PPH 23?',
            text: 'Semua nilai PPH per item akan di-reset ke 0.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger me-3 waves-effect',
                cancelButton: 'btn btn-label-secondary waves-effect',
            },
            buttonsStyling: false,
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/invoice/unit/' + id + '/pph/delete',
                    type: 'POST',
                    data: { '_method': 'PATCH', '_token': '{{ csrf_token() }}' },
                    success: function () { location.reload(); },
                });
            }
        });
    });

    // Delete PPH Manual
    $(document).on('click', '.delete-pph-manual-unit', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus PPH Manual?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger me-3 waves-effect',
                cancelButton: 'btn btn-label-secondary waves-effect',
            },
            buttonsStyling: false,
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/invoice/unit/' + id + '/pph-manual/delete',
                    type: 'POST',
                    data: { '_method': 'PATCH', '_token': '{{ csrf_token() }}' },
                    success: function () { location.reload(); },
                });
            }
        });
    });

    // Undo confirm payment
    $(document).on('click', '.undo-payment-unit', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            url: '/invoice/unit/' + id + '/payment/undo',
            type: 'POST',
            data: { '_method': 'PATCH', '_token': '{{ csrf_token() }}' },
            success: function () { location.reload(); },
        });
    });

    // Input Hand Sign
    $(document).on('click', '.input-hand-sign-unit', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: 'Input Hand Sign?',
            text: 'Tanda tangan akan otomatis ditambahkan ke invoice.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, input!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-primary me-3 waves-effect',
                cancelButton: 'btn btn-label-secondary waves-effect',
            },
            buttonsStyling: false,
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/invoice/unit/' + id + '/sign',
                    type: 'POST',
                    data: { '_token': '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Hand sign berhasil ditambahkan.',
                                customClass: { confirmButton: 'btn btn-success waves-effect' },
                            });
                            setTimeout(function () { location.reload(); }, 1500);
                        }
                    },
                });
            }
        });
    });

    // Add Payment — toggle Tempo field
    $('#inv-add-payment-type').on('change', function () {
        if ($(this).val() === 'Tempo') {
            $('#inv-tempo-group').show().find('input').prop('required', true);
        } else {
            $('#inv-tempo-group').hide().find('input').prop('required', false).val('');
        }
    });

    // Upload Bukti — set action URL dinamis lalu buka modal
    var $uploadBtn = null;
    $(document).on('click', '.btn-upload-proof-inv', function () {
        var id = $(this).data('id');
        $uploadBtn = $(this);
        $('#formUploadBuktiInv').data('payment-id', id).attr('action', '/unit-quotation/payment/' + id + '/proof');
        $('#modalUploadBukti').modal('show');
    });

    // Intercept submit → AJAX (biar response JSON tidak tampil di browser)
    $('#formUploadBuktiInv').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var url      = $(this).attr('action');
        var payId    = $(this).data('payment-id');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                $('#modalUploadBukti').modal('hide');
                $('#formUploadBuktiInv')[0].reset();
                if (res.success) {
                    var $row = $('#pay-row-' + payId);
                    $row.find('.badge.bg-label-warning').replaceWith(
                        '<a href="' + res.file_url + '" target="_blank" class="badge bg-label-success text-decoration-none" style="font-size:10px">' +
                        '<i class="mdi mdi-file-check-outline"></i> Bukti Transfer</a>'
                    );
                    if ($uploadBtn) $uploadBtn.remove();
                    $row.find('.d-flex.gap-1.ms-2').append(
                        '<button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete-proof" data-id="' + payId + '" title="Hapus Bukti Transfer"><i class="mdi mdi-file-remove-outline"></i></button>'
                    );
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Bukti transfer berhasil diupload.', timer: 1500, showConfirmButton: false })
                        .then(function () { window.location.reload(); });
                }
            },
            error: function () {
                $('#modalUploadBukti').modal('hide');
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal upload. Cek format dan ukuran file.' });
            }
        });
    });

    // Hapus Bukti Transfer
    $(document).on('click', '.btn-delete-proof', function () {
        var id   = $(this).data('id');
        var $btn = $(this);
        Swal.fire({
            title: 'Hapus bukti transfer?',
            text: 'File bukti transfer akan dihapus, payment tetap ada.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger me-2 waves-effect',
                cancelButton: 'btn btn-label-secondary waves-effect',
            },
            buttonsStyling: false,
        }).then(function (result) {
            if (!result.isConfirmed) return;
            $.ajax({
                url: '/unit-quotation/payment/' + id + '/proof',
                type: 'POST',
                data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                success: function (res) {
                    if (res.success) {
                        var $row = $btn.closest('[id^="pay-row-"]');
                        $row.find('.badge.bg-label-success')
                            .replaceWith('<span class="badge bg-label-warning" style="font-size:10px">Belum ada bukti</span>');
                        $btn.remove();
                        $row.find('.d-flex.gap-1.ms-2').prepend(
                            '<button type="button" class="btn btn-sm btn-icon btn-outline-success btn-upload-proof-inv" data-id="' + id + '" title="Upload Bukti"><i class="mdi mdi-upload"></i></button>'
                        );
                        Swal.fire({ icon: 'success', title: 'Dihapus', text: 'Bukti transfer berhasil dihapus.', timer: 1500, showConfirmButton: false });
                    }
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan.' });
                }
            });
        });
    });

    // Delete Hand Sign
    $(document).on('click', '.delete-hand-sign-unit', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Hand Sign?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger me-3 waves-effect',
                cancelButton: 'btn btn-label-secondary waves-effect',
            },
            buttonsStyling: false,
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/invoice/unit/' + id + '/del-sign',
                    type: 'POST',
                    data: { '_method': 'DELETE', '_token': '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Dihapus!',
                                customClass: { confirmButton: 'btn btn-success waves-effect' },
                            });
                            setTimeout(function () { location.reload(); }, 1500);
                        }
                    },
                });
            }
        });
    });
</script>
@endpush
