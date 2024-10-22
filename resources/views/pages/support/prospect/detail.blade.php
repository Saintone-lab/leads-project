@extends('layouts.sales.app')
@section('title', 'Detail Prospect')
@section('content')
    <h3>
        Prospect {{ $client->company }}
    </h3>
    <div class="row invoice-preview">
        {{-- Invoice --}}
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="fw-bold pb-1 mb-3">
                                    Details
                                </h5>
                                <p class="card-text">
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Address
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->address }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Sub Address
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->subAddress }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Area
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->area }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Phone
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->phone }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Email
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->email }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Mobile
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->mobile }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        R/U
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->ru }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Source
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->source }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Machine
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->machine }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        Assigned
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->sales->name }}
                                    </div>
                                </div>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="fw-bold pb-1 mb-3">
                                    PIC
                                </h5>
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
                                        Phone
                                    </div>
                                    <div class="col-9">
                                        : {{ $pic->phone_pic }}
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
                                        Email
                                    </div>
                                    <div class="col-9">
                                        : {{ $pic->email_pic }}
                                    </div>
                                </div>
                                </p>
                                <div class="prospect my-3">
                                    <h5>Prospect</h5>
                                    <div class="row">
                                        <div class="col-3">
                                            Prospect
                                        </div>
                                        <div class="col-9">
                                            : {{ $prospect->kebutuhan }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- End: Invoice --}}
        {{-- Button Invocie --}}
        @if (Auth::user()->role != 'Support')
            <div class="col-xl-3 col-md-4 col-12 invoice-actions">
                <div class="card">
                    <form action="{{ route('add_sales.prospect', $prospect->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md">
                                    <div
                                        class="form-check custom-option custom-option-icon  {{ @$prospect->provide == '1' ? 'checked disabled' : '' }} h-100">
                                        <label class="form-check-label custom-option-content" for="provideCheck1">
                                            <span class="custom-option-body">
                                                <i class="mdi mdi-file-check-outline"></i>
                                                <span class="custom-option-title"> Provided </span>
                                                <small> Prospect is Provided. </small>
                                            </span>
                                            <input name="provideCheck" class="form-check-input check-provide" type="radio"
                                                value="1" id="provideCheck1"
                                                {{ @$prospect->provide == '1' ? 'checked disabled' : '' }}>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div
                                        class="form-check custom-option custom-option-icon  {{ @$prospect->provide == '0' ? 'checked' : '' }} h-100">
                                        <label class="form-check-label custom-option-content" for="provideCheck2">
                                            <span class="custom-option-body">
                                                <i class="mdi mdi-file-alert-outline"></i>
                                                <span class="custom-option-title"> No Provided </span>
                                                <small> Prospect is No Provided. </small>
                                            </span>
                                            <input name="provideCheck" class="form-check-input check-no-provide" type="radio"
                                                value="0" id="provideCheck2"
                                                {{ @$prospect->provide == '0' ? 'checked' : '' }}{{ @$prospect->provide == '1' ? ' disabled' : '' }}>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating form-floating-outline form-sales"
                                {{ @$prospect->provide == '1' ? 'disabled' : 'hidden' }}>
                                <select class="form-select" id="selectSales" aria-label="Default select example"
                                    name="sales" {{ @$prospect->id_sales ? 'disabled' : '' }}>
                                    <option disabled="">----- Choose Sales -----</option>
                                    @foreach ($sales as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <label for="selectSales">Sales</label>
                            </div>
                        </div>
                        <div class="card-footer float-end" {{ @$prospect->provide == '1' ? 'hidden' : '' }}>
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="col-xl-3 col-md-4 col-12 invoice-actions">
                <div class="card">
                    <div class="card-body">
                        <a href="#" class="btn btn-outline-danger d-grid w-100 waves-effect delete-prospect"
                            data-id="{{ $prospect->id }}">
                            Delete
                        </a>
                    </div>
                </div>
            </div>
        @endif
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
    <script src="{{ asset('assets') }}/js/form-layouts.js"></script>
@endpush
@push('script')
    <script>
        $(document).on('click', '.delete-prospect', function() {
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
                        'url': '{{ url('prospect') }}/' + id,
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
                                    window.location.href = '/prospect';
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
        });
        $(document).on('change', '.check-provide', function() {
            if ($(this).is(':checked')) {
                $('.form-sales').removeAttr('hidden');
                // $('.card-footer').removeAttr('hidden');
            } else {
                $('.form-sales').attr('hidden', 'hidden');
                // $('.card-footer').attr('hidden', 'hidden');
            }
        });
        $(document).on('change', '.check-no-provide', function() {
            if ($(this).is(':checked')) {
                $('.form-sales').attr('hidden', true);
                // $('.card-footer').attr('hidden', true);
            } else {
                $('.form-sales').removeAttr('hidden');
                // $('.card-footer').removeAttr('hidden');
            }
        });
    </script>
@endpush
