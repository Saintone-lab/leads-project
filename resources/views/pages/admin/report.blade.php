@extends('layouts.sales.app')
@section('title', 'Overview Sales')
@section('content')
    @php
        $fullPercent  = $totalTarget > 0 ? round(($poTotal / ($totalTarget * 6)) * 100, 1) : 0;
        $pctColor     = $fullPercent >= 100 ? 'success' : ($fullPercent >= 80 ? 'warning' : 'danger');
        $semesterLabel = $report->semester == 1 ? 'Januari – Juni' : 'Juli – Desember';
        $s1Report     = $semester->where('year', $report->year)->where('semester', 1)->first();
        $s2Report     = $semester->where('year', $report->year)->where('semester', 2)->first();
    @endphp
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">

        {{-- Kiri: judul + meta --}}
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-label-primary fs-6 px-3 py-2">
                    <i class="mdi mdi-chart-areaspline me-1"></i> Semester {{ $report->semester }}
                </span>
                <span class="text-muted fw-semibold">{{ $report->year }}</span>
                <span class="text-muted">•</span>
                <small class="text-muted">{{ $semesterLabel }}</small>
            </div>
            <h4 class="fw-bold mb-1 text-heading">Overview Report Penjualan</h4>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-{{ $pctColor }} rounded-pill px-3">
                    {{ $fullPercent }}% pencapaian target
                </span>
                <small class="text-muted">Rp {{ number_format($poTotal, 0, ',', '.') }} dari Rp {{ number_format($totalTarget * 6, 0, ',', '.') }}</small>
            </div>
        </div>

        {{-- Kanan: toggle S1/S2 + pilih tahun --}}
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="btn-group" role="group">
                @if ($s1Report)
                    <a href="{{ route('report.semester', $s1Report->id) }}"
                       class="btn btn-sm waves-effect {{ $report->semester == 1 ? 'btn-primary' : 'btn-outline-primary' }}">
                        Semester 1
                    </a>
                @endif
                @if ($s2Report)
                    <a href="{{ route('report.semester', $s2Report->id) }}"
                       class="btn btn-sm waves-effect {{ $report->semester == 2 ? 'btn-primary' : 'btn-outline-primary' }}">
                        Semester 2
                    </a>
                @endif
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle waves-effect"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-calendar me-1"></i> {{ $report->year }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @foreach ($semester->pluck('year')->unique()->sortDesc() as $yr)
                        <li>
                            <a class="dropdown-item waves-effect {{ $yr == $report->year ? 'active' : '' }}"
                                href="{{ route('report.year', $yr) }}">{{ $yr }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
    @php
        $winRate        = $quoteOnCount > 0 ? round(($poCount   / $quoteOnCount) * 100, 1) : 0;
        $lossRate       = $quoteOnCount > 0 ? round(($lossCount / $quoteOnCount) * 100, 1) : 0;
        $mktContrib     = $poTotal > 0 ? round(($poTotalSupport / $poTotal) * 100, 1) : 0;
        $winColor       = $winRate  >= 50 ? 'success' : ($winRate  >= 30 ? 'warning' : 'danger');
        $lossColor      = $lossRate <= 20 ? 'success' : ($lossRate <= 40 ? 'warning' : 'danger');
        $mktColor       = $mktContrib >= 30 ? 'success' : ($mktContrib >= 15 ? 'warning' : 'secondary');
        $cards = [
            [
                'label'  => 'Purchase Order',
                'icon'   => 'mdi-cart-plus',
                'color'  => 'success',
                'amount' => 'Rp ' . number_format($poTotal, 0, ',', '.'),
                'sub'    => $poCount . ' transaksi',
            ],
            [
                'label'  => 'Total Quotation',
                'icon'   => 'mdi-cart',
                'color'  => 'primary',
                'amount' => 'Rp ' . number_format($quoteOnTotal, 0, ',', '.'),
                'sub'    => $quoteOnCount . ' transaksi',
            ],
            [
                'label'  => 'Quotation Aktif',
                'icon'   => 'mdi-cart-outline',
                'color'  => 'info',
                'amount' => 'Rp ' . number_format($quoteTotal, 0, ',', '.'),
                'sub'    => $quoteCount . ' transaksi',
            ],
            [
                'label'  => 'Loss',
                'icon'   => 'mdi-cart-minus',
                'color'  => 'danger',
                'amount' => 'Rp ' . number_format($lossTotal, 0, ',', '.'),
                'sub'    => $lossCount . ' transaksi',
            ],
            [
                'label'  => 'Convertion Rate',
                'icon'   => 'mdi-trophy-outline',
                'color'  => $winColor,
                'amount' => $winRate . '%',
                'sub'    => $poCount . ' PO dari ' . $quoteOnCount . ' quotation',
            ],
            [
                'label'  => 'Loss Rate',
                'icon'   => 'mdi-trending-down',
                'color'  => $lossColor,
                'amount' => $lossRate . '%',
                'sub'    => $lossCount . ' loss dari ' . $quoteOnCount . ' quotation',
            ],
            [
                'label'  => 'Marketing Contribution',
                'icon'   => 'mdi-handshake-outline',
                'color'  => $mktColor,
                'amount' => 'Rp ' . number_format($poTotalSupport, 0, ',', '.'),
                'sub'    => $mktContrib . '% dari total PO semester',
            ],
            [
                'label'  => 'Marketing Quotation',
                'icon'   => 'mdi-file-document-outline',
                'color'  => 'secondary',
                'amount' => 'Rp ' . number_format($quoteTotalSupport, 0, ',', '.'),
                'sub'    => $quoteCountSupport . ' quotation',
            ],
        ];
    @endphp
    <div class="row mb-4 g-3">
        @foreach ($cards as $card)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-{{ $card['color'] }} rounded">
                                    <i class="mdi {{ $card['icon'] }} mdi-24px"></i>
                                </div>
                            </div>
                            <div class="text-end">
                                <p class="mb-0 fw-semibold text-heading" style="font-size:0.82rem">{{ $card['label'] }}</p>
                                <small class="text-muted">{{ $card['sub'] }}</small>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-0 text-{{ $card['color'] }}">{{ $card['amount'] }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{-- ===== GRAFIK PENJUALAN PER BULAN ===== --}}
    @php
        $bulanLabelShort = [
            1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',
            7=>'Jul',8=>'Ags',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des',
        ];
        $startMonthChart = $report->semester == 1 ? 1 : 7;
        $ecommerceIdsChart = [16, 23];

        $chartLabels  = [];
        $chartSeries  = [];
        $combinedTotal = array_fill(0, 6, 0);

        for ($i = 0; $i < 6; $i++) {
            $chartLabels[] = $bulanLabelShort[$startMonthChart + $i];
        }

        // Regular sales
        foreach (array_filter($data, fn($s) => !in_array($s['id'], $ecommerceIdsChart)) as $s) {
            $monthlyData = [];
            for ($i = 0; $i < 6; $i++) {
                $val = $s['jumlah'][$i]['total'] ?? 0;
                $monthlyData[] = $val;
                $combinedTotal[$i] += $val;
            }
            $chartSeries[] = ['name' => $s['name'], 'data' => $monthlyData];
        }

        // E-Commerce team digabung
        $ecoMembers = array_filter($data, fn($s) => in_array($s['id'], $ecommerceIdsChart));
        if (count($ecoMembers) > 0) {
            $ecoData = array_fill(0, 6, 0);
            foreach ($ecoMembers as $m) {
                for ($i = 0; $i < 6; $i++) {
                    $val = $m['jumlah'][$i]['total'] ?? 0;
                    $ecoData[$i] += $val;
                    $combinedTotal[$i] += $val;
                }
            }
            $chartSeries[] = ['name' => 'E-Commerce', 'data' => array_values($ecoData)];
        }

        $chartTargetLine = array_fill(0, 6, $totalTarget);
    @endphp
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="mb-0">Grafik Penjualan Semester {{ $report->semester }} — {{ $report->year }}</h5>
                <small class="text-muted">
                    {{ $report->semester == 1 ? 'Januari – Juni' : 'Juli – Desember' }} {{ $report->year }} &bull; Semua Sales
                </small>
            </div>
            <div class="text-end">
                @php
                    $totalSemester = array_sum($combinedTotal);
                    $pctSemester   = $totalTarget > 0 ? round(($totalSemester / ($totalTarget * 6)) * 100, 1) : 0;
                    $badgeSem      = $pctSemester >= 100 ? 'success' : ($pctSemester >= 80 ? 'warning' : 'danger');
                @endphp
                <span class="badge bg-label-{{ $badgeSem }} fs-6">
                    Rp {{ number_format($totalSemester, 0, ',', '.') }} &nbsp;|&nbsp; {{ $pctSemester }}%
                </span>
            </div>
        </div>
        <div class="card-body">
            <div id="chartPenjualanSemester"></div>
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
        @php
            $ecommerceIds = [16, 23];
            $ecommerceMembers = array_values(array_filter($data, fn($s) => in_array($s['id'], $ecommerceIds)));
            $regularSales = array_values(array_filter($data, fn($s) => !in_array($s['id'], $ecommerceIds)));

            // Gabungkan data bulanan Tim E-Commerce
            $teamTotal = array_sum(array_column($ecommerceMembers, 'total'));
            $teamTarget = array_sum(array_column($ecommerceMembers, 'target'));
            $teamJumlah = [];
            foreach ($ecommerceMembers as $member) {
                foreach ($member['jumlah'] as $j) {
                    $bulan = $j['bulan'];
                    if (!isset($teamJumlah[$bulan])) $teamJumlah[$bulan] = 0;
                    $teamJumlah[$bulan] += $j['total'];
                }
            }
            ksort($teamJumlah);
            $teamJumlahArr = [];
            foreach ($teamJumlah as $b => $t) {
                $teamJumlahArr[] = ['bulan' => $b, 'total' => $t];
            }
        @endphp
        {{-- @foreach ($sales as $user) --}}
        @foreach ($regularSales as $sale)
            <div class="col-6 col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-3 p-0">
                                <img src="{{ url('') . '/' . $sale['image'] }}" alt="" srcset=""
                                    class="rounded" style="width : 100%; height:100%;">
                            </div>
                            <div class="col-9">
                                <h4 class="badge bg-label-primary w-100 text-center fs-4 fw-semibold">{{ $sale['name'] }}</h4>
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
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('overview-sales.semester', [$report->id, $sale['id']]) }}"
                            class="btn btn-primary d-grid w-100">Details</a>
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

        {{-- Team E-Commerce (ID 16 & 23 digabung) --}}
        @if (count($ecommerceMembers) > 0)
            <div class="col-6 col-lg-4 mb-3">
                <div class="card h-100 border border-warning">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-3 p-0">
                                @php $mainMember = collect($ecommerceMembers)->firstWhere('id', 16); @endphp
                                <img src="{{ url('') . '/' . $mainMember['image'] }}" alt=""
                                    class="rounded" style="width:100%; height:100%;">
                            </div>
                            <div class="col-9">
                                <h4 class="badge bg-label-warning w-100 text-center fs-4 fw-semibold">Team E-Commerce</h4>
                                <h5 class="text-center">Rp {{ number_format($teamTotal, 0, ',', '.') }}</h5>
                                <div class="d-flex flex-wrap gap-1 justify-content-center mt-1">
                                    @foreach ($ecommerceMembers as $member)
                                        <span class="badge bg-label-secondary small">{{ $member['name'] }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row">
                                @foreach ($teamJumlahArr as $item)
                                    <div class="col-4 mb-2">
                                        <h6 class="mb-0">{{ $bulanMap[$item['bulan']] }}</h6>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <p class="fw-semibold text-heading text-end p-0 m-0">Rp
                                            {{ number_format($item['total'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="col-2 mb-2">
                                        @php
                                            $pct = $teamTarget > 0 ? round(($item['total'] / $teamTarget) * 100, 2) : 0;
                                            $lbl = $pct >= 100 ? 'success' : ($pct >= 90 ? 'warning' : 'danger');
                                        @endphp
                                        <div class="badge bg-label-{{ $lbl }} rounded-pill">{{ $pct }}%</div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-3 p-0">
                            <img src="{{ url('') . '/' . $support->image }}" alt="" srcset="" class="rounded"
                                style="width : 100%; height:100%;">
                        </div>
                        <div class="col-9">
                            <h4 class="badge bg-label-success w-100 text-center fs-4 fw-semibold">{{ $support->name }}</h4>
                            <h5 class="text-center text fw-semibold">Rp {{ number_format($poTotalSupport, 0, ',', '.') }}
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
                                <hr>
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

                                        </div>  --}}
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('overview-sales.semester', [$report->id, $support->id]) }}"
                        class="btn btn-success d-grid w-100">Detail</a>
                </div>
            </div>
        </div>
    </div>
    @foreach ($data as $sale)
        @include('components.modal.overview.report')
    @endforeach
@endsection

@push('before-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/apex-charts/apex-charts.css" />
@endpush

@push('page-script')
    <script src="{{ asset('assets') }}/vendor/libs/apex-charts/apexcharts.js"></script>
    <script>
        (function () {
            const isDark       = document.documentElement.classList.contains('dark-style');
            const labelColor   = isDark ? '#a8aaae' : '#6d6b77';
            const borderColor  = isDark ? '#404152' : '#dbdade';
            const cardColor    = isDark ? '#2f3349' : '#fff';

            const formatRp = val => {
                if (val >= 1_000_000_000) return 'Rp ' + (val / 1_000_000_000).toFixed(1) + 'M';
                if (val >= 1_000_000)     return 'Rp ' + (val / 1_000_000).toFixed(1) + 'jt';
                return 'Rp ' + val.toLocaleString('id-ID');
            };

            const series      = @json($chartSeries);
            const targetLine  = @json($chartTargetLine);
            const labels      = @json($chartLabels);

            // Tambahkan target line sebagai series terakhir (type line)
            const allSeries = [
                ...series.map(s => ({ ...s, type: 'bar' })),
                { name: 'Target Bulanan', type: 'line', data: targetLine },
            ];

            const chartEl = document.querySelector('#chartPenjualanSemester');
            if (!chartEl) return;

            new ApexCharts(chartEl, {
                chart: {
                    type: 'bar',
                    height: 340,
                    stacked: true,
                    toolbar: { show: false },
                    parentHeightOffset: 0,
                },
                series: allSeries,
                plotOptions: {
                    bar: { borderRadius: 4, columnWidth: '50%', borderRadiusWhenStacked: 'last' },
                },
                stroke: {
                    width: allSeries.map((s, i) => i === allSeries.length - 1 ? 2 : 0),
                    curve: 'smooth',
                    dashArray: allSeries.map((s, i) => i === allSeries.length - 1 ? 5 : 0),
                },
                markers: {
                    size: allSeries.map((s, i) => i === allSeries.length - 1 ? 4 : 0),
                    strokeWidth: 2,
                    colors: [cardColor],
                    strokeColors: '#ff4c51',
                },
                colors: [
                    '#696cff','#03c3ec','#71dd37','#ffab00','#ff3e1d',
                    '#26c6da','#8c57ff','#20c997','#ff4c51'
                ],
                dataLabels: { enabled: false },
                legend: {
                    show: true,
                    position: 'top',
                    labels: { colors: labelColor },
                },
                xaxis: {
                    categories: labels,
                    labels: { style: { colors: labelColor, fontSize: '13px' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    labels: {
                        formatter: formatRp,
                        style: { colors: labelColor, fontSize: '11px' },
                    },
                },
                grid: {
                    borderColor,
                    strokeDashArray: 5,
                    padding: { top: -10, bottom: -5 },
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    custom: function({ series, dataPointIndex, w }) {
                        const month    = labels[dataPointIndex];
                        const barCount = allSeries.length - 1; // semua kecuali target line
                        let rows       = '';
                        let grandTotal = 0;

                        for (let i = 0; i < barCount; i++) {
                            const val   = series[i][dataPointIndex] || 0;
                            grandTotal += val;
                            if (val <= 0) continue;
                            const color = w.globals.colors[i];
                            const name  = w.globals.seriesNames[i];
                            rows += `<div style="display:flex;align-items:center;gap:8px;padding:3px 0">
                                        <span style="width:10px;height:10px;border-radius:50%;background:${color};flex-shrink:0"></span>
                                        <span style="flex:1;color:#6d6b77;font-size:12px">${name}</span>
                                        <span style="font-size:12px">Rp ${val.toLocaleString('id-ID')}</span>
                                     </div>`;
                        }

                        const targetVal   = series[barCount]?.[dataPointIndex] || 0;
                        const targetColor = w.globals.colors[barCount] ?? '#ff4c51';

                        const pct      = targetVal > 0 ? Math.round((grandTotal / targetVal) * 100) : 0;
                        const pctColor = pct >= 100 ? '#71dd37' : pct >= 80 ? '#ffab00' : '#ff4c51';

                        const totalRow  = `<div style="display:flex;align-items:center;gap:8px;padding:4px 0;border-top:1px solid #dbdade;margin-top:4px">
                                               <span style="width:10px;height:10px;border-radius:2px;background:#444;flex-shrink:0"></span>
                                               <span style="flex:1;font-weight:600;font-size:12px">Total Penjualan</span>
                                               <span style="font-weight:600;font-size:12px">Rp ${grandTotal.toLocaleString('id-ID')}</span>
                                               <span style="margin-left:6px;padding:1px 6px;border-radius:10px;background:${pctColor};color:#fff;font-size:11px;font-weight:700">${pct}%</span>
                                           </div>`;

                        const targetRow  = `<div style="display:flex;align-items:center;gap:8px;padding:3px 0">
                                                <span style="width:10px;height:2px;background:${targetColor};flex-shrink:0;display:inline-block"></span>
                                                <span style="flex:1;color:#6d6b77;font-size:12px">Target Bulanan</span>
                                                <span style="font-size:12px">Rp ${targetVal.toLocaleString('id-ID')}</span>
                                            </div>`;

                        return `<div style="padding:10px 12px;min-width:230px;font-family:inherit">
                                    <div style="font-weight:600;border-bottom:1px solid #dbdade;padding-bottom:6px;margin-bottom:4px">${month}</div>
                                    ${rows}
                                    ${totalRow}
                                    ${targetRow}
                                </div>`;
                    },
                },
            }).render();
        })();
    </script>
@endpush
