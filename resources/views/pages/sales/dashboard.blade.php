@extends('layouts.sales.app')
@section('title', 'My Dashboard')
@section('content')
    @if (Auth::user()->role == 'Sales')
        <div class="row gy-4 mb-4">
            @if (Auth::user()->detail[0]->area == 'Bekasi' ||
                    Auth::user()->detail[0]->area == 'Jabodetabek' ||
                    Auth::user()->detail[0]->area == 'Jawa Barat')
                <!-- Congratulations card -->
                <div class="col-xl-4 col-lg-4 col-12">
                    <div class="card h-100">
                        <div class="card-body text-nowrap">
                            <h4 class="card-title mb-1 d-flex gap-2 flex-wrap">
                                Congratulations <strong>{{ Auth::user()->name }}</strong> 🎉
                            </h4>
                            <p class="pb-0">Best seller of the month</p>
                            <h4 class="text-primary mb-1">Rp. {{ $formattedTotalPrice }}</h4>
                            @php
                                $jumlah_target = 0;
                                $jumlah_target = ($poTotalPrice / $target->total) * 100;
                                $formatted_jumlah_target = number_format($jumlah_target, 3);
                            @endphp
                            <p class="mb-2 pb-1">{{ $formatted_jumlah_target }}% of target 🚀</p>
                            <a href="javascript:;" class="btn btn-sm btn-primary waves-effect waves-light">View Sales</a>
                        </div>
                        <img src="{{ asset('assets') }}/img/illustrations/trophy.png"
                            class="position-absolute bottom-0 end-0 me-3" height="140" alt="view sales">
                    </div>
                </div>
                <!-- Total Leads -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-info rounded">
                                        <i class="mdi mdi-phone-outline mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">{{ $dailyCall }} <small class="text-muted fs-tiny">/
                                        @php
                                            if (is_array($weekPerMonth)) {
                                                $jumlahData = count($weekPerMonth);
                                            }
                                        @endphp
                                        @if ($jumlahData > 4)
                                            {{ round($target->dc + $target->dc / 4) }}
                                        @elseif($jumlahData == 4)
                                            {{ round($target->dc) }}
                                        @endif
                                    </small></h4>
                                <div class="badge bg-label-secondary rounded-pill">Daily Call</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Total Leads -->
                <!-- Total Expenses -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-account-multiple-outline mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">{{ $customers }} <small class="text-muted fs-tiny">/
                                        @if ($jumlahData > 4)
                                            {{ round($target->crm + $target->crm / 4) }}
                                        @elseif($jumlahData == 4)
                                            {{ round($target->crm) }}
                                        @endif
                                    </small>
                                </h4>
                                <div class="badge bg-label-secondary rounded-pill">CRM Existing</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Total Expenses -->
                <!-- Total Expenses -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-danger rounded">
                                        <i class="mdi mdi-office-building-marker-outline mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">{{ $visit }} <small class="text-muted fs-tiny">/
                                        @if ($jumlahData > 4)
                                            {{ round($target->visit + $target->visit / 4) }}
                                        @elseif($jumlahData == 4)
                                            {{ round($target->visit) }}
                                        @endif
                                    </small>
                                </h4>
                                <div class="badge bg-label-secondary rounded-pill">Visit</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Total Expenses -->
                <!-- Total Profit chart -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-warning rounded">
                                        <i class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">{{ $quotation->count() }} <small class="text-muted fs-tiny">/
                                        @if ($jumlahData > 4)
                                            {{ round($target->quote + $target->quote / 4) }}
                                        @elseif($jumlahData == 4)
                                            {{ round($target->quote) }}
                                        @endif
                                    </small>
                                </h4>
                                <div class="badge bg-label-secondary rounded-pill">Quotation</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Total Profit chart -->
                <!-- Total Growth chart -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-success rounded">
                                        <i class="mdi mdi-cart-plus mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">{{ $po->count() }} <small class="text-muted fs-tiny">/
                                        @if ($jumlahData > 4)
                                            {{ round($target->po + $target->po / 4) }}
                                        @elseif($jumlahData == 4)
                                            {{ round($target->po) }}
                                        @endif
                                    </small>
                                </h4>
                                <div class="badge bg-label-secondary rounded-pill">Pruchase Order</div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-xl-8 col-lg-8 col-12">
                    <div class="row">
                        <!--/ Congratulations card -->
                        <!-- Total Leads -->
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                        <div class="avatar">
                                            <div class="avatar-initial bg-label-info rounded">
                                                <i class="mdi mdi-phone-outline mdi-24px"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-info mt-4 pt-1">
                                        <h4 class="mb-2">{{ $dailyCall }} <small class="text-muted fs-tiny">/
                                                @php
                                                    if (is_array($weekPerMonth)) {
                                                        $jumlahData = count($weekPerMonth);
                                                    }
                                                @endphp
                                                @if ($jumlahData > 4)
                                                    {{ round($target->dc + $target->dc / 4) }}
                                                @elseif($jumlahData == 4)
                                                    {{ round($target->dc) }}
                                                @endif
                                            </small></h4>
                                        <div class="badge bg-label-secondary rounded-pill">Daily Call</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Total Leads -->
                        <!-- Total Expenses -->
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                        <div class="avatar">
                                            <div class="avatar-initial bg-label-primary rounded">
                                                <i class="mdi mdi-account-multiple-outline mdi-24px"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-info mt-4 pt-1">
                                        <h4 class="mb-2">{{ $customers }} <small class="text-muted fs-tiny">/
                                                @if ($jumlahData > 4)
                                                    {{ round($target->crm + $target->crm / 4) }}
                                                @elseif($jumlahData == 4)
                                                    {{ round($target->crm) }}
                                                @endif
                                            </small>
                                        </h4>
                                        <div class="badge bg-label-secondary rounded-pill">CRM Existing</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Total Expenses -->
                        <!-- Total Expenses -->
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                        <div class="avatar">
                                            <div class="avatar-initial bg-label-danger rounded">
                                                <i class="mdi mdi-office-building-marker-outline mdi-24px"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-info mt-4 pt-1">
                                        <h4 class="mb-2">{{ $visit }} <small class="text-muted fs-tiny">/
                                                @if ($jumlahData > 4)
                                                    {{ round($target->visit + $target->visit / 4) }}
                                                @elseif($jumlahData == 4)
                                                    {{ round($target->visit) }}
                                                @endif
                                            </small>
                                        </h4>
                                        <div class="badge bg-label-secondary rounded-pill">Visit</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Total Expenses -->
                        <!-- Total Profit chart -->
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                        <div class="avatar">
                                            <div class="avatar-initial bg-label-warning rounded">
                                                <i class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-info mt-4 pt-1">
                                        <h4 class="mb-2">{{ $quotation->count() }} <small class="text-muted fs-tiny">/
                                                @if ($jumlahData > 4)
                                                    {{ round($target->quote + $target->quote / 4) }}
                                                @elseif($jumlahData == 4)
                                                    {{ round($target->quote) }}
                                                @endif
                                            </small>
                                        </h4>
                                        <div class="badge bg-label-secondary rounded-pill">Quotation</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Total Profit chart -->
                        <!-- Total Growth chart -->
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                        <div class="avatar">
                                            <div class="avatar-initial bg-label-success rounded">
                                                <i class="mdi mdi-cart-plus mdi-24px"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-info mt-4 pt-1">
                                        <h4 class="mb-2">{{ $po->count() }} <small class="text-muted fs-tiny">/
                                                @if ($jumlahData > 4)
                                                    {{ round($target->po + $target->po / 4) }}
                                                @elseif($jumlahData == 4)
                                                    {{ round($target->po) }}
                                                @endif
                                            </small>
                                        </h4>
                                        <div class="badge bg-label-secondary rounded-pill">Pruchase Order</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!--/ Total Sales chart -->
            @else
                <!-- Congratulations card -->
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-8 col-12">
                    <div class="card h-100">
                        <div class="card-body text-nowrap">
                            <h4 class="card-title mb-1 d-flex gap-2 flex-wrap">
                                Congratulations <strong>{{ Auth::user()->name }}</strong> 🎉
                            </h4>
                            <p class="pb-0">Best seller of the month</p>
                            <h4 class="text-primary mb-1">Rp. {{ $formattedTotalPrice }}</h4>
                            @php
                                $jumlah_target = 0;
                                $jumlah_target = ($poTotalPrice / $target->total) * 100;
                                $formatted_jumlah_target = number_format($jumlah_target, 3);
                            @endphp
                            <p class="mb-2 pb-1">{{ $formatted_jumlah_target }}% of target 🚀</p>
                            <a href="javascript:;" class="btn btn-sm btn-primary waves-effect waves-light">View Sales</a>
                        </div>
                        <img src="{{ asset('assets') }}/img/illustrations/trophy.png"
                            class="position-absolute bottom-0 end-0 me-3" height="140" alt="view sales">
                    </div>
                </div>
                <!--/ Congratulations card -->
                <!-- Total Leads -->
                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-info rounded">
                                        <i class="mdi mdi-phone-outline mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">{{ $dailyCall }} <small class="text-muted fs-tiny">/
                                        @php
                                            if (is_array($weekPerMonth)) {
                                                $jumlahData = count($weekPerMonth);
                                            }
                                        @endphp
                                        @if ($jumlahData > 4)
                                            {{ round($target->dc + $target->dc / 4) }}
                                        @elseif($jumlahData == 4)
                                            {{ round($target->dc) }}
                                        @endif
                                    </small></h4>
                                <div class="badge bg-label-secondary rounded-pill">Daily Call</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Total Leads -->
                <!-- Total Expenses -->
                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-account-multiple-outline mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">{{ $customers }} <small class="text-muted fs-tiny">/
                                        @if ($jumlahData > 4)
                                            {{ round($target->crm + $target->crm / 4) }}
                                        @elseif($jumlahData == 4)
                                            {{ round($target->crm) }}
                                        @endif
                                    </small>
                                </h4>
                                <div class="badge bg-label-secondary rounded-pill">CRM Existing</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Total Expenses -->
                <!-- Total Profit chart -->
                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-warning rounded">
                                        <i class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">{{ $quotation->count() }} <small class="text-muted fs-tiny">/
                                        @if ($jumlahData > 4)
                                            {{ round($target->quote + $target->quote / 4) }}
                                        @elseif($jumlahData == 4)
                                            {{ round($target->quote) }}
                                        @endif
                                    </small>
                                </h4>
                                <div class="badge bg-label-secondary rounded-pill">Quotation</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Total Profit chart -->
                <!-- Total Growth chart -->
                <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-success rounded">
                                        <i class="mdi mdi-cart-plus mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">{{ $po->count() }} <small class="text-muted fs-tiny">/
                                        @if ($jumlahData > 4)
                                            {{ round($target->po + $target->po / 4) }}
                                        @elseif($jumlahData == 4)
                                            {{ round($target->po) }}
                                        @endif
                                    </small>
                                </h4>
                                <div class="badge bg-label-secondary rounded-pill">Pruchase Order</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Total Sales chart -->
            @endif
        </div>

        <div class="row gy-4 mb-4">
            {{-- Start Diagram --}}
            <div class="col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">Monthly Sales</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="monthlyOverviewChartRegita"></div>
                        <div class="mt-1">
                            <div class="d-flex align-items-center gap-3">
                                <h3 class="mb-0">62%</h3>
                                <p class="mb-0 text-muted">Your sales performance is 35% 😎 better compared to last month
                                </p>
                            </div>
                            <div class="d-grid mt-3">
                                <button class="btn btn-primary" type="button">Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End:: Diagram --}}
            {{-- Prospect Table --}}
            <div class="col-md-6 col-12">
                <div class="card mb-3">
                    <div class="card-datatable table-responsive pt-0">
                        <table class="datatable-comment table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>ID</th>
                                    <th>No Quote</th>
                                    <th>Status</th>
                                    <th>Admin</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            {{-- End:: Prospect Table --}}

            {{-- Prospect Table --}}
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-datatable table-responsive pt-0">
                        <table class="datatable-prospect-quote-sales table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Company</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            {{-- End:: Prospect Table --}}
        </div>

        <div class="card app-calendar-wrapper">
            <div class="row gy-4">
                <!-- Calendar Sidebar -->
                <div class="col app-calendar-sidebar pt-1" id="app-calendar-sidebar">
                    <div class="p-3 pb-2 my-sm-0 mb-3">
                        <div class="d-grid">
                            <button class="btn btn-primary btn-toggle-sidebar" data-bs-toggle="offcanvas"
                                data-bs-target="#addEventSidebar" aria-controls="addEventSidebar">
                                <i class="mdi mdi-plus me-1"></i>
                                <span class="align-middle">Add Event</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <!-- inline calendar (flatpicker) -->
                        <div class="inline-calendar"></div>

                        <hr class="container-m-nx my-4" />

                        <!-- Filter -->
                        <div class="mb-4">
                            <small class="text-small text-muted text-uppercase align-middle">Filter</small>
                        </div>

                        <div class="form-check form-check-secondary mb-3">
                            <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all"
                                checked />
                            <label class="form-check-label" for="selectAll">View All</label>
                        </div>

                        <div class="app-calendar-events-filter">
                            <div class="form-check form-check-danger mb-3">
                                <input class="form-check-input input-filter" type="checkbox" id="select-personal"
                                    data-value="personal" checked />
                                <label class="form-check-label" for="select-personal">Personal</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input input-filter" type="checkbox" id="select-business"
                                    data-value="business" checked />
                                <label class="form-check-label" for="select-business">Business</label>
                            </div>
                            <div class="form-check form-check-warning mb-3">
                                <input class="form-check-input input-filter" type="checkbox" id="select-family"
                                    data-value="family" checked />
                                <label class="form-check-label" for="select-family">Family</label>
                            </div>
                            <div class="form-check form-check-success mb-3">
                                <input class="form-check-input input-filter" type="checkbox" id="select-holiday"
                                    data-value="holiday" checked />
                                <label class="form-check-label" for="select-holiday">Holiday</label>
                            </div>
                            <div class="form-check form-check-info">
                                <input class="form-check-input input-filter" type="checkbox" id="select-etc"
                                    data-value="etc" checked />
                                <label class="form-check-label" for="select-etc">ETC</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Calendar Sidebar -->

                <!-- Calendar & Modal -->
                <div class="col app-calendar-content">
                    <div class="card shadow-none border-0 border-start rounded-0">
                        <div class="card-body pb-0">
                            <!-- FullCalendar -->
                            <div id="calendar"></div>
                        </div>
                    </div>
                    <div class="app-overlay"></div>
                    <!-- FullCalendar Offcanvas -->
                    <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar"
                        aria-labelledby="addEventSidebarLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="addEventSidebarLabel">Add Event</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <form class="event-form pt-0" id="eventForm" onsubmit="return false">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="eventTitle" name="eventTitle"
                                        placeholder="Event Title" />
                                    <label for="eventTitle">Title</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <select class="select2 select-event-label form-select" id="eventLabel"
                                        name="eventLabel">
                                        <option data-label="primary" value="Business" selected>Business</option>
                                        <option data-label="danger" value="Personal">Personal</option>
                                        <option data-label="warning" value="Family">Family</option>
                                        <option data-label="success" value="Holiday">Holiday</option>
                                        <option data-label="info" value="ETC">ETC</option>
                                    </select>
                                    <label for="eventLabel">Label</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="eventStartDate" name="eventStartDate"
                                        placeholder="Start Date" />
                                    <label for="eventStartDate">Start Date</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="eventEndDate" name="eventEndDate"
                                        placeholder="End Date" />
                                    <label for="eventEndDate">End Date</label>
                                </div>
                                <div class="mb-3">
                                    <label class="switch">
                                        <input type="checkbox" class="switch-input allDay-switch" />
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on"></span>
                                            <span class="switch-off"></span>
                                        </span>
                                        <span class="switch-label">All Day</span>
                                    </label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="url" class="form-control" id="eventURL" name="eventURL"
                                        placeholder="https://www.google.com" />
                                    <label for="eventURL">Event URL</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4 select2-primary">
                                    <select class="select2 select-event-guests form-select" id="eventGuests"
                                        name="eventGuests" multiple>
                                        <option data-avatar="1.png" value="Jane Foster">Jane Foster</option>
                                        <option data-avatar="3.png" value="Donna Frank">Donna Frank</option>
                                        <option data-avatar="5.png" value="Gabrielle Robertson">Gabrielle Robertson
                                        </option>
                                        <option data-avatar="7.png" value="Lori Spears">Lori Spears</option>
                                        <option data-avatar="9.png" value="Sandy Vega">Sandy Vega</option>
                                        <option data-avatar="11.png" value="Cheryl May">Cheryl May</option>
                                    </select>
                                    <label for="eventGuests">Add Guests</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="eventLocation" name="eventLocation"
                                        placeholder="Enter Location" />
                                    <label for="eventLocation">Location</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <textarea class="form-control" name="eventDescription" id="eventDescription"></textarea>
                                    <label for="eventDescription">Description</label>
                                </div>
                                <div class="mb-3 d-flex justify-content-sm-between justify-content-start my-4 gap-2">
                                    <div class="d-flex">
                                        <button type="submit"
                                            class="btn btn-primary btn-add-event me-sm-2 me-1">Add</button>
                                        <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1"
                                            data-bs-dismiss="offcanvas">
                                            Cancel
                                        </button>
                                    </div>
                                    <button class="btn btn-label-danger btn-delete-event d-none">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /Calendar & Modal -->
            </div>

        </div>
    @elseif (Auth::user()->role == 'Admin')
        <div class="row gy-4 mb-4">
            <div class="col-12 col-lg-4">
                <div class="card mb-3">
                    <div class="card-body text-nowrap">
                        <h4 class="card-title mb-1 d-flex gap-2 flex-wrap">
                            Sales Results</strong> 🎉
                        </h4>
                        <p class="pb-0">sell of the month</p>
                        <h4 class="text-primary mb-1">Rp. {{ $formattedTotalPriceAdmin }}</h4>
                        @php
                            $jumlah_target = 0;
                            $jumlah_target = ($poTotalPriceAdmin / 1000000000) * 100;
                            $formatted_jumlah_target = number_format($jumlah_target, 3);
                        @endphp
                        <p class="mb-2 pb-1">{{ $formatted_jumlah_target }}% of target 🚀</p>
                        <a href="javascript:;" class="btn btn-sm btn-primary waves-effect waves-light">View Sales</a>
                    </div>
                    <img src="{{ asset('assets') }}/img/illustrations/trophy.png"
                        class="position-absolute bottom-0 end-0 me-3" height="140" alt="view sales">
                </div>
                <div class="card">
                    <div class="card-body pb-0 pt-3">
                        <div class="row d-flex align-items-center">
                            <div class="col-5 col-lg-6 col-xl-5" style="position: relative;">
                                <div class="chart-progress" data-color="primary" data-series="70"
                                    data-icon="../../assets/img/icons/misc/card-icon-laptop.png"
                                    style="min-height: 98px;">
                                    <div id="apexchartss5ddnay7"
                                        class="apexcharts-canvas apexchartss5ddnay7 apexcharts-theme-light"
                                        style="width: 88px; height: 98px;"><svg id="SvgjsSvg1279" width="88"
                                            height="98" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                            class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                            style="background: transparent;">
                                            <g id="SvgjsG1281" class="apexcharts-inner apexcharts-graphical"
                                                transform="translate(-4.5, 0)">
                                                <defs id="SvgjsDefs1280">
                                                    <clipPath id="gridRectMasks5ddnay7">
                                                        <rect id="SvgjsRect1283" width="103" height="99" x="-3"
                                                            y="-1" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMasks5ddnay7"></clipPath>
                                                    <clipPath id="nonForecastMasks5ddnay7"></clipPath>
                                                    <clipPath id="gridRectMarkerMasks5ddnay7">
                                                        <rect id="SvgjsRect1284" width="101" height="101" x="-2"
                                                            y="-2" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                </defs>
                                                <g id="SvgjsG1285" class="apexcharts-radialbar">
                                                    <g id="SvgjsG1286">
                                                        <g id="SvgjsG1287" class="apexcharts-tracks">
                                                            <g id="SvgjsG1288"
                                                                class="apexcharts-radialbar-track apexcharts-track"
                                                                rel="1">
                                                                <path id="apexcharts-radialbarTrack-0"
                                                                    d="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 1 1 48.494278299912935 15.717073670044236"
                                                                    fill="none" fill-opacity="1" stroke="#6d788d29"
                                                                    stroke-opacity="1" stroke-linecap="round"
                                                                    stroke-width="6.165414634146342" stroke-dasharray="0"
                                                                    class="apexcharts-radialbar-area"
                                                                    data:pathOrig="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 1 1 48.494278299912935 15.717073670044236">
                                                                </path>
                                                            </g>
                                                        </g>
                                                        <g id="SvgjsG1290">
                                                            <image id="SvgjsImage1291"
                                                                xlink:href="../../assets/img/icons/misc/card-icon-laptop.png"
                                                                width="18" height="18" x="39.5" y="39.5"></image>
                                                            <g id="SvgjsG1294"
                                                                class="apexcharts-series apexcharts-radial-series"
                                                                seriesName="Progress" rel="1" data:realIndex="0">
                                                                <path id="SvgjsPath1295"
                                                                    d="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 1 1 17.321583815797176 58.63048151559431"
                                                                    fill="none" fill-opacity="0.85"
                                                                    stroke="rgba(102,108,255,0.85)" stroke-opacity="1"
                                                                    stroke-linecap="round"
                                                                    stroke-width="6.356097560975611" stroke-dasharray="0"
                                                                    class="apexcharts-radialbar-area apexcharts-radialbar-slice-0"
                                                                    data:angle="252" data:value="70" index="0" j="0"
                                                                    data:pathOrig="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 1 1 17.321583815797176 58.63048151559431">
                                                                </path>
                                                            </g>
                                                            <circle id="SvgjsCircle1292" r="24.70021951219512"
                                                                cx="48.5" cy="48.5"
                                                                class="apexcharts-radialbar-hollow" fill="transparent">
                                                            </circle>
                                                            <g id="SvgjsG1293" class="apexcharts-datalabels-group"
                                                                transform="translate(0, 0) scale(1)" style="opacity: 1;">
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                                <line id="SvgjsLine1296" x1="0" y1="0" x2="97"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="1" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line id="SvgjsLine1297" x1="0" y1="0" x2="97"
                                                    y2="0" stroke-dasharray="0" stroke-width="0"
                                                    stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                            </g>
                                            <g id="SvgjsG1282" class="apexcharts-annotations"></g>
                                        </svg>
                                        <div class="apexcharts-legend"></div>
                                    </div>
                                </div>
                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 113px; height: 99px;"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                            <div class="col-7 col-lg-6 col-xl-7">
                                <div class="card-info">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h5 class="mb-0">84k</h5>
                                        <div class="d-flex text-danger">
                                            <p class="mb-0">-24%</p>
                                            <div class="mdi mdi-chevron-down"></div>
                                        </div>
                                    </div>
                                    <p class="mb-0 mt-1">Total Impression</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="card-body pt-0 pb-3">
                        <div class="row d-flex align-items-center">
                            <div class="col-5 col-lg-6 col-xl-5" style="position: relative;">
                                <div class="chart-progress" data-color="warning" data-series="40"
                                    data-icon="../../assets/img/icons/misc/card-icon-bag.png" style="min-height: 98px;">
                                    <div id="apexcharts2mo0zjp2k"
                                        class="apexcharts-canvas apexcharts2mo0zjp2k apexcharts-theme-light"
                                        style="width: 88px; height: 98px;"><svg id="SvgjsSvg1298" width="88"
                                            height="98" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                            class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                            style="background: transparent;">
                                            <g id="SvgjsG1300" class="apexcharts-inner apexcharts-graphical"
                                                transform="translate(-4.5, 0)">
                                                <defs id="SvgjsDefs1299">
                                                    <clipPath id="gridRectMask2mo0zjp2k">
                                                        <rect id="SvgjsRect1302" width="103" height="99" x="-3"
                                                            y="-1" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMask2mo0zjp2k"></clipPath>
                                                    <clipPath id="nonForecastMask2mo0zjp2k"></clipPath>
                                                    <clipPath id="gridRectMarkerMask2mo0zjp2k">
                                                        <rect id="SvgjsRect1303" width="101" height="101" x="-2"
                                                            y="-2" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                </defs>
                                                <g id="SvgjsG1304" class="apexcharts-radialbar">
                                                    <g id="SvgjsG1305">
                                                        <g id="SvgjsG1306" class="apexcharts-tracks">
                                                            <g id="SvgjsG1307"
                                                                class="apexcharts-radialbar-track apexcharts-track"
                                                                rel="1">
                                                                <path id="apexcharts-radialbarTrack-0"
                                                                    d="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 1 1 48.494278299912935 15.717073670044236"
                                                                    fill="none" fill-opacity="1" stroke="#6d788d29"
                                                                    stroke-opacity="1" stroke-linecap="round"
                                                                    stroke-width="6.165414634146342" stroke-dasharray="0"
                                                                    class="apexcharts-radialbar-area"
                                                                    data:pathOrig="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 1 1 48.494278299912935 15.717073670044236">
                                                                </path>
                                                            </g>
                                                        </g>
                                                        <g id="SvgjsG1309">
                                                            <image id="SvgjsImage1310"
                                                                xlink:href="../../assets/img/icons/misc/card-icon-bag.png"
                                                                width="18" height="18" x="39.5" y="39.5"></image>
                                                            <g id="SvgjsG1313"
                                                                class="apexcharts-series apexcharts-radial-series"
                                                                seriesName="Progress" rel="1" data:realIndex="0">
                                                                <path id="SvgjsPath1314"
                                                                    d="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 0 1 67.76932091722715 75.02194493022846"
                                                                    fill="none" fill-opacity="0.85"
                                                                    stroke="rgba(253,181,40,0.85)" stroke-opacity="1"
                                                                    stroke-linecap="round"
                                                                    stroke-width="6.356097560975611" stroke-dasharray="0"
                                                                    class="apexcharts-radialbar-area apexcharts-radialbar-slice-0"
                                                                    data:angle="144" data:value="40" index="0" j="0"
                                                                    data:pathOrig="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 0 1 67.76932091722715 75.02194493022846">
                                                                </path>
                                                            </g>
                                                            <circle id="SvgjsCircle1311" r="24.70021951219512"
                                                                cx="48.5" cy="48.5"
                                                                class="apexcharts-radialbar-hollow" fill="transparent">
                                                            </circle>
                                                            <g id="SvgjsG1312" class="apexcharts-datalabels-group"
                                                                transform="translate(0, 0) scale(1)" style="opacity: 1;">
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                                <line id="SvgjsLine1315" x1="0" y1="0" x2="97"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="1" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line id="SvgjsLine1316" x1="0" y1="0" x2="97"
                                                    y2="0" stroke-dasharray="0" stroke-width="0"
                                                    stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                            </g>
                                            <g id="SvgjsG1301" class="apexcharts-annotations"></g>
                                        </svg>
                                        <div class="apexcharts-legend"></div>
                                    </div>
                                </div>
                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 113px; height: 99px;"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                            <div class="col-7 col-lg-6 col-xl-7">
                                <div class="card-info">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h5 class="mb-0">22k</h5>
                                        <div class="d-flex text-success">
                                            <p class="mb-0">+15%</p>
                                            <div class="mdi mdi-chevron-up"></div>
                                        </div>
                                    </div>
                                    <p class="mb-0 mt-1">Total Order</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title m-0">
                            <h5 class="mb-0">Sales Overview</h5>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="earningReportsTabsId" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReportsTabsId">
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">View More</a>
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Delete</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-3">
                        <ul class="nav nav-tabs nav-tabs-widget pb-3 gap-2 d-flex flex-nowrap" role="tablist">
                            @foreach ($sales as $user)
                                <li class="nav-item" role="presentation" style="width: 80%;">
                                    <div class="nav-link btn {{ $user->id == 1 ? 'active' : '' }} d-flex flex-column align-items-center justify-content-center"
                                        role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-sales-{{ $user->id }}"
                                        aria-controls="navs-sales-{{ $user->id }}" aria-selected="true">
                                        <img src="{{ url('') . '/' . $user->image }}" alt="" srcset=""
                                            style="width : 100%; height:100%; object-fit: cover;">
                                    </div>
                                </li>
                            @endforeach
                            <span class="tab-slider" style="left: 0px; width: 112px; bottom: 0px;"></span>
                        </ul>
                        <div class="tab-content p-0 ms-0 ms-sm-2">
                            @php
                                $item = 0;
                            @endphp
                            @foreach ($sales as $user)
                                <div class="tab-pane fade{{ $user->id == 1 ? ' show active' : '' }}"
                                    id="navs-sales-{{ $user->id }}" role="tabpanel">

                                    <div class="mb-3">
                                        <div data-id="{{ $item }}">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <div class="d-flex justify-content-between">
                                                            <h4 class="">{{ $user->name }}'s Overview</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="d-flex mb-2 gap-2">
                                                            <a type="button" data-bs-toggle="modal"
                                                                data-bs-target="#overview-sales-{{ $item }}">
                                                                <div class="avatar">
                                                                    <button type="button"
                                                                        class="avatar-initial bg-label-info rounded">
                                                                        <i class="mdi mdi-phone-outline mdi-24px"></i>
                                                                    </button>
                                                                </div>
                                                            </a>
                                                            <div class="card-info">
                                                                <h5 class="mb-0">{{ $filteredDC[$item] }} <span
                                                                        class="text-muted fs-tiny fw-normal">/{{ $targetSales[$item][0]->dc }}</span>
                                                                </h5>
                                                                <small
                                                                    class="text-muted">{{ $user->id == '1' ? 'New Leads' : 'Daily Call' }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mb-2 gap-2">
                                                            <div class="avatar">
                                                                <div class="avatar-initial bg-label-primary rounded">
                                                                    <i
                                                                        class="mdi mdi-account-multiple-outline mdi-24px"></i>
                                                                </div>
                                                            </div>
                                                            <div class="card-info">
                                                                <h5 class="mb-0">{{ $filteredCRM[$item] }}<span
                                                                        class="text-muted fs-tiny fw-normal">/{{ $targetSales[$item][0]->crm }}</span>
                                                                </h5>
                                                                <small class="text-muted">CRM</small>
                                                            </div>
                                                        </div>
                                                        @php
                                                            $lastDetail = $user->detail->last();
                                                        @endphp
                                                        @if ($lastDetail->area == 'Bekasi' || $lastDetail->area == 'Jabodetabek' || $lastDetail->area == 'Jawa Barat')
                                                            <div class="d-flex mb-2 gap-2">
                                                                <div class="avatar">
                                                                    <div class="avatar-initial bg-label-danger rounded">
                                                                        <i
                                                                            class="mdi mdi-office-building-marker-outline mdi-24px"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="card-info">
                                                                    <h5 class="mb-0">{{ $filteredVisit[$item] }}<span
                                                                            class="text-muted fs-tiny fw-normal">/{{ $targetSales[$item][0]->visit }}</span>
                                                                    </h5>
                                                                    <small class="text-muted">Visit</small>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="d-flex mb-2 gap-2">
                                                            <a href="{{ route('sales.quotation', $user->id) }}">
                                                                <div class="avatar">
                                                                    <div class="avatar-initial bg-label-warning rounded">
                                                                        <i
                                                                            class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                            <div class="card-info">
                                                                <h5 class="mb-0">{{ $filteredQuote[$item] }}<span
                                                                        class="text-muted fs-tiny fw-normal">/{{ $targetSales[$item][0]->quote }}</span>
                                                                </h5>
                                                                <small class="text-muted">Quotation</small>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex mb-2 gap-2">
                                                            <a href="{{ route('sales.po', $user->id) }}">
                                                                <div class="avatar">
                                                                    <div class="avatar-initial bg-label-success rounded">
                                                                        <i class="mdi mdi-cart-plus mdi-24px"></i>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                            <div class="card-info">
                                                                <h5 class="mb-0">{{ $filteredPO[$item] }}<span
                                                                        class="text-muted fs-tiny fw-normal">/{{ $targetSales[$item][0]->po }}</span>
                                                                </h5>
                                                                <small class="text-muted">PO</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="d-flex mb-2 gap-2">
                                                                <div class="avatar">
                                                                    <div class="avatar-initial bg-label-success rounded">
                                                                        <i class="mdi mdi-cart-plus mdi-24px"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="card-info">
                                                                    <h5 class="mb-0">Rp
                                                                        {{ number_format($totalPO[$item], 2, ',', '.') }}
                                                                        @php
                                                                            $jumlah_target = [];
                                                                            foreach ($totalPO as $key => $value) {
                                                                                if (
                                                                                    isset($targett[$key]) &&
                                                                                    $targett[$key] != 0
                                                                                ) {
                                                                                    $jumlah_target[$key] =
                                                                                        ($value / $targett[$key]) * 100;
                                                                                } else {
                                                                                    $jumlah_target[$key] = 0;
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <span class="text-success mb-0">
                                                                            {{ number_format($jumlah_target[$item], 3) }}%
                                                                        </span>
                                                                    </h5>
                                                                    <small class="text-muted">Total Sales</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="d-flex mb-2 gap-2">
                                                                <div class="avatar">
                                                                    <div class="avatar-initial bg-label-primary rounded">
                                                                        <i
                                                                            class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="card-info">
                                                                    <h5 class="mb-0">
                                                                        Rp
                                                                        {{ $totalForecast[$item] }}
                                                                    </h5>
                                                                    <small class="text-muted">Quotation</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="d-flex mb-2 gap-2">
                                                                <div class="avatar">
                                                                    <div class="avatar-initial bg-label-warning rounded">
                                                                        <i
                                                                            class="mdi mdi-email-alert-outline mdi-24px"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="card-info">
                                                                    <h5 class="mb-0">Rp
                                                                        {{ $totalProspect[$item] }}
                                                                    </h5>
                                                                    <small class="text-muted">Hot Prospect</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            @php
                                                                $month = date('m');
                                                                $year = date('Y');
                                                                $dateNow = $month . '-' . $year;
                                                            @endphp
                                                            <div class="col-6">
                                                                <a class="btn btn-facebook d-grid w-100 waves-effect h-100"
                                                                    href="{{ route('detail-overview.semester', ['sales' => $user->id, 'date' => $dateNow]) }}">
                                                                    Overview {{ date('F') }}
                                                                </a>
                                                            </div>
                                                            <div class="col-6">
                                                                <a class="btn btn-secondary d-grid w-100 waves-effect h-100"
                                                                    href="{{ route('overview.semester', $user->id) }}">
                                                                    Overview Semester
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $item++;
                                    @endphp
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-datatable table-responsive pt-0">
                        <table class="datatable-prospect-quote table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Quote No.</th>
                                    <th>Company</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Assigned</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @php
            $item = 0;
        @endphp
        @foreach ($sales as $user)
            @include('components.modal.overview')
            @php
                $item++;
            @endphp
        @endforeach
    @elseif(Auth::user()->role == 'Logistic')
        <h4 class="fw-bold py-3 mb-4">
            Product
        </h4>
        <div class="card mb-4">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                    <p class="mb-2">Comodity</p>
                                    <h4 class="mb-2">{{ $commodity }}</h4>
                                    <p class="mb-0"><span class="badge rounded-pill bg-label-success"></span></p>
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="mdi mdi-home-outline mdi-24px"></i>
                                    </span>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                                <div>
                                    <p class="mb-2">Equivalent</p>
                                    <h4 class="mb-2">{{ $sproduct }}</h4>
                                    <p class="mb-0"><span class="badge rounded-pill bg-label-success"></span></p>
                                </div>
                                <div class="avatar me-lg-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="mdi mdi-laptop mdi-24px"></i>
                                    </span>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none">
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                <div>
                                    <p class="mb-2">Pruchase Order</p>
                                    <h4 class="mb-2">1</h4>
                                    <p class="mb-0"><span class="badge rounded-pill bg-label-success"></span></p>
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="mdi mdi-wallet-giftcard mdi-24px"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-2">Loss Order</p>
                                    <h4 class="mb-2">2</h4>
                                    <p class="mb-0"><span class="badge rounded-pill bg-label-danger"></span></p>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="mdi mdi-currency-usd mdi-24px"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-product table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Part Number</th>
                            <th>Desc</th>
                            <th>Dimension</th>
                            <th>G/O</th>
                            <th>Stock BDG</th>
                            <th>Stock BKS</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        @include('components.modal.warehouse.product.form')
    @elseif(Auth::user()->role == 'Coordinator')
        <h4 class="fw-3">Request Visit</h4>
        <div class="card mb-3">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-visit-coordinator table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>company</th>
                            <th>Machine</th>
                            <th>Date Request</th>
                            <th>Sales</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <h4 class="fw-3">Visit Schedule</h4>
        <div class="card mb-3">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-visit-accept table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>company</th>
                            <th>Machine</th>
                            <th>Date</th>
                            <th>Sales</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        @foreach ($visits as $visit)
            @include('components.modal.req-visit.form-accept')
        @endforeach
        @foreach ($visited as $visit)
            @include('components.modal.req-visit.form-visited')
        @endforeach
    @endif
@endsection
@push('after-style')
    {{-- All --}}
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />

    {{-- sales --}}
    @if (Auth::user()->role == 'Sales')
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-calendar.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/swiper/swiper.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/fullcalendar/fullcalendar.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
    @endif

    {{-- admin --}}
    @if (Auth::user()->role == 'Admin')
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/cards-statistics.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/cards-analytics.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/apex-charts/apex-charts.css" />
    @endif

    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />

    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css" />
@endpush

@push('after-script')
    <!-- Vendors JS -->
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    {{-- sales --}}
    {{-- sales --}}
    @if (Auth::user()->role == 'Sales')
        <script src="{{ asset('assets') }}/vendor/libs/fullcalendar/fullcalendar.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/apex-charts/apexcharts.js"></script>
        <script>
            $(document).on('click', '.view-quote', function(e) {
                e.preventDefault(); // Mencegah perubahan halaman segera

                var id = $(this).data('id');
                var idQ = $(this).data('quotation');
                var href = $(this).attr('href'); // Ambil URL tujuan

                $.ajax({
                    url: '{{ url('quotation') }}/' + id + '/view_comment',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}', // Token CSRF
                    },
                    success: function(response) {
                        console.log(response); // Lakukan apa yang perlu dilakukan setelah AJAX sukses

                        // Arahkan ke halaman baru setelah AJAX selesai
                        window.location.href = href;
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText); // Tangani error jika ada
                    }
                });
            });
        </script>
    @endif
@endpush
@push('page-script')
    <!-- Page JS -->
    <script src="{{ asset('assets') }}/js/ui-modals.js"></script>

    @if (Auth::user()->role == 'Sales')
        <script src="{{ asset('assets') }}/js/dashboards-crm.js"></script>
        <script src="{{ asset('assets') }}/js/app-calendar-events.js"></script>
        <script src="{{ asset('assets') }}/js/app-calendar.js"></script>
        <script src="{{ asset('assets') }}/includes/chart/card-monthly.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
        <script src="{{ asset('assets') }}/includes/table-prospect-sales.js"></script>
        <script src="{{ asset('assets') }}/includes/table-req-visit-sales.js"></script>
    @endif

    <script src="{{ asset('assets') }}/includes/table-prospect.js"></script>
    <script src="{{ asset('assets') }}/includes/table-comment.js"></script>

    <script src="{{ asset('assets') }}/includes/table-product-sales.js"></script>
    <script src="{{ asset('assets') }}/includes/table-product-logistic.js"></script>

    <script src="{{ asset('assets') }}/includes/table-req-visit-service.js"></script>
    <script src="{{ asset('assets') }}/includes/table-req-visit-accept.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> --}}

@endpush
