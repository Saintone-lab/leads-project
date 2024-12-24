@extends('layouts.sales.app')
@section('title', 'Service Reports')
@section('content')
    <div class="row invoice-preview">
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-row flex-column">
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
                        <div>
                            <h3 class="fw-bold">DAILY MONITORING</h3>
                            <div>
                                <span class="fw-bolder">{{ $machine->unit->unit->unit }}</span>
                            </div>
                            <div class="mt-1">
                                <span class="text-muted">{{ $machine->tag }} - {{ $machine->location }}</span>
                            </div>
                        </div>
                    </div>
                    <hr class="my-2">
                    {{-- <div class="row mb-3">
                        <div class="col-lg-2 col-4 fw-medium">
                            <p class="mb-1">Customers </p>
                            <p class="mb-1">Address </p>
                            <p class="mb-1">PIC </p>
                        </div>
                        <div class="col-lg-4 col-8">
                            <p class="mb-1">: a</p>
                            <p class="mb-1">: a</p>
                            <p class="mb-1">: a</p>
                        </div>
                        <div class="col-lg-2 col-4 fw-medium">
                            <p class="mb-1">Unit Type </p>
                            <p class="mb-1">Serial Number </p>
                            <p class="mb-1">Running & Load </p>
                        </div>
                        <div class="col-lg-4 col-8">
                            <p class="mb-1">: a</p>
                            <p class="mb-1">: a</p>
                            <p class="mb-1">: a</p>
                        </div>
                    </div>
                    <hr> --}}
                    <div class="table-responsive text-nowrap mb-4">
                        @if ($machine->unit->unit->unit != 'REFRIGERANT AIR DRYER')
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <th>Date</th>
                                    <th>Condition</th>
                                    <th>R Hr.</th>
                                    <th>L Hr.</th>
                                    <th>Press.</th>
                                    <th>Temp.</th>
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
                                            <td>{{ $item['temp'] }}</td>
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
                    <div class="row mt-5">
                        <div class="col-4 mt-5 text-center">
                            <p>PT Reftech Jaya Optima</p>
                                <div class="pb-5"></div>
                            <p class="pt-3">( Arie Sudjiwo )</p>
                        </div>
                        <div class="col-4"></div>
                        <div class="col-4 mt-5 text-center">
                            <p>PT Reftech Jaya Optima</p>
                                <div class="pb-5"></div>
                            <p class="pt-3">( Arie Supratman )</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- End: Invoice --}}
        {{-- Button Invocie --}}
        <div class="col-xl-3 col-md-4 col-12 invoice-actions">
            <div class="card mb-3">
                <div class="card-body">
                    <a class="btn btn-primary btn-outline-secondary d-grid w-100 mb-3 waves-effect" target="_blank"
                        href="{{ route('service-manager.print', [request()->route('id'), request()->route('month')]) }}">
                        Download
                    </a>
                    <button id="buttonShare" data-id="1"
                        class="btn btn-success d-grid w-100 waves-effect mb-3">Bagikan</button>
                </div>
            </div>
        </div>
        {{-- End : Button Invoice --}}
    </div>
@endsection
@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
@endpush
@push('page-script')
    <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
@endpush
@push('script')
    <script>
        $(() => {
            $('#buttonShare').on('click', function() {
                const id = $(this).data('id')
                if (navigator.share) {
                    navigator.share({
                        title: 'Service Reports',
                        text: 'Check out this link!',
                        url: '{{ route('service-reports.show', ':id') }}'.replace(':id', id)
                    }).then(() => {
                        console.log('Thanks for sharing!');
                    }).catch(err => {
                        console.error('Error sharing:', err);
                    });
                } else {
                    alert('Sharing not supported in this browser.');
                }
            });
        });
    </script>
@endpush
