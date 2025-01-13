@extends('layouts.sales.app')
@section('title', 'Monitoring machine')
@section('content')
    <div class="row mb-4">
        <div class="col-12 col-md-3 mb-2">
            <div class="card bg-label-info">
                <div class="card-body">
                    <h5>Machine On All Plant</h5>
                    <p class="text-black float-end fs-5">{{ $allPlantMonitoring }}/ <span
                            class="text-muted fs-6">{{ $allPlant }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card">
                <div class="card-body">
                    <h5>Plant GT 3</h5>
                    <p class="float-end fs-5">{{ $GT3Monitoring }}/ <span class="text-muted fs-6">{{ $GT3 }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card">
                <div class="card-body">
                    <h5>Plant GT</h5>
                    <p class="float-end fs-5">{{ $GTMonitoring }}/ <span class="text-muted fs-6">{{ $GT }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card">
                <div class="card-body">
                    <h5>Plant INC</h5>
                    <p class="float-end fs-5">{{ $INCMonitoring }}/ <span class="text-muted fs-6">{{ $INC }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Plant BOILER 3</h5>
                    <p class="float-end fs-5">{{ $BOILER3Monitoring }}/ <span
                            class="text-muted fs-6">{{ $BOILER3 }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Plant PM 1-2</h5>
                    <p class="float-end fs-5">{{ $PM12Monitoring }}/ <span
                            class="text-muted fs-6">{{ $PM12 }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Plant PM 3-5</h5>
                    <p class="float-end fs-5">{{ $PM35Monitoring }}/ <span
                            class="text-muted fs-6">{{ $PM35 }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Plant PM 7-8</h5>
                    <p class="float-end fs-5">{{ $PM78Monitoring }}/ <span
                            class="text-muted fs-6">{{ $PM78 }}</span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5> Air Compressor </h5>
                    <div class="card-datatable table-responsive pt-0">
                        <table class="datatable-recap-compressor table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Brand</th>
                                    <th>Tag</th>
                                    <th>Location</th>
                                    <th>PIC</th>
                                    <th>Info</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5> Dryer </h5>
                    <div class="card-datatable table-responsive pt-0">
                        <table class="datatable-recap-dryer table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Brand</th>
                                    <th>Tag</th>
                                    <th>Location</th>
                                    <th>PIC</th>
                                    <th>Info</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <script src="{{ asset('assets') }}/includes/table-recap-compressor.js"></script>
    <script src="{{ asset('assets') }}/includes/table-recap-dryer.js"></script>
@endpush

@push('script')
    <script>
        var recapRoute = "{{ route('service-manager.recap', [':month', ':year']) }}";
    </script>
@endpush
