@extends('layouts.sales.app')
@section('title', 'My Dashboard')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4 mb-4">
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="card-body">
                                <div class="card-info mb-3 pb-2">
                                    <h5 class="mb-3 text-nowrap">Mr Yusuf</h5>
                                    <div class="badge bg-label-primary rounded-pill lh-xs">Year of 2021</div>
                                </div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 me-2">8.14k</h4>
                                    <small class="text-success">+15.6%</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end d-flex align-items-end">
                            <div class="card-body pb-0 pt-3">
                                <img src="{{ asset('assets') }}/img/illustrations/faq-illustration.png" alt="Ratings"
                                    class="img-fluid" width="95">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="card-body">
                                <div class="card-info mb-3 pb-2">
                                    <h5 class="mb-3 text-nowrap">Ms Regita</h5>
                                    <div class="badge bg-label-success rounded-pill lh-xs">Last Month</div>
                                </div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 me-2">12.2k</h4>
                                    <small class="text-danger">-25.5%</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end d-flex align-items-end">
                            <div class="card-body pb-0 pt-3">
                                <img src="{{ asset('assets') }}/img/illustrations/card-session-illustration.png"
                                    alt="Ratings" class="img-fluid" width="81">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="card-body">
                                <div class="card-info mb-3 pb-2">
                                    <h5 class="mb-3 text-nowrap">Mr Ari</h5>
                                    <div class="badge bg-label-warning rounded-pill lh-xs">Daily Customers</div>
                                </div>
                                <div class="d-flex align-items-end d-flex align-items-end">
                                    <h4 class="mb-0 me-2">42.4k</h4>
                                    <small class="text-success">+9.2%</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end d-flex align-items-end">
                            <div class="card-body pb-0 pt-3">
                                <img src="{{ asset('assets') }}/img/illustrations/card-customers-illustration.png"
                                    alt="Ratings" class="img-fluid" width="84">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="card-body">
                                <div class="card-info mb-3 pb-2">
                                    <h5 class="mb-3 text-nowrap">Ms Yolan</h5>
                                    <div class="badge bg-label-secondary rounded-pill lh-xs">Last Week</div>
                                </div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 me-2">42.5k</h4>
                                    <small class="text-success">+10.8%</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-end d-flex align-items-end">
                            <div class="card-body pb-0 pt-3">
                                <img src="{{ asset('assets') }}/img/illustrations/card-orders-illustration.png"
                                    alt="Ratings" class="img-fluid" width="78">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            <!-- Weekly Overview Chart -->
            <div class="col-12 col-md-6 order-md-2 order-lg-0">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">Ms Regitas's Monthly Overview </h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="weeklyOverviewDropdown" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="weeklyOverviewDropdown">
                                    <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Total $42,580 Sales</p>
                    </div>
                    <div class="card-body">
                        <div id="monthlyOverviewChartRegita"></div>
                        <div class="mt-1">
                            <div class="d-grid mt-3">
                                <button class="btn btn-primary" type="button">Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Weekly Overview Chart -->
            <!-- Weekly Overview Chart -->
            <div class="col-12 col-md-6 order-md-2 order-lg-0">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">Ms Yolan's Weekly Overview</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="weeklyOverviewDropdown"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="weeklyOverviewDropdown">
                                    <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Total $42,580 Sales</p>
                    </div>
                    <div class="card-body">
                        <div id="monthlyOverviewChartYolan"></div>
                        <div class="mt-1">
                            <div class="d-grid mt-3">
                                <button class="btn btn-primary" type="button">Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Weekly Overview Chart -->
            <!-- Weekly Overview Chart -->
            <div class="col-12 col-md-6 order-md-2 order-lg-0">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">Mr Yusuf's Weekly Overview</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="weeklyOverviewDropdown"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="weeklyOverviewDropdown">
                                    <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Total $42,580 Sales</p>
                    </div>
                    <div class="card-body">
                        <div id="monthlyOverviewChartYusuf"></div>
                        <div class="mt-1">
                            <div class="d-grid mt-3">
                                <button class="btn btn-primary" type="button">Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Weekly Overview Chart -->
            <!-- Weekly Overview Chart -->
            <div class="col-12 col-md-6 order-md-2 order-lg-0">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">Mr Ari's Weekly Overview</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="weeklyOverviewDropdown"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="weeklyOverviewDropdown">
                                    <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                </div>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Total $42,580 Sales</p>
                    </div>
                    <div class="card-body">
                        <div id="monthlyOverviewChartAri"></div>
                        <div class="mt-1">
                            <div class="d-grid mt-3">
                                <button class="btn btn-primary" type="button">Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Weekly Overview Chart -->
        </div>
    </div>
@endsection
@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/cards-statistics.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/cards-analytics.css" />
@endpush

@push('after-script')
    <!-- Vendors JS -->
    <script src="{{ asset('assets') }}/vendor/libs/apex-charts/apexcharts.js"></script>
@endpush
@push('page-script')
    <!-- Page JS -->
    <script src="{{ asset('assets') }}/js/dashboards-crm.js"></script>
    <script src="{{ asset('assets') }}/includes/chart/card-monthly.js"></script>
@endpush
