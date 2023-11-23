@extends('layouts.sales.app')
@section('title', 'My Leads')
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Clients /</span> Leads
    </h4>
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label text-center">
                        <h5 class="card-title mb-0">Leads</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                            data-bs-target="#createLeads">
                            <i class="mdi mdi-plus me-sm-1"></i>
                            Add New Leads
                        </button>
                        @include('pages.sales.leads.form')
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="DataTables_Table_0_length"><label>Show <select
                                    name="DataTables_Table_0_length" aria-controls="DataTables_Table_0"
                                    class="form-select form-select-sm">
                                    <option value="7">7</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="75">75</option>
                                    <option value="100">100</option>
                                </select> entries</label></div>
                    </div>
                    <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">
                        <div id="DataTables_Table_0_filter" class="dataTables_filter"><label>Search:<input type="search"
                                    class="form-control form-control-sm" placeholder=""
                                    aria-controls="DataTables_Table_0"></label></div>
                    </div>
                </div>
                <table class="table table-striped dataTable no-footer dtr-column" id="DataTables_Table_0"
                    aria-describedby="DataTables_Table_0_info" style="width: 1214px;">
                    <thead>
                        <tr>
                            <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1"
                                style="width: 36.2px; display: none;" aria-label=""></th>
                            <th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1"
                                colspan="1" style="width: 37.2px;" data-col="1" aria-label=""><input type="checkbox"
                                    class="form-check-input"></th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" style="width: 118.2px;" aria-label="Name: activate to sort column ascending">
                                Company</th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" style="width: 122.2px;"
                                aria-label="Email: activate to sort column ascending">PIC</th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" style="width: 109.2px;" aria-label="Date: activate to sort column ascending">
                                Address</th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" style="width: 145.2px;"
                                aria-label="Salary: activate to sort column ascending">Machine</th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" style="width: 143.2px;"
                                aria-label="Status: activate to sort column ascending">Status</th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" style="width: 143.2px;"
                                aria-label="Status: activate to sort column ascending">Last Contact</th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" style="width: 143.2px;"
                                aria-label="Status: activate to sort column ascending">Next Follow up</th>
                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" style="width: 143.2px;"
                                aria-label="Status: activate to sort column ascending">Assigned</th>
                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 151px;"
                                aria-label="Actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <tr>
                            <td class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1"
                                colspan="1" style="width: 37.2px;" data-col="1" aria-label=""><input
                                    type="checkbox" class="form-check-input"></td>
                            <td>
                                <span class="fw-medium">PT Teras Adhi Kharisma</span>
                            </td>
                            <td>
                                Mr Ganang
                            </td>
                            <td>
                                Boyolali
                            </td>
                            <td>
                                Kaeser
                            </td>
                            <td>
                                <span class="badge bg-label-info me-1">Send Introduction</span>
                            </td>
                            <td>
                                10 Oct 2023
                            </td>
                            <td>
                                17 Oct 2023
                            </td>
                            <td>
                                Miss Vita
                            </td>
                            <td>
                                <div class="d-inline-block">
                                    <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                        <a href="{{url('leads/detail')}}" class="dropdown-item">Details</a>
                                        <div class="dropdown-divider"></div><a href="javascript:;" class="dropdown-item text-danger delete-record">Loss</a>
                                    </div>
                                </div>
                                <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit">
                                    <i class="mdi mdi-pencil-outline"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1"
                                colspan="1" style="width: 37.2px;" data-col="1" aria-label=""><input
                                    type="checkbox" class="form-check-input"></td>
                            <td>
                                <span class="fw-medium">PT Indospring tbk.</span>
                            </td>
                            <td>
                                Mr Lukman
                            </td>
                            <td>
                                Boyolali
                            </td>
                            <td>
                                Kaeser
                            </td>
                            <td>
                                <span class="badge bg-label-primary me-1">Send Quotation</span>
                            </td>
                            <td>
                                10 Oct 2023
                            </td>
                            <td>
                                17 Oct 2023
                            </td>
                            <td>
                                Miss Vita
                            </td>
                            <td>
                                <div class="d-inline-block">
                                    <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                        <a href="javascript:;" class="dropdown-item">Details</a>
                                        <div class="dropdown-divider"></div><a href="javascript:;" class="dropdown-item text-danger delete-record">Loss</a>
                                    </div>
                                </div>
                                <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit">
                                    <i class="mdi mdi-pencil-outline"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1"
                                colspan="1" style="width: 37.2px;" data-col="1" aria-label=""><input
                                    type="checkbox" class="form-check-input"></td>
                            <td>
                                <span class="fw-medium">PT Guci Mas Plasindo</span>
                            </td>
                            <td>
                                Mrs. Ichiis
                            </td>
                            <td>
                                Boyolali
                            </td>
                            <td>
                                Kaeser
                            </td>
                            <td>
                                <span class="badge bg-label-success me-1">Done PO</span>
                            </td>
                            <td>
                                10 Oct 2023
                            </td>
                            <td>
                                17 Oct 2023
                            </td>
                            <td>
                                Miss Vita
                            </td>
                            <td>
                                <div class="d-inline-block">
                                    <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                        <a href="javascript:;" class="dropdown-item">Details</a>
                                        <div class="dropdown-divider"></div><a href="javascript:;" class="dropdown-item text-danger delete-record">Loss</a>
                                    </div>
                                </div>
                                <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit">
                                    <i class="mdi mdi-pencil-outline"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1"
                                colspan="1" style="width: 37.2px;" data-col="1" aria-label=""><input
                                    type="checkbox" class="form-check-input"></td>
                            <td>
                                <span class="fw-medium">PT Indonesia Sejahtera</span>
                            </td>
                            <td>
                                Mr Dewo
                            </td>
                            <td>
                                Boyolali
                            </td>
                            <td>
                                Kaeser
                            </td>
                            <td>
                                <span class="badge bg-label-danger me-1">Loss</span>
                            </td>
                            <td>
                                10 Oct 2023
                            </td>
                            <td>
                                17 Oct 2023
                            </td>
                            <td>
                                Miss Vita
                            </td>
                            <td>
                                <div class="d-inline-block">
                                    <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end m-0">
                                        <a href="javascript:;" class="dropdown-item">Details</a>
                                        <div class="dropdown-divider"></div><a href="javascript:;" class="dropdown-item text-danger delete-record">Loss</a>
                                    </div>
                                </div>
                                <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit">
                                    <i class="mdi mdi-pencil-outline"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">
                            Showing 0 to 0 of 0 entries</div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                            <ul class="pagination">
                                <li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous">
                                    <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="previous"
                                        tabindex="0" class="page-link">Previous</a>
                                </li>
                                <li class="paginate_button page-item next disabled" id="DataTables_Table_0_next"><a
                                        href="#" aria-controls="DataTables_Table_0" data-dt-idx="next"
                                        tabindex="0" class="page-link">Next</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css">
    {{-- Row Group CSS --}}
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    {{-- Form Validation --}}
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
@endpush

@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/js/tables-datatables-basic.js"></script>
    <script src="{{ asset('assets') }}/js/ui-modals.js"></script>
@endpush
