@extends('layouts.sales.app')
@section('title', 'Monitoring machine')
@section('content')
    <div class="row mb-3">
        <div class="col-12 col-md-6 mb-4">
            <h5>Data Unit</h5>
            <div class="card h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">Date Issue</div>
                        <div class="col-8">: {{ $monitoring->date }}</div>
                        <div class="col-4">Location</div>
                        <div class="col-8">: {{ $monitoring->machine->location }}</div>
                        <div class="col-4">Tag Number</div>
                        <div class="col-8">: {{ $monitoring->machine->tag }}</div>
                        <div class="col-4">Type / Model </div>
                        <div class="col-8">: {{ $monitoring->machine->unit->brand }}
                            {{ $monitoring->machine->unit->unit->sku }}</div>
                        <div class="col-4">PIC User</div>
                        <div class="col-8">: {{ $monitoring->machine->desc }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-4">
            <div class="d-flex justify-content-between">
                <h5 class="fw-bold m-0 pt-2">
                    Issue
                </h5>
                <a type="button" data-bs-toggle="modal" data-bs-target="#updateIssue">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        {{ $monitoring->issue || $monitoring->issue == '-' ? 'Edit' : '+' }}
                    </button>
                </a>
            </div>
            <div class="card h-100">
                <div class="card-body">
                    {{ $monitoring->issue ?? 'Belum ada Issue' }}
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 my-4">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold m-0 pt-2">
                    Recomendation
                </h5>
                <a type="button" data-bs-toggle="modal" data-bs-target="#updateRecommendation">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        {{ $monitoring->recommendation && $monitoring->recommendation != '-' ? 'Edit' : '+' }}
                    </button>
                </a>
            </div>
            <div class="card h-100">
                <div class="card-body">
                    {{ $monitoring->recommendation && $monitoring->recommendation != '-' ? $monitoring->recommendation : 'Belum ada Recommendation' }}
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 my-4">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold m-0 pt-2">
                    Part Number
                </h5>
                <a type="button" data-bs-toggle="modal" data-bs-target="#updatePn">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        +
                    </button>
                </a>
            </div>
            <div class="card h-100">
                <div class="card-body">
                    <div class="table-responsive text-nowrap mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40%;">PN</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pn as $machine)
                                    <tr>
                                        <td>{{ $machine->pn }}</td>
                                        <td>{{ $machine->desc }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 my-4">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold m-0 pt-2">
                    Quotation
                </h5>
                <a href="{{ route('monitoring.create-quotation', $monitoring->id) }}" type="button"
                    class="btn btn-primary waves-effect waves-light">
                    +
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>No. Quote</th>
                                    <th>Title</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($quotes as $quote)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($quote->estimated_date)->format('d-m-Y') }}</td>
                                        <td>{{ $quote->no_quote }}</td>
                                        <td>{{ $quote->title }}</td>
                                        <td>{{ $quote->harga_total }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum Ada Quote</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 my-5">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold m-0 pt-2">
                    Activity Timeline
                </h5>
                <a type="button" data-bs-toggle="modal" data-bs-target="#updateStatus">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        Update Status
                    </button>
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    @foreach ($status as $item)
                        @php
                            switch ($item->status) {
                                case '0':
                                    $stat = 'Monitoring Created';
                                    $label = 'bg-label-dark';
                                    break;
                                case '1':
                                    $stat = 'Process FU to User';
                                    $label = 'bg-label-info';
                                    break;
                                case '2':
                                    $stat = 'Send Inquiry';
                                    $label = 'bg-label-warning';
                                    break;
                                case '3':
                                    $stat = 'Hold By User';
                                    $label = 'bg-label-danger';
                                    break;
                                case '4':
                                    $stat = 'Done';
                                    $label = 'bg-label-success';
                                    break;

                                default:
                                    # code...
                                    break;
                            }
                        @endphp
                        <div class="d-flex justify-content-between">
                            <h5 class="badge rounded-pill {{ $label }} fs-5 fw-5">
                                {{ $stat }}
                            </h5>
                            <h6>
                                {{ $item->pic->name }}
                            </h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>{{ $item->desc }}</p>
                            <p>{{ $item->date }}</p>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @include('components.modal.monitoring.client.issueDet')
    @include('components.modal.monitoring.client.recommendation')
    @include('components.modal.monitoring.client.status')
    @include('components.modal.monitoring.client.pn')
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
