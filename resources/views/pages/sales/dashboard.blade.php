@extends('layouts.sales.app')
@section('title', 'My Dashboard')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4 mb-4">
            <!-- Congratulations card -->
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-8 col-12">
                <div class="card h-100">
                    <div class="card-body text-nowrap">
                        <h4 class="card-title mb-1 d-flex gap-2 flex-wrap">
                            Congratulations <strong>Miss Regita!</strong> 🎉
                        </h4>
                        <p class="pb-0">Best seller of the month</p>
                        <h4 class="text-primary mb-1">Rp. 150 juta</h4>
                        <p class="mb-2 pb-1">78% of target 🚀</p>
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
                            <h4 class="mb-2">{{$leads->count()}} <small class="text-muted fs-tiny">/ 360</small></h4>
                            <p class="text-muted">Daily Call</p>
                            <div class="badge bg-label-secondary rounded-pill">Miss Regita</div>
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
                            <h4 class="mb-2">{{$customers->count()}} <small class="text-muted fs-tiny">/ 360</small></h4>
                            <p class="text-muted">CRM Existing</p>
                            <div class="badge bg-label-secondary rounded-pill">Miss Regita</div>
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
                            <h4 class="mb-2">{{$quotation->count()}} <small class="text-muted fs-tiny">/ 360</small></h4>
                            <p class="text-muted">Quotation</p>
                            <div class="badge bg-label-secondary rounded-pill">Miss Regita</div>
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
                            <h4 class="mb-2">{{$po->count()}} <small class="text-muted fs-tiny">/ 360</small></h4>
                            <p class="text-muted">Pruchase Order</p>
                            <div class="badge bg-label-secondary rounded-pill">Miss Regita</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Total Sales chart -->
        </div>

        <div class="row gy-4 mb-4">
            <!-- Radial bar Chart -->
            <div class="col-md-8 col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Report</h5>
                        <div class="dropdown">
                            <button type="button" class="btn dropdown-toggle p-0" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="mdi mdi-account-outline"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Today</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                        class="dropdown-item d-flex align-items-center">Yesterday</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last
                                        7
                                        Days</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last
                                        30
                                        Days</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Current
                                        Month</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last
                                        Month</a>
                                </li>
                            </ul>
                            <button type="button" class="btn dropdown-toggle p-0" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="mdi mdi-calendar-month-outline"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Today</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);"
                                        class="dropdown-item d-flex align-items-center">Yesterday</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last
                                        7
                                        Days</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last
                                        30
                                        Days</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Current
                                        Month</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last
                                        Month</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="reportBarChart"></div>
                    </div>
                </div>
            </div>
            <!-- /Radial bar Chart -->

            <!-- Meeting Schedule -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 me-2">Call Schedule</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="meetingSchedule" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="meetingSchedule">
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last 28 Days</a>
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Month</a>
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Year</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <ul class="p-0 m-0">
                            @foreach ($call as $calls)
                                <li class="d-flex mb-4 pb-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <img src="{{ asset('assets') }}/img/avatars/4.png" alt="avatar"
                                            class="rounded">
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0 fw-semibold">{{ $calls->client->company }}</h6>
                                            <small class="text-muted">
                                                <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                                <span>{{ \Carbon\Carbon::parse($calls->follow_up)->toFormattedDateString() }}</span>
                                            </small>
                                        </div>
                                        <div
                                            class="badge bg-label-{{ $calls->name == 'Daily Call' ? 'primary' : 'success' }} rounded-pill">
                                            {{ $calls->name }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Meeting Schedule -->
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
    @endsection
    @push('after-style')
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/apex-charts/apex-charts.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/swiper/swiper.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/fullcalendar/fullcalendar.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/quill/editor.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />

        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/cards-statistics.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/cards-analytics.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/apex-charts/apex-charts.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-calendar.css" />
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
    @endpush
    @push('page-script')
        <!-- Page JS -->
        <script src="{{ asset('assets') }}/js/dashboards-crm.js"></script>
        <script src="{{ asset('assets') }}/js/charts-apex.js"></script>
        <script src="{{ asset('assets') }}/js/app-calendar-events.js"></script>
        <script src="{{ asset('assets') }}/js/app-calendar.js"></script>
    @endpush
