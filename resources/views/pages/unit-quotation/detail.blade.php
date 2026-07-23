@extends('layouts.sales.app')
@section('title', 'Detail Unit Quotation')
@section('content')

@php
    use Illuminate\Support\Facades\Storage;
    $statusMap = [
        'draft'        => ['label' => 'DRAFT',        'color' => 'secondary', 'solid' => true],
        'sent'         => ['label' => 'SENT',          'color' => 'info',      'solid' => true],
        'negotiation'  => ['label' => 'NEGOTIATION',   'color' => 'warning',   'solid' => true],
        'revision'     => ['label' => 'REVISI',        'color' => 'primary',   'solid' => true],
        'hot_prospect' => ['label' => 'HOT PROSPECT',  'color' => 'danger',    'solid' => true],
        'po_received'  => ['label' => 'PO RECEIVED',   'color' => 'success',   'solid' => true],
        'loss'         => ['label' => 'LOSS',          'color' => 'dark',      'solid' => true],
    ];
    $st = $statusMap[$quote->status] ?? ['label' => strtoupper($quote->status), 'color' => 'secondary'];
@endphp

{{-- Revision selector (only shows when more than 1 version exists) --}}
@if ($allVersions->count() > 1)
<div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <span class="text-muted small fw-semibold">Versi:</span>
    @foreach ($allVersions as $v)
        @php $vLabel = $v->revision_number === 0 ? $v->no_quote : 'Revisi ' . $v->revision_number; @endphp
        @if ($v->id === $quote->id)
            <span class="badge bg-primary">{{ $vLabel }}</span>
        @else
            <a href="{{ route('unit-quotation.show', $v->id) }}"
               class="badge bg-label-secondary text-decoration-none">{{ $vLabel }}</a>
        @endif
    @endforeach
</div>
@endif

