@extends('layouts.sales.app')
@section('title', $invoice->no_invoice)
<div class="invoice-print p-4">
    <div class="container-fluid flex-grow-1 container-p-y">
        @if ($invoice->flag == 'Reftech')
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
                    <h1 class="fw-bold title-invoice">INVOICE</h1>
                    <div>
                        <span class="fw-bolder">#{{ $invoice->no_invoice }}</span>
                    </div>
                    <div class="mt-1">
                        <span class="text-muted">{{ Carbon\Carbon::parse($invoice->date)->format('d-m-Y') }}</span>
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
                    <h1 class="fw-bold title-invoice">INVOICE</h1>
                    <div>
                        <span class="fw-bolder">#{{ $invoice->no_invoice }}</span>
                    </div>
                    <div class="mt-1">
                        <span class="text-muted">{{ Carbon\Carbon::parse($invoice->date)->format('d-m-Y') }}</span>
                    </div>
                </div>
            </div>
        @endif
        <hr>
        <h5>Invoice To</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" style="border: 1px solid black;">
                <tr>
                    <td rowspan="3" style="vertical-align: top; width: 50%;">
                        <div class="row">
                            <div class="col-4 fw-medium">
                                <p class="mb-1">Bill To </p>
                                <p class="mb-1">Phone </p>
                                <p class="mb-1">Adress</p>
                            </div>
                            <div class="col-8">
                                <p class="mb-1">: {{ $quote->pic->client->company }}</p>
                                <p class="mb-1">: {{ $quote->pic->client->phone }}</p>
                                @if ($invoice->invoiceTo == '1')
                                    <pre
                                        style="font-size: 13px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">: {{ $quote->pic->client->address }}</pre>
                                @else
                                    <pre
                                        style="font-size: 13px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">: {{ $quote->pic->client->subAddress }}</pre>
                                @endif
                            </div>
                        </div>
                        {{-- <p>Bill To : {{ $quote->pic->client->company }}</p>
                        <p>Phone : {{ $quote->pic->client->phone }}</p>
                        <p>Address : {{ $address }}</p> --}}
                    </td>
                    <td>
                        <p>Purchase Order :</p>
                    </td>
                    <td>
                        <p>{{ $invoice->no_po }}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style=" background-color: #F9F9F9;" class="text-center">
                        <p class="fs-6 text-black fw-medium m-0">Term Of Payment:</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <pre
                            style="font-size: 13px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $invoice->term }}</pre>
                    </td>
                </tr>
            </table>
        </div>
        <div class="mb-2">
            <table class="table table-bordered m-0" style="border: 1px solid rgb(60, 60, 60)">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th style="width: 5%">Disc</th>
                        <th style="width: 25%">Amount</th>
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
                            <td class="align-top text-end">RP {{ number_format($product->price, 0, '', '.') }}
                            </td>
                            <td class="align-top">{{ $product->qty }} {{ $product->info_qty }} </td>
                            <td class="align-top">{{ $product->disc }}%</td>
                            <td class="align-top text-end">RP {{ number_format($product->amount, 0, '', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr class="fw-semibold" style="font-size: 13px">
                        <td colspan="3" rowspan="9" id="dynamicRows" style="border-bottom :none !important;">
                        </td>
                        <td colspan="2" id="price" class="text-end pl-4 py-0"
                            style="padding-right: 10px !important;">
                            <p class="m-0">Total</p>
                        </td>
                        <td id="price" class="pr-4 py-0" style="padding-left: 0 !important;">
                            <p class="text-end m-0">RP
                                {{ number_format($quote->subtotal, 0, '', '.') }}</p>
                        </td>
                    </tr>
                    @if ($invoice->type == 'CT')
                        @if ($quote->diskon != 0)
                            <tr class="fw-semibold" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Discount</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">RP
                                        {{ number_format($quote->diskon, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                            <tr class="fw-semibold" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Total After Discount</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">RP
                                        {{ number_format($afterDisc, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @endif
                        @if ($quote->shipping != 0)
                            <tr class="fw-semibold" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Shipping Cost</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">RP
                                        {{ number_format($quote->shipping, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @endif
                        @if ($quote->tax != 0)
                            <tr class="fw-semibold py-0" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">VAT {{ $quote->tax == '11' ? '11%' : '' }}</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">
                                        {{ $tax == '0' ? '0' : 'RP ' . number_format($tax, 0, '', '.') }}</p>
                                </td>
                            </tr>
                            <tr class="fw-semibold py-0" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Total Include VAT</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">
                                        {{ $tax == '0' ? '0' : 'RP ' . number_format($quote->harga_total, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @endif
                        </td>
                    @elseif ($invoice->type == 'DP')
                        @php
                            $amount1 = $payments[0]->amount / (1 + $quote->tax / 100);
                            $vat = $amount1 * ($quote->tax / 100);
                        @endphp
                        @if ($quote->diskon != 0)
                            <tr class="fw-semibold" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Discount</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">RP
                                        {{ number_format($quote->diskon, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                            <tr class="fw-semibold" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Total After Discount</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">RP
                                        {{ number_format($afterDisc, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @endif
                        @if ($quote->shipping != 0)
                            <tr class="fw-semibold" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Shipping Cost</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">RP
                                        {{ number_format($quote->shipping, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @endif
                        <tr class="fw-semibold" style="font-size: 13px">
                            <td colspan="2" class="text-end py-0 px-0">
                                <p class="m-0"
                                    style="background-color: yellow; padding-left:20px; padding-right:10px;">
                                    {{ $payments[0]->note }}
                                    {{ $payments[0]->percent }}%:</p>
                            </td>
                            <td class="px-0 py-0" style="padding-left: 0 !important;">
                                <p class="fw-semibold m-0 text-end"
                                    style="background-color: yellow; padding-right:20px;">
                                    RP
                                    {{ number_format($amount1, 0, '', '.') }}</p>
                            </td>
                        </tr>
                        @if ($quote->tax != 0)
                            <tr class="fw-semibold py-0" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">VAT {{ $quote->tax == '11' ? '11%' : '' }}</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">
                                        {{ $vat == '0' ? '0' : 'RP ' . number_format($vat, 0, '', '.') }}</p>
                                </td>
                            </tr>
                            <tr class="fw-semibold py-0" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Total Include VAT</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">
                                        {{ number_format($payments[0]->amount, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @else
                            <tr class="fw-semibold py-0" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Total</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">
                                        {{ number_format($payments[0]->amount, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @endif
                    @elseif ($invoice->type == 'BP')
                        @php
                            $amount1 = $payments[0]->amount / (1 + $quote->tax / 100);
                            $amount2 = $payments[1]->amount / (1 + $quote->tax / 100);
                            $vat = $amount2 * ($quote->tax / 100);
                        @endphp
                        @if ($quote->diskon != 0)
                            <tr class="fw-semibold" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Discount</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">RP
                                        {{ number_format($quote->diskon, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                            <tr class="fw-semibold" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Total After Discount</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">RP
                                        {{ number_format($afterDisc, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @endif
                        @if ($quote->shipping != 0)
                            <tr class="fw-semibold" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Shipping Cost</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">RP
                                        {{ number_format($quote->shipping, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @endif
                        <tr class="fw-semibold" style="font-size: 13px">
                            <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                <p class="m-0">
                                    {{ $payments[0]->note }}
                                    {{ $payments[0]->percent }}%:</p>
                            </td>
                            <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                <p class="m-0 text-end">
                                    RP
                                    {{ number_format($amount1, 0, '', '.') }}</p>
                            </td>
                        </tr>
                        <tr class="fw-semibold" style="font-size: 13px">
                            <td colspan="2" class="text-end py-0 px-0">
                                <p class="m-0"
                                    style="background-color: yellow; padding-left:20px; padding-right:10px;">
                                    {{ $payments[1]->note }}
                                    {{ $payments[1]->percent }}%:</p>
                            </td>
                            <td class="px-0 py-0" style="padding-left: 0 !important;">
                                <p class="m-0 text-end" style="background-color: yellow; padding-right:20px;">
                                    RP
                                    {{ number_format($amount2, 0, '', '.') }}</p>
                            </td>
                        </tr>
                        @if ($quote->tax != 0)
                            <tr class="fw-semibold py-0" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">VAT {{ $quote->tax == '11' ? '11%' : '' }}</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">
                                        {{ $vat == '0' ? '0' : 'RP ' . number_format($vat, 0, '', '.') }}</p>
                                </td>
                            </tr>
                            <tr class="fw-semibold py-0" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Total Include VAT</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">
                                        {{ number_format($payments[1]->amount, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @else
                            <tr class="fw-semibold py-0" style="font-size: 13px">
                                <td colspan="2" class="text-end py-0" style="padding-right: 10px !important;">
                                    <p class="m-0">Total</p>
                                </td>
                                <td class="pr-4 py-0" style="padding-left: 0 !important;">
                                    <p class="m-0 text-end">
                                        {{ number_format($payments[1]->amount, 0, '', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @endif
                    @endif
                </tbody>
            </table>
        </div>
        @if (@$harga)
            <p class="fs-5 fw-medium mt-2 p-2" style="background-color: rgb(248, 248, 248); width:70%;"> Say
                amount: #
                {{ $price }} Rupiah</p>
        @else
            <p class="fs-5 fw-medium mt-2 p-2" style="background-color: rgb(248, 248, 248); width:70%;"> Say
                amount: #
                {{ $fullPrice }} Rupiah</p>
        @endif
        <div class="row mt-4">
            <div class="col-6 m-4">
                <h5 class="my-4">Payment by Transfer or Giro shall be made in Full amount to :</h5>
                <div class="row">
                    <div class="col-3 fw-medium">
                        <p class="mb-1">Payable to</p>
                        <p class="mb-1">Acc Name </p>
                        <p class="mb-1">Acc No. </p>
                        <p class="mb-1">Swift Code </p>
                    </div>
                    @if ($invoice->flag == 'Reftech' && $invoice->quote->tax == 0)
                        <div class="col">
                            <p class="mb-1">: Bank BCA (IDR) - Asia Afrika Kota Bandung</p>
                            <p class="mb-1">: ARIEP RACHMAN</p>
                            <p class="mb-1">: 166 - 2242 - 271</p>
                            <p class="mb-1">: -</p>
                        </div>
                    @elseif ($invoice->flag == 'Reftech' && $invoice->quote->tax > 0)
                        <div class="col">
                            <p class="mb-1">: Bank BCA (IDR) - Asia Afrika Kota Bandung</p>
                            <p class="mb-1">: PT. REFTECH JAYA Optima</p>
                            <p class="mb-1">: 008 - 6289 - 789</p>
                            <p class="mb-1">: CENAIDJA</p>
                        </div>
                    @elseif ($invoice->flag == 'Kojisha' && $invoice->quote->tax == 0)
                        <div class="col">
                            <p class="mb-1">: Bank BCA (IDR)</p>
                            <p class="mb-1">: REGITA DWI MELINDA</p>
                            <p class="mb-1">: 1560239137</p>
                            <p class="mb-1">: - </p>
                        </div>
                    @elseif ($invoice->flag == 'Kojisha' && $invoice->quote->tax > 0)
                        <div class="col">
                            <p class="mb-1">: Bank BCA (IDR)</p>
                            <p class="mb-1">: KOJISHA INNOTIV INDONESIA PT</p>
                            <p class="mb-1">: 5223876543</p>
                            <p class="mb-1">: - </p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col"></div>
            <div class="col-4 my-5 text-center">
                <p>Bandung, {{ Carbon\Carbon::parse($invoice->date)->format('d F Y') }}</p>
                <p class="fs-normal fw-bolder">PT. Reftech Jaya Optima</p>
                @if (isset($invoice->sign))
                    <img src="{{ url('') . '/' . $invoice->sign }}" alt="" srcset="" height="77">
                @else
                    <div style="padding: 40px 0;"></div>
                @endif
                {{-- <div class="pb-5"></div> --}}
                <p class="pt-3 fw-bolder">Ariep Rachman</p>
                <p>Director</p>
            </div>
        </div>
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
