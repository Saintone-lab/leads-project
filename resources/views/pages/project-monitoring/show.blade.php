@extends('layouts.sales.app')
@section('title', 'Analisis Profitabilitas Proyek')
@section('no-container') @endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">
                <span class="text-muted fw-normal">Project Monitoring /</span> Detail Profitabilitas
            </h4>
            <a href="{{ route('project-monitoring.index') }}" class="btn btn-outline-secondary waves-effect">
                <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>

        @include('components.dashboard.tab-navigation')

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $categories = [
                'Service PM' => [
                    1 => 'Pengecekan Spare Part',
                    2 => 'Penjadwalan',
                    3 => 'In Progress',
                    4 => 'Selesai'
                ],
                'Overhaul' => [
                    1 => 'Pengecekan Spare Part',
                    2 => 'Penjadwalan',
                    3 => 'In Progress',
                    4 => 'Selesai'
                ],
                'Rental' => [
                    1 => 'Pengecekan Unit',
                    2 => 'Jadwal Pickup Unit',
                    3 => 'In Progress / Commissioning',
                    4 => 'Pickup Kembali Unit',
                    5 => 'Selesai'
                ],
                'Unit' => [
                    1 => 'Pengecekan Stok Unit',
                    2 => 'Jadwal Pickup',
                    3 => 'Jadwal Commissioning',
                    4 => 'Selesai'
                ],
                'Piping' => [
                    1 => 'Pengecekan Material',
                    2 => 'Kirim Material',
                    3 => 'Progress',
                    4 => 'Commissioning',
                    5 => 'Selesai'
                ]
            ];

            $currentCategory = $project->project_category ?? 'Service PM';
            $steps = $categories[$currentCategory] ?? $categories['Service PM'];
            $totalSteps = count($steps);

            if ($project->status == 6) {
                $currentStep = $totalSteps;
            } elseif ($project->status == 0) {
                $currentStep = 1;
            } else {
                $currentStep = $project->project_status_step ?? 1;
            }
        @endphp

        <!-- Progress Stepper Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <h5 class="mb-1 fw-bold"><i class="mdi mdi-ray-start-arrow text-primary me-1"></i> Project Progress Tracker</h5>
                        <p class="text-muted small mb-0">Klik pada salah satu langkah di bawah untuk memperbarui progress proyek secara instan (khusus Sales/Admin/Koordinator).</p>
                    </div>
                    <div>
                        <!-- Category Selector -->
                        <form action="{{ route('project-monitoring.update-status-step', $project->id) }}" method="POST" id="form-update-category" class="d-flex align-items-center gap-2">
                            @csrf
                            <input type="hidden" name="project_status_step" value="1">
                            <label class="fw-semibold text-muted text-nowrap small mb-0">Kategori Proyek:</label>
                            <select name="project_category" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width: 155px;"
                                {{ !in_array(Auth::user()->role, ['Admin', 'Sales', 'Coordinator']) ? 'disabled' : '' }}>
                                <option value="Service PM" {{ $currentCategory == 'Service PM' ? 'selected' : '' }}>Service PM</option>
                                <option value="Overhaul" {{ $currentCategory == 'Overhaul' ? 'selected' : '' }}>Overhaul / Rebearing</option>
                                <option value="Rental" {{ $currentCategory == 'Rental' ? 'selected' : '' }}>Rental</option>
                                <option value="Unit" {{ $currentCategory == 'Unit' ? 'selected' : '' }}>Unit Only</option>
                                <option value="Piping" {{ $currentCategory == 'Piping' ? 'selected' : '' }}>Piping</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Visual Stepper Timeline -->
                <div class="position-relative py-3 px-2">
                    @php
                        $totalSteps = count($steps);
                        $progressPercent = $totalSteps > 1 ? (($currentStep - 1) / ($totalSteps - 1)) * 100 : 0;
                    @endphp
                    <div class="progress position-absolute top-50 start-0 translate-middle-y w-100" style="height: 4px; z-index: 1;">
                        <div class="progress-bar bg-primary animate-bar" role="progressbar" style="width: {{ $progressPercent }}%" aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="d-flex justify-content-between position-relative" style="z-index: 2;">
                        @foreach($steps as $stepNum => $stepLabel)
                            <div class="d-flex flex-column align-items-center text-center" style="width: {{ 100 / $totalSteps }}%;">
                                <form action="{{ route('project-monitoring.update-status-step', $project->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="project_category" value="{{ $currentCategory }}">
                                    <input type="hidden" name="project_status_step" value="{{ $stepNum }}">
                                    
                                    <button type="submit" class="btn btn-icon rounded-pill p-0 d-flex align-items-center justify-content-center border border-3
                                        {{ $stepNum < $currentStep ? 'btn-success border-success text-white' : ($stepNum == $currentStep ? 'btn-primary border-primary shadow text-white fw-bold' : 'btn-light border-secondary text-muted') }}"
                                        style="width: 42px; height: 42px;"
                                        {{ !in_array(Auth::user()->role, ['Admin', 'Sales', 'Coordinator']) ? 'disabled' : '' }}>
                                        @if($stepNum < $currentStep)
                                            <i class="mdi mdi-check mdi-20px"></i>
                                        @else
                                            <span>{{ $stepNum }}</span>
                                        @endif
                                    </button>
                                </form>
                                <span class="fw-semibold small mt-2 d-block text-wrap px-1 {{ $stepNum == $currentStep ? 'text-primary fw-bold' : 'text-muted' }}" style="font-size: 11px; max-width: 130px; line-height: 1.2;">
                                    {{ $stepLabel }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Project Information & KPI Column -->
            <div class="col-12 col-lg-4 mb-4">
                <!-- Info Card -->
                <div class="card mb-4">
                    <div class="card-header pb-2">
                        <h5 class="card-title mb-0">Informasi Proyek</h5>
                    </div>
                    <div class="card-body">
                        <small class="text-muted text-uppercase">Detail</small>
                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-briefcase-outline me-2"></i>
                                <span class="fw-semibold mx-2">No Project:</span>
                                <span>{{ $project->no_pending }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-domain me-2"></i>
                                <span class="fw-semibold mx-2">Customer:</span>
                                <span>{{ $project->company }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-file-document-outline me-2"></i>
                                <span class="fw-semibold mx-2">No Penawaran:</span>
                                <span>{{ $project->no_quote }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-account-outline me-2"></i>
                                <span class="fw-semibold mx-2">Sales PIC:</span>
                                <span>{{ $project->sales_name }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-account-circle-outline me-2"></i>
                                <span class="fw-semibold mx-2">Customer PIC:</span>
                                <span>{{ $project->pic_name }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-calendar-blank-outline me-2"></i>
                                <span class="fw-semibold mx-2">Tanggal:</span>
                                <span>{{ \Carbon\Carbon::parse($project->date)->format('d-M-Y') }}</span>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-center">
                            @if ($project->status == 6)
                                <span class="badge bg-success w-100 py-2 fs-6">Proyek Selesai (Done)</span>
                            @else
                                <span class="badge bg-primary w-100 py-2 fs-6">Proyek Berjalan (In Progress)</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Financial Health Card -->
                <div class="card bg-label-primary">
                    <div class="card-body">
                        <h5 class="card-title text-primary mb-3">Kesehatan Keuangan</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Pendapatan:</span>
                            <span class="fw-semibold text-dark">Rp {{ number_format($project->revenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Biaya (COGS):</span>
                            <span class="fw-semibold text-danger">Rp {{ number_format($totalCost, 0, ',', '.') }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Net Profit:</span>
                            <span class="fw-bold {{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($profit, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Progress Bar of Cost Ratio -->
                        @php
                            $costRatio = $project->revenue > 0 ? ($totalCost / $project->revenue) * 100 : 0;
                            $profitRatio = $project->revenue > 0 ? ($profit / $project->revenue) * 100 : 0;
                        @endphp
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted">Rasio Biaya vs Margin</small>
                                <small class="fw-semibold">{{ number_format($profitRatio, 1) }}% Margin</small>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $costRatio }}%" aria-valuenow="{{ $costRatio }}" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $profitRatio }}%" aria-valuenow="{{ $profitRatio }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Section Column -->
            <div class="col-12 col-lg-8 mb-4">
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-revenue" aria-controls="navs-revenue" aria-selected="true">
                                <i class="mdi mdi-bank-transfer-in me-1"></i> Pendapatan (Quotation)
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-purchases" aria-controls="navs-purchases" aria-selected="false">
                                <i class="mdi mdi-cart-outline me-1"></i> Pembelian Barang (PR)
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-expenses" aria-controls="navs-expenses" aria-selected="false">
                                <i class="mdi mdi-cash-multiple me-1"></i> Biaya Operasional
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Revenue Details Tab -->
                        <div class="tab-pane fade show active" id="navs-revenue" role="tabpanel">
                            <h6 class="fw-semibold mb-3">Item Penawaran yang Disetujui</h6>
                            <div class="table-responsive text-nowrap border rounded">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr class="table-primary">
                                            <th>Nama Barang / Jasa</th>
                                            <th class="text-center">Qty</th>
                                            <th>Satuan</th>
                                            <th class="text-end">Harga Satuan</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($quoteItems as $item)
                                            <tr>
                                                <td class="text-wrap">{{ $item->item_name }}</td>
                                                <td class="text-center">{{ $item->qty }}</td>
                                                <td>{{ $item->unit ?? 'Unit' }}</td>
                                                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                <td class="text-end">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada rincian penawaran.</td>
                                            </tr>
                                        @endforelse
                                        <tr class="table-light">
                                            <td colspan="4" class="text-end fw-bold">Nett Pendapatan:</td>
                                            <td class="text-end fw-bold text-success">Rp {{ number_format($project->revenue, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Purchase requests Tab -->
                        <div class="tab-pane fade" id="navs-purchases" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold m-0">Log Pembelian Barang & Spare Part</h6>
                                <span class="badge bg-label-danger">Total Biaya PR: Rp {{ number_format($materialCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="table-responsive text-nowrap border rounded">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr class="table-warning">
                                            <th>No PR</th>
                                            <th>Commodity / Part</th>
                                            <th class="text-center">Qty</th>
                                            <th>Status</th>
                                            <th class="text-end">Harga Beli</th>
                                            <th class="text-end">Total Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($purchases as $pr)
                                            <tr>
                                                <td>
                                                    <span class="text-primary fw-semibold">{{ $pr->no_pr }}</span>
                                                </td>
                                                <td class="text-wrap">
                                                    @if($pr->equivalent)
                                                        {{ $pr->equivalent->product->commodity ?? '-' }}
                                                        ({{ $pr->equivalent->pn ?? '' }})
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $pr->qty }}</td>
                                                <td>
                                                    @if($pr->status == '3')
                                                        <span class="badge bg-success">Received</span>
                                                    @elseif($pr->status == '2')
                                                        <span class="badge bg-info">Ordered</span>
                                                    @elseif($pr->status == '1')
                                                        <span class="badge bg-warning">Approved</span>
                                                    @else
                                                        <span class="badge bg-secondary">Pending</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if($pr->price)
                                                        Rp {{ number_format($pr->price, 0, ',', '.') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    @if($pr->amount)
                                                        Rp {{ number_format($pr->amount, 0, ',', '.') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Belum ada pengajuan pembelian barang (PR) untuk proyek ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Operational Expenses Tab -->
                        <div class="tab-pane fade" id="navs-expenses" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold m-0">Pengeluaran Operasional & Lapangan</h6>
                                <button type="button" class="btn btn-primary btn-sm waves-effect" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                                    <i class="mdi mdi-plus me-1"></i> Catat Biaya Baru
                                </button>
                            </div>

                            <!-- Summary of Expenses -->
                            <div class="row mb-3">
                                <div class="col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light">
                                        <small class="text-muted d-block">Biaya Operasional Lapangan</small>
                                        <span class="h5 fw-bold text-danger">Rp {{ number_format($generalCost, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="p-3 border rounded bg-light">
                                        <small class="text-muted d-block">Biaya Pengiriman (Ongkir Resi)</small>
                                        <span class="h5 fw-bold text-danger">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive text-nowrap border rounded mb-3">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr class="table-info">
                                            <th>Tanggal</th>
                                            <th>Nama Pengeluaran</th>
                                            <th>Kategori</th>
                                            <th>Oleh</th>
                                            <th class="text-end">Nominal</th>
                                            <th class="text-center">Nota</th>
                                            @if(in_array(Auth::user()->role, ['Admin', 'Accounting', 'Finance']))
                                                <th class="text-center">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($expenses as $exp)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($exp->date)->format('d-M-Y') }}</td>
                                                <td class="text-wrap" style="max-width: 150px;">{{ $exp->name }}</td>
                                                <td>
                                                    <span class="badge bg-label-info">{{ $exp->category }}</span>
                                                </td>
                                                <td>{{ $exp->user->name }}</td>
                                                <td class="text-end">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    @if ($exp->receipt)
                                                        <a href="{{ asset($exp->receipt) }}" target="_blank" class="text-primary fs-4">
                                                            <i class="mdi mdi-file-image-outline"></i>
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                @if(in_array(Auth::user()->role, ['Admin', 'Accounting', 'Finance']))
                                                    <td class="text-center">
                                                        <form action="{{ route('project-monitoring.destroy-expense', $exp->id) }}" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus biaya ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-outline-danger p-1">
                                                                <i class="mdi mdi-delete-outline"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Belum ada pengeluaran operasional yang dicatat.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Standalone Cek Barang (Logistik) Card -->
    <div class="card mb-4 shadow-sm border">
        <div class="card-header bg-light py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="m-0 fw-bold"><i class="mdi mdi-checkbox-marked-circle-outline text-primary me-1"></i> Pengecekan Spare Part & Unit Proyek (Logistik)</h5>
            @if (in_array(Auth::user()->role, ['Admin', 'Logistic', 'Coordinator']))
                <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#replacementEdit">
                    <i class="mdi mdi-square-edit-outline me-1"></i> Update Status & Stock
                </button>
            @else
                <button type="button" class="btn btn-secondary btn-sm" disabled>
                    <i class="mdi mdi-lock-outline me-1"></i> Update (Logistic/Admin Only)
                </button>
            @endif
        </div>
        <div class="card-body pt-3">
            <div class="table-responsive text-nowrap border rounded">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr class="table-info">
                            <th style="width: 5%">No</th>
                            <th style="width: 25%">Item</th>
                            <th style="width: 20%">Equivalent / Replacement</th>
                            <th style="width: 8%" class="text-center">Qty</th>
                            <th style="width: 12%">Status</th>
                            <th style="width: 8%" class="text-center">BDG</th>
                            <th style="width: 8%" class="text-center">BKS</th>
                            <th style="width: 14%">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $abjad = 64; @endphp
                        @foreach ($subQuote as $subJudul)
                            @php
                                $no = 1;
                                $abjad++;
                            @endphp
                            <tr class="table-light border-top">
                                <td class="fw-bold text-center">{{ chr($abjad) }}</td>
                                <td colspan="7" class="fw-bold">{{ $subJudul->subtitle }}</td>
                            </tr>
                            @foreach ($subJudul->detail as $product)
                                @php
                                    switch (@$product->pending[0]->status) {
                                        case 1: $status = 'On Check'; $badge = 'bg-label-warning'; break;
                                        case 2: $status = 'Ready Stock'; $badge = 'bg-label-info'; break;
                                        case 3: $status = 'Kurang'; $badge = 'bg-label-danger'; break;
                                        case 4: $status = 'Pre-Order'; $badge = 'bg-label-primary'; break;
                                        case 5: $status = 'Delivery Process'; $badge = 'bg-label-linkedin'; break;
                                        case 6: $status = 'Done'; $badge = 'bg-label-success'; break;
                                        case 7: $status = 'Cancel'; $badge = 'bg-label-danger'; break;
                                        default: $status = 'Belum Di Cek'; $badge = 'bg-label-secondary'; break;
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $no }}</td>
                                    <td class="text-wrap" style="max-width: 200px;">{{ $product->product }}</td>
                                    <td>
                                        @if ($product->pending[0]->id_equivalent && $product->pending[0]->equivalent)
                                            <span class="fw-semibold text-primary">
                                                {{ $product->pending[0]->equivalent->brand }} {{ $product->pending[0]->equivalent->pn }}
                                            </span>
                                            <small class="text-muted d-block" style="font-size: 11px;">
                                                {{ $product->pending[0]->equivalent->product?->commodity }} ({{ $product->pending[0]->equivalent->product?->go == 'Replacement' ? 'R' : 'G' }})
                                            </small>
                                        @else
                                            <span class="text-muted small">Belum dipetakan ke stok</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $product->qty }} {{ $product->info_qty }}</td>
                                    <td>
                                        <span class="badge {{ $project->status == 6 ? 'bg-label-success' : $badge }}">
                                            {{ $project->status == 6 ? 'Done' : $status }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $product->pending[0]->bdg ?? 0 }}</td>
                                    <td class="text-center">{{ $product->pending[0]->bks ?? 0 }}</td>
                                    <td class="text-wrap" style="max-width: 150px;">{{ $product->pending[0]->note ?? '-' }}</td>
                                </tr>
                                @php $no++; @endphp
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Expense Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Catat Pengeluaran Proyek Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('project-monitoring.store-expense', $project->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="name" class="form-label">Deskripsi Pengeluaran</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: Tiket Kereta Teknisi / Bensin Mobil" required />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6 mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <select id="category" name="category" class="form-select" required>
                                    <option value="" disabled selected>Pilih Kategori...</option>
                                    <option value="Transport">Transport</option>
                                    <option value="Akomodasi">Akomodasi (Hotel/Penginapan)</option>
                                    <option value="Konsumsi">Konsumsi</option>
                                    <option value="Material">Material Lapangan</option>
                                    <option value="Alat">Sewa Alat</option>
                                    <option value="Lain-lain">Lain-lain</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="date" class="form-label">Tanggal Pengeluaran</label>
                                <input type="date" id="date" name="date" class="form-control" value="{{ now()->format('Y-m-d') }}" required />
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6 mb-3">
                                <label for="amount" class="form-label">Nominal Biaya (Rp)</label>
                                <input type="number" id="amount" name="amount" class="form-control" min="0" placeholder="Contoh: 150000" required />
                            </div>
                            <div class="col-6 mb-3">
                                <label for="receipt" class="form-label">Upload Nota / Receipt (Gambar/PDF)</label>
                                <input type="file" id="receipt" name="receipt" class="form-control" accept="image/*,application/pdf" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Biaya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Replacement Stock Checking Modal -->
    @include('components.modal.pending.project', ['pending' => $project])

    @push('after-style')
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
    @endpush

    @push('after-script')
        <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
        <script>
            $(document).ready(function() {
                if ($('#replacementEdit .select2').length) {
                    $('#replacementEdit .select2').each(function() {
                        $(this).select2({
                            dropdownParent: $('#replacementEdit')
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
