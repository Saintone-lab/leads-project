@extends('layouts.sales.app')
@section('title', 'Sales Order')
@section('no-container') @endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center py-3 mb-4 gap-2">
            <h4 class="fw-bold m-0">
                <span class="text-muted fw-normal">Sales /</span> Sales Order (Spare Parts)
            </h4>
            <div class="d-flex align-items-center">
                <form action="{{ route('pending-po.sales-order') }}" method="GET" class="d-flex align-items-center">
                    <label for="filter-year" class="me-2 fw-semibold text-muted text-nowrap">Tahun:</label>
                    <select name="year" id="filter-year" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width: 130px;">
                        <option value="all" {{ $selectedYear == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                        @foreach($availableYears as $yr)
                            <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- KPI Cards Grid -->
        <div class="row gy-4 mb-4">
            <!-- Total Orders Card -->
            <div class="col-sm-6 col-lg-3">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="mdi mdi-cart-outline mdi-24px"></i>
                                </span>
                            </div>
                            <h4 class="ms-1 mb-0 fw-bold text-primary">{{ $totalOrdersCount }}</h4>
                        </div>
                        <p class="mb-0 text-primary-900 fw-semibold">Total Sales Order</p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="col-sm-6 col-lg-3">
                <div class="card card-border-shadow-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="mdi mdi-currency-usd mdi-24px"></i>
                                </span>
                            </div>
                            <h5 class="ms-1 mb-0 text-success fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
                        </div>
                        <p class="mb-0 text-primary-900 fw-semibold">Total Revenue (Quotation)</p>
                    </div>
                </div>
            </div>

            <!-- Total Cost Card -->
            <div class="col-sm-6 col-lg-3">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class="mdi mdi-bank-minus mdi-24px"></i>
                                </span>
                            </div>
                            <h5 class="ms-1 mb-0 text-danger fw-bold">Rp {{ number_format($totalCost, 0, ',', '.') }}</h5>
                        </div>
                        <p class="mb-0 text-primary-900 fw-semibold">Purchase & Delivery Cost</p>
                    </div>
                </div>
            </div>

            <!-- Net Profit Card -->
            <div class="col-sm-6 col-lg-3">
                <div class="card card-border-shadow-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-info text-white">
                                    <i class="mdi mdi-trending-up mdi-24px"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <h5 class="ms-1 mb-0 text-primary fw-bold">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h5>
                                <small class="ms-1 text-muted fw-bold">Margin: {{ number_format($overallMargin, 1) }}%</small>
                            </div>
                        </div>
                        <p class="mb-0 text-primary-900 fw-semibold">Net Profit</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Orders Tabs Card -->
        <div class="card">
            <div class="card-header p-0">
                <div class="nav-align-top">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab-new" aria-selected="true">
                                New
                                <span class="badge rounded-pill bg-danger ms-1">{{ $newOrders->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-check">
                                Check Parts
                                <span class="badge rounded-pill bg-warning ms-1">{{ $checkPartsOrders->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-delivery">
                                Delivery Process
                                <span class="badge rounded-pill bg-info ms-1">{{ $deliveryOrders->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-completed">
                                Selesai
                                <span class="badge rounded-pill bg-success ms-1">{{ $completedOrders->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-delayed">
                                Delayed
                                <span class="badge rounded-pill bg-danger ms-1">{{ $delayedOrders->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-return">
                                Return
                                <span class="badge rounded-pill bg-warning ms-1">{{ $returnOrders->count() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content p-0 border-0 shadow-none">
                    <!-- Tab New -->
                    <div class="tab-pane fade show active" id="tab-new" role="tabpanel">
                        @include('pages.sorder._table', ['orderList' => $newOrders, 'tableId' => 'table-new'])
                    </div>
                    <!-- Tab Check Parts -->
                    <div class="tab-pane fade" id="tab-check" role="tabpanel">
                        @include('pages.sorder._table', ['orderList' => $checkPartsOrders, 'tableId' => 'table-check'])
                    </div>
                    <!-- Tab Delivery Process -->
                    <div class="tab-pane fade" id="tab-delivery" role="tabpanel">
                        @include('pages.sorder._table', ['orderList' => $deliveryOrders, 'tableId' => 'table-delivery'])
                    </div>
                    <!-- Tab Completed -->
                    <div class="tab-pane fade" id="tab-completed" role="tabpanel">
                        @include('pages.sorder._table', ['orderList' => $completedOrders, 'tableId' => 'table-completed'])
                    </div>
                    <!-- Tab Delayed -->
                    <div class="tab-pane fade" id="tab-delayed" role="tabpanel">
                        @include('pages.sorder._table', ['orderList' => $delayedOrders, 'tableId' => 'table-delayed'])
                    </div>
                    <!-- Tab Return -->
                    <div class="tab-pane fade" id="tab-return" role="tabpanel">
                        @include('pages.sorder._table', ['orderList' => $returnOrders, 'tableId' => 'table-return'])
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($orders as $order)
        @include('components.modal.pending.jadwal.schedule')
    @endforeach
    @foreach ($schedules as $schedule)
        @include('components.modal.pending.jadwal.reschedule')
        @include('components.modal.pending.jadwal.dokumentasi')
    @endforeach
@endsection()

@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
@endpush

@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
@endpush

@push('page-script')
    <script src="{{ asset('assets') }}/js/forms-selects.js"></script>
    <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('.datatable-sorder').each(function() {
                var $table = $(this);

                // Clone header for search row
                $table.find('thead tr')
                    .clone(true)
                    .appendTo($table.find('thead'));

                var table = $table.DataTable({
                    orderCellsTop: true,
                    order: [[2, 'desc']], // Sort by Date descending
                    pageLength: 10,
                    language: {
                        search: "Cari Sales Order:",
                        lengthMenu: "Tampilkan _MENU_",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ sales order",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Berikutnya",
                            previous: "Sebelumnya"
                        }
                    }
                });

                // Replace cloned headers with input fields
                $table.find('thead tr:eq(1) th').each(function(i) {
                    var title = $(this).text();
                    if (i === 6) { // Skip Sales avatar column
                        $(this).html('');
                        return;
                    }
                    $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Cari ' + title + '..." />');

                    $('input', this).on('keyup change', function() {
                        if (table.column(i).search() !== this.value) {
                            table.column(i).search(this.value).draw();
                        }
                    });
                });
            });
        });
    </script>
@endpush
