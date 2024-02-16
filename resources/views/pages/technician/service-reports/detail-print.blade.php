@extends('layouts.sales.app')
@section('title', 'Detail Quotation')

<div class="invoice-print p-4">
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
            <div class="mb-xl-0 pb-1">
                <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                    <span class="app-brand-logo demo">
                        <span style="color: var(--bs-primary)">
                            <img class="text-md"
                                src="{{ url('https://reftech.id/wp-content/uploads/2021/10/Reftech-Logo-Hitam.png') }}"
                                alt="" srcset="" class="img-logo">
                        </span>
                    </span>
                </div>
                <p class="mb-1 fw-bolder">PT Reftech Jaya Optima</p>
                <div style="font-size: 10px">
                    <p class="mb-1">Taman Kopo Indah V, Ruko Sommerville No. 31</p>
                    <p class="mb-1">Bandung – Jawa Barat 40218</p>
                    <p class="mb-1">
                        <i class="mdi mdi-phone-outline scaleX-n1-rtl me-1"></i>022 54417653
                    </p>
                </div>
            </div>
            <div>
                <h3 class="fw-bold">SERVICE REPORTS</h3>
                <div>
                    <span class="fw-bolder">#{{ $service->no_service }}</span>
                </div>
                <div class="mt-1">
                    <span class="text-muted">{{ $service->date }}</span>
                </div>
            </div>
        </div>
        <hr class="my-2">
        <div class="row mb-3">
            <div class="col-2 fw-medium">
                <p class="mb-1">Customers </p>
                <p class="mb-1">Address </p>
                <p class="mb-1">PIC </p>
            </div>
            <div class="col-4">
                <p class="mb-1">: {{ $service->pic->client->company }}</p>
                <p class="mb-1">: {{ $service->pic->client->area }}</p>
                <p class="mb-1">: {{ $service->pic->name_pic }}</p>
            </div>
            <div class="col-2 fw-medium">
                <p class="mb-1">Date </p>
                <p class="mb-1">Unit Type </p>
                <p class="mb-1">Serial Number </p>
                <p class="mb-1">Running & Load </p>
            </div>
            <div class="col-4">
                <p class="mb-1">: {{ $service->date }}</p>
                <p class="mb-1">: {{ $service->unit }}</p>
                <p class="mb-1">: {{ $service->serial_number }}</p>
                <p class="mb-1">: {{ $service->running }} | {{ $service->load }}</p>
            </div>
        </div>
        <hr>
        <div class="my-2">
            <div class="row fw-bold fs-6">
                <div class="col-2">Job Description</div>
                <div class="col-4">: {{ $service->jobdesc }}</div>
            </div>
        </div>
        <hr>
        <div class="row my-2">
            <div class="col-8">
                <h5 class="my-2">Description</h5>
                <pre class="mb-1" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $service->desc }}</pre>
            </div>
            <div class="col-4">
                <h5 class="my-2">Recomendation</h5>
                <pre class="mb-1" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $service->recomendation }}</pre>
            </div>
        </div>
        <hr>
        <h5 class="my-4">Picture</h5>
        <div class="row mb-2">
            @foreach ($pict as $picture)
                <div class="col-4 text-center">
                    <img src="{{ url('') . '/' . $picture->picture }}" alt="" srcset=""
                        style="max-width: 200px" class="img-reports">
                    <p class="fw-bolder">{{ $picture->keterangan }}</p>
                </div>
            @endforeach
        </div>
        <div class="row mt-2">
            <div class="col-4 mt-5 fw-bold text-center">
                <p class="pb-5">PT Reftech Jaya Optima</p>
                <p class="pt-3">( {{ $service->technician->name }} )</p>
            </div>
            <div class="col-4"></div>
            <div class="col-4 mt-5 fw-bold text-center">
                <p class="pb-5">{{ $service->pic->client->company }}</p>
                <p class="pt-3">( {{ $service->pic->name_pic }} )</p>
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
