@extends('layouts.sales.app')
@section('title', 'Detail Existing')
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">CRM Existing /</span> Details {{ $existing->company }}
    </h4>
    <div class="row mb-4">
        <div class="col-md-6">
            <h5 class="fw-bold pb-1 mb-3">
                Details
            </h5>
            <div class="card">
                <div class="card-header pb-0">
                    <div class="text-end text-muted">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#updateExisting{{ $existing->id }}">
                            <button type="button" class="btn btn-sm btn-label-primary">Edit</button>
                        </a>
                        <a href="#" data-id="{{ $existing->id }}"
                            class="btn btn-sm btn-label-danger delete-existing">Delete</a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text">
                    <div class="row mb-1">
                        <div class="col-3">
                            Adress
                        </div>
                        <div class="col-9">
                            : {{ $existing->address }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            Area
                        </div>
                        <div class="col-9">
                            : {{ $existing->area }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            Phone
                        </div>
                        <div class="col-9">
                            : {{ $existing->phone }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            Email
                        </div>
                        <div class="col-9">
                            : {{ $existing->email }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            Mobile
                        </div>
                        <div class="col-9">
                            : {{ $existing->mobile }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            R/U
                        </div>
                        <div class="col-9">
                            : {{ $existing->ru }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            Source
                        </div>
                        <div class="col-9">
                            : {{ $existing->source }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            Machine
                        </div>
                        <div class="col-9">
                            : {{ $existing->machine }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            Assigned
                        </div>
                        <div class="col-9">
                            : {{ $existing->sales->name }}
                        </div>
                    </div>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold pb-1 mb-3">
                    PIC
                </h5>
                <a type="button" data-bs-toggle="modal" data-bs-target="#createPic">
                    <button type="button" class="btn btn-primary">
                        + New PIC
                    </button>
                </a>
            </div>
            @foreach ($charge as $pic)
                <div class="card mb-2">
                    <div class="card-header pb-0">
                        <div class="text-end text-muted">
                            <a type="button" data-bs-toggle="modal" data-bs-target="#updatePic-{{ $pic->id }}">
                                <button type="button" class="btn btn-sm btn-label-primary">
                                    <i class="menu-icon tf-icons mdi mdi-14px mdi-account-edit-outline"></i>Edit
                                </button>
                            </a>
                            <a href="#" data-id="{{ $pic->id }}" class="btn btn-sm btn-label-danger delete-pic">
                                <i class="menu-icon tf-icons mdi mdi-14px mdi-delete-outline"></i>Delete
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                        <div class="row mb-1">
                            <div class="col-3">
                                Name
                            </div>
                            <div class="col-9">
                                : {{ $pic->name_pic }}
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-3">
                                Position
                            </div>
                            <div class="col-9">
                                : {{ $pic->position }}
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-3">
                                Phone
                            </div>
                            <div class="col-9">
                                : {{ $pic->phone_pic }}
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-3">
                                Email
                            </div>
                            <div class="col-9">
                                : {{ $pic->email_pic }}
                            </div>
                        </div>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row mb-3">
        <div class="d-flex justify-content-between mb-2">
            <h5 class="fw-bold m-0 pt-2">
                CRM Existing
            </h5>
            <a type="button" data-bs-toggle="modal" data-bs-target="#createAction{{ $existing->id }}">
                <button type="button" class="btn btn-primary">
                    + New Action
                </button>
            </a>
        </div>
        <div class="card">

            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            @php
                                $bulan = array_keys($crmhis);
                                $mon1 = count($crmhis['January 2024']);
                                $mon2 = count($crmhis['February 2024']);
                                $mon3 = count($crmhis['March 2024']);
                                $mon4 = count($crmhis['April 2024']);
                                $mon5 = count($crmhis['May 2024']);
                                $mon6 = count($crmhis['June 2024']);
                            @endphp
                            {{-- {{print_r($crmhis)}} --}}
                            @foreach ($bulan as $data => $data_bulan)
                                <th
                                    colspan="{{ $data_bulan == 'January 2024' ? $mon1 : '' }}{{ $data_bulan == 'February 2024' ? $mon2 : '' }}{{ $data_bulan == 'March 2024' ? $mon3 : '' }}{{ $data_bulan == 'April 2024' ? $mon4 : '' }}{{ $data_bulan == 'May 2024' ? $mon5 : '' }}{{ $data_bulan == 'June 2024' ? $mon6 : '' }}">
                                    {{ $data_bulan }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            @php
                                $weeks = 0;
                            @endphp
                            @foreach ($crmhis['January 2024'] as $data)
                                @php
                                    $weeks += 1;
                                @endphp
                                <th>Week {{ $weeks }}</th>
                            @endforeach
                            @php
                                $weeks = 0;
                            @endphp
                            @foreach ($crmhis['February 2024'] as $data)
                                @php
                                    $weeks += 1;
                                @endphp
                                <th>Week {{ $weeks }}</th>
                            @endforeach
                            @php
                                $weeks = 0;
                            @endphp
                            @foreach ($crmhis['March 2024'] as $data)
                                @php
                                    $weeks += 1;
                                @endphp
                                <th>Week {{ $weeks }}</th>
                            @endforeach
                            @php
                                $weeks = 0;
                            @endphp
                            @foreach ($crmhis['April 2024'] as $data)
                                @php
                                    $weeks += 1;
                                @endphp
                                <th>Week {{ $weeks }}</th>
                            @endforeach
                            @php
                                $weeks = 0;
                            @endphp
                            @foreach ($crmhis['May 2024'] as $data)
                                @php
                                    $weeks += 1;
                                @endphp
                                <th>Week {{ $weeks }}</th>
                            @endforeach
                            @php
                                $weeks = 0;
                            @endphp
                            @foreach ($crmhis['June 2024'] as $data)
                                @php
                                    $weeks += 1;
                                @endphp
                                <th>Week {{ $weeks }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($crmhis as $item)
                                @foreach ($item as $minggu)
                                    <td data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-custom-class="tooltip-primary"
                                        data-bs-original-title="{{ $minggu['note'][0] }}">
                                        {{ $minggu['data'][0] }}
                                    </td>
                                @endforeach
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 my-3">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold pb-1 mb-2">
                    CRM History
                </h5>
            </div>
            <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>note</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($callhis as $callhistory)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($callhistory->date)->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        {{ $callhistory->action }}
                                    </td>
                                    <td>
                                        {{ $callhistory->status }}
                                    </td>
                                    <td>
                                        {{ $callhistory->note }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        Kamu belum punya Call History.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 my-3">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold m-0 pt-2">
                    Quotation
                </h5>
                <a href="{{ route('quotation.create') }}" type="button" class="btn btn-primary">
                    + New Quotation
                </a>
            </div>
            <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Number Quote</th>
                                <th>Status</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($quote as $quotation)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($quotation->estimated_date)->format('d-m-Y') }}
                                    </td>
                                    <td class="fw-medium">
                                        <a class="text-black"
                                            href="{{ route('quotation.show', $quotation->id) }}">{{ $quotation->no_quote }}</a>

                                    </td>
                                    <td><span
                                            class="badge bg-label-{{ $quotation->status == '25' ? 'info' : ($quotation->status == '50' ? 'warning' : ($quotation->status == '75' ? 'primary' : ($quotation->status == '100' ? 'success' : ($quotation->status == '0' ? 'danger' : '')))) }}">{{ $quotation->status }}%</span>
                                    </td>
                                    <td>
                                        RP {{ number_format($quotation->harga_total, 0, '', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        Kamu belum punya Quotation.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 my-3">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold pb-1 mb-2">
                    Service History
                </h5>
            </div>
            <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No Service</th>
                                <th>Unit</th>
                                <th>Teknisi</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($service as $reports)
                                <tr>
                                    <td class="fw-medium">
                                        <a class="text-black"
                                            href="{{ route('service-reports.show', $reports->id) }}">{{ $reports->no_service }}</a>

                                    </td>
                                    <td>
                                        {{ $reports->unit }}
                                    </td>
                                    <td>
                                        {{ $reports->technician->name }}
                                    </td>
                                    <td>
                                        {{ $reports->date }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        Kamu belum Pernah Melakukan Service.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 my-3">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold m-0 pt-2">
                    Purchasing Order
                </h5>
            </div>
            <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Number Quote</th>
                                <th>Status</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>-</td>
                                <td>
                                    -
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col my-3">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold m-0 pt-2">
                    Service History
                </h5>
            </div>
            <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Unit</th>
                                <th>Running Hour</th>
                                <th>Teknisi</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>
                                    -
                                </td>
                                <td>-</td>
                                <td>
                                    -
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('pages.sales.existing.form')
    @include('components.modal.pic.existing.form-create')
    @include('pages.sales.activities.form-existing')
    @foreach ($charge as $pic)
        @include('components.modal.pic.existing.form-update')
    @endforeach
@endsection()
@push('after-style')
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
        $(document).on('click', '.delete-pic', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('pic') }}/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: "Your file has been deleted.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    location.reload();
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Delete!'
                                });
                            }
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Cancelled",
                        text: "Your imaginary file is safe :)",
                        icon: "error",
                        customClass: {
                            confirmButton: "btn btn-success waves-effect",
                        },
                    });
                }
            });
            // Swal.fire({
            //     title: "Are you sure?",
            //     text: "You won't be able to revert this!",
            //     icon: "warning",
            //     showCancelButton: true,
            //     confirmButtonColor: "#3085d6",
            //     cancelButtonColor: "#d33",
            //     confirmButtonText: "Yes, delete it!"
            // }).then((result) => {
            //     if (result.isConfirmed) {
            //         $.ajax({
            //             'url': '{{ url('existing') }}/' + id,
            //             'type': 'POST',
            //             'data': {
            //                 '_method': 'DELETE',
            //                 '_token': '{{ csrf_token() }}'
            //             },
            //             success: function(response) {
            //                 if (response == 1) {
            //                     Swal.fire({
            //                         title: "Deleted!",
            //                         text: "Your file has been deleted.",
            //                         icon: "success"
            //                     })
            //                     window.setTimeout(function() {
            //                         location.reload();
            //                     }, 2000);
            //                 } else {
            //                     Swal.fire({
            //                         icon: 'error',
            //                         title: 'Oops...',
            //                         text: 'Data Failed to Delete!'
            //                     });
            //                 }
            //             }
            //         });
            //     }
            // });
        });
        $(document).on('click', '.delete-existing', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('existing') }}/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: "Your file has been deleted.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/existing';
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Delete!'
                                });
                            }
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Cancelled",
                        text: "Your imaginary file is safe :)",
                        icon: "error",
                        customClass: {
                            confirmButton: "btn btn-success waves-effect",
                        },
                    });
                }
            });
            // Swal.fire({
            //     title: "Are you sure?",
            //     text: "You won't be able to revert this!",
            //     icon: "warning",
            //     showCancelButton: true,
            //     confirmButtonColor: "#3085d6",
            //     cancelButtonColor: "#d33",
            //     confirmButtonText: "Yes, delete it!"
            // }).then((result) => {
            //     if (result.isConfirmed) {
            //         $.ajax({
            //             'url': '{{ url('existing') }}/' + id,
            //             'type': 'POST',
            //             'data': {
            //                 '_method': 'DELETE',
            //                 '_token': '{{ csrf_token() }}'
            //             },
            //             success: function(response) {
            //                 if (response == 1) {
            //                     Swal.fire({
            //                         title: "Deleted!",
            //                         text: "Your file has been deleted.",
            //                         icon: "success"
            //                     })
            //                     window.setTimeout(function() {
            //                         location.reload();
            //                     }, 2000);
            //                 } else {
            //                     Swal.fire({
            //                         icon: 'error',
            //                         title: 'Oops...',
            //                         text: 'Data Failed to Delete!'
            //                     });
            //                 }
            //             }
            //         });
            //     }
            // });
        });
    </script>
@endpush
