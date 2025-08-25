@extends('layouts.sales.app')
@section('title', 'Marketing Tools')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h5 class="fw-bold pb-1 mb-3">
            Detail Of {{ $invoice->no_po ?? $quotation->pic->client->company }}
        </h5>
        <div class="tombol">
            <button type="button" data-bs-toggle="modal" data-bs-target="#deliveryEdit"
                class="btn btn-info"{{ auth::user()->role != 'Sales' ? '' : 'disabled' }}>
                Update Kurir
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusEdit"
                {{ auth()->user()->role != 'Sales' ? '' : 'disabled' }}>
                Update Status Barang
            </button>
            <a href="{{ route('pending-po.index') }}" type="button" class="btn btn-secondary"> Back </a>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">Sales</div>
                        <div class="col-8">: {{ $quotation->sales->name }}</div>
                        <div class="col-4">Flag</div>
                        <div class="col-8">: {{ $quotation->pic->client->info }}</div>
                        <div class="col-4">Client</div>
                        <div class="col-8">: {{ $quotation->pic->client->company }}</div>
                        <div class="col-4">PIC</div>
                        <div class="col-8">: {{ $quotation->pic->name_pic }}</div>
                        <div class="col-4">Address</div>
                        <div class="col-8">: {{ $quotation->pic->client->address }}</div>
                        @php
                            switch ($pending->delivery) {
                                case 1:
                                    $delivery = 'Kurir';
                                    break;
                                case 2:
                                    $delivery = 'Teknisi';
                                    break;
                                case 3:
                                    $delivery = 'Direct';
                                    break;
                                case 4:
                                    $delivery = 'Other';
                                    break;
                                default:
                                    $delivery = 'Error';
                                    break;
                            }
                        @endphp
                        <div class="col-4">Kurir</div>
                        <div class="col-8">: {{ $delivery }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">No Quotation</div>
                        <div class="col-8">: {{ $quotation->no_quote }}</div>
                        <div class="col-4">No Invoice</div>
                        <div class="col-8">: {{ $invoice->no_invoice ?? 'Belum ada invoice' }}</div>
                        <div class="col-4">Payment Info</div>
                        <div class="col-8">:
                            {{ $invoice ? ($invoice->status_p == 1 ? 'Payment Confirmed' : 'Unpaid') : 'Belum ada invoice' }}
                        </div>
                        <div class="col-4">PO Date</div>
                        <div class="col-8">: {{ \Carbon\Carbon::parse($quotation->po_date)->format('d-m-Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3" style="display: flex; justify-content: flex-end;">
        <button type="button" class="btn btn-facebook float-end" data-bs-toggle="modal" data-bs-target="#productEdit"
            {{ auth()->user()->role != 'Sales' ? '' : 'disabled' }}>
            Update Status Barang
        </button>
        {{-- <a type="button" data-bs-toggle="modal" data-bs-target="#productEdit"
            {{ auth::user()->role != 'Sales' ? '' : 'disable' }}>
            <button type="button" class="btn btn-facebook float-end">
                
            </button>
        </a> --}}
    </div>
    <div class="card mb-4">
        <div class="table-responsive text-nowrap h-100">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th>Desc</th>
                        <th>G/R</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($detQuotation as $item)
                        @php
                            switch ($item->status) {
                                case 1:
                                    $status = 'On Check';
                                    break;
                                case 2:
                                    $status = 'Ready Stock';
                                    break;
                                case 3:
                                    $status = 'Kurang';
                                    break;
                                case 4:
                                    $status = 'Pre-Order';
                                    break;
                                case 5:
                                    $status = 'Delivery Process';
                                    break;
                                case 6:
                                    $status = 'Done';
                                    break;
                                default:
                                    $status = 'Belum Di Cek';
                                    break;
                            }
                        @endphp
                        <tr>
                            <td>{{ $no }}</td>
                            <td>
                                @if ($item->id_equivalent == '0')
                                    -
                                @else
                                    {{ $item->equivalent->brand }} {{ $item->equivalent->pn }}
                                @endif
                            </td>
                            <td>{{ $item->detail_product }}</td>
                            <td>{{ $item->equivalent->product->go }}</td>
                            <td>{{ $item->qty }} {{ $item->info_qty }}</td>
                            <td>{{ $status }}</td>
                            <td> {{ $item->note }}</td>
                        </tr>
                        @php
                            $no++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if ($activity->count() >= 1)
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="mb-0">Activity Timeline</h5>
                </div>
            </div>
            <div class="card-body pt-4" id="viewComment">
                <ul class="timeline card-timeline mb-0">
                    @foreach ($activity as $stats)
                        @php
                            if ($stats->status == '1') {
                                $status = 'Pending On Check';
                                $color = 'info';
                            } elseif ($stats->status == '2') {
                                $status = 'Updated in to Ready Stock';
                                $color = 'whatsapp';
                            } elseif ($stats->status == '3') {
                                $status = 'Updated in to Kurang';
                                $color = 'reddit';
                            } elseif ($stats->status == '4') {
                                $status = 'Updated in to Pre-Order';
                                $color = 'primary';
                            } elseif ($stats->status == '5') {
                                $status = 'Updated in to Delivery Process';
                                $color = 'linkedin';
                            } elseif ($stats->status == '6') {
                                $status = 'Pending is Done';
                                $color = 'success';
                            } elseif ($stats->status == '7') {
                                $status = 'Pending is Canceled';
                                $color = 'danger';
                            } else {
                                $status = 'Pending Created';
                                $color = 'info';
                            }
                        @endphp
                        <li class="timeline-item timeline-item-transparent clearfix">
                            <span class="timeline-point timeline-point-{{ $color }}"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">{{ $status }}</h6>
                                    <small
                                        class="text-muted">{{ $stats->date->diffInHours(Carbon\Carbon::now()) > 24 ? $stats->date->format('d M y h:i:s') : $stats->date->diffForHumans() }}
                                    </small>
                                </div>
                                <p class="mb-3">
                                    {{ $stats->note }}
                                </p>
                                @foreach ($stats->comment as $item)
                                    <div class="d-flex justify-content-between align-items-center px-2 mb-2{{ $item->id_user == Auth::user()->id ? ' rounded bg-label-primary float-end' : '' }}"
                                        style="width : 80%;">
                                        <div class="d-flex align-items-center mb-1">
                                            <img src="{{ url('') . '/' . $item->user->image }}" alt="ini photo"
                                                style="width: 50px;" class="mx-2 rounded-pill">
                                            <p class="mb-0">
                                                <span class="fw-medium">{{ $item->user->name }}</span>:
                                                {{ $item->comment }}
                                            </p>
                                        </div>
                                        <small
                                            class="text-muted">{{ $item->date->diffInHours(Carbon\Carbon::now()) > 24 ? $item->date->format('d M y h:i:s') : $item->date->diffForHumans() }}</small>
                                    </div>
                                @endforeach
                                @php
                                    $lastStat = App\Models\ChangeStatus::where('id_pending', $pending->id)
                                        ->orderByDesc('id')
                                        ->first();
                                @endphp
                            </div>
                        </li>
                        @if ($stats->id == $lastStat->id)
                            <form action="{{ route('pending-po.addComment', $pending->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-floating mt-3">
                                    <input type="text" class="form-control" id="floatingInputFilled"
                                        placeholder="Comment" name="comment" aria-describedby="floatingInputFilledHelp">
                                    <label for="floatingInputFilled">Comment</label>
                                    <span class="form-floating-focused"></span>
                                </div>
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light float-end">Comment</button>
                            </form>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    {{-- @foreach ($pendingPO as $pending)
        @include('components.modal.pending.detail')
    @endforeach --}}
    @include('components.modal.pending.status')
    @include('components.modal.pending.kurir')
    @include('components.modal.pending.product')
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
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/tagify/tagify.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/bloodhound/bloodhound.js"></script>
@endpush

@push('page-script')
    <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
    <script src="{{ asset('assets') }}/js/tables-datatables-basic.js"></script>
    <script src="{{ asset('assets') }}/includes/table-pending-non-project-admin.js"></script>
    <script src="{{ asset('assets') }}/includes/table-pending.js"></script>
    <script src="{{ asset('assets') }}/js/forms-selects.js"></script>
@endpush

@push('script')
    <script>
        // Initialize Bootstrap tooltips using jQuery
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        $(document).on('click', '.button-clear', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure to Clear it??",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Cleared it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('notulen') }}/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'PATCH',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Cleared!",
                                    text: "This Notulen has been Cleared.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/notulen';
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Cleared!'
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