<div class="row invoice-preview">

    {{-- ── LEFT: Invoice Card ── --}}
    <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
        <div class="card invoice-preview-card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                    <div class="mb-xl-0 pb-1">
                        <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                            <span class="app-brand-logo demo">
                                <span style="color: var(--bs-primary)">
                                    <img src="{{ asset('/asset') }}/logo/Reftech-Log.png" alt="" width="60%">
                                </span>
                            </span>
                        </div>
                        <p class="mb-1 fw-bolder">PT Reftech Jaya Optima</p>
                        <div style="font-size: 10px">
                            <p class="mb-1">Taman Kopo Indah V, Ruko Sommerville No. 31</p>
                            <p class="mb-1">Bandung – Jawa Barat 40218</p>
                            <p class="mb-1">
                                <i class="mdi mdi-phone-outline me-1 mdi-14px"></i>022 54417653
                                &nbsp;&nbsp;
                                <i class="mdi mdi-email-outline me-1 mdi-14px"></i>info@reftech.id
                            </p>
                        </div>
                    </div>
                    <div class="text-end">
                        <h3 class="fw-bold">QUOTATION</h3>
                        <div><span class="fw-bolder">#{{ $quote->no_quote }}</span></div>
                        <div class="mt-1">
                            <span class="badge bg-{{ $st['color'] }} fs-6">{{ $st['label'] }}</span>
                        </div>
                        <div class="mt-1">
                            <span class="text-muted">{{ $quote->date?->format('d-m-Y') }}</span>
                        </div>
                        @if ($quote->type || $quote->week)
                            <div class="mt-1 text-muted small">
                                {{ $quote->type }}{{ $quote->type && $quote->week ? ' — ' : '' }}{{ $quote->week ? 'Week ' . $quote->week : '' }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <hr class="my-0">

            {{-- Quote To --}}
            <div class="card-body mb-3">
                <h6 class="fw-semibold fs-4 mb-3">Quote To:</h6>
                <div class="row">
                    <div class="col-2 fw-medium">
                        <p class="mb-1">Company</p>
                        <p class="mb-1">Name PIC</p>
                        <p class="mb-1">Phone</p>
                    </div>
                    <div class="col-4">
                        <p class="mb-1">: {{ $quote->client?->company ?? '-' }}</p>
                        <p class="mb-1">: {{ $quote->pic?->name_pic ?? '-' }}</p>
                        <p class="mb-1">: {{ $quote->pic?->phone_pic ?? '-' }}</p>
                    </div>
                    <div class="col-3 fw-medium text-end">
                        <p class="mb-1">Seller :</p>
                        <p class="mb-1">No PR :</p>
                        <p class="mb-1">Email :</p>
                    </div>
                    <div class="col-3 text-end">
                        <p class="mb-1">PT Reftech Jaya Optima</p>
                        <p class="mb-1">{{ $quote->no_pr ?? '-' }}</p>
                        <p class="mb-1">{{ $quote->client?->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="table-responsive">
                <table class="table m-0">
                    <thead class="table-light border-top">
                        <tr>
                            <th>No.</th>
                            <th>Item Description</th>
                            <th>Qty</th>
                            <th class="text-end">Price (IDR)</th>
                            <th class="text-center">Disc</th>
                            <th class="text-end">Total Price (IDR)</th>
                        </tr>
                    </thead>
                    <tbody>
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
                        @endphp
                        @foreach ($quote->details as $i => $item)
                            <tr style="font-size: 13px">
                                <td class="align-top">{{ $i + 1 }}</td>
                                <td class="align-top">
                                    @if ($item->type === 'unit' && $item->unit)
                                        <p class="mb-1 fw-semibold" style="font-size: 12px">
                                            {{ $item->label ?: ($item->unit->brand . ' ' . $item->unit->sku . ($item->unit->model ? ' — ' . $item->unit->model : '')) }}
                                        </p>
                                        @php $specs = $item->getSpecVisibleArray(); @endphp
                                        @if (!empty($specs))
                                            <div style="font-size:11px; color:#888; margin-top:4px;">
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
                                    @else
                                        <p class="mb-0 fw-semibold" style="font-size: 12px">{{ $item->label }}</p>
                                        @if ($item->description)
                                            <span style="font-size: 11px">{{ $item->description }}</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="align-top text-center">{{ $item->qty }} {{ $item->info_qty ?? 'Unit' }}</td>
                                <td class="align-top text-end">{{ number_format($item->price, 0, '', '.') }}</td>
                                <td class="align-top text-center">{{ $item->disc > 0 ? $item->disc . '%' : '-' }}</td>
                                <td class="align-top text-end">{{ number_format($item->amount, 0, '', '.') }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="2" class="align-top px-4 py-4">
                                @if ($quote->note)
                                    <span class="text-muted" style="font-size:12px"><strong>Note:</strong> {{ $quote->note }}</span>
                                @else
                                    <span class="text-muted">Thanks for your business</span>
                                @endif
                            </td>
                            <td colspan="2" class="text-end px-4 py-4">
                                <p class="mb-2">Subtotal :</p>
                                @if ($quote->diskon > 0)
                                    <p class="mb-2">Discount {{ $quote->discount_label }} :</p>
                                    <p class="mb-2">Subtotal After Discount :</p>
                                @endif
                                <p class="mb-0">Tax :</p>
                            </td>
                            <td colspan="2" class="px-4 py-4">
                                <p class="fw-semibold mb-2 text-end">Rp {{ number_format($quote->subtotal, 0, '', '.') }}</p>
                                @if ($quote->diskon > 0)
                                    <p class="fw-semibold mb-2 text-end">- Rp {{ number_format($quote->discount_amount, 0, '', '.') }}</p>
                                    <p class="fw-semibold mb-2 text-end">Rp {{ number_format($quote->subtotal - $quote->discount_amount, 0, '', '.') }}</p>
                                @endif
                                <p class="fw-semibold mb-0 text-end">
                                    {{ $quote->tax ? 'Rp ' . number_format($quote->tax_amount, 0, '', '.') : '0' }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end fw-bold px-4 py-3">TOTAL PRICE :</td>
                            <td colspan="2" class="text-end fw-bold px-4 py-3 text-primary fs-6">
                                Rp {{ number_format($quote->total, 0, '', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- T&C --}}
            <div class="card-body mt-2">
                <h5 class="my-4">Term & Condition</h5>
                <div class="row">
                    <div class="col-3 fw-medium">
                        <p class="mb-1">Validity Of Quotation</p>
                        <p class="mb-1">Price</p>
                        <p class="mb-1">Delivery Process</p>
                        <p class="mb-1">Payment</p>
                    </div>
                    <div class="col">
                        <p class="mb-1">: {{ $quote->validity ?? '-' }}</p>
                        <p class="mb-1">: {{ $quote->pricing ?? '-' }}</p>
                        <p class="mb-1">: {{ $quote->delivery_process ?? '-' }}</p>
                        <p class="mb-1">: {{ $quote->payment ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity Timeline --}}
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <h5 class="mb-0">Activity Timeline</h5>
                <span class="badge bg-label-secondary">{{ $quote->statusHistory->count() }} aktivitas</span>
            </div>
            <div class="card-body pt-3">
                @php
                    $hstMap = [
                        'draft'        => ['label' => 'Quotation Dibuat',   'color' => 'secondary', 'icon' => 'mdi-file-document-outline'],
                        'sent'         => ['label' => 'Terkirim ke Client', 'color' => 'info',      'icon' => 'mdi-send-outline'],
                        'negotiation'  => ['label' => 'Negosiasi',          'color' => 'warning',   'icon' => 'mdi-handshake-outline'],
                        'hot_prospect' => ['label' => 'Hot Prospect',       'color' => 'warning',   'icon' => 'mdi-fire'],
                        'revision'     => ['label' => 'Revisi',             'color' => 'primary',   'icon' => 'mdi-file-replace-outline'],
                        'po_received'  => ['label' => 'PO Diterima',        'color' => 'success',   'icon' => 'mdi-clipboard-check-outline'],
                        'done'         => ['label' => 'Done',               'color' => 'success',   'icon' => 'mdi-check-circle-outline'],
                        'loss'         => ['label' => 'Loss',               'color' => 'danger',    'icon' => 'mdi-close-circle-outline'],
                    ];
                @endphp

                @if ($quote->statusHistory->count() === 0)
                    {{-- Fallback untuk quotation lama tanpa history --}}
                    <ul class="timeline card-timeline mb-0">
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-secondary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Quotation Dibuat</h6>
                                    <small class="text-muted">{{ $quote->created_at->format('d M Y H:i') }}</small>
                                </div>
                                <p class="mb-0 text-muted" style="font-size:12px;">{{ $quote->no_quote }}</p>
                            </div>
                        </li>
                    </ul>
                @else
                    <ul class="timeline card-timeline mb-0">
                        @foreach ($quote->statusHistory as $hist)
                            @php
                                $hst = $hstMap[$hist->status] ?? ['label' => ucfirst(str_replace('_',' ',$hist->status)), 'color' => 'secondary', 'icon' => 'mdi-circle-outline'];
                                $isLast = $loop->last;
                            @endphp
                            <li class="timeline-item {{ $isLast ? '' : 'timeline-item-transparent' }}">
                                <span class="timeline-point timeline-point-{{ $hst['color'] }}">
                                    <i class="mdi {{ $hst['icon'] }} mdi-14px"></i>
                                </span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0 fw-semibold" style="font-size:13px;">{{ $hst['label'] }}</h6>
                                        <small class="text-muted" title="{{ $hist->created_at->format('d M Y H:i') }}">
                                            {{ $hist->created_at->diffInHours(now()) > 48
                                                ? $hist->created_at->format('d M Y, H:i')
                                                : $hist->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    @if ($hist->note)
                                        <p class="mb-0 text-muted" style="font-size:12px;">{{ $hist->note }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    </div>
    {{-- END LEFT --}}

    {{-- ── RIGHT: Action Sidebar ── --}}
    <div class="col-xl-3 col-md-4 col-12 invoice-actions">

        {{-- Action Card --}}
        @php
            $sellingContract          = $contracts->where('type', 'Selling')->where('level', '1')->first();
            $requestedSellingContract = $contracts->where('type', 'Selling')->where('level', '0')->first();
            $issuedInvoices  = $invoices->filter(fn($i) => !is_null($i->no_invoice));
            $pendingInvoices = $invoices->filter(fn($i) => is_null($i->no_invoice));
            $issuedTotal     = $issuedInvoices->sum(fn($i) => round($quote->total * floatval($i->percent ?? 100) / 100));
            $remaining       = $quote->total - $issuedTotal;
        @endphp
        <div class="card mb-3">
            <div class="card-body pb-2">

                {{-- Download --}}
                <a href="{{ route('unit-quotation.print', $quote->id) }}" target="_blank"
                   class="btn btn-primary d-grid w-100 waves-effect mb-2">
                    <i class="mdi mdi-download me-1"></i> Download / Print
                </a>

                {{-- Edit & Revisi (side by side) — hidden when po_received --}}
                @if (($quote->status !== 'po_received') && (Auth::user()->role === 'Sales' || Auth::user()->role === 'Admin'))
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <a href="{{ route('unit-quotation.edit', $quote->id) }}"
                           class="btn btn-outline-secondary w-100 waves-effect">
                            <i class="mdi mdi-pencil-outline"></i> Edit
                        </a>
                    </div>
                    <div class="col-6">
                        @if (Auth::user()->role === 'Sales')
                            <form action="{{ route('unit-quotation.revise', $quote->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-info w-100 waves-effect"
                                    onclick="return confirm('Buat revisi dari quotation ini?')">
                                    <i class="mdi mdi-file-replace-outline"></i> Revisi
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endif

                <hr class="my-2">

                {{-- PO --}}
                @if (Auth::user()->role === 'Sales' && $quote->status !== 'po_received')
                    <button type="button" class="btn btn-outline-success d-grid w-100 waves-effect mb-2 btn-upload-po-unit"
                        data-npwp="{{ $quote->client->npwp ?? '' }}"
                        data-client-url="{{ $quote->client->role == 'Leads' ? route('detail.leads', $quote->client->id) : route('existing.show', $quote->client->id) }}">
                        <i class="mdi mdi-upload me-1"></i> Upload PO
                    </button>
                @endif
                @if ($quote->po_file)
                    <a href="{{ Storage::url($quote->po_file) }}" target="_blank"
                       class="btn btn-outline-secondary d-grid w-100 waves-effect mb-2">
                        <i class="mdi mdi-file-pdf-box me-1"></i> Lihat File PO
                    </a>
                @endif

                {{-- Selling Contract --}}
                @if ($sellingContract)
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <a class="btn btn-facebook w-100 waves-effect"
                                href="{{ route('contract.show', $sellingContract->id) }}">
                                <i class="mdi mdi-file-sign"></i> Kontrak
                            </a>
                        </div>
                        <div class="col-6">
                            <a class="btn btn-outline-primary w-100 waves-effect" target="_blank"
                                href="{{ route('contract.print', $sellingContract->id) }}">
                                <i class="mdi mdi-download"></i> Unduh
                            </a>
                        </div>
                    </div>
                @elseif ($requestedSellingContract)
                    <div class="d-flex align-items-center gap-2 mb-2 px-1">
                        <i class="mdi mdi-clock-outline text-warning"></i>
                        <small class="text-muted">Menunggu Accounting buat kontrak</small>
                    </div>
                @elseif (Auth::user()->role === 'Sales' && $quote->status !== 'po_received')
                    <a href="#" data-id="{{ $quote->id }}"
                        class="btn btn-outline-dark d-grid w-100 waves-effect mb-2 request-selling-unit">
                        <i class="mdi mdi-file-sign me-1"></i> Request Selling Contract
                    </a>
                @elseif (Auth::user()->role === 'Admin' || Auth::user()->role === 'Accounting')
                    <button type="button" class="btn btn-outline-primary d-grid w-100 waves-effect mb-2"
                        data-bs-toggle="modal" data-bs-target="#modalSellingContractUnit">
                        <i class="mdi mdi-file-sign me-1"></i> Create Selling Contract
                    </button>
                @endif

                <hr class="my-2">

                {{-- Invoice --}}
                @if ($invoices->isNotEmpty())
                    @if ($issuedInvoices->isNotEmpty())
                        @foreach ($issuedInvoices as $inv)
                            <a href="{{ route('invoice.show_unit', $inv->id) }}"
                               class="btn btn-outline-primary d-grid w-100 waves-effect mb-2">
                                <i class="mdi mdi-file-document-outline me-1"></i> Invoice
                                @if ($inv->type !== 'CT')
                                    <span class="badge bg-primary ms-1">{{ $inv->type }}</span>
                                @endif
                            </a>
                        @endforeach
                    @else
                        <div class="d-flex align-items-center gap-2 mb-2 px-1">
                            <i class="mdi mdi-clock-outline text-warning"></i>
                            <small class="text-muted">Menunggu invoice diterbitkan</small>
                        </div>
                    @endif

                    @if ($pendingInvoices->isNotEmpty())
                        <div class="d-flex align-items-center gap-2 mb-2 px-1">
                            <i class="mdi mdi-clock-outline text-warning"></i>
                            <small class="text-muted">{{ $pendingInvoices->count() }} invoice menunggu terbit</small>
                        </div>
                    @endif

                    @if ($issuedInvoices->isNotEmpty() && $pendingInvoices->isEmpty() && $remaining > 0)
                        @if (Auth::user()->role === 'Sales' || Auth::user()->role === 'Admin')
                            <button type="button" class="btn btn-outline-success d-grid w-100 waves-effect mb-2"
                                data-bs-toggle="modal" data-bs-target="#modalRequestNextInvoice">
                                <i class="mdi mdi-plus-circle-outline me-1"></i> Ajukan Invoice Selanjutnya
                            </button>
                        @endif
                    @endif
                @endif

                {{-- Change Status --}}
                @if (Auth::user()->role === 'Sales' && $quote->status !== 'po_received')
                    <button type="button" class="btn btn-outline-secondary d-grid w-100 waves-effect mb-2"
                        data-bs-toggle="modal" data-bs-target="#modalChangeStatus">
                        <i class="mdi mdi-swap-horizontal me-1"></i> Change Status
                    </button>
                @endif

            </div>

            {{-- Delete / Cancel PO --}}
            @if (Auth::user()->role === 'Sales' || Auth::user()->role === 'Admin' || Auth::user()->role === 'Accounting')
            <div class="card-footer pt-0 pb-3 px-3 border-0">
                @if ($quote->status === 'po_received')
                    @if ($quote->cancel_request)
                        {{-- Pending approval state --}}
                        @if (Auth::user()->role === 'Accounting' || Auth::user()->role === 'Admin')
                            <p class="text-muted small mb-2 text-center">
                                <i class="mdi mdi-alert-circle-outline text-warning me-1"></i>
                                Sales mengajukan pembatalan PO
                            </p>
                            <div class="row g-2">
                                <div class="col-6">
                                    <form action="{{ route('unit-quotation.approve-cancel', $quote->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100 waves-effect"
                                            onclick="return confirm('Setujui pembatalan PO ini?')">
                                            <i class="mdi mdi-check"></i> Setuju
                                        </button>
                                    </form>
                                </div>
                                <div class="col-6">
                                    <form action="{{ route('unit-quotation.reject-cancel', $quote->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger w-100 waves-effect">
                                            <i class="mdi mdi-close"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center gap-2 px-1">
                                <i class="mdi mdi-clock-outline text-warning"></i>
                                <small class="text-muted">Menunggu persetujuan Accounting</small>
                            </div>
                        @endif
                    @else
                        {{-- Cancel PO button for Sales/Admin --}}
                        @if (Auth::user()->role === 'Sales' || Auth::user()->role === 'Admin')
                            <form action="{{ route('unit-quotation.cancel-po', $quote->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger d-grid w-100 waves-effect"
                                    onclick="return confirm('Batalkan PO untuk penawaran ini? Tindakan ini tidak bisa dibatalkan.')">
                                    <i class="mdi mdi-cancel me-1"></i> Cancel PO
                                </button>
                            </form>
                        @endif
                    @endif
                @else
                    @if (Auth::user()->role === 'Sales' || Auth::user()->role === 'Admin')
                    <a href="#" class="btn btn-outline-danger d-grid w-100 waves-effect delete-quote"
                        data-id="{{ $quote->id }}">
                        <i class="mdi mdi-trash-can-outline me-1"></i> Delete
                    </a>
                    @endif
                @endif
            </div>
            @endif
        </div>

        {{-- Payment Card --}}
        @if ($payments->isNotEmpty() || $quote->status === 'po_received')
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <h5 class="mb-0">Payment</h5>
                @php $paidTotal = $payments->sum('amount'); @endphp
                @if ($payments->isNotEmpty())
                    <span class="badge bg-label-success">Rp {{ number_format($paidTotal, 0, '', '.') }}</span>
                @endif
            </div>
            @if ($payments->isNotEmpty())
            <div class="card-body p-0">
                @foreach ($payments as $pay)
                <div class="d-flex align-items-start justify-content-between px-3 py-2 border-bottom" id="pay-row-{{ $pay->id }}">
                    <div>
                        <p class="mb-0 fw-semibold small">Rp {{ number_format($pay->amount, 0, '', '.') }}
                            @if ($pay->percent)
                                <span class="text-muted small">({{ $pay->percent }}%)</span>
                            @endif
                            @if ($pay->type)
                                <span class="badge bg-label-primary ms-1 small">{{ $pay->type }}</span>
                            @endif
                        </p>
                        @if ($pay->method)
                            <p class="mb-0 text-muted small">{{ $pay->method }}</p>
                        @endif
                        @if ($pay->note)
                            <p class="mb-0 text-muted small">{{ $pay->note }}</p>
                        @endif
                        <div class="mt-1">
                            @if ($pay->file)
                                <a href="{{ asset($pay->file) }}" target="_blank"
                                   class="badge bg-label-success text-decoration-none me-1">
                                    <i class="mdi mdi-file-check-outline"></i> Bukti Transfer
                                </a>
                            @else
                                <span class="badge bg-label-warning">Belum ada bukti</span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-1 ms-2">
                        @if (!$pay->file)
                            <button type="button" class="btn btn-sm btn-icon btn-outline-success btn-upload-proof"
                                data-id="{{ $pay->id }}" title="Upload Bukti Transfer">
                                <i class="mdi mdi-upload"></i>
                            </button>
                        @endif
                        @if (Auth::user()->role === 'Admin' || Auth::user()->role === 'Accounting')
                            <button type="button" class="btn btn-sm btn-icon btn-label-danger btn-delete-payment"
                                data-id="{{ $pay->id }}" title="Hapus">
                                <i class="mdi mdi-delete-outline"></i>
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            @if ($quote->status === 'po_received' && Auth::user()->role === 'Sales')
            <div class="card-footer p-3">
                <button type="button" class="btn btn-outline-success d-grid w-100 waves-effect"
                    data-bs-toggle="modal" data-bs-target="#modalAddPayment">
                    <i class="mdi mdi-cash-plus me-1"></i> Tambah Payment
                </button>
            </div>
            @endif
        </div>
        @endif

        {{-- Info Card --}}
        <div class="card mb-3">
            <div class="card-body">
                <p class="fw-semibold mb-1 text-muted small">Sales</p>
                <p class="mb-3">{{ $quote->sales?->name ?? '-' }}</p>
                <p class="fw-semibold mb-1 text-muted small">Date</p>
                <p class="mb-3">{{ $quote->date?->format('d M Y') ?? '-' }}</p>
                @if ($quote->no_pr)
                    <p class="fw-semibold mb-1 text-muted small">No PR</p>
                    <p class="mb-3">{{ $quote->no_pr }}</p>
                @endif
                @if ($quote->po_number)
                    <p class="fw-semibold mb-1 text-muted small">No PO</p>
                    <p class="mb-3">{{ $quote->po_number }}</p>
                @endif
                @if ($quote->payment_method)
                    <p class="fw-semibold mb-1 text-muted small">Payment Method</p>
                    <p class="mb-0">{{ $quote->payment_method }}</p>
                @endif
            </div>
        </div>

    </div>
    {{-- END RIGHT --}}

</div>

{{-- Modal Upload PO --}}
<div class="modal fade" id="modalUploadPO" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="mdi mdi-upload me-1"></i> Upload Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('unit-quotation.upload-po', $quote->id) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. PO <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="po_number"
                               placeholder="Masukkan nomor PO dari customer"
                               value="{{ old('po_number', $quote->po_number) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan Payment <span class="text-muted small">(untuk T&C quotation)</span></label>
                        <input type="text" class="form-control" name="payment_method"
                               placeholder="misal: CBD, DP 50% & Pelunasan NET 30, Tempo 30 Hari..."
                               maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipe Invoice Pertama <span class="text-danger">*</span></label>
                        <select class="form-select" name="invoice_type" id="select-invoice-type" required>
                            <option value="" disabled selected>-- Pilih --</option>
                            <option value="DP">Down Payment (DP)</option>
                            <option value="CT">Full Payment</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="dp-percent-group">
                        <label class="form-label fw-semibold">Persentase DP <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="dp_percent" id="dp-percent-input"
                                   min="1" max="99" value="50" placeholder="50">
                            <span class="input-group-text">%</span>
                        </div>
                        <div class="form-text" id="dp-amount-preview">DP: Rp ... | Sisa: Rp ...</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File PO (PDF) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="po_file" accept=".pdf" required>
                        <div class="form-text">Maksimal 5MB, format PDF.</div>
                    </div>
                    <div class="alert alert-info mb-0 py-2">
                        <i class="mdi mdi-information-outline me-1"></i>
                        Status quotation akan otomatis berubah ke <strong>PO Received</strong> setelah upload.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-upload me-1"></i> Upload & Konfirmasi PO
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Create Selling Contract (Admin/Accounting) --}}
@if (!isset($sellingContract) || !$sellingContract)
<div class="modal fade" id="modalSellingContractUnit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('unit-quotation.selling-contract', $quote->id) }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title">Create Selling Contract</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <h5 class="mb-1">{{ $quote->no_quote }}</h5>
                    <p class="text-muted mb-3">{{ $quote->client?->company }}</p>
                    <div class="mb-3 text-start">
                        <label class="form-label fw-semibold">No. Selling Contract</label>
                        <input type="text" class="form-control" name="no_contract"
                            value="{{ $formattedNumberSC }}/{{ $quote->tax ? 'P' : 'NP' }}/SELLCTX/RJO/{{ $thisYear }}"
                            required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary waves-effect">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Modal Ajukan Invoice Selanjutnya --}}
@if (isset($issuedInvoices) && $issuedInvoices->isNotEmpty() && isset($remaining) && $remaining > 0)
<div class="modal fade" id="modalRequestNextInvoice" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="mdi mdi-plus-circle-outline me-1"></i> Ajukan Invoice Selanjutnya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('unit-quotation.request-next-invoice', $quote->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Sisa yang belum ditagih</label>
                        <div class="form-control bg-light fw-bold">Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan Invoice <span class="text-danger">*</span></label>
                        <select class="form-select" name="label" id="next-inv-label" required>
                            <option value="Balance Payment">Balance Payment</option>
                            <option value="Down Payment 2">Down Payment 2</option>
                            <option value="Down Payment 3">Down Payment 3</option>
                            <option value="Pelunasan">Pelunasan</option>
                            <option value="__custom__">Lainnya (isi sendiri)...</option>
                        </select>
                        <input type="text" class="form-control mt-2 d-none" id="next-inv-label-custom"
                               placeholder="Tulis keterangan invoice..." maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Persentase dari sisa <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="percent" id="next-inv-percent"
                                   min="1" max="100" value="100" required>
                            <span class="input-group-text">%</span>
                        </div>
                        <div class="form-text" id="next-inv-amount">
                            = Rp {{ number_format($remaining, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-send-outline me-1"></i> Ajukan Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

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
                        <select class="form-select" name="type" id="add-payment-type" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="DP">DP (Down Payment)</option>
                            <option value="BP">BP (Balance Payment)</option>
                            <option value="CBD">CBD (Cash Before Delivery)</option>
                            <option value="COD">COD (Cash On Delivery)</option>
                            <option value="Tempo">Tempo</option>
                        </select>
                    </div>
                    <div class="mb-3" id="tempo-group" style="display:none;">
                        <label class="form-label fw-semibold">Jangka Tempo (hari) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="tempo" min="1" placeholder="misal: 30">
                            <span class="input-group-text">hari</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select class="form-select" name="method" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="Transfer">Transfer</option>
                            <option value="Cash">Cash</option>
                            <option value="Giro">Giro</option>
                            <option value="Escrow">Escrow</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah (IDR) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="amount"
                                   min="1" step="1" placeholder="Masukkan jumlah yang diterima" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Persentase <span class="text-muted small">(opsional)</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="percent"
                                   min="1" max="100" placeholder="misal 50">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan <span class="text-muted small">(opsional)</span></label>
                        <input type="text" class="form-control" name="note"
                               placeholder="misal: Down Payment, Pelunasan...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-check me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Upload Proof (hidden, shown via JS) --}}
<div class="modal fade" id="modalUploadProof" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="mdi mdi-upload me-1"></i> Upload Bukti Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">File Bukti <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="proof-file-input"
                           accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text">PDF / JPG / PNG, maks 5MB</div>
                </div>
                <div id="proof-upload-msg"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btn-do-upload-proof">
                    <i class="mdi mdi-upload me-1"></i> Upload
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Change Status --}}
<div class="modal fade" id="modalChangeStatus" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('unit-quotation.change-status', $quote->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-floating form-floating-outline">
                        <select class="form-select" name="status">
                            <option value="sent"         {{ $quote->status === 'sent'         ? 'selected' : '' }}>Sent</option>
                            <option value="negotiation"  {{ $quote->status === 'negotiation'  ? 'selected' : '' }}>Negotiation</option>
                            <option value="revision"     {{ $quote->status === 'revision'     ? 'selected' : '' }}>Revisi</option>
                            <option value="hot_prospect" {{ $quote->status === 'hot_prospect' ? 'selected' : '' }}>Hot Prospect</option>
                            <option value="po_received"  {{ $quote->status === 'po_received'  ? 'selected' : '' }}>PO Received</option>
                            <option value="loss"         {{ $quote->status === 'loss'         ? 'selected' : '' }}>Loss</option>
                        </select>
                        <label>Status</label>
                    </div>
                    <div class="form-floating form-floating-outline mt-3">
                        <textarea class="form-control" name="note" style="height:80px" placeholder="Note (optional)"></textarea>
                        <label>Note (optional)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice.css" />
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
@endpush
@push('script')
<script>
    var totalQuote = {{ $quote->total }};

    function updateDpPreview() {
        var pct = parseFloat($('#dp-percent-input').val()) || 50;
        pct = Math.min(99, Math.max(1, pct));
        var dpAmt  = Math.round(totalQuote * pct / 100);
        var remAmt = totalQuote - dpAmt;
        $('#dp-amount-preview').text(
            'DP: Rp ' + dpAmt.toLocaleString('id-ID') +
            ' | Sisa: Rp ' + remAmt.toLocaleString('id-ID')
        );
    }

    // Add Payment modal — show Tempo field only when type=Tempo
    $('#add-payment-type').on('change', function () {
        if ($(this).val() === 'Tempo') {
            $('#tempo-group').show().find('input').prop('required', true);
        } else {
            $('#tempo-group').hide().find('input').prop('required', false).val('');
        }
    });

    // Invoice type toggle — show DP% only when DP selected
    $('#select-invoice-type').on('change', function () {
        var $dpGroup = $('#dp-percent-group');
        var $dpInput = $('#dp-percent-input');
        if ($(this).val() === 'DP') {
            $dpGroup.removeClass('d-none');
            $dpInput.prop('required', true);
            updateDpPreview();
        } else {
            $dpGroup.addClass('d-none');
            $dpInput.prop('required', false);
        }
    });

    $('#dp-percent-input').on('input', updateDpPreview);

    // Next invoice — label custom toggle
    $('#next-inv-label').on('change', function () {
        var $custom = $('#next-inv-label-custom');
        if ($(this).val() === '__custom__') {
            $custom.removeClass('d-none').prop('required', true);
        } else {
            $custom.addClass('d-none').prop('required', false).val('');
        }
    });

    // Before submit next invoice: swap __custom__ label
    $('#modalRequestNextInvoice form').on('submit', function () {
        var $sel = $('#next-inv-label');
        if ($sel.val() === '__custom__') {
            var custom = $('#next-inv-label-custom').val().trim();
            if (!custom) { $('#next-inv-label-custom').focus(); return false; }
            $sel.append('<option value="' + custom + '" selected></option>');
            $sel.val(custom);
        }
    });

    // Request Selling Contract (Sales)
    $(document).on('click', '.request-selling-unit', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        Swal.fire({
            title: 'Request Selling Contract?',
            text: 'Permintaan akan dikirim ke accounting untuk diproses.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Request',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-primary me-3 waves-effect',
                cancelButton: 'btn btn-label-secondary waves-effect',
            },
            buttonsStyling: false,
        }).then(function (result) {
            if (result.isConfirmed) {
                $.post('{{ url('unit-quotation') }}/' + id + '/request-selling-contract', {
                    _token: '{{ csrf_token() }}'
                }, function (response) {
                    if (response == 1) {
                        Swal.fire({
                            icon: 'success', title: 'Requested!',
                            text: 'Permintaan Selling Contract telah dikirim.',
                            customClass: { confirmButton: 'btn btn-success waves-effect' },
                            buttonsStyling: false,
                        }).then(function () { location.reload(); });
                    }
                });
            }
        });
    });

    // Next invoice modal — live amount preview
    @if (isset($remaining) && $remaining > 0)
    var remainingAmount = {{ $remaining }};
    $('#next-inv-percent').on('input', function () {
        var pct = parseFloat($(this).val()) || 100;
        pct = Math.min(100, Math.max(1, pct));
        var amt = Math.round(remainingAmount * pct / 100);
        $('#next-inv-amount').text('= Rp ' + amt.toLocaleString('id-ID'));
    });
    @endif

    // Upload Proof Payment
    var currentProofPaymentId = null;
    $(document).on('click', '.btn-upload-proof', function () {
        currentProofPaymentId = $(this).data('id');
        $('#proof-file-input').val('');
        $('#proof-upload-msg').html('');
        new bootstrap.Modal(document.getElementById('modalUploadProof')).show();
    });

    $('#btn-do-upload-proof').on('click', function () {
        var file = $('#proof-file-input')[0].files[0];
        if (!file) {
            $('#proof-upload-msg').html('<div class="alert alert-warning py-2">Pilih file terlebih dahulu.</div>');
            return;
        }
        var fd = new FormData();
        fd.append('file', file);
        fd.append('_token', '{{ csrf_token() }}');
        $(this).prop('disabled', true).text('Uploading...');
        $.ajax({
            url: '/unit-quotation/payment/' + currentProofPaymentId + '/proof',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function (res) {
                $('#proof-upload-msg').html('<div class="alert alert-success py-2">Berhasil diupload!</div>');
                setTimeout(function () { location.reload(); }, 1000);
            },
            error: function () {
                $('#proof-upload-msg').html('<div class="alert alert-danger py-2">Gagal upload. Cek ukuran/format file.</div>');
                $('#btn-do-upload-proof').prop('disabled', false).text('Upload');
            }
        });
    });

    // Delete Payment
    $(document).on('click', '.btn-delete-payment', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus payment ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger me-3 waves-effect',
                cancelButton: 'btn btn-label-secondary waves-effect',
            },
            buttonsStyling: false,
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/unit-quotation/payment/' + id,
                    type: 'POST',
                    data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                    success: function (res) {
                        if (res == 1) {
                            $('#pay-row-' + id).fadeOut(300, function () { $(this).remove(); });
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.delete-quote', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                cancelButton: 'btn btn-label-secondary waves-effect',
            },
            buttonsStyling: false,
        }).then(function (result) {
            if (result.isConfirmed) {
                $.post('{{ url('unit-quotation') }}/' + id, {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                }, function (response) {
                    if (response == 1) {
                        Swal.fire({
                            icon: 'success', title: 'Deleted!', text: 'Quotation has been deleted.',
                            customClass: { confirmButton: 'btn btn-success waves-effect' },
                        }).then(function () {
                            window.location.href = '{{ route('quotation.index') }}';
                        });
                    }
                });
            }
        });
    });
</script>
@endpush
