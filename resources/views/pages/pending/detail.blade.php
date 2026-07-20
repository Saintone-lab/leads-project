@extends('layouts.sales.app')
@section('title', 'Detail Sales Order')
@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb breadcrumb-style1 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ url('/') }}">Home</a>
            </li>
            @if ($pending->type == 'Project')
                <li class="breadcrumb-item">
                    <a href="{{ route('project-monitoring.index') }}">Project Monitoring</a>
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ route('pending-po.sales-order') }}">Sales Order</a>
                </li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">Detail #{{ $pending->no_pending }}</li>
        </ol>
    </nav>

    <!-- Header Block -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center py-2 mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1">
                {{ $pending->no_pending }}
            </h4>
            <p class="text-muted mb-0">Detail Sales Order untuk <span class="fw-semibold text-primary">{{ $quotation->pic->client->company }}</span></p>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if ($pending->status != '6' && $pending->status != '8' && $pending->status != '9')
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-light"
                        data-bs-toggle="dropdown" aria-expanded="false" {{ auth::user()->role != 'Sales' ? '' : 'disabled' }}>
                        <i class="mdi mdi-square-edit-outline me-1"></i> Update Status
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item waves-effect" href="javascript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#deliveryEdit"><i class="mdi mdi-truck-delivery-outline me-2 text-primary"></i>Kurir</a></li>
                        <li><a class="dropdown-item waves-effect" href="javascript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#statusEdit"><i class="mdi mdi-list-status me-2 text-warning"></i>Pending PO</a></li>
                        <li><a class="dropdown-item waves-effect" href="javascript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#resiEdit"><i class="mdi mdi-barcode-scan me-2 text-success"></i>Upload Resi</a></li>
                    </ul>
                </div>
            @elseif ($pending->status == '6')
                @if ($pending->id_product_out == null)
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#inputProductOut"
                        {{ auth()->user()->role != 'Sales' ? '' : 'disabled' }}>
                        <i class="mdi mdi-connection me-1"></i> Connect Product Out
                    </button>
                @else
                    <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#productReturn"
                        {{ auth()->user()->role != 'Sales' ? '' : 'disabled' }}>
                        <i class="mdi mdi-arrow-u-left-bottom me-1"></i> Retur Barang
                    </button>
                @endif
            @elseif ($pending->status == '9')
                <button type="button" class="btn btn-success done-po waves-effect waves-light" data-id="{{ $pending->id }}"
                    {{ auth()->user()->role != 'Sales' ? '' : 'disabled' }}>
                    <i class="mdi mdi-check-decagram-outline me-1"></i> Done
                </button>
                <button type="button" class="btn btn-danger waves-effect waves-light ms-2" data-bs-toggle="modal" data-bs-target="#inputProductOut"
                    {{ auth()->user()->role != 'Sales' ? '' : 'disabled' }}>
                    <i class="mdi mdi-connection me-1"></i> Connect Product Out
                </button>
            @endif
            <a href="{{ $pending->type == 'Project' ? route('project-monitoring.index') : route('pending-po.sales-order') }}" class="btn btn-label-secondary waves-effect">
                <i class="mdi mdi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Metadata Grid -->
    <div class="row g-4 mb-4">
        <!-- Client & PIC Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border">
                <div class="card-body">
                    <h5 class="fw-bold border-bottom pb-2 mb-3 text-primary">
                        <i class="mdi mdi-office-building-outline me-1"></i> Informasi Klien
                    </h5>
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="fw-semibold text-muted py-1" style="width: 30%">Sales</td>
                            <td class="py-1">: {{ $quotation->sales->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted py-1">Klien</td>
                            <td class="py-1">: {{ $quotation->pic->client->company }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted py-1">PIC Klien</td>
                            <td class="py-1">: {{ $quotation->pic->name_pic }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted py-1">Flag Info</td>
                            <td class="py-1">: <span class="badge bg-label-info">{{ $quotation->pic->client->info ?? '-' }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted py-1">Alamat</td>
                            <td class="py-1 text-wrap">: {{ $quotation->pic->client->address }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Document Info Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border">
                <div class="card-body">
                    <h5 class="fw-bold border-bottom pb-2 mb-3 text-primary">
                        <i class="mdi mdi-file-document-outline me-1"></i> Informasi Dokumen
                    </h5>
                    @php
                        if ($quotation->type == 'Sparepart') {
                            $link = 'quotation.show';
                        } elseif ($quotation->type == 'Overhaul') {
                            $link = 'show-overhaul.quotation';
                        } else {
                            $link = 'show-service.quotation';
                        }
                    @endphp
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="fw-semibold text-muted py-1" style="width: 35%">No Penawaran</td>
                            <td class="py-1">: 
                                <a class="fw-bold text-primary" href="{{ route($link, $quotation->id) }}">
                                    {{ $quotation->no_quote }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted py-1">No Invoice</td>
                            <td class="py-1">: 
                                @if (@$invoice->no_invoice)
                                    <a class="fw-bold text-success" href="{{ route('invoice.show', $invoice->id) }}">
                                        {{ $invoice->no_invoice }}
                                    </a>
                                @else
                                    <span class="text-danger fw-semibold">Belum ada invoice</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted py-1">Status Bayar</td>
                            <td class="py-1">: 
                                @if ($invoice)
                                    <span class="badge {{ $invoice->status_p == 1 ? 'bg-label-success' : 'bg-label-danger' }}">
                                        {{ $invoice->status_p == 1 ? 'Payment Confirmed' : 'Unpaid' }}
                                    </span>
                                @else
                                    <span class="badge bg-label-secondary">No Invoice</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted py-1">Tanggal PO</td>
                            <td class="py-1">: {{ \Carbon\Carbon::parse($quotation->po_date)->format('d-m-Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Shipping / Resi Info Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border">
                <div class="card-body d-flex flex-column">
                    <h5 class="fw-bold border-bottom pb-2 mb-3 text-primary">
                        <i class="mdi mdi-truck-delivery-outline me-1"></i> Informasi Pengiriman
                    </h5>
                    @php
                        switch ($pending->delivery) {
                            case 1:
                                $delivery = 'Kurir';
                                break;
                            case 2:
                                $delivery = 'Teknisi';
                                break;
                            case 3:
                                $delivery = 'Direct';
                                break;
                            case 4:
                                $delivery = 'Other';
                                break;
                            default:
                                $delivery = '-';
                                break;
                        }
                        switch ($pending->charged) {
                            case 1:
                                $charged = 'Company';
                                break;
                            case 2:
                                $charged = 'Customer';
                                break;
                            default:
                                $charged = '';
                                break;
                        }
                    @endphp
                    <table class="table table-borderless table-sm mb-3">
                        <tr>
                            <td class="fw-semibold text-muted py-1" style="width: 30%">Kurir SO</td>
                            <td class="py-1">: {{ $delivery }} {{ $pending->charged ? "($charged)" : '' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted py-1">Cargo Resi</td>
                            <td class="py-1">: {{ $resi->kurir ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted py-1">No Resi</td>
                            <td class="py-1">: <code class="text-dark">{{ $resi->no_track ?? '-' }}</code></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted py-1">Ongkos Kirim</td>
                            <td class="py-1">: Rp {{ number_format($resi->cost ?? 0, 0, '.', ',') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold text-muted py-1">Tanggal Resi</td>
                            <td class="py-1">: {{ $resi->date ?? '-' }}</td>
                        </tr>
                    </table>

                    @if (@$resi->image != null)
                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ url($resi->image) }}" class="btn btn-sm btn-outline-primary w-100 waves-effect" target="_blank">
                                <i class="mdi mdi-image-outline me-1"></i> Lihat Foto Resi
                            </a>
                            <a href="#" data-id="{{ $resi->id }}" data-pending="{{ $pending->id }}" class="btn btn-sm btn-outline-danger delete-resi waves-effect px-3">
                                <i class="mdi mdi-delete-outline"></i>
                            </a>
                        </div>
                    @else
                        <button class="btn btn-sm btn-outline-secondary w-100 mt-auto" disabled>
                            <i class="mdi mdi-alert-circle-outline me-1"></i> Resi Belum Diunggah
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    @if ($pending->type == 'Project')
        @if ($pending->status != '6' && $pending->status != '8')
            <div class="d-flex justify-content-end mb-3 gap-2">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#purchaseReqPrj" {{ auth()->user()->role != 'Sales' ? '' : 'disabled' }}>
                    <i class="mdi mdi-plus-box me-1"></i> Purchase Request
                </button>
                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                    data-bs-target="#replacementEdit" {{ auth()->user()->role != 'Sales' ? '' : 'disabled' }}>
                    <i class="mdi mdi-list-status me-1"></i> Update Status Barang
                </button>
            </div>
        @endif

        <!-- Items Table -->
        <div class="card mb-4 shadow-sm border">
            <div class="card-header bg-light py-3 border-bottom">
                <h5 class="m-0 fw-bold"><i class="mdi mdi-package-variant-closed text-primary me-1"></i> Daftar Barang Proyek</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Status</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $abjad = 64; @endphp
                        @foreach ($subQuote as $subJudul)
                            @php
                                $no = 0;
                                $abjad++;
                            @endphp
                            <tr class="table-secondary">
                                <td class="align-middle fw-bold text-center">{{ chr($abjad) }}</td>
                                <td colspan="5" class="align-middle fw-bold">{{ $subJudul->subtitle }}</td>
                            </tr>
                            @foreach ($subJudul->detail as $product)
                                @php
                                    switch ($product->pending[0]->status) {
                                        case 1: $status = 'On Check'; $badge = 'bg-label-warning'; break;
                                        case 2: $status = 'Ready Stock'; $badge = 'bg-label-info'; break;
                                        case 3: $status = 'Kurang'; $badge = 'bg-label-danger'; break;
                                        case 4: $status = 'Pre-Order'; $badge = 'bg-label-primary'; break;
                                        case 5: $status = 'Delivery Process'; $badge = 'bg-label-linkedin'; break;
                                        case 6: $status = 'Done'; $badge = 'bg-label-success'; break;
                                        default: $status = 'Belum Di Cek'; $badge = 'bg-label-secondary'; break;
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center">@php $no++; @endphp {{ $no }}</td>
                                    <td class="fw-semibold text-wrap" style="max-width: 250px;">{{ $product->product }}</td>
                                    <td class="text-wrap" style="max-width: 350px;">
                                        @if ($product->detail != '-')
                                            <pre class="mb-0 text-muted" style="font-family: inherit; font-size: 13px; white-space: pre-wrap;">{{ $product->detail }}</pre>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $product->qty }} {{ $product->info_qty }}</td>
                                    <td>
                                        <span class="badge {{ $pending->status == '6' ? 'bg-label-success' : $badge }}">
                                            {{ $pending->status == '6' ? 'Done' : $status }}
                                        </span>
                                    </td>
                                    <td class="text-wrap" style="max-width: 200px;">{{ $pending->status == '6' ? 'Done' : ($product->pending[0]->note ?? '-') }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Purchase Requests -->
        <div class="card mb-4 shadow-sm border">
            <div class="card-header bg-light py-3 border-bottom">
                <h5 class="m-0 fw-bold"><i class="mdi mdi-cart-arrow-down text-primary me-1"></i> Purchase Request</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>No PR</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse ($purchase as $pr)
                            @php
                                switch ($pr->status) {
                                    case 1: $status_pr = 'Sudah di ACC'; $color_pr = 'bg-label-primary'; break;
                                    case 2: $status_pr = 'Dalam Pengiriman'; $color_pr = 'bg-label-warning'; break;
                                    case 3: $status_pr = 'Done'; $color_pr = 'bg-label-success'; break;
                                    default: $status_pr = 'Belum Di ACC'; $color_pr = 'bg-label-secondary'; break;
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $no }}</td>
                                <td class="fw-bold">{{ $pr->no_pr ?? '-' }}</td>
                                <td>
                                    @if ($pr->id_equivalent == '0')
                                        -
                                    @else
                                        {{ $pr->equivalent->brand }} {{ $pr->equivalent->pn }}
                                    @endif
                                </td>
                                <td>{{ $pr->qty }} {{ $pr->equivalent->product->unit ?? '' }}</td>
                                <td class="text-wrap" style="max-width: 250px;">{{ $pr->note ?? '-' }}</td>
                                <td><span class="badge {{ $color_pr }}">{{ $status_pr }}</span></td>
                                <td class="text-center">
                                    <button data-id="{{ $pr->id }}" data-pending="{{ $pending->id }}"
                                        class="btn btn-sm btn-outline-danger delete-request waves-effect">
                                        <i class="mdi mdi-delete-outline"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            @php $no++; @endphp
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Tidak Ada Purchase Request</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        @if ($pending->status != '6' && $pending->status != '8')
            <div class="d-flex justify-content-end mb-3 gap-2">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#purchaseReq" {{ auth()->user()->role != 'Sales' ? '' : 'disabled' }}>
                    <i class="mdi mdi-plus-box me-1"></i> Purchase Request
                </button>
                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                    data-bs-target="#productEdit" {{ auth()->user()->role != 'Sales' ? '' : 'disabled' }}>
                    <i class="mdi mdi-list-status me-1"></i> Update Status Barang
                </button>
            </div>
        @endif

        <!-- Items Table (Spare Part / Non-Project) -->
        <div class="card mb-4 shadow-sm border">
            <div class="card-header bg-light py-3 border-bottom">
                <h5 class="m-0 fw-bold"><i class="mdi mdi-package-variant-closed text-primary me-1"></i> Daftar Barang Spare Parts</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Item</th>
                            <th>Description</th>
                            <th>G/R</th>
                            <th>Qty</th>
                            <th>Status</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($detQuotation as $item)
                            @php
                                switch ($item->status) {
                                    case 1: $status = 'On Check'; $badge = 'bg-label-warning'; break;
                                    case 2: $status = 'Ready Stock'; $badge = 'bg-label-info'; break;
                                    case 3: $status = 'Kurang'; $badge = 'bg-label-danger'; break;
                                    case 4: $status = 'Pre-Order'; $badge = 'bg-label-primary'; break;
                                    case 5: $status = 'Delivery Process'; $badge = 'bg-label-linkedin'; break;
                                    case 6: $status = 'Done'; $badge = 'bg-label-success'; break;
                                    default: $status = 'Belum Di Cek'; $badge = 'bg-label-secondary'; break;
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $no }}</td>
                                <td class="fw-semibold">
                                    @if ($item->id_equivalent == '0')
                                        -
                                    @else
                                        {{ $item->equivalent->brand }} {{ $item->equivalent->pn }}
                                    @endif
                                </td>
                                <td class="text-wrap" style="max-width: 350px;">
                                    <pre class="mb-0 text-muted" style="font-family: inherit; font-size: 13px; white-space: pre-wrap;">{{ $item->detail_product }}</pre>
                                </td>
                                <td>{{ $item->equivalent->product->go ?? '-' }}</td>
                                <td>{{ $item->qty }} {{ $item->info_qty }}</td>
                                <td>
                                    <span class="badge {{ $pending->status == '6' ? 'bg-label-success' : $badge }}">
                                        {{ $pending->status == '6' ? 'Done' : $status }}
                                    </span>
                                </td>
                                <td class="text-wrap" style="max-width: 200px;">{{ $pending->status == '6' ? 'Done' : ($item->note ?? '-') }}</td>
                            </tr>
                            @php $no++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Purchase Requests -->
        <div class="card mb-4 shadow-sm border">
            <div class="card-header bg-light py-3 border-bottom">
                <h5 class="m-0 fw-bold"><i class="mdi mdi-cart-arrow-down text-primary me-1"></i> Purchase Request</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>No PR</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse ($purchase as $pr)
                            @php
                                switch ($pr->status) {
                                    case 1: $status_pr = 'Sudah di ACC'; $color_pr = 'bg-label-primary'; break;
                                    case 2: $status_pr = 'Dalam Pengiriman'; $color_pr = 'bg-label-warning'; break;
                                    case 3: $status_pr = 'Done'; $color_pr = 'bg-label-success'; break;
                                    default: $status_pr = 'Belum Di ACC'; $color_pr = 'bg-label-secondary'; break;
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $no }}</td>
                                <td class="fw-bold">{{ $pr->no_pr ?? '-' }}</td>
                                <td>
                                    @if ($pr->id_equivalent == '0')
                                        -
                                    @else
                                        {{ $pr->equivalent->brand }} {{ $pr->equivalent->pn }}
                                    @endif
                                </td>
                                <td>{{ $pr->qty }} {{ $pr->equivalent->product->unit ?? '' }}</td>
                                <td class="text-wrap" style="max-width: 250px;">{{ $pr->note ?? '-' }}</td>
                                <td><span class="badge {{ $color_pr }}">{{ $status_pr }}</span></td>
                                <td class="text-center">
                                    <button data-id="{{ $pr->id }}" data-pending="{{ $pending->id }}"
                                        class="btn btn-sm btn-outline-danger delete-request waves-effect">
                                        <i class="mdi mdi-delete-outline"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            @php $no++; @endphp
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Tidak Ada Purchase Request</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Returns Section -->
        <div class="card mb-4 shadow-sm border">
            <div class="card-header bg-light py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold"><i class="mdi mdi-arrow-u-left-bottom text-primary me-1"></i> Retur Barang</h5>
                <a href="#" class="btn btn-sm btn-outline-danger clear-return waves-effect" data-id="{{ $pending->id }}">
                    <i class="mdi mdi-eraser-variant me-1"></i> Clear Return
                </a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>No Return</th>
                            <th>No DO</th>
                            <th>Tanggal Return</th>
                            <th>Tanggal Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse ($return as $retur)
                            <tr>
                                <td class="text-center">{{ $no }}</td>
                                <td>
                                    <a href="{{ route('return.show', $retur->id) }}" class="fw-bold text-primary">
                                        {{ $retur->no_return }}
                                    </a>
                                </td>
                                <td>{{ $retur->product_in->no_do ?? 'Belum Ada Product In' }}</td>
                                <td>{{ $retur->date }}</td>
                                <td>{{ $retur->date_done ?? '-' }}</td>
                            </tr>
                            @php $no++; @endphp
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Tidak ada return di invoice ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Activity Timeline / Comment Log -->
    @if ($activity->count() >= 1)
        <div class="card mb-4 shadow-sm border">
            <div class="card-header bg-light py-3 border-bottom">
                <h5 class="m-0 fw-bold"><i class="mdi mdi-history text-primary me-1"></i> Activity & Progress Timeline</h5>
            </div>
            <div class="card-body pt-4" id="viewComment">
                <ul class="timeline card-timeline mb-0">
                    @foreach ($activity as $stats)
                        @php
                            switch ($stats->status) {
                                case '1': $status = 'Pending On Check'; $color = 'warning'; break;
                                case '2': $status = 'Updated in to Ready Stock'; $color = 'info'; break;
                                case '3': $status = 'Updated in to Kurang'; $color = 'danger'; break;
                                case '4': $status = 'Updated in to Pre-Order'; $color = 'primary'; break;
                                case '5': $status = 'Updated in to Delivery Process'; $color = 'info'; break;
                                case '6': $status = 'Pending is Done'; $color = 'success'; break;
                                case '7': $status = 'Pending is Canceled'; $color = 'danger'; break;
                                case '8': $status = 'Retur Product'; $color = 'warning'; break;
                                case '9': $status = 'Delayed Done'; $color = 'secondary'; break;
                                default: $status = 'Pending Created'; $color = 'info'; break;
                            }
                        @endphp
                        <li class="timeline-item timeline-item-transparent clearfix">
                            <span class="timeline-point timeline-point-{{ $color }}"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1 border-bottom pb-1">
                                    <h6 class="mb-0 fw-bold text-{{ $color }}">{{ $status }}</h6>
                                    <small class="text-muted">
                                        {{ $stats->date->diffInHours(Carbon\Carbon::now()) > 24 ? $stats->date->format('d M Y H:i') : $stats->date->diffForHumans() }}
                                    </small>
                                </div>
                                <p class="mb-3 text-muted" style="font-size: 13px;">{{ $stats->note }}</p>
                                
                                <!-- Comments loop in chat bubble layout -->
                                @foreach ($stats->comment as $item)
                                    <div class="d-flex mb-3 align-items-start {{ $item->id_user == Auth::user()->id ? 'justify-content-end' : '' }}">
                                        <div class="d-flex {{ $item->id_user == Auth::user()->id ? 'flex-row-reverse' : '' }} gap-2 align-items-start" style="max-width: 80%;">
                                            <img src="{{ $item->user->image ? asset($item->user->image) : asset('assets/img/avatars/1.png') }}" 
                                                 alt="User Image" 
                                                 style="width: 38px; height: 38px; object-fit: cover;" 
                                                 class="rounded-circle border">
                                            <div class="p-2.5 px-3 rounded shadow-sm {{ $item->id_user == Auth::user()->id ? 'bg-primary text-white' : 'bg-light text-dark border' }}" style="border-radius: 12px !important;">
                                                <div class="d-flex justify-content-between align-items-center gap-4 mb-1">
                                                    <span class="fw-bold small {{ $item->id_user == Auth::user()->id ? 'text-white' : 'text-primary' }}">{{ $item->user->name }}</span>
                                                    <small class="small {{ $item->id_user == Auth::user()->id ? 'text-white-50' : 'text-muted' }}" style="font-size: 10px;">
                                                        {{ $item->date->diffInHours(Carbon\Carbon::now()) > 24 ? $item->date->format('d M Y H:i') : $item->date->diffForHumans() }}
                                                    </small>
                                                </div>
                                                <p class="mb-0 small" style="white-space: pre-wrap; font-size: 13px;">{{ $item->comment }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @php
                                    $lastStat = App\Models\ChangeStatus::where('id_pending', $pending->id)
                                        ->orderByDesc('id')
                                        ->first();
                                @endphp
                                
                                @if ($stats->id == $lastStat->id && $pending->status != '6')
                                    <form action="{{ route('pending-po.addComment', $pending->id) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mt-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm border" placeholder="Tulis komentar/catatan progress..." name="comment" required>
                                                <button type="submit" class="btn btn-sm btn-primary waves-effect">
                                                    <i class="mdi mdi-send-outline me-1"></i> Kirim
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Finished Product Out Invoice -->
    @if ($pending->status == '6' && $pending->id_product_out != null)
        <div class="card invoice-preview-card border shadow-sm mb-4">
            <div class="card-header bg-light py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="m-0 fw-bold text-success"><i class="mdi mdi-file-document-check-outline me-1"></i> Surat Jalan Barang Keluar ({{ $product->vers }})</h5>
                <h6 class="m-0 fw-semibold text-muted">#{{ $product->no_type == '1' ? $product->invoice : $product->po }}</h6>
            </div>
            <div class="card-body pt-3">
                <div class="row gy-3">
                    <div class="col-md-6">
                        <span class="text-muted fw-semibold d-block mb-1">Customers / Alamat Pengiriman:</span>
                        <pre class="mb-0 text-dark fw-medium p-2 bg-light rounded border text-wrap" style="font-family: inherit; font-size: 14px;">{{ $product->detail_client }}</pre>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted fw-semibold d-block mb-1">Tanggal Keluar:</span>
                        <p class="mb-0 fw-medium text-dark"><i class="mdi mdi-calendar-range me-1"></i> {{ Carbon\Carbon::parse($product->date)->format('d-m-Y') }}</p>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted fw-semibold d-block mb-1">Dibuat Oleh:</span>
                        <p class="mb-0 fw-medium text-dark"><i class="mdi mdi-account-circle-outline me-1"></i> {{ $product->user->name }}</p>
                    </div>
                    @if($product->note)
                        <div class="col-12">
                            <span class="text-muted fw-semibold d-block mb-1">Catatan Tambahan:</span>
                            <pre class="mb-0 text-muted p-2 bg-light rounded border text-wrap" style="font-family: inherit; font-size: 13px;">{{ $product->note }}</pre>
                        </div>
                    @endif
                </div>

                <!-- Product Out Items Table -->
                <div class="table-responsive border rounded mt-4">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Item Keluar</th>
                                <th>Qty</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($detProduct as $products)
                                <tr>
                                    <td class="text-center">{{ $no }}</td>
                                    <td>
                                        <p class="mb-0 fw-semibold text-primary">{{ $products->detailProduct->replacement }}</p>
                                        <small class="text-muted">{{ $products->detailProduct->product->description }}</small>
                                    </td>
                                    <td>{{ $products->qty }} {{ $products->detailProduct->product->unit }}</td>
                                    <td>Rp {{ number_format($products->price, 0, ',', '.') }}</td>
                                    <td class="fw-bold">Rp {{ number_format($products->amount, 0, ',', '.') }}</td>
                                </tr>
                                @php $no++; @endphp
                            @endforeach
                            <tr class="table-light">
                                <td colspan="3" class="border-0"></td>
                                <td class="fw-semibold">Shipping Cost</td>
                                <td class="fw-bold">: Rp {{ number_format($product->shipping, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="3" class="border-0"></td>
                                <td class="fw-semibold border-top text-primary">Grand Total</td>
                                <td class="fw-bold border-top text-primary">: Rp {{ number_format($product->total, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if ($activity->count() >= 1)
        <div class="card mb-3">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="mb-0">Activity Timeline</h5>
                </div>
            </div>
            <div class="card-body pt-4" id="viewComment">
                <ul class="timeline card-timeline mb-0">
                    @foreach ($activity as $stats)
                        @php
                            if ($stats->status == '1') {
                                $status = 'Pending On Check';
                                $color = 'info';
                            } elseif ($stats->status == '2') {
                                $status = 'Updated in to Ready Stock';
                                $color = 'whatsapp';
                            } elseif ($stats->status == '3') {
                                $status = 'Updated in to Kurang';
                                $color = 'reddit';
                            } elseif ($stats->status == '4') {
                                $status = 'Updated in to Pre-Order';
                                $color = 'primary';
                            } elseif ($stats->status == '5') {
                                $status = 'Updated in to Delivery Process';
                                $color = 'linkedin';
                            } elseif ($stats->status == '6') {
                                $status = 'Pending is Done';
                                $color = 'success';
                            } elseif ($stats->status == '7') {
                                $status = 'Pending is Canceled';
                                $color = 'danger';
                            } elseif ($stats->status == '8') {
                                $status = 'Retur Product';
                                $color = 'warning';
                            } elseif ($stats->status == '9') {
                                $status = 'Delayed Done';
                                $color = 'secondary';
                            } else {
                                $status = 'Pending Created';
                                $color = 'info';
                            }
                        @endphp
                        <li class="timeline-item timeline-item-transparent clearfix">
                            <span class="timeline-point timeline-point-{{ $color }}"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">{{ $status }}</h6>
                                    <small
                                        class="text-muted">{{ $stats->date->diffInHours(Carbon\Carbon::now()) > 24 ? $stats->date->format('d M y h:i:s') : $stats->date->diffForHumans() }}
                                    </small>
                                </div>
                                <p class="mb-3">
                                    {{ $stats->note }}
                                </p>
                                @foreach ($stats->comment as $item)
                                    <div class="d-flex justify-content-between align-items-center px-2 mb-2{{ $item->id_user == Auth::user()->id ? ' rounded bg-label-primary float-end' : '' }}"
                                        style="width : 80%;">
                                        <div class="d-flex align-items-center mb-1">
                                            <img src="{{ url('') . '/' . $item->user->image }}" alt="ini photo"
                                                style="width: 50px;" class="mx-2 rounded-pill">
                                            <p class="mb-0">
                                                <span class="fw-medium">{{ $item->user->name }}</span>:
                                                {{ $item->comment }}
                                            </p>
                                        </div>
                                        <small
                                            class="text-muted">{{ $item->date->diffInHours(Carbon\Carbon::now()) > 24 ? $item->date->format('d M y h:i:s') : $item->date->diffForHumans() }}</small>
                                    </div>
                                @endforeach
                                @php
                                    $lastStat = App\Models\ChangeStatus::where('id_pending', $pending->id)
                                        ->orderByDesc('id')
                                        ->first();
                                @endphp
                            </div>
                        </li>
                        @if ($stats->id == $lastStat->id && $pending->status != '6')
                            <form action="{{ route('pending-po.addComment', $pending->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-floating mt-3">
                                    <input type="text" class="form-control" id="floatingInputFilled"
                                        placeholder="Comment" name="comment" aria-describedby="floatingInputFilledHelp">
                                    <label for="floatingInputFilled">Comment</label>
                                    <span class="form-floating-focused"></span>
                                </div>
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light float-end">Comment</button>
                            </form>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($pending->status == '6' && $pending->id_product_out != null)
        <div class="card invoice-preview-card">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                    <div class="mb-xl-0 pb-1">
                        <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                            <span class="app-brand-logo demo">
                                <span style="color: var(--bs-primary)">
                                    <img class="text-md" src="{{ asset('/asset') }}/logo/Reftech-Log.png" alt=""
                                        srcset="" width="60%">
                                </span>
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        <h3 class="fw-bold">Barang Keluar ({{ $product->vers }})</h3>
                        <div>
                            <span
                                class="fw-bolder">#{{ $product->no_type == '1' ? $product->invoice : $product->po }}</span>
                        </div>
                        <div class="mt-1">
                            <span class="text-muted">{{ Carbon\Carbon::parse($product->date)->format('d-m-Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-0">
            <div class="card-body mb-3">
                <div class="row">
                    <div class="col-4 col-lg-2 fw-medium">
                        <p class="mb-1">Customers </p>
                    </div>
                    <div class="col-8 col-lg-10">
                        <pre class="mb-1"
                            style="font-size: 15px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">: {{ $product->detail_client }}</pre>
                    </div>
                </div>
            </div>
            <hr class="my-0">
            <div class="card-body mb-3">
                <div class="row">
                    <div class="col-4 col-lg-2 fw-medium">
                        <p class="mb-1">Note</p>
                    </div>
                    <div class="col-8 col-lg-6">
                        <pre
                            style="font-size: 15px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow-x: auto; white-space: pre-wrap;">: {{ $product->note }}</pre>
                    </div>
                    <div class="col-4 col-lg-2 fw-medium">
                        <p class="mb-1">User</p>
                    </div>
                    <div class="col-8 col-lg-2">
                        <p class="mb-1">: {{ $product->user->name }}</p>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table m-0 mb-4">
                    <thead class="table-light border-top">
                        <tr>
                            <th>No.</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 0;
                        @endphp
                        @foreach ($detProduct as $products)
                            @php
                                $no++;
                            @endphp
                            <tr style="font-size: 13px">
                                <td class="align-top">{{ $no }}</td>
                                <td class="text-nowrap align-top">
                                    <p class="mb-0 fw-semibold" style="font-size: 12px">
                                        {{ $products->detailProduct->replacement }}
                                    </p>
                                    <pre class="mb-0"
                                        style="font-size: 10px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $products->detailProduct->product->description }}</pre>
                                </td>
                                <td class="align-top">{{ $products->qty }}
                                    {{ $products->detailProduct->product->unit }}
                                </td>
                                <td class="align-top">RP {{ number_format($products->price, 0, '', '.') }}</td>
                                <td class="align-top">RP {{ number_format($products->amount, 0, '', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr style="font-size: 13px;">
                            <td colspan="3" style="border:none;"></td>
                            <td>Shipping</td>
                            <td>: RP {{ number_format($product->shipping, 0, '', '.') }}</td>
                        </tr>
                        <tr style="font-size: 13px">
                            <td colspan="3" style="border:none;"></td>
                            <td style="border:none;">Total</td>
                            <td style="border:none;">: RP {{ number_format($product->total, 0, '', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    {{-- @foreach ($pendingPO as $pending)
        @include('components.modal.pending.detail')
    @endforeach --}}
    @include('components.modal.pending.status')
    @include('components.modal.pending.kurir')
    @include('components.modal.pending.product')
    @include('components.modal.pending.product-out')
    @include('components.modal.pending.project')
    @include('components.modal.pending.return')
    @include('components.modal.pending.resi')
    @if ($pending->type == 'Project')
        @include('components.modal.pending.request-project')
    @else
        @include('components.modal.pending.request')
    @endif
@endsection()

@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
@endpush

@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/tagify/tagify.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/bloodhound/bloodhound.js"></script>
@endpush

@push('page-script')
    <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
    <script src="{{ asset('assets') }}/js/tables-datatables-basic.js"></script>
    <script src="{{ asset('assets') }}/includes/table-pending-non-project-admin.js"></script>
    <script src="{{ asset('assets') }}/includes/table-pending.js"></script>
    <script src="{{ asset('assets') }}/js/forms-selects.js"></script>
@endpush

@push('script')
    <script>
        // Initialize Bootstrap tooltips using jQuery
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
            $('.select-project').select2({
                dropdownParent: $('#purchaseReqPrj')
            });
        });

        $(document).on('click', '.button-clear', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure to Clear it??",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Cleared it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('notulen') }}/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'PATCH',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Cleared!",
                                    text: "This Notulen has been Cleared.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/notulen';
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Cleared!'
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
        $(document).on('click', '.delete-resi', function() {
            var id = $(this).data('id');
            var pending = $(this).data('pending');
            Swal.fire({
                title: "Are you sure to Delete it??",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Deleted it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('pending-po') }}/delete-resi/' + id,
                        'type': 'DELETE',
                        'data': {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: "This Notulen has been Deleted.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/pending-po/' + pending;
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Deleted!'
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

        $(document).on('click', '.delete-request', function() {
            var id = $(this).data('id');
            var pending = $(this).data('pending');
            Swal.fire({
                title: "Are you sure to Delete it??",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Deleted it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('purchase-request') }}/delete/' + id,
                        'type': 'DELETE',
                        'data': {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: "This Notulen has been Deleted.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/pending-po/' + pending;
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Deleted!'
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
        $(document).on('click', '.clear-return', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Accept it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('pending-po') }}/clear-return/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'POST',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Accepted!",
                                    text: "Your file has been Accepted.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Accept!'
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
        $(document).on('click', '.done-po', function() {
            var id = $(this).data('id');
            var pending = $(this).data('pending');
            Swal.fire({
                title: "Are you sure to Done it??",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Done it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('pending-po') }}/done/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'POST',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Done!",
                                    text: "This Notulen has been Done.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/pending-po/product-out-project/' + id;
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Done!'
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
