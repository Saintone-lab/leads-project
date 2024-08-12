@extends('layouts.sales.app')
@section('title', 'Detail Overview Sales')
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        Detail Overview {{ $user->name }}, {{ $dates }}
    </h4>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    <img src="{{ url('') . '/' . $user->image }}" alt="" srcset="" class="h-100 w-100">
                </div>
                <div class="col-12 col-md-9">
                    <div class="row">
                        <div class="col-12">
                            <h4>{{ $user->name }}</h4>
                        </div>
                        <div class="col-4">
                            <p class="fw-medium fs-normal">Key Performance Indicator</p>
                            <div class="d-flex mb-2 gap-2">
                                <a href="#activities">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-info rounded">
                                            <i class="mdi mdi-phone-outline mdi-24px"></i>
                                        </div>
                                    </div>
                                </a>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $totalDC }} <span
                                            class="text-muted fs-tiny fw-normal">/{{ $target->dc }}</span>
                                    </h5>
                                    <small
                                        class="text-muted">{{ $user->id == '1' ? 'New Leads' : 'Daily Call' }}</small>
                                </div>
                            </div>
                            <div class="d-flex mb-2 gap-2">
                                <a href="#activities">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-primary rounded">
                                            <i class="mdi mdi-account-multiple-outline mdi-24px"></i>
                                        </div>
                                    </div>
                                </a>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $totalCRM }}<span
                                            class="text-muted fs-tiny fw-normal">/{{ $target->crm }}</span>
                                    </h5>
                                    <small class="text-muted">CRM</small>
                                </div>
                            </div>
                            @php
                                $lastDetail = $user->detail->last();
                            @endphp
                            @if ($lastDetail->area == 'Bekasi' || $lastDetail->area == 'Jabodetabek' || $lastDetail->area == 'Jawa Barat')
                                <div class="d-flex mb-2 gap-2">
                                    <a href="#activities">
                                        <div class="avatar">
                                            <div class="avatar-initial bg-label-danger rounded">
                                                <i class="mdi mdi-office-building-marker-outline mdi-24px"></i>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $totalVisit }}<span
                                                class="text-muted fs-tiny fw-normal">/{{ $target->visit }}</span>
                                        </h5>
                                        <small class="text-muted">Visit</small>
                                    </div>
                                </div>
                            @endif
                            <div class="d-flex mb-2 gap-2">
                                <a href="#quote">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-warning rounded">
                                            <i class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                        </div>
                                    </div>
                                </a>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $totalQuote }}<span
                                            class="text-muted fs-tiny fw-normal">/{{ $target->quote }}</span>
                                    </h5>
                                    <small class="text-muted">Quotation</small>
                                </div>
                            </div>
                            <div class="d-flex mb-2 gap-2">
                                <a href="#po">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-success rounded">
                                            <i class="mdi mdi-cart-plus mdi-24px"></i>
                                        </div>
                                    </div>
                                </a>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ $totalPO }}<span
                                            class="text-muted fs-tiny fw-normal">/{{ $target->po }}</span>
                                    </h5>
                                    <small class="text-muted">PO</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-8">
                            <p class="fw-medium fs-normal">Achievement</p>

                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex mb-2 gap-2">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-success rounded">
                                            <i class="mdi mdi-cart-plus mdi-24px"></i>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">Rp
                                            {{ number_format($amountSales, 2, ',', '.') }}
                                            @php
                                                $jumlah_target = [];
                                                if (isset($target->total) && $target->total != 0) {
                                                    $jumlah_target = ($amountSales / $target->total) * 100;
                                                } else {
                                                    $jumlah_target = 0;
                                                }
                                            @endphp
                                            <span class="text-success mb-0">
                                                {{ number_format($jumlah_target, 3) }}%
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
                                            <i class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">
                                            Rp
                                            {{ number_format($amountQuote, 2, ',', '.') }}
                                        </h5>
                                        <small class="text-muted">Quotation</small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex mb-2 gap-2">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-warning rounded">
                                            <i class="mdi mdi-email-alert-outline mdi-24px"></i>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">Rp {{ number_format($amountProspect, 2, ',', '.') }}
                                        </h5>
                                        <small class="text-muted">Hot Prospect</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6 mb-3">
            <div class="card" id="activities">
                <div class="card-datatable table-responsive pt-0">
                    <table class="datatable-overview-call table table-striped" id="dataTableCrm">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>ID</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Note</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
                    <table class="datatable-overview-crm table table-striped" id="dataTableCrm">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>ID</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Note</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="card" id="quote">
                <div class="card-datatable table-responsive pt-0">
                    <table class="datatable-overview-quotation table table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>ID</th>
                                <th>Quote No.</th>
                                <th>Company</th>
                                <th>Total Price</th>
                                <th>Description</th>
                                <th>Date Quotation</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="card" id="quote">
                <div class="card-datatable table-responsive pt-0">
                    <table class="datatable-overview-po table table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>ID</th>
                                <th>Quote No.</th>
                                <th>Company</th>
                                <th>Description</th>
                                <th>Date Quotation</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        {{-- <div class="col-12 mb-3">
            <div class="card" id="po">
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>PO No.</th>
                                <th>Company</th>
                                <th>Title</th>
                                <th>PO Date</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $totalP = 0;
                                $key = 0;
                            @endphp
                            @forelse ($quotation as $quote)
                                @php
                                    $totalQ = $quote->nett;
                                    $totalP += $totalQ;
                                @endphp
                                <tr>
                                    <td class="fw-medium">
                                        <a class="text-black"
                                            href="{{ route('quotation.show', $quote->id) }}">{{ $quote->no_quote }}</a>
                                    </td>
                                    <td>{{ $quote->pic->client->company }}</td>
                                    <td>{{ $quote->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($quote->po_date)->format('d-m-Y') }}</td>
                                    <td class="text-end">Rp
                                        {{ number_format($quote->nett, 0, '', '.') }}</td>
                                </tr>
                                @php
                                    $key++;
                                @endphp
                            @empty
                                <td colspan="5" class="text-center">Kamu belum punya quotation</td>
                            @endforelse
                            <tr class="bg-label-secondary">
                                <td colspan="3">
                                </td>
                                <td><strong>Total</strong></td>
                                <td class="text-end"><strong>Rp {{ number_format($totalP, 0, '', '.') }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}
    </div>
@endsection


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
    <script src="{{ asset('assets') }}/includes/table-overview-call.js"></script>
    <script src="{{ asset('assets') }}/includes/table-overview-crm.js"></script>
    <script src="{{ asset('assets') }}/includes/table-overview-quotation.js"></script>
    <script src="{{ asset('assets') }}/includes/table-overview-po.js"></script>
@endpush

@push('script')
    <script>
        // Initialize Bootstrap tooltips using jQuery
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
