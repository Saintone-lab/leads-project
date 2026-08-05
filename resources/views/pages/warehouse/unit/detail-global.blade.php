@extends('layouts.sales.app')
@section('title', 'Detail Unit — ' . $product->sku)
@section('content')
    @php
        $isPriv = in_array(auth::user()->role, ['Admin', 'Sales', 'Logistic']);
        $isCompressor = in_array($product->unit, ['PISTON COMPRESSOR', 'AIR COMPRESSOR SCREW']);
        $isDryer      = in_array($product->unit, ['REFRIGERANT AIR DRYER', 'DESICANT DRYER']);
    @endphp

    {{-- Top Header Action Bar & Title --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('unit-global.index') }}" class="btn btn-icon btn-outline-secondary btn-sm rounded-circle" data-bs-toggle="tooltip" title="Kembali ke Daftar Unit">
                <i class="mdi mdi-arrow-left fs-4"></i>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-label-primary px-2.5 py-1 rounded-pill small fw-semibold">{{ $product->unit }}</span>
                    @if ($product->status)
                        <span class="badge bg-label-success px-2.5 py-1 rounded-pill small fw-semibold">{{ $product->status }}</span>
                    @endif
                </div>
                <h4 class="fw-bold mb-0 text-dark">{{ $product->sku }}</h4>
            </div>
        </div>
        @if ($isPriv)
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#updateProduct-{{ $product->id }}">
                    <i class="mdi mdi-pencil-outline me-1"></i> Edit Unit
                </button>
                <button type="button" data-id="{{ $product->id }}" class="btn btn-label-danger delete-product">
                    <i class="mdi mdi-delete-outline me-1"></i> Delete
                </button>
            </div>
        @endif
    </div>

    {{-- Hero Summary Card --}}
    <div class="card unit-hero-card mb-4 border-0">
        <div class="card-body py-3 px-4">
            <div class="row align-items-center g-3">
                <div class="col-6 col-md-3 border-end-md">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar avatar-md bg-label-primary rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-cog-outline fs-3"></i>
                        </div>
                        <div>
                            <span class="metric-label d-block text-muted">Brand & Model</span>
                            <h6 class="mb-0 fw-bold text-dark">{{ $product->brand ?: '-' }} {{ $product->model ? '/ ' . $product->model : '' }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 border-end-md">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar avatar-md bg-label-info rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-lightning-bolt-outline fs-3"></i>
                        </div>
                        <div>
                            <span class="metric-label d-block text-muted">Motor Power</span>
                            <h6 class="mb-0 fw-bold text-dark">{{ $product->power ?: '-' }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 border-end-md">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar avatar-md bg-label-warning rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-fan fs-3"></i>
                        </div>
                        <div>
                            <span class="metric-label d-block text-muted">Air Capacity / FAD</span>
                            <h6 class="mb-0 fw-bold text-dark">{{ $product->air_cap ? $product->air_cap . ' m³/min' : '-' }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar avatar-md bg-label-success rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <i class="mdi mdi-cube-outline fs-3"></i>
                        </div>
                        <div>
                            <span class="metric-label d-block text-muted">Total Stock</span>
                            <h6 class="mb-0 fw-bold text-dark">{{ $allStock }} <span class="small text-muted fw-normal">(Awal: {{ $product->frist_stock ?: 0 }})</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Nav Tabs Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom p-0">
            <ul class="nav nav-tabs custom-nav-tabs m-0" id="unit-global-detail-tab-nav" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-unit-detail" type="button">
                        <i class="mdi mdi-information-outline me-1.5 fs-5"></i>Detail Specifications
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-unit-consumable" type="button">
                        <i class="mdi mdi-toolbox-outline me-1.5 fs-5"></i>Consumable Part
                        <span class="badge bg-label-primary rounded-pill ms-2">{{ $consumable->count() }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-unit-nonconsumable" type="button">
                        <i class="mdi mdi-tools me-1.5 fs-5"></i>Non Consumable Part
                        <span class="badge bg-label-secondary rounded-pill ms-2">{{ $nonconsumable->count() }}</span>
                    </button>
                </li>
                @if ($isPriv)
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-unit-pm-template" type="button">
                            <i class="mdi mdi-file-document-edit-outline me-1.5 fs-5"></i>Template Penawaran PM
                            <span class="badge bg-success rounded-pill ms-2">BARU</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-unit-equivalent" type="button">
                            <i class="mdi mdi-swap-horizontal me-1.5 fs-5"></i>Equivalent
                        </button>
                    </li>
                @endif
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content p-0">

                {{-- ==================== TAB: DETAIL ==================== --}}
                <div class="tab-pane fade show active" id="tab-unit-detail" role="tabpanel">
                    <div class="row g-4">
                        {{-- General Specs --}}
                        <div class="col-lg-6">
                            <div class="card spec-card">
                                <div class="spec-header d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 fw-bold text-dark">
                                        <i class="mdi mdi-format-list-bulleted me-2 text-primary"></i>Informasi Umum
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row spec-item align-items-center">
                                        <div class="col-5 spec-label">Kategori Unit</div>
                                        <div class="col-7 spec-val">{{ $product->unit ?: '-' }}</div>
                                    </div>
                                    <div class="row spec-item align-items-center">
                                        <div class="col-5 spec-label">SKU</div>
                                        <div class="col-7 spec-val"><code class="text-primary fw-bold">{{ $product->sku ?: '-' }}</code></div>
                                    </div>
                                    <div class="row spec-item align-items-center">
                                        <div class="col-5 spec-label">Brand</div>
                                        <div class="col-7 spec-val">{{ $product->brand ?: '-' }}</div>
                                    </div>
                                    <div class="row spec-item align-items-center">
                                        <div class="col-5 spec-label">Model</div>
                                        <div class="col-7 spec-val">{{ $product->model ?: '-' }}</div>
                                    </div>
                                    <div class="row spec-item align-items-center">
                                        <div class="col-5 spec-label">Status Unit</div>
                                        <div class="col-7 spec-val">
                                            @if ($product->status)
                                                <span class="badge bg-label-info">{{ $product->status }}</span>
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row spec-item align-items-center">
                                        <div class="col-5 spec-label">Stock Awal</div>
                                        <div class="col-7 spec-val">{{ $product->frist_stock !== null ? $product->frist_stock : '-' }}</div>
                                    </div>
                                    @if ($product->desc && !$isCompressor && !$isDryer)
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Deskripsi</div>
                                            <div class="col-7 spec-val">{{ $product->desc }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Technical Specs --}}
                        <div class="col-lg-6">
                            <div class="card spec-card">
                                <div class="spec-header d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 fw-bold text-dark">
                                        <i class="mdi mdi-tune me-2 text-primary"></i>Spesifikasi Teknis
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    @if ($isCompressor)
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Type Compressor</div>
                                            <div class="col-7 spec-val">{{ $product->type_unit ?: '-' }}</div>
                                        </div>
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Short Description</div>
                                            <div class="col-7 spec-val">{{ $product->desc ?: '-' }}</div>
                                        </div>
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Max. Working Pressure</div>
                                            <div class="col-7 spec-val">{{ $product->bar ? $product->bar . ' Bar' : '-' }}</div>
                                        </div>
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Air Capacity</div>
                                            <div class="col-7 spec-val">{{ $product->air_cap ? $product->air_cap . ' m³/min' : '-' }}</div>
                                        </div>
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Motor Power</div>
                                            <div class="col-7 spec-val"><span class="badge bg-label-primary">{{ $product->power ?: '-' }}</span></div>
                                        </div>
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Rated Voltage</div>
                                            <div class="col-7 spec-val">{{ $product->voltage ?: '-' }}</div>
                                        </div>
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Drive</div>
                                            <div class="col-7 spec-val">{{ $product->connect ?: '-' }}</div>
                                        </div>
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Cooling Method</div>
                                            <div class="col-7 spec-val">{{ $product->cooling ?: '-' }}</div>
                                        </div>
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Discharge Connection</div>
                                            <div class="col-7 spec-val">{{ $product->exhaust ?: '-' }}</div>
                                        </div>
                                    @elseif ($isDryer)
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">FAD / Air Capacity</div>
                                            <div class="col-7 spec-val">{{ $product->air_cap ? $product->air_cap . ' m³/min' : '-' }}</div>
                                        </div>
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Refrigerant Type</div>
                                            <div class="col-7 spec-val">{{ $product->refrigerant_type ?: '-' }}</div>
                                        </div>
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">PDP</div>
                                            <div class="col-7 spec-val">{{ $product->pdp ?: '-' }}</div>
                                        </div>
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Rated Voltage</div>
                                            <div class="col-7 spec-val">{{ $product->voltage ?: '-' }}</div>
                                        </div>
                                    @else
                                        <div class="row spec-item align-items-center">
                                            <div class="col-5 spec-label">Short Description</div>
                                            <div class="col-7 spec-val">{{ $product->desc ?: '-' }}</div>
                                        </div>
                                    @endif
                                    <div class="row spec-item align-items-center">
                                        <div class="col-5 spec-label">Dimension</div>
                                        <div class="col-7 spec-val">{{ $product->dimension ?: '-' }}</div>
                                    </div>
                                    <div class="row spec-item align-items-center">
                                        <div class="col-5 spec-label">Weight</div>
                                        <div class="col-7 spec-val">{{ $product->weight ? $product->weight . ' Kg' : '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($product->note)
                            <div class="col-12">
                                <div class="card spec-card bg-light border-0">
                                    <div class="card-body p-3.5">
                                        <h6 class="fw-bold text-dark mb-2">
                                            <i class="mdi mdi-note-text-outline me-2 text-warning"></i>Catatan (Note)
                                        </h6>
                                        <div class="p-3 bg-white rounded border text-secondary" style="font-family: inherit; white-space: pre-wrap;">{{ $product->note }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ==================== TAB: CONSUMABLE PART ==================== --}}
                <div class="tab-pane fade" id="tab-unit-consumable" role="tabpanel">
                    @if ($isPriv)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold text-dark">Daftar Consumable Part</h6>
                            <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#createSparepart">
                                <i class="mdi mdi-plus me-1"></i> New Sparepart
                            </button>
                        </div>
                        <div class="table-responsive text-nowrap rounded border">
                            <table class="table table-hover table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>Part Number (PN)</th>
                                        <th>Description</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">PM Level</th>
                                        <th class="text-center">Total Stock</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($consumable as $part)
                                        @php
                                            $allStock = $part->warehouse_stock + $part->stock;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-dark">{{ $part->pn }}</span>
                                            </td>
                                            <td>
                                                <span class="text-secondary">{{ $part->description }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-label-secondary text-dark px-2.5 py-1 fw-semibold">
                                                    {{ $part->qty }} {{ $part->equivalent->product->unit ?? 'Pcs' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-label-info px-2.5 py-1 fw-semibold">{{ $part->pm_level ?? 'PM1' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-label-success px-2.5 py-1 fw-semibold">{{ $allStock }}</span>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#editSparepart-{{ $part->id }}"
                                                    class="btn btn-icon btn-sm btn-label-warning me-1" title="Edit Sparepart">
                                                    <i class="mdi mdi-pencil-outline"></i>
                                                </button>
                                                <button type="button" data-id="{{ $part->id }}"
                                                    class="btn btn-icon btn-sm btn-label-danger delete-sparepart" title="Delete Sparepart">
                                                    <i class="mdi mdi-delete-outline"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="mdi mdi-toolbox-outline fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                                    Belum ada Consumable Part untuk unit ini.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive text-nowrap rounded border">
                            <table class="table table-hover table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>Part Number (PN)</th>
                                        <th>Description</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($consumable as $part)
                                        @php
                                            $allStock = $part->warehouse_stock + $part->stock;
                                        @endphp
                                        <tr>
                                            <td><span class="fw-bold text-dark">{{ $part->pn }}</span></td>
                                            <td><span class="text-secondary">{{ $part->description }}</span></td>
                                            <td class="text-center">{{ $part->qty }} {{ $part->info_qty }}</td>
                                            <td class="text-center"><span class="badge bg-label-success">{{ $allStock }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <div class="text-muted">Belum ada Consumable Part.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- ==================== TAB: NON CONSUMABLE PART ==================== --}}
                <div class="tab-pane fade" id="tab-unit-nonconsumable" role="tabpanel">
                    @if ($isPriv)
                        <div class="table-responsive text-nowrap rounded border">
                            <table class="table table-hover table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>Part Number (PN)</th>
                                        <th>Description</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Total Stock</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($nonconsumable as $part)
                                        @php
                                            $allStock = $part->warehouse_stock + $part->stock;
                                        @endphp
                                        <tr>
                                            <td><span class="fw-bold text-dark">{{ $part->pn }}</span></td>
                                            <td><span class="text-secondary">{{ $part->description }}</span></td>
                                            <td class="text-center">
                                                <span class="badge bg-label-secondary text-dark px-2.5 py-1 fw-semibold">
                                                    {{ $part->qty }} {{ $part->equivalent->product->unit ?? 'Pcs' }}
                                                </span>
                                            </td>
                                            <td class="text-center"><span class="badge bg-label-success px-2.5 py-1 fw-semibold">{{ $allStock }}</span></td>
                                            <td class="text-end">
                                                <button type="button" data-id="{{ $part->id }}"
                                                    class="btn btn-icon btn-sm btn-label-danger delete-sparepart" title="Delete Sparepart">
                                                    <i class="mdi mdi-delete-outline"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="mdi mdi-tools fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                                    Belum ada Non Consumable Part untuk unit ini.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive text-nowrap rounded border">
                            <table class="table table-hover table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>Part Number (PN)</th>
                                        <th>Description</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($nonconsumable as $part)
                                        @php
                                            $allStock = $part->warehouse_stock + $part->stock;
                                        @endphp
                                        <tr>
                                            <td><span class="fw-bold text-dark">{{ $part->pn }}</span></td>
                                            <td><span class="text-secondary">{{ $part->description }}</span></td>
                                            <td class="text-center">{{ $part->qty }} {{ $part->info_qty }}</td>
                                            <td class="text-center"><span class="badge bg-label-success">{{ $allStock }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <div class="text-muted">Belum ada Non Consumable Part.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                @if ($isPriv)
                    {{-- ==================== TAB: TEMPLATE PENAWARAN PM ==================== --}}
                    <div class="tab-pane fade" id="tab-unit-pm-template" role="tabpanel">
                        <div id="pm-template-card"
                            data-endpoint="{{ route('unit-global.pm-template', $product->id) }}"
                            data-quotation-create-url="{{ route('unit-quotation.create') }}">

                            <div class="alert alert-info border-0 shadow-xs d-flex align-items-center mb-4 rounded-3">
                                <i class="mdi mdi-information-outline fs-4 me-3 text-info"></i>
                                <div class="small">
                                    Draft item dirakit otomatis dari sparepart unit ini (per Level PM) dan tarif jasa
                                    <a href="{{ route('forecast.prices') }}" target="_blank" class="fw-bold text-decoration-underline">pricelist Forecast</a>
                                    berdasarkan Motor Power unit ini.
                                </div>
                            </div>

                            <div class="row g-4">
                                {{-- Kolom kiri: ringkasan unit + pilih level --}}
                                <div class="col-lg-3">
                                    <div class="card spec-card mb-3">
                                        <div class="card-body p-3">
                                            <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing:.04em;">
                                                Ringkasan Unit
                                            </h6>
                                            <dl class="row mb-0 small">
                                                <dt class="col-5 text-muted fw-normal">SKU</dt>
                                                <dd class="col-7 text-end mb-2 fw-semibold text-dark">{{ $product->sku }}</dd>
                                                <dt class="col-5 text-muted fw-normal">Brand</dt>
                                                <dd class="col-7 text-end mb-2 text-dark">{{ $product->brand ?: '-' }}</dd>
                                                <dt class="col-5 text-muted fw-normal">Model</dt>
                                                <dd class="col-7 text-end mb-2 text-dark">{{ $product->model ?: '-' }}</dd>
                                                <dt class="col-5 text-muted fw-normal">Power</dt>
                                                <dd class="col-7 text-end mb-0 fw-bold text-primary">{{ $product->power ?: '-' }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="card spec-card">
                                        <div class="card-body p-3">
                                            <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing:.04em;">
                                                Pilih Level PM
                                            </h6>
                                            <div class="d-flex flex-wrap gap-2" id="pm-level-group">
                                                <button type="button" class="btn btn-sm rounded-pill px-3 pm-level-btn" data-level="PM1">PM1</button>
                                                <button type="button" class="btn btn-sm rounded-pill px-3 pm-level-btn" data-level="PM2">PM2</button>
                                                <button type="button" class="btn btn-sm rounded-pill px-3 pm-level-btn" data-level="PM3">PM3</button>
                                                <button type="button" class="btn btn-sm rounded-pill px-3 pm-level-btn" data-level="PM4">PM4</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Kolom kanan: hasil generate --}}
                                <div class="col-lg-9">
                                    <div id="pm-template-empty" class="card spec-card">
                                        <div class="card-body text-center text-muted py-5">
                                            <i class="mdi mdi-arrow-left-bold-circle-outline fs-1 d-block mb-2 text-primary opacity-50"></i>
                                            Pilih level PM di sebelah kiri untuk melihat draft item dari sparepart &amp; tarif jasa unit ini.
                                        </div>
                                    </div>

                                    <div id="pm-template-result" style="display:none;">
                                        <div class="row g-3">
                                            <div class="col-lg-7">
                                                <div class="card spec-card h-100">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h6 class="mb-0 fw-bold text-dark">Parts dari Sparepart</h6>
                                                            <span class="badge bg-label-primary rounded-pill px-2.5" id="pm-template-level-badge">-</span>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-hover mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-uppercase small text-muted">Part</th>
                                                                        <th class="text-end text-uppercase small text-muted">Qty</th>
                                                                        <th class="text-end text-uppercase small text-muted">Harga</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="pm-template-rows"></tbody>
                                                            </table>
                                                        </div>
                                                        <div id="pm-template-noparts-warning" class="alert alert-warning py-2 px-3 small mt-2 mb-0 rounded" style="display:none;">
                                                            Belum ada sparepart dengan Level PM ini untuk unit tersebut.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="card spec-card h-100">
                                                    <div class="card-body p-3">
                                                        <h6 class="mb-3 fw-bold text-dark">Biaya Jasa</h6>
                                                        <div id="pm-template-service-rows"></div>
                                                        <div id="pm-template-power-warning" class="alert alert-warning py-2 px-3 small mb-0 rounded" style="display:none;">
                                                            Tarif jasa untuk power unit ini belum ada di pricelist Forecast. Baris jasa tidak ditambahkan otomatis — isi manual di quotation nanti.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card spec-card mt-3">
                                            <div class="card-body p-3 d-flex justify-content-between align-items-center flex-wrap gap-3 pm-total-bar">
                                                <div>
                                                    <div class="text-muted small">Total Estimated Price</div>
                                                    <div class="fw-bold fs-4 text-primary" id="pm-template-total">Rp 0</div>
                                                </div>
                                                <button type="button" class="btn btn-primary btn-md shadow-sm" id="btn-generate-quotation" disabled>
                                                    <i class="mdi mdi-file-plus-outline me-1"></i> Generate ke Quotation
                                                    <small class="d-block fw-normal" style="font-size:.68rem; opacity:.85;">
                                                        menambah baris ke unit_quotation_detail
                                                    </small>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ==================== TAB: EQUIVALENT ==================== --}}
                    <div class="tab-pane fade" id="tab-unit-equivalent" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold text-dark">Data Equivalent Unit</h6>
                            <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#createEquivalent-{{ $product->id }}">
                                <i class="mdi mdi-plus me-1"></i> New Equivalent
                            </button>
                        </div>
                        <div class="table-responsive text-nowrap rounded border p-2">
                            <table class="datatable-product-equivalent table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Brand</th>
                                        <th>PN</th>
                                        <th>Bar</th>
                                        <th>Air Capacity</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('components.modal.warehouse.unit.form-global')
    @include('components.modal.warehouse.unit.sparepart')
    @if ($isPriv)
        @foreach ($consumable as $part)
            <!-- Edit Sparepart Modal -->
            <div class="modal fade" id="editSparepart-{{ $part->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form action="{{ route('unit-sparepart.update', $part->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Edit Sparepart — {{ $part->pn }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating form-floating-outline mb-3">
                                    <input type="number" class="form-control" name="qty" id="qty-{{ $part->id }}" min="1" value="{{ $part->qty }}" required>
                                    <label for="qty-{{ $part->id }}">Quantity</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-3">
                                    <select class="form-select" name="pm_level" id="pm-{{ $part->id }}">
                                        <option value="PM1" {{ $part->pm_level == 'PM2' || $part->pm_level == 'PM3' || $part->pm_level == 'PM4' ? '' : 'selected' }}>PM1 (Minor)</option>
                                        <option value="PM2" {{ $part->pm_level == 'PM2' ? 'selected' : '' }}>PM2 (Major)</option>
                                        <option value="PM3" {{ $part->pm_level == 'PM3' ? 'selected' : '' }}>PM3</option>
                                        <option value="PM4" {{ $part->pm_level == 'PM4' ? 'selected' : '' }}>PM4</option>
                                    </select>
                                    <label for="pm-{{ $part->id }}">PM Level</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    @include('components.modal.warehouse.replacement.form')
    @include('components.modal.warehouse.equivalent.form-global')
    @php
        $no = 0;
    @endphp
    @foreach ($serials as $serial)
        @include('components.modal.warehouse.equivalent.form-global')
        @php
            $no++;
        @endphp
    @endforeach
    @foreach ($details as $detail)
        @include('components.modal.warehouse.replacement.form-price')
    @endforeach
@endsection()

@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />

    <style>
        .unit-hero-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }
        .border-end-md {
            border-right: 1px solid rgba(0, 0, 0, 0.08);
        }
        @media (max-width: 767.98px) {
            .border-end-md {
                border-right: none;
                border-bottom: 1px solid rgba(0, 0, 0, 0.08);
                padding-bottom: 12px;
            }
        }
        .metric-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .spec-card {
            border-radius: 14px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.02);
            height: 100%;
            transition: all 0.2s ease;
        }
        .spec-card:hover {
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
        }
        .spec-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            padding: 14px 18px;
            background: rgba(248, 249, 250, 0.7);
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
        }
        .spec-item {
            padding: 10px 0;
            border-bottom: 1px dashed rgba(0, 0, 0, 0.08);
        }
        .spec-item:last-child {
            border-bottom: none;
        }
        .spec-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
        }
        .spec-val {
            font-size: 0.9rem;
            color: #2b3445;
            font-weight: 600;
        }
        .custom-nav-tabs .nav-link {
            border: none;
            border-bottom: 2.5px solid transparent;
            color: #6c757d;
            font-weight: 600;
            padding: 14px 22px;
            border-radius: 0;
            transition: all 0.2s ease;
        }
        .custom-nav-tabs .nav-link:hover {
            color: #696cff;
            border-bottom-color: rgba(105, 108, 255, 0.4);
        }
        .custom-nav-tabs .nav-link.active {
            color: #696cff;
            background: transparent;
            border-bottom-color: #696cff;
        }
        .table-modern th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #566a7f;
            background-color: #f8f9fa;
            border-top: none;
            border-bottom: 1px solid #e7e7e8;
            padding: 12px 16px;
        }
        .table-modern td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f1f2;
        }
        #tab-unit-pm-template .pm-level-btn {
            border: 1px solid #696cff;
            background: rgba(105, 108, 255, 0.05);
            color: #696cff;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        #tab-unit-pm-template .pm-level-btn:hover {
            background: rgba(105, 108, 255, 0.15);
        }
        #tab-unit-pm-template .pm-level-btn.active {
            background: #696cff;
            border-color: #696cff;
            color: #fff;
            box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);
        }
        #tab-unit-pm-template .pm-source-note {
            font-family: ui-monospace, SFMono-Regular, Consolas, monospace;
            font-size: .72rem;
            color: var(--bs-secondary-color, #6c757d);
            display: block;
            margin-top: 2px;
        }
        #tab-unit-pm-template .pm-total-bar {
            border-top: 1px dashed var(--bs-border-color);
        }
    </style>
