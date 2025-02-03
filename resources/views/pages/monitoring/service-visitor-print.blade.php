@extends('layouts.sales.app')
@section('title', 'Service reports')

<div class="invoice-print p-4">
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
            <div class="mb-xl-0 pb-1">
                <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                    <span class="app-brand-logo demo">
                        <span style="color: var(--bs-primary)">
                            <img class="text-md"
                                src="{{ url('https://reftech.id/wp-content/uploads/2021/10/Reftech-Logo-Hitam.png') }}"
                                alt="" srcset="" width="60%">
                        </span>
                    </span>
                </div>
                <p class="mb-1 fw-bolder">PT Reftech Jaya Optima</p>
                <div style="font-size: 10px">
                    <p class="mb-1">Taman Kopo Indah V, Ruko Sommerville No. 31</p>
                    <p class="mb-1">Bandung – Jawa Barat 40218</p>
                    <p class="mb-1">
                        <i class="mdi mdi-phone-outline scaleX-n1-rtl me-1 mdi-14px"></i>022
                        54417653{{ '  |  ' }}<i
                            class="mdi mdi-email-outline scaleX-n1-rtl me-1 mdi-14px"></i>admin@reftech.id
                    </p>
                </div>
            </div>
            <div class="text-end">
                <h3 class="fw-bold">DAILY MONITORING</h3>
                <div>
                    <span class="fw-bolder">{{ $machine->unit->unit->unit }}</span>
                </div>
                <div class="mt-1">
                    <span class="text-muted">{{ $machine->unit->brand }} {{ $machine->unit->unit->sku }}</span>
                </div>
                <div class="mt-1">
                    <span class="text-muted">{{ $machine->tag }} - {{ $machine->location }}</span>
                </div>
            </div>
        </div>
        <hr class="my-2">
        {{-- <div class="row mb-3">
            <div class="col-2 fw-medium">
                <p class="mb-1">Customers </p>
                <p class="mb-1">Address </p>
                <p class="mb-1">PIC </p>
            </div>
            <div class="col-4">
                <p class="mb-1">: {{ $service->pic->client->company }}</p>
                <p class="mb-1">: {{ $service->pic->client->area }}</p>
                <p class="mb-1">: {{ $service->pic->name_pic }}</p>
            </div>
            <div class="col-2 fw-medium">
                <p class="mb-1">Unit Type </p>
                <p class="mb-1">Serial Number </p>
                <p class="mb-1">Running & Load </p>
            </div>
            <div class="col-4">
                <p class="mb-1">: {{ $service->machine->unit->brand }} {{ $service->machine->unit->unit->sku }}
                </p>
                <p class="mb-1">: {{ $service->machine->unit->sn }}</p>
                <p class="mb-1">: {{ $service->running }} | {{ $service->load }}</p>
            </div>
        </div> --}}

        <div class="table-responsive text-nowrap">
            @if ($machine->unit->unit->unit != 'REFRIGERANT AIR DRYER')
                <table class="table table-bordered">
                    <thead class="table-light">
                        <th>Date</th>
                        <th>Condition</th>
                        <th>R Hr.</th>
                        <th>L Hr.</th>
                        <th>Press.</th>
                        <th>Temp. (85°C - 90°C)</th>
                        <th>Oil Lvl</th>
                        <th>PIC</th>
                    </thead>
                    <tbody>
                        @foreach ($compressor as $item)
                            <tr>
                                <td>{{ $item['date'] }}</td>
                                <td>{{ $item['condition'] }}</td>
                                <td>{{ $item['running'] }}</td>
                                <td>{{ $item['loading'] }}</td>
                                <td>{{ $item['pressure'] }}</td>
                                <td>
                                    @php
                                        $stringTemp = $item['temp'] ?? ''; // Pastikan nilai tidak null
                                        $tempNumber = null;

                                        if (preg_match('/\d+(\.\d+)?/', $stringTemp, $matches)) {
                                            $tempNumber = (float) $matches[0]; // Gunakan float agar mendukung desimal
                                        }
                                    @endphp

                                    @if (!is_null($tempNumber) && $tempNumber > 90)
                                        <p class="mb-0 fw-bold fs-6 text-danger">{{ $item['temp'] }}</p>
                                    @else
                                        {{ $item['temp'] }}
                                    @endif
                                </td>
                                <td>{{ $item['oil_level'] }}</td>
                                <td>{{ $item['pic'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <table class="table table-bordered">
                    <thead class="table-light">
                        <th>Date</th>
                        <th>Condition</th>
                        <th>Temp IN</th>
                        <th>Temp OUT</th>
                        <th>Dew P.</th>
                        <th>Auto Drain</th>
                        <th>PIC</th>
                    </thead>
                    <tbody>
                        @foreach ($dryer as $item)
                            <tr>
                                <td>{{ $item['date'] }}</td>
                                <td>{{ $item['condition'] }}</td>
                                <td>{{ $item['temp'] }}</td>
                                <td>{{ $item['temp_out'] }}</td>
                                <td>{{ $item['dew'] }}</td>
                                <td>{{ $item['drain'] }}</td>
                                <td>{{ $item['pic'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>


        <div class="issue mt-5">
            <h5>Issue Recommendation</h5>
            <div class="table-responsive text-nowrap mb-4">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width:20%;">Date</th>
                            <th>Issue</th>
                            <th style="width:25%;">Pic</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($issue as $issues)
                            <tr>
                                <td>{{ $issues->date ?? 'N/A' }}</td>
                                <td>
                                    <pre class="mb-1"
                                        style="font-size: 15px; font-family: 'Inter', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto;">{{ $issues->desc }}</pre>
                                </td>
                                <td>{{ $issues->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mainlog mt-5">
            <h5>Maintenance Log</h5>
            <div class="table-responsive text-nowrap mb-4">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width:20%;">Date</th>
                            <th>Maintenance</th>
                            <th style="width:25%;">Pic</th>
                            {{-- <th style="width:10%;">Button</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mainlog as $item)
                            <tr>
                                <td>{{ $item->date ?? 'N/A' }}</td>
                                <td>
                                    <pre class="mb-1"
                                        style="font-size: 15px; font-family: 'Inter', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto;">{{ $item->main_desc }}</pre>
                                </td>
                                <td>{{ $item->name }}</td>
                                {{-- @if ($mainlog['id_service'] != null)
                                    <td>
                                        <a class="btn btn-warning waves-effect"
                                            href="{{ route('service-reports.show', $mainlog['id_service']) }}">
                                            <i class="menu-icon tf-icons mdi mdi-eye-outline"></i>
                                        </a>
                                    </td>
                                @elseif($mainlog['id_service'] == null && $mainlog['id_pic'] == Auth::user()->id)
                                    <td>
                                        <a class="btn btn-primary waves-effect"
                                            href="{{ route('create.daily-monitoring-reports', [$mainlog['id'], $mainlog['id_machine']]) }}">
                                            <i class="menu-icon tf-icons mdi mdi-file-plus-outline"></i>
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        Has No Reports
                                    </td>
                                @endif --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-4 mt-5 text-center">
                <p>PT Reftech Jaya Optima</p>
                <div class="pb-5"></div>
                <p class="pt-3">Angel Irene</p>
            </div>
            <div class="col-4"></div>
            <div class="col-4 mt-5 text-center">
                <p>PT Fajar Surya Wisesa</p>
                <div class="pb-5"></div>
                <p class="pt-3">..........................................</p>
            </div>
        </div>
    </div>
</div>
@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-monitoring-print.css" />
    <link rel="stylesheet" href="style.css">
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/js/app-invoice-print.js"></script>
    <script>
        window.addEventListener('beforeprint', () => {
            const rows = document.querySelectorAll('table tr');
            rows.forEach((row, index) => {
                const rect = row.getBoundingClientRect();
                if (rect.top > window.innerHeight) {
                    row.style.marginTop = '20mm';
                }
            });
        });
    </script>
@endpush
