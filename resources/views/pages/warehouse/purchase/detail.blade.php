@extends('layouts.sales.app')
@section('title', 'Detail Quotation')
@section('content')
    <div class="row invoice-preview">
        {{-- Invoice --}}
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">

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
                                    switch ($pending->charged) {
                                        case 1:
                                            $charged = 'Company';
                                            break;
                                        case 2:
                                            $charged = 'Customer';
                                            break;
                                        default:
                                            $charged = '';
                                            break;
                                    }
                                @endphp
                                {{-- <div class="col-4">Kurir</div>
                                <div class="col-8">
                                    : {{ $delivery }} {{ $pending->charged ? "($charged)" : '' }}
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">No Quotation</div>
                                @php
                                    if ($quotation->type == 'Sparepart') {
                                        $link = 'quotation.show';
                                    } elseif ($quotation->type == 'Overhaul') {
                                        $link = 'show-overhaul.quotation';
                                    } else {
                                        $link = 'show-service.quotation';
                                    }

                                @endphp
                                <div class="col-8">: <a class="text-dark cursor-pointer"
                                        href="{{ route($link, $quotation->id) }}">{{ $quotation->no_quote }}</a>
                                </div>
                                <div class="col-4">No Invoice</div>
                                <div class="col-8">:
                                    @if (@$invoice->no_invoice)
                                        <a class="text-dark cursor-pointer"
                                            href="{{ route('invoice.show', $invoice->id) }}">
                                            {{ $invoice->no_invoice }}
                                        </a>
                                    @else
                                        Belum ada invoice
                                    @endif
                                </div>
                                <div class="col-4">No SO</div>
                                <div class="col-8">: <a class="text-dark cursor-pointer"
                                        href="{{ route('pending-po.show', $pending->id) }}">
                                        {{ $pending->no_pending }}
                                    </a></div>
                                <div class="col-4">Payment Info</div>
                                <div class="col-8">:
                                    {{ $invoice ? ($invoice->status_p == 1 ? 'Payment Confirmed' : 'Unpaid') : 'Belum ada invoice' }}
                                </div>
                                <div class="col-4">PO Date</div>
                                <div class="col-8">: {{ \Carbon\Carbon::parse($quotation->po_date)->format('d-m-Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="fw-medium card-title mb-3">
                            Purchase Request
                        </h4>
                        <div class="tombol">
                            @if ($purchase->where('status', 0)->count() > 0)
                                <a href="#" class="btn btn-info d-grid w-100 waves-effect acc-all-purchase"
                                    data-id="{{ $pending->id }}">ACC All</a>
                            @elseif($purchase->where('status', 1)->count() > 0)
                                <a href="#" class="btn btn-twitter d-grid w-100 waves-effect delivery-all-purchase"
                                    data-id="{{ $pending->id }}">On
                                    Delivery All</a>
                            @endif
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Note</th>
                                @if (Auth::user()->role != 'Logistic')
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $no = 1;
                            @endphp
                            @forelse ($purchase as $pr)
                                @php
                                    // switch ($item->status) {
                                    //     case 1:
                                    //         $status = 'On Check';
                                    //         break;
                                    //     case 2:
                                    //         $status = 'Ready Stock';
                                    //         break;
                                    //     case 3:
                                    //         $status = 'Kurang';
                                    //         break;
                                    //     case 4:
                                    //         $status = 'Pre-Order';
                                    //         break;
                                    //     case 5:
                                    //         $status = 'Delivery Process';
                                    //         break;
                                    //     case 6:
                                    //         $status = 'Done';
                                    //         break;
                                    //     default:
                                    //         $status = 'Belum Di Cek';
                                    //         break;
                                    // }
                                @endphp
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>
                                        @if ($pr->id_equivalent == '0')
                                            -
                                        @else
                                            {{ $pr->equivalent->brand }} {{ $pr->equivalent->pn }}
                                        @endif
                                    </td>
                                    {{-- <td>
                                    <pre class="mb-0"
                                        style="font-size: 15px; font-family: 'Inter', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $item->detail_product }}</pre>
                                </td> --}}
                                    <td>{{ $pr->qty }} {{ $pr->equivalent->product->unit }}</td>
                                    <td>{{ $pr->note }}</td>
                                    @if (Auth::user()->role != 'Logistic')
                                        <td>
                                            @if ($pr->status == 0)
                                                <a href="#"
                                                    class="btn btn-info d-grid w-100 waves-effect acc-purchase"
                                                    data-id="{{ $pr->id }}"
                                                    data-pending="{{ $pending->id }}">ACC</a>
                                            @elseif($pr->status == 1)
                                                <a href="#"
                                                    class="btn btn-twitter d-grid w-100 waves-effect delivery-purchase"
                                                    data-id="{{ $pr->id }}" data-pending="{{ $pending->id }}">On
                                                    Delivery</a>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                                @php
                                    $no++;
                                @endphp
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak Ada Purchase Request</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- End: Invoice --}}
        {{-- Button Invocie --}}
        <div class="col-xl-3 col-md-4 col-12 invoice-actions">

            <div class="card mb-3">
                @php
                    $stat = $purchase->every(fn($p) => $p->status == 2) ? 1 : 0;
                @endphp
                <div class="card-body">
                    <a class="btn btn-primary d-grid w-100 mb-3 waves-effect {{ $stat == 1 ? '' : 'disabled' }}"
                        href="{{ $stat == 1 ? route('purchase-request.done-all', $pending->id) : '#' }}"
                        tabindex="{{ $stat == 1 ? '0' : '-1' }}" aria-disabled="{{ $stat == 1 ? 'false' : 'true' }}">
                        Cetak Product In
                    </a>
                    @if (Auth::user()->role != 'Logistic')
                        <a href="#" class="btn btn-outline-danger d-grid w-100 waves-effect delete-invoice mb-3"
                            data-id="{{ $quotation->id }}">Delete</a>
                    @endif
                    <button class="btn btn-outline-secondary d-grid w-100 mb-3 waves-effect" id="backButton">
                        Back
                    </button>
                </div>
            </div>
        </div>
        {{-- @endif --}}
    </div>
    {{-- End : Button Invoice --}}
    </div>
@endsection
@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/dropzone/dropzone.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/dropzone/dropzone.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
@endpush
@push('page-script')
    <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
@endpush
@push('script')
    <script>
        $('#backButton').click(function() {
            window.history.back();
        });
        $(document).on('click', '.acc-purchase', function() {
            var id = $(this).data('id');
            var pending = $(this).data('pending');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to acc this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Acc it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('purchase-request') }}/acc/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'PATCH',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Acc succed!",
                                    text: "Your file has been acc.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/purchase-request/' +
                                        pending;
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Acc!'
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
        $(document).on('click', '.acc-all-purchase', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to acc this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Acc it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url(path: 'purchase-request') }}/acc-all/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'PATCH',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Acc succed!",
                                    text: "Your file has been acc.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/purchase-request/' +
                                        id;
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Acc!'
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
        $(document).on('click', '.delivery-purchase', function() {
            var id = $(this).data('id');
            var pending = $(this).data('pending');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to deliverry this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delivery it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('purchase-request') }}/delivery/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'PATCH',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Delivery succed!",
                                    text: "Your file has been deliveried.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/purchase-request/' +
                                        pending;
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Derlivery!'
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
        $(document).on('click', '.delivery-all-purchase', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to delivery this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Delivery it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url(path: 'purchase-request') }}/delivery-all/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'PATCH',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Delivery succed!",
                                    text: "Your file has been delivery.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/purchase-request/' +
                                        id;
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Delivery!'
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
