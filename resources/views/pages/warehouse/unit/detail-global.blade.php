@extends('layouts.sales.app')
@section('title', 'Data Unit')
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Units /</span> {{ $product->sku }}
    </h4>
    <div class="row mb-3">
        <div class="col-12 mb-4">
            <div class="card">
                @if (auth::user()->role == 'Admin' || auth::user()->role == 'Logistic')
                    <div class="card-header pb-0">
                        <div class="text-end text-muted">
                            <a type="button" data-bs-toggle="modal" data-bs-target="#updateStock-{{ $product->id }}">
                                <button type="button" class="btn btn-sm btn-label-success">Edit Stock</button>
                            </a>
                            <a type="button" data-bs-toggle="modal" data-bs-target="#updateProduct-{{ $product->id }}">
                                <button type="button" class="btn btn-sm btn-label-primary">Edit</button>
                            </a>
                            <a href="#" data-id="{{ $product->id }}"
                                class="btn btn-sm btn-label-danger delete-product">Delete
                            </a>
                        </div>
                    </div>
                @endif
                <div class="card-body">
                    <p class="card-text">
                    <div class="row mb-1">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-3">
                                    SKU
                                </div>
                                <div class="col-9">
                                    : {{ $product->sku }}
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-3">
                                    Short Description
                                </div>
                                <div class="col-9">
                                    : {{ $product->desc }}
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-3">
                                    Voltage
                                </div>
                                <div class="col-9">
                                    : {{ $product->voltage }}
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-3">
                                    Bar
                                </div>
                                <div class="col-9">
                                    : {{ $product->bar }}
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-3">
                                    Stock Awal
                                </div>
                                <div class="col-9">
                                    : {{ $product->first_stock }}
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-3">
                                    Warehouse Stock
                                </div>
                                <div class="col-9">
                                    : {{ $product->warehouse_stock }}
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-3">
                                    Office Stock
                                </div>
                                <div class="col-9">
                                    : {{ $product->stock }}
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-3">
                                    All Stock
                                </div>
                                <div class="col-9">
                                    : {{ $allStock }}
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-3">
                                    Note
                                </div>
                                <div class="col-9">
                                    <pre class="mb-1"
                                        style="font-family: 'Inter', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">: {{ $product->note }}
                                </pre>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Status
                                    </div>
                                    <div class="col-9">
                                        : {{ $product->status }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Unit
                                    </div>
                                    <div class="col-9">
                                        : {{ $product->unit }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Power
                                    </div>
                                    <div class="col-9">
                                        : {{ $product->power }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Air Capacity
                                    </div>
                                    <div class="col-9">
                                        : {{ $product->air_cap }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Connection
                                    </div>
                                    <div class="col-9">
                                        : {{ $product->connect }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Dimension
                                    </div>
                                    <div class="col-9">
                                        : {{ $product->dimension }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Weight
                                    </div>
                                    <div class="col-9">
                                        : {{ $product->weight }} Kg
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </p>
                </div>
            </div>
        </div>
        @if (auth::user()->role == 'Admin' || auth::user()->role == 'Logistic')
            <div class="row">
                <div class="col-md-6 col-12 ">
                    <div class="d-flex justify-content-between mb-2">
                        <h5 class="fw-bold pb-1 mb-2">
                            Sparepart Consumable Part
                        </h5>
                        <a type="button" data-bs-toggle="modal" data-bs-target="#createSparepart">
                            <button type="button" class="btn btn-primary">
                                + New Sparepart
                            </button>
                        </a>
                    </div>
                    <div class="card mb-4">
                        <div class="table-responsive text-nowrap h-100">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>PN</th>
                                        <th>Desc</th>
                                        <th>Quantity</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse ($consumable as $part)
                                        @php
                                            $allStock = $part->warehouse_stock + $part->stock;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $part->pn }}
                                            </td>
                                            <td>
                                                {{ $part->description }}
                                            </td>
                                            <td>
                                                {{ $part->qty }} {{ $part->equivalent->product->unit ?? 'Pcs' }}
                                            </td>
                                            <td>
                                                {{ $allStock }}
                                            </td>
                                            <td>
                                                <a href="#" data-id="{{ $part->id }}"
                                                    class="btn btn-sm btn-label-danger delete-sparepart">
                                                    <i class="menu-icon tf-icons mdi mdi-14px mdi-delete-outline"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                Kamu belum punya Consumable Part.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <h5 class="fw-bold pb-1 mb-2">
                        Sparepart Non Consumable Part
                    </h5>
                    <div class="card">
                        <div class="table-responsive text-nowrap h-100">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>PN</th>
                                        <th>Desc</th>
                                        <th>Quantity</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse ($nonconsumable as $part)
                                        @php
                                            $allStock = $part->warehouse_stock + $part->stock;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $part->pn }}
                                            </td>
                                            <td>
                                                {{ $part->description }}
                                            </td>
                                            <td>
                                                {{ $part->qty }} {{ $part->equivalent->product->unit ?? 'Pcs' }}
                                            </td>
                                            <td>
                                                {{ $allStock }}
                                            </td>
                                            <td>
                                                <a href="#" data-id="{{ $part->id }}"
                                                    class="btn btn-sm btn-label-danger delete-sparepart">
                                                    <i class="menu-icon tf-icons mdi mdi-14px mdi-delete-outline"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                Kamu belum punya Consumable Part.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 flex-1 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <h5 class="fw-bold pb-1 mb-2">
                            Equivalent
                        </h5>
                        <a type="button" data-bs-toggle="modal" data-bs-target="#createEquivalent-{{ $product->id }}">
                            <button type="button" class="btn btn-primary">
                                + New Equivalent
                            </button>
                        </a>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="datatable-product-equivalent table table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Brand</th>
                                        <th>PN</th>
                                        <th>Bar</th>
                                        <th>Air Capacity</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-6 col-12 ">
                    <div class="d-flex justify-content-between mb-2">
                        <h5 class="fw-bold pb-1 mb-2">
                            Sparepart Consumable Part
                        </h5>
                    </div>
                    <div class="card mb-4">
                        <div class="table-responsive text-nowrap h-100">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>PN</th>
                                        <th>Desc</th>
                                        <th>Quantity</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse ($consumable as $part)
                                        @php
                                            $allStock = $part->warehouse_stock + $part->stock;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $part->pn }}
                                            </td>
                                            <td>
                                                {{ $part->description }}
                                            </td>
                                            <td>
                                                {{ $part->qty }} {{ $part->info_qty }}
                                            </td>
                                            <td>
                                                {{ $allStock }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                Kamu belum punya Consumable Part.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="d-flex justify-content-between mb-2">
                        <h5 class="fw-bold pb-1 mb-2">
                            Sparepart Non Consumable Part
                        </h5>
                    </div>
                    <div class="card">
                        <div class="table-responsive text-nowrap h-100">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>PN</th>
                                        <th>Desc</th>
                                        <th>Quantity</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @forelse ($nonconsumable as $part)
                                        @php
                                            $allStock = $part->warehouse_stock + $part->stock;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $part->pn }}
                                            </td>
                                            <td>
                                                {{ $part->description }}
                                            </td>
                                            <td>
                                                {{ $part->qty }} {{ $part->info_qty }}
                                            </td>
                                            <td>
                                                {{ $allStock }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                Kamu belum punya Consumable Part.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- <div class="row">
            <div class="col-12 col-lg-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <table class="datatable-product-in-detail table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>ID</th>
                                    <th>invoice</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <table class="datatable-product-out-detail table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>ID</th>
                                    <th>invoice</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="datatable-product-quotation table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>ID</th>
                                    <th>no quote</th>
                                    <th>equivalent</th>
                                    <th>Qty</th>
                                    <th>price</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    @include('components.modal.warehouse.unit.form-global')
    @include('components.modal.warehouse.unit.stock')
    @include('components.modal.warehouse.unit.sparepart')
    @include('components.modal.warehouse.replacement.form')
    @include('components.modal.warehouse.equivalent.form-global')
    @php
        $no = 0;
    @endphp
    @foreach ($serials as $serial)
        @include('components.modal.warehouse.equivalent.form-global')
        @php
            $no++;
        @endphp
    @endforeach
    @foreach ($details as $detail)
        @include('components.modal.warehouse.replacement.form-price')
    @endforeach
@endsection()
@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/tagify/tagify.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/bloodhound/bloodhound.js"></script>
@endpush
@push('page-script')
    <script src="{{ asset('assets') }}/js/tables-datatables-basic.js"></script>
    <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
    <script src="{{ asset('assets') }}/includes/table-equivalent-global.js"></script>
    <script src="{{ asset('assets') }}/includes/table-product-in-detail.js"></script>
    <script src="{{ asset('assets') }}/includes/table-product-out-detail.js"></script>
    <script src="{{ asset('assets') }}/includes/table-quotation-product.js"></script>
    <script src="{{ asset('assets') }}/js/forms-selects.js"></script>
@endpush
@push('script')
    <script>
        $(document).on('click', '.delete-product', function() {
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
                        'url': '{{ url('unit') }}/' + id,
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
                                    window.location.href = '/unit-global';
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
        $(document).on('click', '.delete-replacement', function() {
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
                        'url': '{{ url('product') }}/replacement/' + id,
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
        });
        $(document).on('click', '.delete-equivalent', function() {
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
                        'url': '{{ url('product') }}/equivalent/' + id,
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
        });
        $(document).on('click', '.delete-sparepart', function() {
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
                        'url': '{{ url('delete') }}/sparepart/' + id,
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
        });
        $(() => {

            function formatNumber(n) {
                return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }


            $(".invoice-item-price-label").on('keyup', function() {
                var input = $(this)
                var id = input.data('id');
                var input_val = input.val();

                // original length
                var original_len = input_val.length;

                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = input_val;

                // send updated string to input
                input.val(input_val);
                var nomorInt = parseFloat(input_val.replace(/[.,]/g, ''));
                console.log(id);
                console.log(nomorInt);
                $(`#price-${id}`).val(nomorInt);
            });
            $(".invoice-item-modal-label").on('keyup', function() {
                var input = $(this)
                var id = input.data('id');
                var input_val = input.val();

                // original length
                var original_len = input_val.length;

                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = input_val;

                // send updated string to input
                input.val(input_val);
                var nomorInt = parseFloat(input_val.replace(/[.,]/g, ''));
                console.log(id);
                console.log(nomorInt);
                $(`#modal-${id}`).val(nomorInt);
            });
        });
    </script>
@endpush
