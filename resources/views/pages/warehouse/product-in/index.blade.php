@extends('layouts.sales.app')
@section('title', 'Product In')
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        Product In
    </h4>
    @if (Auth::user()->role == 'Admin')
        <div class="card mb-3">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-product-in-req table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>No DO</th>
                            <th>Date</th>
                            <th>Total Qty</th>
                            @if (Auth::user()->role != 'Logistic')
                                <th></th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    @endif
    @if (in_array(Auth::user()->id, [5, 7, 18, 19, 20, 21]))
        <div class="card mb-3">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-product-in-req-logistic table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>No DO</th>
                            <th>Date</th>
                            <th>Total Qty</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    @endif
    <div class="card mb-3">
        <div class="card-datatable table-responsive pt-0">
            <table
                class="datatable-product-{{ Auth::user()->role == 'Logistic' ? 'in-logistic' : 'in' }} table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>ID</th>
                        <th>Invoice</th>
                        <th>Supplier</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @if (Auth::user()->role != 'Logistic')
        <div class="card mb-3">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-product-in-no-tax table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>Invoice</th>
                            <th>Supplier</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    @endif
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
    <script src="{{ asset('assets') }}/includes/table-product-in.js"></script>
    <script src="{{ asset('assets') }}/includes/table-product-in-req.js"></script>
    <script src="{{ asset('assets') }}/includes/table-product-in-req-logistic.js"></script>
    <script src="{{ asset('assets') }}/includes/table-product-in-no-tax.js"></script>
    <script src="{{ asset('assets') }}/includes/table-product-in-logistic.js"></script>
@endpush
