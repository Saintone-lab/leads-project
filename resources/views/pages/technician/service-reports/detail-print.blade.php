@extends('layouts.sales.app')
@section('title', 'Detail Quotation')

<div class="invoice-print p-4">
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
            <div class="mb-xl-0 pb-3">
                <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                    <span class="app-brand-logo demo">
                        <span style="color: var(--bs-primary)">
                            <img class="text-md" src="{{ asset('assets') }}/img/favicon/logo-reftech1.png" alt=""
                                srcset="">
                        </span>
                    </span>
                    <span class="h4 mb-0 app-brand-text fw-bold fs-2">PT REFTECH JAYA OPTIMA</span>
                </div>
                <p class="mb-1">Taman Kopo Indah V, Ruko Sommerville No. 27</p>
                <p class="mb-1">Bandung – Jawa Barat 40218</p>
                <p>
                    <i class="mdi mdi-phone-outline scaleX-n1-rtl me-1"></i>022 54417653
                </p>
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
        <hr class="my-0">
        <div class="row">
            <div class="col-lg-6 col-md-6 my-3">
                <h6 class="pb-2 fw-semibold fs-4">Service To :</h6>
            </div>
            <div class="col-md-6 my-3">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-2 fw-medium">
                <p class="mb-1">Company </p>
                <p class="mb-1">PIC </p>
                <p class="mb-1">Area </p>
                <p class="mb-1">Phone </p>
            </div>
            <div class="col-4">
                <p class="mb-1">: {{ $service->pic->client->company }}</p>
                <p class="mb-1">: {{ $service->pic->name }}</p>
                <p class="mb-1">: {{ $service->pic->client->area }}</p>
                <p class="mb-1">: {{ $service->pic->client->phone }}</p>
            </div>
            <div class="col-2 fw-medium">
                <p class="mb-1">Technician </p>
                <p class="mb-1">Phone </p>
                <p class="mb-1">Job Desc </p>
            </div>
            <div class="col-4">
                <p class="mb-1">: {{ $service->technician->name }}</p>
                <p class="mb-1">: {{ $service->technician->phone }}</p>
                <p class="mb-1">: {{ $service->jobdesc }}</p>
            </div>
        </div>
        <div class="mb-2">
            <table class="table table-borderless m-0" style="width: 100%">
                <thead class="table-light border-top">
                    <tr>
                        <th>Unit</th>
                        <th>Serial Number</th>
                        <th>Running</th>
                        <th>Loading</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-nowrap">{{ $service->unit }}</td>
                        <td>{{ $service->serial_number }}</td>
                        <td>{{ $service->running }}</td>
                        <td>{{ $service->load }}</td>
                        <td
                            style="max-width: 200px; word-wrap: break-word; word-break: break-all; white-space: normal;">
                            {{ $service->desc }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h5 class="my-4">Picture</h5>
        <div class="row mb-5">
            @foreach ($pict as $picture)
                <div class="col-6 text-center">
                    <img src="{{ url('') . '/' . $picture->picture }}" alt="" srcset=""
                        style="max-width: 150px">
                </div>
            @endforeach
        </div>
        <div class="row mt-5">
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
