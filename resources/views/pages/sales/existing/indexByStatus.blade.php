@extends('layouts.sales.app')
@section('title', 'My Leads')
@section('content')

    @if (Session::has('message'))
        <div class="bs-toast toast toast-placement-ex m-2 fade top-0 end-0 hide" role="alert" aria-live="assertive"
            aria-atomic="true" data-bs-delay="2000">
            <div class="toast-header">
                <i class="mdi mdi-home me-2 text-success"></i>
                <div class="me-auto fw-semibold">Successfully</div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">{{ Session::get('message') }}</div>
        </div>
    @endif

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Clients /</span> Customers
    </h4>

    <div class="d-flex align-items-center justify-content-end mb-3 gap-2">
        <label class="form-label mb-0 text-muted" style="white-space:nowrap;">Filter Tipe:</label>
        <select class="form-select form-select-sm" id="ru-type-filter" style="max-width:180px;">
            <option value="">Semua Tipe</option>
            <option value="User">User</option>
            <option value="Reseller">Reseller</option>
        </select>

        @if (in_array(Auth::user()->role, ['Admin', 'Sales Manager', 'Accounting', 'ServiceM', 'Finance Manager']))
        <label class="form-label mb-0 text-muted ms-2" style="white-space:nowrap;">Filter Sales:</label>
        <select class="form-select form-select-sm" id="admin-sales-filter" style="max-width:220px;">
            <option value="">Semua Sales</option>
            @foreach ($sales as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>
        @endif
    </div>

    <div class="card">
        <div class="card-header py-2">
            <ul class="nav nav-tabs card-header-tabs border-0 m-0" id="crm-tab-nav" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-active" type="button">
                        <i class="mdi mdi-check-circle-outline me-1"></i>Active
                        <span class="badge rounded-pill bg-success ms-1" id="badge-active">-</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-non-active" type="button">
                        <i class="mdi mdi-close-circle-outline me-1"></i>Non Active
                        <span class="badge rounded-pill bg-warning ms-1" id="badge-non-active">-</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-bangkrupt" type="button">
                        <i class="mdi mdi-alert-circle-outline me-1"></i>Bangkrupt
                        <span class="badge rounded-pill bg-danger ms-1" id="badge-bangkrupt">-</span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="tab-content border-0 p-0 m-0">
                {{-- Tab 1: Active --}}
                <div class="tab-pane fade show active" id="tab-active">
                    <div class="table-responsive">
                        <table class="datatable-customers-active table table-bordered" data-badge="badge-active">
                            <thead>
                                <tr>
                                    <th class="text-center">Company</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Area</th>
                                    <th class="text-center">Last Contact</th>
                                    <th class="text-center">Next FU</th>
                                    <th class="text-center">Flag</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                {{-- Tab 2: Non Active --}}
                <div class="tab-pane fade" id="tab-non-active">
                    <div class="table-responsive">
                        <table class="datatable-customers-non-active table table-bordered" data-badge="badge-non-active">
                            <thead>
                                <tr>
                                    <th class="text-center">Company</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Area</th>
                                    <th class="text-center">Last Contact</th>
                                    <th class="text-center">Next FU</th>
                                    <th class="text-center">Flag</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                {{-- Tab 3: Bangkrupt --}}
                <div class="tab-pane fade" id="tab-bangkrupt">
                    <div class="table-responsive">
                        <table class="datatable-customers-bangkrupt table table-bordered" data-badge="badge-bangkrupt">
                            <thead>
                                <tr>
                                    <th class="text-center">Company</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Area</th>
                                    <th class="text-center">Last Contact</th>
                                    <th class="text-center">Next FU</th>
                                    <th class="text-center">Flag</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
@endpush

@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
@endpush

@push('page-script')
    <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
    <script src="{{ asset('assets') }}/js/tables-datatables-advanced.js"></script>
    <script src="{{ asset('assets') }}/includes/table-customer-by-status.js"></script>
@endpush

@push('script')
    <script>
        // Initialize Bootstrap tooltips using jQuery
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

            $(document).on('change', '.status-dropdown', function() {
                var selectedValue = $(this).val();
                var rowId = $(this).data('id');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                console.log('id = ' + rowId);

                $.ajax({
                    type: 'POST',
                    url: '/existing/update-status/' + rowId,
                    data: {
                        status: selectedValue,
                        _token: csrfToken
                    },
                    success: function(response) {
                        console.log('Perubahan status berhasil dikirim ke server');
                        if (window.dtCustomerActive) window.dtCustomerActive.ajax.reload();
                        if (window.dtCustomerNonActive) window.dtCustomerNonActive.ajax.reload();
                        if (window.dtCustomerBangkrupt) window.dtCustomerBangkrupt.ajax.reload();
                    },
                    error: function(error) {
                        console.error('Gagal mengirim permintaan ke server:', error);
                    }
                });
            });
        });

        $(document).on('click', '.delete-data-leads', function() {
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
                        'url': '{{ url('leads') }}/' + id,
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
    </script>
@endpush
