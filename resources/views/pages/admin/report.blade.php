@extends('layouts.sales.app')
@section('title', 'Overview Sales')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                <div class="title">
                    @php
                        $fullPercent = $totalTarget > 0 ? round(($poTotal / ($totalTarget * 6)) * 100, 1) : 0;
                    @endphp
                    <h2 class="text-black">OVERVIEW SEMESTER {{ $report->semester }} / {{ $report->year }}
                        ({{ $fullPercent }}%)</h2>
                </div>
                <div class="text-end">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle waves-effect"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Choose Semester
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ($semester as $semesters)
                                <li><a class="dropdown-item waves-effect"
                                        href="{{ route('report.semester', $semesters->id) }}">Semester
                                        {{ $semesters->semester }} {{ $semesters->year }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-success rounded">
                                        <i class="mdi mdi-cart-plus mdi-24px"></i>
                                    </div>
                                </div>
                                {{-- <div class="d-flex align-items-center">
                                    <p class="mb-0 text-success me-1">+22%</p>
                                    <i class="mdi mdi-chevron-up text-success"></i>
                                </div> --}}
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h5 class="mb-2">Rp {{ number_format($poTotal, 0, ',', '.') }}</h5>
                                <p class="text-muted">{{ $poCount }}</p>
                                <p>Purchase Order</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-cart mdi-24px"></i>
                                    </div>
                                </div>
                                {{-- <div class="d-flex align-items-center">
                                    <p class="mb-0 text-success me-1">+22%</p>
                                    <i class="mdi mdi-chevron-up text-success"></i>
                                </div> --}}
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h5 class="mb-2">Rp {{ number_format($quoteOnTotal, 0, ',', '.') }}</h5>
                                <p class="text-muted">{{ $quoteOnCount }}</p>
                                <p>Quotation</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-danger rounded">
                                        <i class="mdi mdi-cart-minus mdi-24px"></i>
                                    </div>
                                </div>
                                {{-- <div class="d-flex align-items-center">
                                    <p class="mb-0 text-success me-1">+22%</p>
                                    <i class="mdi mdi-chevron-up text-success"></i>
                                </div> --}}
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h5 class="mb-2">Rp {{ number_format($lossTotal, 0, ',', '.') }}</h5>
                                <p class="text-muted">{{ $lossCount }}</p>
                                <p>Loss</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-secondary rounded">
                                        <i class="mdi mdi-cart-outline mdi-24px"></i>
                                    </div>
                                </div>
                                {{-- <div class="d-flex align-items-center">
                                    <p class="mb-0 text-success me-1">+22%</p>
                                    <i class="mdi mdi-chevron-up text-success"></i>
                                </div> --}}
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h5 class="mb-2">Rp {{ number_format($quoteTotal, 0, ',', '.') }}</h5>
                                <p class="text-muted">{{ $quoteCount }}</p>
                                <p>Quotation Active</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @php
                    $bulanMap = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ];
                @endphp
                {{-- @foreach ($sales as $user) --}}
                @foreach ($data as $sale)
                    <div class="col-6 col-lg-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-2 p-0">
                                        <img src="{{ url('') . '/' . $sale['image'] }}" alt="" srcset=""
                                            class="rounded" style="width : 100%; height:100%;">
                                    </div>
                                    <div class="col-10">
                                        <h4 class="badge bg-label-secondary w-100 text-center fs-5">{{$sale['name']}}</h4>
                                        <h5 class="text-center">Rp {{ number_format($sale['total'], 0, ',', '.') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="container">
                                    <div class="row">
                                        @foreach ($sale['jumlah'] as $item)
                                            <div class="col-4 mb-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0">{{ $bulanMap[$item['bulan']] }}</h6>
                                                </div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <p class="fw-semibold text-heading text-end p-0 m-0">Rp
                                                    {{ number_format($item['total'], 0, ',', '.') }}</p>
                                            </div>
                                            <div class="col-2 mb-2">
                                                @php
                                                    $persenanSales =
                                                        $sale['target'] > 0
                                                            ? round(($item['total'] / $sale['target']) * 100, 2)
                                                            : 0;
                                                    if ($persenanSales >= 100) {
                                                        $label = 'success';
                                                    } elseif ($persenanSales >= 90) {
                                                        $label = 'warning';
                                                    } else {
                                                        $label = 'danger';
                                                    }
                                                @endphp
                                                <div class="badge bg-label-{{ $label }} rounded-pill">
                                                    {{ $persenanSales }}%</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('overview-sales.semester', [$report->id, $sale['id']]) }}"
                                    class="btn btn-primary waves-control float-end">Details</a>
                            </div>

                            {{-- <div class="row">
                                    <div class="col-4">
                                        <img src="{{ url('') . '/' . $user->image }}" alt="" srcset=""
                                            class="rounded" style="width : 100%; height:100%;">
                                    </div>
                                    <div class="col-8 m-auto">
                                        @php
                                            $lastDetail = $user->detail->last();
                                        @endphp
                                        <h3>{{ $user->name }}</h3>
                                    </div>
                                </div> --}}
                        </div>
                        {{-- <a href="#" class="text-decoration-none text-black" data-bs-toggle="modal"
                                data-bs-target="#detailReport{{ $user->id }}">
                            </a> --}}
                    </div>
                @endforeach
                {{-- @endforeach --}}
                <div class="col-6 col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-2 p-0">
                                    <img src="{{ url('') . '/' . $support->image }}" alt="" srcset=""
                                        class="rounded" style="width : 100%; height:100%;">
                                </div>
                                <div class="col-10">
                                    <h4 class="badge bg-label-secondary w-100 text-center fs-5">{{$support->name}}</h4>
                                    <h5 class="text-center">Rp {{ number_format($poTotalSupport, 0, ',', '.') }}
                                    </h5>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    @foreach ($dataSupport as $item)
                                        <div class="col-4 mb-2">
                                            <div class="me-2">
                                                <h6 class="mb-0">{{ $bulanMap[$item->bulan] }}</h6>
                                            </div>
                                        </div>
                                        <div class="col-8 mb-2">
                                            <p class="fw-semibold text-heading text-end p-0 m-0">Rp
                                                {{ number_format($item->total, 0, ',', '.') }}</p>
                                        </div>
                                        {{-- <div class="col-2 mb-2">
                                            @php
                                                $persenanSales =
                                                    $sale['target'] > 0
                                                        ? round(($item['total'] / $sale['target']) * 100, 2)
                                                        : 0;
                                                if ($persenanSales >= 100) {
                                                    $label = 'success';
                                                } elseif ($persenanSales >= 90) {
                                                    $label = 'warning';
                                                } else {
                                                    $label = 'danger';
                                                }
                                            @endphp
                                            <div class="badge bg-label-{{ $label }} rounded-pill">
                                                {{ $persenanSales }}%</div>
                                        </div> --}}
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('overview-sales.semester', [$report->id, $support->id]) }}"
                                class="btn btn-primary waves-control float-end">Details</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($data as $sale)
        @include('components.modal.overview.report')
    @endforeach
@endsection
