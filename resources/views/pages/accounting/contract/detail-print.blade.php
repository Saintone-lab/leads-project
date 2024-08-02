@extends('layouts.sales.app')
@section('title', $sellcon->no_contract)
<div class="invoice-print p-4">
    <div class="container-fluid flex-grow-1 container-p-y">
        @if ($sellcon->type == 'Selling')
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
                            {{ '  |  ' }}<i
                                class="mdi mdi-email-outline scaleX-n1-rtl me-1 mdi-14px"></i>info@reftech.id
                        </p>
                        <p class="mb-1">
                        </p>
                    </div>
                </div>
                <div class="text-end">
                    <h3 class="fw-bold">SELLING CONTRACT</h3>
                    <div>
                        <span class="fw-bolder">#{{ $sellcon->no_contract }}</span>
                    </div>
                    <div class="mt-1">
                        <span class="text-muted">{{ Carbon\Carbon::parse($sellcon->date)->format('d-m-Y') }}</span>
                    </div>
                </div>
            </div>
        @else
            <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                <div class="mb-xl-0 pb-1">
                    <div class="d-flex svg-illustration align-items-center gap-2 mb-2">
                        <span class="app-brand-logo demo">
                            <span style="color: var(--bs-primary)">
                                <img class="text-md" src="{{ asset('/asset') }}/logo/Logo-update-size.png"
                                    alt="" srcset="" width="60%">
                            </span>
                        </span>
                    </div>
                    <p class="mb-1 fw-bolder">PT Kojisha Innotiv Indonesia</p>
                    <div style="font-size: 10px">
                        <p class="mb-1">Jl. Nancep No. 45A, Setu</p>
                        <p class="mb-1">Cibitung - Kab. Bekasi 17320</p>
                        <p class="mb-1">
                            <i class="mdi mdi-phone-outline scaleX-n1-rtl me-1 mdi-14px"></i>+62 812-1000-0997
                            {{ ' | ' }}<i
                                class="mdi mdi-email-outline scaleX-n1-rtl me-1 mdi-14px"></i>admin@kojisha.com
                        </p>
                    </div>
                </div>
                <div class="text-end">
                    <h3 class="fw-bold">Confirm Order</h3>
                    <div>
                        <span class="fw-bolder">#{{ $sellcon->no_contract }}</span>
                    </div>
                    <div class="mt-1">
                        <span
                            class="text-muted">{{ Carbon\Carbon::parse($quote->estimated_date)->format('d-m-Y') }}</span>
                    </div>
                </div>
            </div>
        @endif
        <hr>
        <div class="mb-4">
            <div class="row">
                <div class="col-6">
                    <h6 class="fw-semibold fs-4 mb-3">Quote To:</h6>
                </div>
                <div class="col-6 mb-2">
                </div>
            </div>
            <div class="row">
                <div class="col-2 fw-medium">
                    <p class="mb-1">Company </p>
                    <p class="mb-1">Name PIC</p>
                    <p class="mb-1">Phone </p>
                </div>
                <div class="col-4">
                    <p class="mb-1">: {{ $quote->pic->client->company }}</p>
                    <p class="mb-1">: {{ $quote->pic->name_pic }}</p>
                    <p class="mb-1">: {{ $quote->pic->client->phone }}</p>
                </div>
                <div class="col-2 fw-medium text-end">
                    <p class="mb-1">Sales :</p>
                    <p class="mb-1">Email :</p>
                </div>
                <div class="col-4 text-end">
                    <p class="mb-1">
                        {{ $sellcon->type == 'Selling' ? 'PT Reftech Jaya Optima' : 'PT Kojisha Innotiv Indonesia' }}
                    </p>
                    <p class="mb-1"> {{ $quote->pic->client->email }}</p>
                </div>
            </div>
        </div>

        <div class="mb-2">
            <table class="table table-borderless m-0" style="width: 100%">
                <thead class="table-light border-top">
                    <tr>
                        <th class="no">No.</th>
                        <th class="item text-nowrap">Item</th>
                        <th class="price">Price</th>
                        <th class="qty">Qty</th>
                        <th class="disc">Disc</th>
                        <th class="amount">Amount</th>
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
                                    style="font-size: 10px; font-family: 'Inter', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $product->detail_product }}</pre>
                            </td>
                            <td class="align-top text-end">RP {{ number_format($product->price, 0, '', '.') }}</td>
                            <td class="align-top">{{ $product->qty }} {{ $product->info_qty }}</td>
                            <td class="align-top">{{ $product->disc }}%</td>
                            <td class="align-top text-end">RP {{ number_format($product->amount, 0, '', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="">
                        <td colspan="3" rowspan="2" class="align-top pt-4">
                        </td>
                        <td colspan="2" class="text-end pt-4 pb-0">
                            <p class="mb-2">Subtotal:</p>
                            @if ($quote->diskon != 0)
                                <p class="mb-2">Discount:</p>
                                <p class="mb-2">Total After Discount:</p>
                            @endif
                            <p class="mb-2">{{ $quote->tax == '11' ? 'Vat (11%)' : 'Vat' }}:</p>
                            <p class="mb-2">Shipping Cost:</p>
                        </td>
                        </td>
                        @php
                            if ($quote->diskon > 0) {
                                $afterDisc = $quote->subtotal - $quote->diskon;
                            } else {
                                $afterDisc = $quote->subtotal;
                            }

                            if ($quote->tax > 0) {
                                $vat = $afterDisc * $quote->tax / 100;
                            } else {
                                $vat = 0;
                            }
                        @endphp
                        <td colspan="2" class="text-end pt-4 pb-0">
                            <p class="fw-semibold mb-2">RP
                                {{ number_format($quote->subtotal, 0, '', '.') }}</p>
                            @if ($quote->diskon != 0)
                                <p class="fw-semibold mb-2">RP
                                    {{ number_format($quote->diskon, 0, '', '.') }}
                                <p class="fw-semibold mb-2">RP
                                    {{ number_format($quote->subtotal - $quote->diskon, 0, '', '.') }}
                            @endif
                            <p class="fw-semibold mb-2">
                                {{ $tax == '0' ? '0' : 'RP ' . number_format($vat, 0, '', '.') }}</p>
                            </p>
                            <p class="fw-semibold mb-2">RP
                                {{ number_format($quote->shipping, 0, '', '.') }}</p>
                        </td>
                    </tr>
                    <tr class="total">
                        <td colspan="2" class="">
                            <p class="mb-0 text-end">Total:</p>
                        </td>
                        <td colspan="2" class="">
                            <p class="fw-semibold mb-0 text-end">Rp
                                {{ number_format($quote->harga_total, 0, '', '.') }}</p>
                        </td>
                    </tr>
                    <tr class="" style="height: 10px !important;">
                        <td colspan="5" class="align-top" style="font-size: 1px;"> </td>
                    </tr>
                    <tr class="">
                        <td colspan="3" rowspan="2" class="align-top pt-4">
                        </td>
                        <td colspan="2" class="note text-end align-top">
                            <p class="mb-0">Note:</p>
                        </td>
                        <td colspan="2" class="note">
                            <pre style="font-family: 'Inter', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap; font-size: 12px;"
                                class="fw-semibold mb-0">
                                {{ $quote->termncon[0]->note }}
                            </pre>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-body">
            <h5 class="mt-4 mb-3">Term & Condition</h5>
            <div class="row">
                <div class="col-3 fw-medium termc p-3">
                    <p class="mb-1">Validity Of Quotation</p>
                    <p class="mb-1">Price </p>
                    <p class="mb-1">Delivery Process </p>
                    <p class="mb-1">Payment </p>
                </div>
                <div class="col termc p-3">
                    <p class="mb-1">: {{ $quote->termncon[0]->validity }}</p>
                    <p class="mb-1">: {{ $quote->termncon[0]->pricing }}</p>
                    <p class="mb-1">: {{ $quote->termncon[0]->delivery_process }}</p>
                    <p class="mb-1">: {{ $quote->termncon[0]->payment }}</p>
                </div>
            </div>
        </div>
        @if ($sellcon->type == 'Selling')
            <div class="row mt-5">
                <div class="col-4 my-3 text-center">
                    <p class="fs-normal fw-medium">Authorized By,</p>
                    <img src="{{ asset('/asset') }}/contract\sign-irene.jpeg" alt="" srcset=""
                        style="width: 100px; height: 77px;">
                    <p class="pt-3">Mrs. Irene</p>
                    <p>PT. Reftech Jaya Optima</p>
                </div>
                <div class="col-4"></div>
                <div class="col-4 mb-3 text-center">
                    <p class="fs-normal fw-medium">Accepted By Customer,</p>
                    <div class="pb-5"></div>
                    <p class="pt-5">{{ $quote->pic->name_pic }}</p>
                    <p>{{ $quote->pic->client->company }}</p>
                </div>
            </div>
        @else
            <div class="row mt-5">
                <div class="col-4 my-3 text-center">
                    <p class="fs-normal fw-medium">Authorized By,</p>
                    <img src="{{ asset('/asset') }}/contract\sign-dedeh.png" alt="" srcset=""
                        style="width: 100px; height: 77px;">
                    <p class="pt-3">Dedeh Sulastri</p>
                    <p>Director</p>
                </div>
                <div class="col-4"></div>
                <div class="col-4 mb-3 text-center">
                    <p class="fs-normal fw-medium">Accepted By Customer,</p>
                    <div class="pb-5"></div>
                    <p class="pt-5">{{ $quote->pic->name_pic }}</p>
                    <p>{{ $quote->pic->client->company }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice-print.css" />
    <link rel="stylesheet" href="style.css">
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/js/app-invoice-print.js"></script>
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            // Ambil tinggi dari elemen <pre>
            var preHeight = $('#notePre').outerHeight();
            // Atur tinggi elemen <p> menjadi sama dengan tinggi elemen <pre>
            $('#noteParagraph').css('height', preHeight + 'px');
        });
    </script>
@endpush
