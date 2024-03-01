@extends('layouts.sales.app')
@section('title', 'My Dashboard')
@section('content')
    @if (Auth::user()->role == 'Sales')
        <div class="row gy-4 mb-4">
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
                                        {{ round($target->intro + $target->intro / 4) }}
                                    @elseif($jumlahData == 4)
                                        {{ round($target->intro) }}
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
        </div>

        <div class="row gy-4 mb-4">
            {{-- Start Diagram --}}
            <div class="col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">Monthly Sales</h5>
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
                    </div>
                    <div class="card-body">
                        <div id="weeklyOverviewChart"></div>
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
            <div class="col-lg-6 col-md-6 col-12">
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
                <div class="card">
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
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">
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
                        <ul class="nav nav-tabs nav-tabs-widget pb-3 gap-4 mx-1 d-flex flex-nowrap" role="tablist">
                            @foreach ($sales as $user)
                                <li class="nav-item" role="presentation">
                                    <div class="nav-link btn {{ $user->id == 1 ? 'active' : '' }} d-flex flex-column align-items-center justify-content-center"
                                        role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-sales-{{ $user->id }}"
                                        aria-controls="navs-sales-{{ $user->id }}" aria-selected="true">
                                        <button type="button"
                                            class="btn btn-icon rounded-pill btn-label-facebook waves-effect">
                                            <img src="{{ url('') . '/' . $user->image }}" alt="" srcset=""
                                                style="max-width : 20px;">
                                        </button>
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
                                                <div class="d-flex justify-content-between">
                                                    <h4 class="mb-2">{{ $user->name }}'s Overview</h4>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <h5 class="mb-0 fw-normal">Total Sales <span class="fs-4">Rp
                                                            {{ number_format($totalPO[$item], 2, ',', '.') }}</span></h5>
                                                    @php
                                                        $jumlah_target = [];
                                                        foreach ($totalPO as $key => $value) {
                                                            if (isset($targett[$key]) && $targett[$key] != 0) {
                                                                $jumlah_target[$key] = ($value / $targett[$key]) * 100;
                                                                $formatted_jumlah_target[$key] = number_format($jumlah_target[$key], 3);
                                                            } else {
                                                                $jumlah_target[$key] = 0;
                                                            }
                                                        }
                                                    @endphp
                                                    <div class="d-flex align-items-center text-success">
                                                        <p class="mb-0"> {{ $formatted_jumlah_target[$item] }}%</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <h5 class="fw-normal mb-0">Forecast <span class="fs-4">Rp
                                                            {{ $totalForecast[$item] }}</span></h5>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <h5 class="fw-normal">Hot Prospect <span class="fs-4">Rp
                                                            {{ $totalProspect[$item] }}</span></h5>
                                                </div>
                                            </div>
                                            <div class="card-body d-flex justify-content-between flex-wrap gap-3">
                                                <div class="d-flex gap-2">
                                                    <div class="avatar">
                                                        <a type="button" data-bs-toggle="modal"
                                                            data-bs-target="#overview-sales-{{ $item }}">
                                                            <button type="button"
                                                                class="avatar-initial bg-label-info rounded">
                                                                <i class="mdi mdi-phone-outline mdi-24px"></i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                    <div class="card-info">
                                                        <h5 class="mb-0">{{ $filteredDC[$item] }}</h5>
                                                        <small class="text-muted">Daily Call</small>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-label-primary rounded">
                                                            <i class="mdi mdi-account-multiple-outline mdi-24px"></i>
                                                        </div>
                                                    </div>
                                                    <div class="card-info">
                                                        <h5 class="mb-0">{{ $filteredCRM[$item] }}</h5>
                                                        <small class="text-muted">CRM</small>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-label-warning rounded">
                                                            <i class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                                        </div>
                                                    </div>
                                                    <div class="card-info">
                                                        <h5 class="mb-0">{{ $filteredQuote[$item] }}</h5>
                                                        <small class="text-muted">Quotation</small>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <div class="avatar">
                                                        <div class="avatar-initial bg-label-success rounded">
                                                            <i class="mdi mdi-cart-plus mdi-24px"></i>
                                                        </div>
                                                    </div>
                                                    <div class="card-info">
                                                        <h5 class="mb-0">{{ $filteredPO[$item] }}</h5>
                                                        <small class="text-muted">PO</small>
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
        </div>

        <div class="row gy-4 mb-4">
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
        @php
            $item = 0;
        @endphp
        @foreach ($sales as $user)
            @include('components.modal.overview')
            @php
                $item++;
            @endphp
        @endforeach
    @endif
@endsection
@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/swiper/swiper.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />

    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/cards-statistics.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/cards-analytics.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-calendar.css" />
    <link rel="stylesheet" type="text/css"
        href="{{ url('https://cdn.datatables.net/searchpanes/2.3.0/css/searchPanes.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ url('https://cdn.datatables.net/select/2.0.0/css/select.dataTables.min.css') }}">
@endpush

@push('after-script')
    <!-- Vendors JS -->
    <script src="{{ asset('assets') }}/vendor/libs/fullcalendar/fullcalendar.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ url('https://cdn.datatables.net/searchpanes/2.3.0/js/dataTables.searchPanes.min.js') }}"></script>
    <script src="{{ url('https://cdn.datatables.net/select/2.0.0/js/select.dataTables.min.js') }}"></script>
@endpush
@push('page-script')
    <!-- Page JS -->
    <script src="{{ asset('assets') }}/js/dashboards-crm.js"></script>
    <script src="{{ asset('assets') }}/js/app-calendar-events.js"></script>
    <script src="{{ asset('assets') }}/js/app-calendar.js"></script>
    <script src="{{ asset('assets') }}/includes/chart/card-monthly.js"></script>
    <script src="{{ asset('assets') }}/includes/table-prospect.js"></script>
    <script src="{{ asset('assets') }}/includes/table-prospect-sales.js"></script>
@endpush
