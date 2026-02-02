@extends('layouts.sales.app')
@section('title', 'Product In')
@section('content')
    <div class="row invoice-preview">
        {{-- Invoice --}}
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                        <div class="mb-xl-0 pb-1">
                            <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                                <span class="app-brand-logo demo">
                                    <span style="color: var(--bs-primary)">
                                        <img class="text-md" src="{{ asset('/asset') }}/logo/Reftech-Log.png" alt=""
                                            srcset="" width="60%">
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <h3 class="fw-bold">Stock Opname</h3>
                            <div>
                                <span class="fw-bolder mb-1">#PERIODE CATURWULAN - {{ $opname->periode }}</span>
                            </div>
                            <span class="fw-medium">Petugas Gudang - {{ $opname->user->name }}</span>
                            <div class="mt-1">
                                <span class="text-muted">{{ Carbon\Carbon::parse($opname->date)->format('d-m-Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-datatable table-responsive pt-0">
                    <table class="datatable-opname table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>id</th>
                                <th>Product</th>
                                <th>Stock Web</th>
                                <th>Stock Gudang</th>
                                <th>Selisih</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        {{-- End: Invoice --}}
        {{-- Button Invocie --}}
        <div class="col-xl-3 col-md-4 col-12 invoice-actions">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-primary btn-outline-secondary d-grid w-100 mb-3 waves-effect" target="_blank"
                        href="{{ route('opname.show_print', $opname->id) }}">
                        Download
                    </a>
                    <a href="#" class="btn btn-danger d-grid w-100 waves-effect delete"
                        data-id="{{ $opname->id }}">Delete</a>
                </div>
            </div>
        </div>
        {{-- End : Button Invoice --}}
    </div>
    @include('components.modal.warehouse.opname.form-product')

@endsection
@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
@endpush
@push('page-script')
    <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
    <script src="{{ asset('assets') }}/js/tables-datatables-advanced.js"></script>
    <script src="{{ asset('assets') }}/js/forms-selects.js"></script>
    <script src="{{ asset('assets') }}/includes/table-opname-stock.js"></script>
@endpush
@push('script')
    <script>
        $(document).ready(function() {

            // Saat replacement dipilih
            $('#replacement').on('change', function() {
                let replacementId = $(this).val();

                // Reset jika kosong
                if (!replacementId) {
                    $('#sistem').val('');
                    $('#gudang').val('');
                    $('#selisih').val('');
                    return;
                }

                // Ambil stock sistem via AJAX
                $.ajax({
                    url: '/stock/replacement/' + replacementId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        let stockSistem = res.stock_sistem ?? 0;

                        $('#sistem').val(stockSistem);
                        hitungSelisih();
                    },
                    error: function() {
                        alert('Gagal mengambil stock sistem');
                    }
                });
            });

            // Saat stock gudang diinput
            $('#gudang').on('keyup change', function() {
                hitungSelisih();
            });

            // Function hitung selisih
            function hitungSelisih() {
                let sistem = parseInt($('#sistem').val()) || 0;
                let gudang = parseInt($('#gudang').val()) || 0;
                let selisih = sistem - gudang;

                $('#selisih').val(selisih);
            }

        });
    </script>
@endpush
