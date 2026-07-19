@extends('layouts.sales.app')
@section('title', 'BAST')
@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-1">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">Accounting /</span> BAST
        </h4>
        <button type="button" class="btn btn-primary btn-sm" id="btnCreateBast">
            <i class="mdi mdi-plus"></i> Buat BAST Manual
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="table-responsive">
                    <table class="table table-striped mb-0" id="bastTable">
                        <thead>
                            <tr>
                                <th>No. BAST</th>
                                <th>Customer</th>
                                <th>Pekerjaan</th>
                                <th>Tgl Pekerjaan</th>
                                <th>No. PO/Kontrak</th>
                                <th>Dibuat oleh</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($basts as $bast)
                                <tr>
                                    <td class="fw-semibold">{{ $bast->no_bast }}</td>
                                    <td>{{ $bast->customer_name }}</td>
                                    <td>{{ $bast->work_title }}</td>
                                    <td>{{ $bast->work_date->format('d-m-Y') }}</td>
                                    <td>{{ $bast->po_number ?: '-' }}</td>
                                    <td>{{ $bast->creator->name ?? '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('bast.print', $bast->id) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-printer-outline"></i> Print
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-bast"
                                            data-id="{{ $bast->id }}">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-bast"
                                            data-id="{{ $bast->id }}" data-no="{{ $bast->no_bast }}">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('components.modal.bast.create')
@endsection()

@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
@endpush

@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable) {
                $('#bastTable').DataTable({
                    order: [],
                    columnDefs: [{
                        orderable: false,
                        targets: -1
                    }],
                    language: {
                        emptyTable: 'Belum ada BAST yang dibuat.',
                        zeroRecords: 'Data tidak ditemukan.'
                    },
                });
            }
        });

        $('#btnCreateBast').on('click', function() {
            window.openBastModal({});
        });

        $(document).on('click', '.btn-edit-bast', function() {
            const id = $(this).data('id');
            $.get(`{{ url('/bast') }}/${id}/edit-data`, function(response) {
                const b = response.bast;
                window.openBastModal({
                    bastId: b.id,
                    entity: b.entity,
                    customerName: b.customer_name,
                    workTitle: b.work_title,
                    poNumber: b.po_number,
                    workDate: b.work_date,
                    testRunningResult: b.test_running_result,
                    units: b.units,
                });
            });
        });

        $(document).on('click', '.btn-delete-bast', function() {
            const id = $(this).data('id');
            const no = $(this).data('no');
            if (!confirm(`Hapus BAST ${no}? Tindakan ini tidak bisa dibatalkan.`)) return;

            $.ajax({
                url: `{{ url('/bast') }}/${id}`,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function() {
                    window.location.reload();
                }
            });
        });

        $(document).on('bast:saved', function() {
            window.location.reload();
        });
    </script>
@endpush
