@extends('layouts.sales.app')
@section('title', $invoice->no_invoice)
<div class="invoice-print p-4">
    <div class="container-fluid flex-grow-1 container-p-y">

        <div class="table-responsive mb-5">
            <table class="table table-bordered m-0" style="border: 1px solid rgb(60, 60, 60)">
                <tbody>
                    <tr>
                        <td colspan="3">
                            <div class="row">
                                <div class="col-8">
                                    <h4 class="fw-bold mb-0">Delivery Order</h4>
                                </div>
                                <div class="col-4">
                                    <p class="mb-0"><span class="fw-bold">D.O. No :</span> {{ $invoice->no_invoice }}
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="row">
                                <div class="col-6">

                                    @if ($invoice->flag == 'Reftech')
                                        <div
                                            class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
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
                                                        <i
                                                            class="mdi mdi-phone-outline scaleX-n1-rtl me-1 mdi-14px"></i>022
                                                        54417653
                                                        {{ '   ' }}<i
                                                            class="mdi mdi-email-outline scaleX-n1-rtl me-1 mdi-14px"></i>admin@reftech.id
                                                    </p>
                                                    <p class="mb-1">
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <h1 class="fw-bold" style="color: blue;">Delivery Order</h1>
                                                <div>
                                                    <span class="fw-bolder">#{{ $invoice->no_invoice }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                                            <div class="mb-xl-0 pb-1">
                                                <div class="d-flex svg-illustration align-items-center gap-2 mb-2">
                                                    <span class="app-brand-logo demo">
                                                        <span style="color: var(--bs-primary)">
                                                            <img class="text-md"
                                                                src="{{ asset('/asset') }}/logo/Logo-update-size.png"
                                                                alt="" srcset="" width="60%">
                                                        </span>
                                                    </span>
                                                </div>
                                                <p class="mb-1 fw-bolder">PT Kojisha Innotiv Indonesia</p>
                                                <div style="font-size: 10px">
                                                    <p class="mb-1">Jl. Nancep No. 45A, Setu</p>
                                                    <p class="mb-1">Cibitung - Kab. Bekasi 17320</p>
                                                    <p class="mb-1">
                                                        <i
                                                            class="mdi mdi-phone-outline scaleX-n1-rtl me-1 mdi-14px"></i>+62
                                                        812-1000-0997
                                                        {{ ' | ' }}<i
                                                            class="mdi mdi-email-outline scaleX-n1-rtl me-1 mdi-14px"></i>admin@kojisha.com
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <div class="row mt-5" style="font-size: 13px">
                                        <div class="col-4 text-end">
                                            <p class="mb-1">Date</p>
                                            <p class="mb-1">Order No</p>
                                            <p class="mb-1">Customer</p>
                                            <p class="mb-1">Delivery No</p>
                                        </div>
                                        <div class="col-8">
                                            <p class="mb-1">: {{ $invoice->date }}</p>
                                            <p class="mb-1">: {{ $invoice->no_po }}</p>
                                            <p class="mb-1">: {{ $quote->pic->client->company }}</p>
                                            <p class="mb-1">: {{ $invoice->no_invoice }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>No</td>
                        <td>Qty</td>
                        <td style="width: 80%">Description</td>
                    </tr>
                    @php
                        $no = 0;
                    @endphp
                    @foreach ($dQuote as $product)
                        @php
                            $no++;
                        @endphp
                        <tr style="font-size: 13px">
                            <td class="align-top">
                                {{ $no }}
                            </td>
                            <td class="align-top">
                                {{ $product->qty }} {{ $product->info_qty }}
                            </td>
                            <td class="text-nowrap align-top">
                                <p class="mb-0 fw-semibold" style="font-size: 12px">
                                    {{ $product->equivalent->brand }} {{ $product->equivalent->pn }}
                                </p>
                                <pre class="mb-0"
                                    style="font-size: 10px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $product->detail_product }}</pre>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3">
                            <div class="row mb-3">
                                <div class="col-4 mt-5 text-center">
                                    <div class="pb-5"></div>
                                    <p class="fw-bold mx-3 mb-0" style="border-top: 1px solid black ">
                                        Shipper</p>
                                </div>
                                <div class="col-4"></div>
                                <div class="col-4 mt-5 text-center">
                                    <div class="pb-5"></div>
                                    <p class="fw-bold mx-3 mb-0" style="border-top: 1px solid black ">
                                        Recieved</p>
                                </div>
                            </div>
                            <p class="mb-0">Distribusi : Putih dan Pink → Pelanggan, <span class="fw-bold">Kuning →
                                    Accounting PT. Reftech</span></p>
                        </td>
                    </tr>
                </tbody>
            </table>
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
