@extends('layouts.sales.app')
@section('title', 'Invoice')
@section('content')
    <div class="row invoice-preview">
        {{-- Invoice --}}
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                        <div class="mb-xl-0 pb-1">
                            <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                                <span class="app-brand-logo demo">
                                    <span style="color: var(--bs-primary)">
                                        <img class="text-md"
                                            src="{{ url('https://reftech.id/wp-content/uploads/2021/10/Reftech-Logo-Hitam.png') }}"
                                            alt="" srcset="" width="60%">
                                    </span>
                                </span>
                            </div>
                            <p class="mb-1 fw-bolder">PT Reftech Jaya Optima</p>
                            <div style="font-size: 10px">
                                <p class="mb-1">Taman Kopo Indah V, Ruko Sommerville No. 31</p>
                                <p class="mb-1">Bandung – Jawa Barat 40218</p>
                                <p class="mb-1">
                                    <i class="mdi mdi-phone-outline scaleX-n1-rtl me-1 mdi-14px"></i>022 54417653
                                    {{ '   ' }}<i
                                        class="mdi mdi-email-outline scaleX-n1-rtl me-1 mdi-14px"></i>info@reftech.id
                                </p>
                                <p class="mb-1">
                                </p>
                            </div>
                        </div>
                        <div class="text-end">
                            <h1 class="fw-bold" style="color: blue;">INVOICE</h1>
                            <div>
                                <span class="fw-bolder">#{{ $invoice->no_invoice }}</span>
                            </div>
                            <div class="mt-1">
                                <span
                                    class="text-muted">{{ Carbon\Carbon::parse($invoice->po_date)->format('d-m-Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body mb-3">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="fw-semibold fs-4 mb-3">Invoice To:</h6>
                        </div>
                        <div class="col-6 mb-2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 fw-medium">
                            <p class="mb-1">Bill To </p>
                            <p class="mb-1">Phone </p>
                            <p class="mb-1">Adress</p>
                        </div>
                        <div class="col-4">
                            <p class="mb-1">: {{ $quote->pic->client->company }}</p>
                            <p class="mb-1">: {{ $quote->pic->client->phone }}</p>
                            <p class="mb-1">: {{ $quote->pic->client->address }}</p>
                        </div>
                        <div class="col-3 fw-medium text-end">
                            <p class="mb-1">Purchase Order :</p>
                        </div>
                        <div class="col-3 text-end">
                            <p class="mb-1"> {{ $invoice->no_po }}
                            </p>
                        </div>
                        <div class="col-6"></div>
                        <div class="col-6 text-center">
                            <div class="title" style="border: 1px solid black; background-color: #F9F9F9;">
                                <p class="fs-5 text-black fw-medium m-0">Term Of Payment:</p>
                            </div>
                            <div class="term" style="border: 1px solid black; border-top: 0;">
                                <pre
                                    style="font-size: 16px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $invoice->term }}</pre>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead class="table-light border-top">
                            <tr>
                                <th>No.</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Discount</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 0;
                            @endphp
                            @foreach ($dquote as $product)
                                @php
                                    $no++;
                                @endphp
                                <tr style="font-size: 13px">
                                    <td class="align-top">{{ $no }}</td>
                                    <td class="text-nowrap align-top">
                                        <p class="mb-0 fw-semibold" style="font-size: 12px">
                                            {{ $product->product }}
                                        </p>
                                        <pre class="mb-0"
                                            style="font-size: 10px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $product->detail_product }}</pre>
                                    </td>
                                    <td class="align-top text-end">RP {{ number_format($product->price, 0, '', '.') }}</td>
                                    <td class="align-top">{{ $product->qty }} {{ $product->info_qty }} </td>
                                    <td class="align-top">{{ $product->disc }}%</td>
                                    <td class="align-top text-end">RP {{ number_format($product->amount, 0, '', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="align-top px-4 py-5">
                                    <span>Thanks for your business</span>
                                </td>
                                <td colspan="2" class="text-end pl-4 py-5" style="padding-right: 0 !important;">
                                    <p class="mb-2">Subtotal:</p>
                                    <p class="mb-2">Discount Quote:</p>
                                    @foreach ($payments as $payment)
                                        <p class="mb-2 py-2" style="background-color: yellow">{{ $payment->note }}:</p>
                                    @endforeach
                                    <p class="mb-2">Shipping Cost:</p>
                                    <p class="mb-2">Tax {{ $quote->tax == '11' ? '(11%)' : '' }}:</p>
                                    <p class="mb-0">Total:</p>
                                </td>
                                <td colspan="3" class="pr-4 py-5" style="padding-left: 0 !important;">
                                    <p class="fw-semibold mb-2 text-end">RP
                                        {{ number_format($quote->subtotal, 0, '', '.') }}</p>
                                    <p class="fw-semibold mb-2 text-end">RP
                                        {{ number_format($quote->diskon, 0, '', '.') }}
                                    </p>
                                    @foreach ($payments as $payment)
                                        <p class="fw-semibold mb-2 text-end  py-2" style="background-color: yellow"> RP
                                            {{ number_format($payment->amount, 0, '', '.') }}</p>
                                    @endforeach
                                    <p class="fw-semibold mb-2 text-end">RP
                                        {{ number_format($quote->shipping, 0, '', '.') }}</p>
                                    <p class="fw-semibold mb-2 text-end">
                                        {{ $tax == '0' ? '0' : 'RP ' . number_format($tax, 0, '', '.') }}</p>
                                    <p class="fw-semibold mb-0 text-end">RP
                                        {{ number_format($remaining, 0, '', '.') }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="fs-5 fw-medium" style="background-color: rgb(248, 248, 248);"> Say
                                    amount:
                                    # {{ $price }} Rupiah</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row mt-5">
                    <div class="col-6 m-4">
                        <h5 class="my-4">Payment by Transfer or Giro shall be made in Full amount to :</h5>
                        <div class="row">
                            <div class="col-3 fw-medium">
                                <p class="mb-1">Payable to</p>
                                <p class="mb-1">Acc Name </p>
                                <p class="mb-1">Acc No. </p>
                                <p class="mb-1">Swift Code </p>
                            </div>
                            <div class="col">
                                <p class="mb-1">: Bank BCA (IDR) - Asia Afrika Kota Bandung</p>
                                <p class="mb-1">: PT. REFTECH JAYA Optima</p>
                                <p class="mb-1">: 008 - 6289 - 789</p>
                                <p class="mb-1">: CENAIDJA</p>
                            </div>
                        </div>
                    </div>
                    <div class="col"></div>
                    <div class="col-4 my-5 text-center">
                        <p>Bandung, 16 Mei 2024</p>
                        <p class="fs-normal fw-bolder">PT. Reftech Jaya Optima</p>
                        <img src="{{ asset('/asset') }}/contract\sign-ariep.jpeg" alt="" srcset=""
                            style="width: 100px; height: 77px;">
                        {{-- <div class="pb-5"></div> --}}
                        <p class="pt-3 fw-bolder">Ariep Rachman</p>
                        <p>Director</p>
                    </div>
                </div>
            </div>
        </div>
        {{-- End: Invoice --}}
        {{-- Button Invocie --}}
        <div class="col-xl-3 col-md-4 col-12 invoice-actions">
            <div class="card mb-3">
                <div class="card-body">

                    <a class="btn btn-primary btn-outline-secondary d-grid w-100 mb-3 waves-effect" target="_blank"
                        href="{{ route('print.invoice', $invoice->id) }}">
                        Print
                    </a>
                    <a href="#" class="btn btn-outline-danger d-grid w-100 waves-effect delete-invoice mb-3"
                        data-id="{{ $quote->id }}">Delete</a>
                    <button class="btn btn-outline-secondary d-grid w-100 mb-3 waves-effect" id="backButton">
                        Back
                    </button>
                </div>
            </div>
            {{-- End : Button Invoice --}}
        </div>
    @endsection
    @push('after-style')
        <!-- Page CSS -->
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
    @endpush
    @push('after-script')
        <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
    @endpush
    @push('page-script')
        <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
    @endpush
    @push('script')
        <script>
            // $(document).on('click', '.delete-contract', function() {
            //     var id = $(this).data('id');
            //     var quoteId = $(this).data('quote');
            //     Swal.fire({
            //         title: "Are you sure?",
            //         text: "You won't be able to revert this!",
            //         icon: "warning",
            //         showCancelButton: true,
            //         confirmButtonText: "Yes, delete it!",
            //         customClass: {
            //             confirmButton: "btn btn-primary me-3 waves-effect waves-light",
            //             cancelButton: "btn btn-label-secondary waves-effect",
            //         },
            //         buttonsStyling: false,
            //     }).then(function(result) {
            //         if (result.value) {
            //             $.ajax({
            //                 'url': '{{ url('contract') }}/' + id,
            //                 'type': 'POST',
            //                 'data': {
            //                     '_method': 'DELETE',
            //                     '_token': '{{ csrf_token() }}'
            //                 },
            //                 success: function(response) {
            //                     if (response == 1) {
            //                         Swal.fire({
            //                             icon: "success",
            //                             title: "Deleted!",
            //                             text: "Your file has been deleted.",
            //                             customClass: {
            //                                 confirmButton: "btn btn-success waves-effect",
            //                             },
            //                         })
            //                         window.setTimeout(function() {
            //                             window.location.href = '/quotation/' + quoteId;
            //                         }, 2000);
            //                     } else {
            //                         Swal.fire({
            //                             icon: 'error',
            //                             title: 'Oops...',
            //                             text: 'Data Failed to Delete!'
            //                         });
            //                     }
            //                 }
            //             });
            //         } else if (result.dismiss === Swal.DismissReason.cancel) {
            //             Swal.fire({
            //                 title: "Cancelled",
            //                 text: "Your imaginary file is safe :)",
            //                 icon: "error",
            //                 customClass: {
            //                     confirmButton: "btn btn-success waves-effect",
            //                 },
            //             });
            //         }
            //     });
            // });
            $('#backButton').click(function() {
                window.history.back();
            });
        </script>
    @endpush