@endpush

@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/tagify/tagify.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/bloodhound/bloodhound.js"></script>
@endpush

@push('page-script')
    <script src="{{ asset('assets') }}/js/tables-datatables-basic.js"></script>
    <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
    <script src="{{ asset('assets') }}/includes/table-equivalent-global.js"></script>
    <script src="{{ asset('assets') }}/includes/table-product-in-detail.js"></script>
    <script src="{{ asset('assets') }}/includes/table-product-out-detail.js"></script>
    <script src="{{ asset('assets') }}/includes/table-quotation-product.js"></script>
    <script src="{{ asset('assets') }}/js/forms-selects.js"></script>
@endpush

@push('script')
    <script>
        // Re-adjust DataTables column widths when switching tabs
        $('#unit-global-detail-tab-nav button[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
            $.fn.dataTable.tables({
                visible: true,
                api: true
            }).columns.adjust().responsive.recalc();
        });

        // Rupiah formatter untuk field pricelist
        $(document).on('input', '.rupiah-price', function () {
            var raw = $(this).val().replace(/\./g, '').replace(/\D/g, '');
            $(this).val(raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
            $('#price-raw').val(raw);
        });

        $(document).on('click', '.delete-product', function() {
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
                        'url': '{{ url('unit') }}/' + id,
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
                                    window.location.href = '/unit-global';
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

        $(document).on('click', '.delete-replacement', function() {
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
                        'url': '{{ url('product') }}/replacement/' + id,
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
                                    location.reload();
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

        $(document).on('click', '.delete-equivalent', function() {
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
                        'url': '{{ url('product') }}/equivalent/' + id,
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
                                    location.reload();
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

        $(document).on('click', '.delete-sparepart', function() {
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
                        'url': '{{ url('delete') }}/sparepart/' + id,
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
                                    location.reload();
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

        $(() => {
            function formatNumber(n) {
                return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }

            $(".invoice-item-price-label").on('keyup', function() {
                var input = $(this)
                var id = input.data('id');
                var input_val = input.val();
                input_val = formatNumber(input_val);
                input.val(input_val);
                var nomorInt = parseFloat(input_val.replace(/[.,]/g, ''));
                $(`#price-${id}`).val(nomorInt);
            });

            $(".invoice-item-modal-label").on('keyup', function() {
                var input = $(this)
                var id = input.data('id');
                var input_val = input.val();
                input_val = formatNumber(input_val);
                input.val(input_val);
                var nomorInt = parseFloat(input_val.replace(/[.,]/g, ''));
                $(`#modal-${id}`).val(nomorInt);
            });

            // ── Template Penawaran PM (Unit Global) ─────────────────────────
            var $pmCard = $('#pm-template-card');
            if ($pmCard.length) {
                var pmEndpoint = $pmCard.data('endpoint');
                var pmQuotationCreateUrl = $pmCard.data('quotation-create-url');
                var pmCurrentData = null;

                function pmFormatRupiah(n) {
                    n = Math.round(n || 0);
                    return 'Rp ' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                function pmEscapeHtml(str) {
                    return $('<div>').text(str == null ? '' : str).html();
                }

                function renderPmTemplate(data) {
                    pmCurrentData = data;
                    var $rows = $('#pm-template-rows').empty();
                    var $serviceRows = $('#pm-template-service-rows').empty();
                    var total = 0;

                    $('#pm-template-level-badge').text(data.level);

                    $.each(data.parts, function(i, part) {
                        var amount = part.qty * part.price;
                        total += amount;
                        $rows.append(
                            '<tr>' +
                                '<td><span class="fw-semibold text-dark">' + pmEscapeHtml(part.pn || 'Spare Part') + '</span>' +
                                    (part.description ? '<span class="text-muted small d-block">' + pmEscapeHtml(part.description) + '</span>' : '') +
                                '</td>' +
                                '<td class="text-end">' + part.qty + ' ' + pmEscapeHtml(part.info_qty || 'Pcs') + '</td>' +
                                '<td class="text-end fw-semibold">' + pmFormatRupiah(part.price) + '</td>' +
                            '</tr>'
                        );
                    });

                    $('#pm-template-noparts-warning').toggle(data.parts.length === 0);

                    if (data.service.matched && data.service.amount !== null) {
                        total += data.service.amount;
                        $serviceRows.append(
                            '<div class="d-flex justify-content-between align-items-baseline py-2 border-bottom">' +
                                '<span class="fw-semibold text-dark">' + pmEscapeHtml(data.service.label) + '</span>' +
                                '<span class="fw-bold text-primary">' + pmFormatRupiah(data.service.amount) + '</span>' +
                            '</div>' +
                            '<span class="pm-source-note">power_service_prices.power = "' + pmEscapeHtml(data.service.power_normalized) + '"</span>'
                        );
                        $('#pm-template-power-warning').hide();
                    } else {
                        $('#pm-template-power-warning').show();
                    }

                    $('#pm-template-total').text(pmFormatRupiah(total));
                    $('#pm-template-empty').hide();
                    $('#pm-template-result').show();
                    $('#btn-generate-quotation').prop('disabled', data.parts.length === 0 && !data.service.matched);
                }

                $(document).on('click', '.pm-level-btn', function() {
                    var level = $(this).data('level');
                    $('.pm-level-btn').removeClass('active');
                    $(this).addClass('active');

                    $.ajax({
                        url: pmEndpoint,
                        method: 'GET',
                        data: { level: level },
                        success: function(data) {
                            renderPmTemplate(data);
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal memuat template',
                                text: 'Coba pilih ulang level PM.'
                            });
                        }
                    });
                });

                $(document).on('click', '#btn-generate-quotation', function() {
                    if (!pmCurrentData) return;

                    var unit = pmCurrentData.unit;
                    var unitLabel = (unit.brand || '') + ' ' + (unit.model || unit.sku || '');
                    var items = [];

                    items.push({
                        type: 'header',
                        label: 'Preventive Maintenance ' + pmCurrentData.level + ' — ' + unitLabel.trim()
                    });

                    $.each(pmCurrentData.parts, function(i, part) {
                        items.push({
                            type: 'custom',
                            label: part.pn || 'Spare Part',
                            description: part.description || '',
                            qty: part.qty,
                            info_qty: part.info_qty || 'Pcs',
                            price: part.price,
                            disc: 0
                        });
                    });

                    if (pmCurrentData.service.matched && pmCurrentData.service.amount !== null) {
                        items.push({
                            type: 'custom',
                            label: pmCurrentData.service.label,
                            description: 'Jasa preventive maintenance untuk ' + unitLabel.trim(),
                            qty: 1,
                            info_qty: 'Lot',
                            price: pmCurrentData.service.amount,
                            disc: 0
                        });
                    }

                    sessionStorage.setItem('pm_template_items', JSON.stringify(items));
                    window.location.href = pmQuotationCreateUrl;
                });
            }
        });
    </script>
@endpush

