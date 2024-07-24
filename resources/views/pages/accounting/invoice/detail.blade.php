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
                            @if ($invoice->invoiceTo == '1')
                                <p class="mb-1">: {{ $quote->pic->client->address }}</p>
                            @else
                                <p class="mb-1">: {{ $quote->pic->client->subAddress }}</p>
                            @endif
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-6 fw-medium text-end">
                                    <p class="mb-1">Purchase Order :</p>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="mb-1"> {{ $invoice->no_po }}
                                    </p>
                                </div>
                                <div class="col-12 text-center">
                                    <div class="termpay">
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
                                @if ($invoice->type == 'CT')
                                    <td colspan="2" class="text-end pl-4 py-5" style="padding-right: 0 !important;">
                                        <p class="mb-2">Total:</p>
                                        @if ($quote->diskon != 0)
                                            <p class="mb-2">Discount:</p>
                                            <p class="mb-2">Total After Discount:</p>
                                        @endif
                                        @if ($quote->shipping != 0)
                                            <p class="mb-2">Shipping Cost:</p>
                                        @endif
                                        @if ($quote->tax != 0)
                                            <p class="mb-2">VAT {{ $quote->tax == '11' ? '11%' : '' }}:</p>
                                            <p class="mb-0">Total Include VAT:</p>
                                        @endif
                                    </td>
                                    <td colspan="3" class="pr-4 py-5" style="padding-left: 0 !important;">
                                        <p class="fw-semibold mb-2 text-end">RP
                                            {{ number_format($quote->subtotal, 0, '', '.') }}</p>
                                        @if ($quote->diskon != 0)
                                            <p class="fw-semibold mb-2 text-end">RP
                                                {{ number_format($quote->diskon, 0, '', '.') }}
                                            </p>
                                            <p class="fw-semibold mb-2 text-end">RP
                                                {{ number_format($afterDisc, 0, '', '.') }}
                                            </p>
                                        @endif
                                        @if ($quote->shipping != 0)
                                            <p class="fw-semibold mb-2 text-end">RP
                                                {{ number_format($quote->shipping, 0, '', '.') }}
                                            </p>
                                        @endif
                                        @if ($quote->tax != 0)
                                            <p class="fw-semibold mb-2 text-end">
                                                {{ $tax == '0' ? '0' : 'RP ' . number_format($tax, 0, '', '.') }}</p>
                                            <p class="fw-semibold mb-2 text-end">
                                                {{ $tax == '0' ? '0' : 'RP ' . number_format($quote->harga_total, 0, '', '.') }}
                                            </p>
                                        @endif
                                    </td>
                                @elseif ($invoice->type == 'DP')
                                    <td colspan="2" class="text-end pl-4 py-5" style="padding-right: 0 !important;">
                                        <p class="mb-2">Total:</p>
                                        @if ($quote->diskon != 0)
                                            <p class="mb-2">Discount:</p>
                                            <p class="mb-2">Total After Discount:</p>
                                        @endif
                                        @if ($quote->shipping != 0)
                                            <p class="mb-2">Shipping Cost:</p>
                                        @endif
                                        <p class="mb-2 py-2" style="background-color: yellow">{{ $payments[0]->note }}
                                            {{ $payments[0]->percent }}%:</p>
                                        @if ($payments->count() >= 1)
                                            <p class="mb-2">VAT {{ $quote->tax == '11' ? '11%' : '' }}:</p>
                                        @else
                                            <p class="mb-2">VAT {{ $quote->tax == '11' ? '11%' : '' }}:</p>
                                        @endif
                                        <p class="mb-0">Total Include VAT:</p>
                                    </td>
                                    <td colspan="3" class="pr-4 py-5" style="padding-left: 0 !important;">
                                        @php
                                            $amount1 = $payments[0]->amount / (1 + $quote->tax / 100);
                                            $vat = $amount1 * ($quote->tax / 100);
                                        @endphp
                                        <p class="fw-semibold mb-2
                                        text-end">RP
                                            {{ number_format($quote->total_no_tax, 0, '', '.') }}</p>
                                        @if ($quote->diskon != 0)
                                            <p class="fw-semibold mb-2 text-end">RP
                                                {{ number_format($quote->diskon, 0, '', '.') }}
                                            </p>
                                            <p class="fw-semibold mb-2 text-end">RP
                                                {{ number_format($afterDisc, 0, '', '.') }}
                                            </p>
                                        @endif
                                        @if ($quote->shipping != 0)
                                            <p class="fw-semibold mb-2 text-end">RP
                                                {{ number_format($quote->shipping, 0, '', '.') }}
                                            </p>
                                        @endif
                                        <p class="fw-semibold mb-2 text-end  py-2" style="background-color: yellow">
                                            RP
                                            {{ number_format($amount1, 0, '', '.') }}</p>
                                        @if ($quote->tax != 0)
                                            <p class="fw-semibold mb-2 text-end">
                                                {{ $vat == '0' ? '0' : 'RP ' . number_format($vat, 0, '', '.') }}</p>
                                            <p class="fw-semibold mb-0 text-end">RP
                                                {{ number_format($payments[0]->amount, 0, '', '.') }}</p>
                                        @else
                                            <p class="fw-semibold mb-0 text-end">RP
                                                {{ number_format($payments[0]->amount, 0, '', '.') }}</p>
                                        @endif
                                    </td>
                                @elseif ($invoice->type == 'BP')
                                    <td colspan="2" class="text-end pl-4 py-5" style="padding-right: 0 !important;">
                                        <p class="mb-2">Total:</p>
                                        @if ($quote->diskon != 0)
                                            <p class="mb-2">Discount:</p>
                                            <p class="mb-2">Total After Discount:</p>
                                        @endif
                                        @if ($quote->shipping != 0)
                                            <p class="mb-2">Shipping Cost:</p>
                                        @endif
                                        <p class="mb-2 py-2">{{ $payments[0]->note }}
                                            {{ $payments[0]->percent }}%:</p>
                                        <p class="mb-2 py-2" style="background-color: yellow">{{ @$payments[1]->note }}
                                            {{ @$payments[1]->percent }}%:</p>
                                        @if ($payments->count() >= 1)
                                            <p class="mb-2">VAT {{ $quote->tax == '11' ? '11%' : '' }}:</p>
                                        @else
                                            <p class="mb-2">VAT {{ $quote->tax == '11' ? '11%' : '' }}:</p>
                                        @endif
                                        <p class="mb-0">Total Include VAT:</p>
                                    </td>
                                    <td colspan="3" class="pr-4 py-5" style="padding-left: 0 !important;">
                                        @php
                                            $amount1 = $payments[0]->amount / (1 + $quote->tax / 100);
                                            $amount2 = @$payments[1]->amount ?? 0 / (1 + $quote->tax / 100);
                                            $vat = $amount2 * ($quote->tax / 100);
                                        @endphp
                                        <p class="fw-semibold mb-2
                                    text-end">RP
                                            {{ number_format($quote->total_no_tax, 0, '', '.') }}</p>
                                        @if ($quote->diskon != 0)
                                            <p class="fw-semibold mb-2 text-end">RP
                                                {{ number_format($quote->diskon, 0, '', '.') }}
                                            </p>
                                            <p class="fw-semibold mb-2 text-end">RP
                                                {{ number_format($afterDisc, 0, '', '.') }}
                                            </p>
                                        @endif
                                        @if ($quote->shipping != 0)
                                            <p class="fw-semibold mb-2 text-end">RP
                                                {{ number_format($quote->shipping, 0, '', '.') }}
                                            </p>
                                        @endif
                                        <p class="fw-semibold mb-2 text-end  py-2">
                                            RP
                                            {{ number_format($amount1, 0, '', '.') }}</p>
                                        <p class="fw-semibold mb-2 text-end  py-2" style="background-color: yellow">
                                            RP
                                            {{ number_format($amount2, 0, '', '.') }}</p>
                                        @if ($quote->tax != 0)
                                            <p class="fw-semibold mb-2 text-end">
                                                {{ $vat == '0' ? '0' : 'RP ' . number_format($vat, 0, '', '.') }}</p>
                                            <p class="fw-semibold mb-0 text-end">RP
                                                {{ number_format($payments[1]->amount, 0, '', '.') }}</p>
                                        @else
                                            <p class="fw-semibold mb-0 text-end">RP
                                                {{ number_format($payments[1]->amount, 0, '', '.') }}</p>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td colspan="3" class="fs-5 fw-medium" style="background-color: rgb(248, 248, 248);">
                                    Say
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
                            @if ($invoice->quote->tax != 0)
                                <div class="col">
                                    <p class="mb-1">: Bank BCA (IDR) - Asia Afrika Kota Bandung</p>
                                    <p class="mb-1">: ARIEP RACHMAN</p>
                                    <p class="mb-1">: 166 - 2242 - 271</p>
                                    <p class="mb-1">: -</p> 
                                </div>
                            @else
                                <div class="col">
                                    <p class="mb-1">: Bank BCA (IDR) - Asia Afrika Kota Bandung</p>
                                    <p class="mb-1">: PT. REFTECH JAYA Optima</p>
                                    <p class="mb-1">: 008 - 6289 - 789</p>
                                    <p class="mb-1">: CENAIDJA</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col"></div>
                    <div class="col-4 my-5 text-center">
                        <p>Bandung, 16 Mei 2024</p>
                        <p class="fs-normal fw-bolder">PT. Reftech Jaya Optima</p>
                        @if (isset($invoice->sign))
                            <img src="{{ url('') . '/' . $invoice->sign }}" alt="" srcset=""
                                height="77">
                        @else
                            <div class="pb-5"></div>
                        @endif
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
                        Download
                    </a>
                    <a href="#" class="btn btn-outline-danger d-grid w-100 waves-effect delete-invoice mb-3"
                        data-id="{{ $quote->id }}">Delete</a>
                    <button class="btn btn-outline-secondary d-grid w-100 mb-3 waves-effect" id="backButton">
                        Back
                    </button>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    @if (isset($invoice->sign))
                        <a href="#" class="btn btn-danger d-grid w-100 waves-effect delete-hand-sign mb-3"
                            data-id="{{ $invoice->id }}">Delete Hand Sign</a>
                    @else
                        <a type="button" data-bs-toggle="modal" data-bs-target="#inputSign-{{ $invoice->id }}"
                            class="d-grid w-100 waves-effect mb-3">
                            <button type="button" class="btn btn-secondary">
                                Input Hand Sign
                            </button>
                        </a>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-secondary w-100 waves-effect waves-light mb-3"
                        data-bs-toggle="modal" data-bs-target="#detailPayment"> Detail Payment </button>
                    <h5>Remaining : Rp {{ number_format($remaining, 0, '.', ',') }}</h5>
                </div>
            </div>
            {{-- End : Button Invoice --}}
        </div>
        @include('components.modal.quotation.detail-payment')
        @include('components.modal.accounting.sign')
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
            $(() => {
                $('#formFileMultiple').on('change', function() {
                    var files = this.files;
                    var dynamicInputsContainer = $('#dynamicInputsContainer');
                    dynamicInputsContainer.empty();

                    // Hanya mengambil satu file (file pertama)
                    var file = files[0];
                    console.log(file);
                    const previewContainer = document.getElementById('image-preview');
                    previewContainer.innerHTML = '';

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const imageContainer = document.createElement('div');
                        const imageElement = document.createElement('img');
                        imageContainer.className = 'image-container'; // Tambahkan kelas sesuai kebutuhan

                        // Set maksimum lebar dan tinggi untuk gambar
                        imageElement.style.maxWidth =
                            '800px'; // Ganti dengan nilai maksimum lebar yang Anda inginkan
                        imageElement.style.maxHeight =
                            '500px'; // Ganti dengan nilai maksimum tinggi yang Anda inginkan

                        imageElement.src = e.target.result;

                        imageContainer.appendChild(imageElement);
                        previewContainer.appendChild(imageContainer);
                    };

                    reader.readAsDataURL(file);
                });
            });
            $(document).on('click', '.delete-invoice', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    customClass: {
                        confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                        cancelButton: "btn btn-label-secondary waves-effect",
                    },
                    buttonsStyling: false,
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            'url': '{{ url('invoice') }}/' + id,
                            'type': 'POST',
                            'data': {
                                '_method': 'DELETE',
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response == 1) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Deleted!",
                                        text: "Your file has been deleted.",
                                        customClass: {
                                            confirmButton: "btn btn-success waves-effect",
                                        },
                                    })
                                    window.setTimeout(function() {
                                        window.location.href = '/quotation';
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Data Failed to Delete!'
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
