@extends('layouts.sales.app')
@section('title', 'Monitoring machine')
@section('content')
    <h3>Rekap Issue {{ \Carbon\Carbon::createFromFormat('m', $month)->format('F') }} , {{ $year }}</h3>
    @foreach ($result as $item)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $item['machine'] }}</h5>
                <div class="table-responsive text-nowrap mb-4">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th style="width:25%;">Date</th>
                                <th>Issue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item['log'] as $log)
                                <tr>
                                    <!-- Menampilkan tanggal log jika ada -->
                                    <td>{{ $log['date'] ?? 'N/A' }}</td>
                                    <!-- Jika tanggal ada, tampilkan, jika tidak tampilkan 'N/A' -->
                                    <td>{{ $log['log'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
    <script src="{{ asset('assets') }}/includes/table-coordinator-compressor.js"></script>
    <script src="{{ asset('assets') }}/includes/table-coordinator-dryer.js"></script>
    <script src="{{ asset('assets') }}/includes/table-recap-month.js"></script>
    <script src="{{ asset('assets') }}/includes/table-issue-month.js"></script>
@endpush
