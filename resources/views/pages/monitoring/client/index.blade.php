@extends('layouts.sales.app')
@section('title', 'Monitoring machine')
@section('content')
    <div class="row mb-4">
        <div class="col-12 col-md-3 mb-2">
            <div class="card bg-label-success h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <h1 class="text-black">{{ $allPlantMonitoring }}/ <span class="text-muted fs-3">{{ $allPlant }}</span>
                    </h1>
                    <h5>Data Input Daily</h5>
                </div>
            </div>
        </div>
        <div class="col-9">
            <div class="row">
                <div class="col-6 col-md-4 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5>Plant GT 3 / BOILER</h5>
                            <p class="float-end fs-5">{{ $GT3Monitoring }}/ <span
                                    class="text-muted fs-6">{{ $GT3 }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5>Plant GT 1-2</h5>
                            <p class="float-end fs-5">{{ $GTMonitoring }}/ <span
                                    class="text-muted fs-6">{{ $GT }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <h5>Plant INC</h5>
                            <p class="float-end fs-5">{{ $INCMonitoring }}/ <span
                                    class="text-muted fs-6">{{ $INC }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Plant PM 1-2</h5>
                            <p class="float-end fs-5">{{ $PM12Monitoring }}/ <span
                                    class="text-muted fs-6">{{ $PM12 }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Plant PM 3-5</h5>
                            <p class="float-end fs-5">{{ $PM35Monitoring }}/ <span
                                    class="text-muted fs-6">{{ $PM35 }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Plant PM 7-8</h5>
                            <p class="float-end fs-5">{{ $PM78Monitoring }}/ <span
                                    class="text-muted fs-6">{{ $PM78 }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5> Machine </h5>
                    <div class="card-datatable table-responsive pt-0">
                        <table class="datatable-compressor-client table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Brand</th>
                                    <th>Unit</th>
                                    <th>Tag</th>
                                    <th>Location</th>
                                    <th>PIC</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5> Issue Daily </h5>
                    <div class="card-datatable table-responsive pt-0">
                        <table class="datatable-issue-monitoring table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Tag</th>
                                    <th>Model / Type</th>
                                    <th>Issue</th>
                                    <th>Pic</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($issued as $monitor)
        @include('components.modal.monitoring.client.issue')
    @endforeach
@endsection()

@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
@endpush

@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
@endpush

@push('page-script')
    <script src="{{ asset('assets') }}/js/tables-datatables-basic.js"></script>
    <script src="{{ asset('assets') }}/includes/table-client-daily.js"></script>
    <script src="{{ asset('assets') }}/includes/table-issue-client-monitoring.js"></script>
@endpush

@push('script')
@endpush
