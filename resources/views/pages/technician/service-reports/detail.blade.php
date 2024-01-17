@extends('layouts.sales.app')
@section('title', 'Detail Audit Tools')
@section('content')
    <div class="row invoice-preview">
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                        <div class="mb-xl-0 pb-1">
                            <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                                <span class="app-brand-logo demo">
                                    <span style="color: var(--bs-primary)">
                                        <img class="text-md" src="{{ asset('assets') }}/img/favicon/logo-reftech1.png"
                                            alt="" srcset="">
                                    </span>
                                </span>
                                <span class="h4 mb-0 app-brand-text fw-bold fs-2">PT REFTECH JAYA OPTIMA</span>
                            </div>
                            <p class="mb-1">Taman Kopo Indah V, Ruko Sommerville No. 27</p>
                            <p class="mb-1">Bandung – Jawa Barat 40218</p>
                            <p class="mb-1">
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
                            <p class="mb-1">: {{ $service->unit }}</p>
                            <p class="mb-1">: {{ $service->unit }}</p>
                            <p class="mb-1">: {{ $service->serial_number }}</p>
                            <p class="mb-1">: {{ $service->running }} | {{$service->load}}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-2 fw-medium">
                            <p class="mb-1">Job Description </p>
                        </div>
                        <div class="col-4">
                            <p class="mb-1">: {{ $service->jobdesc }}</p>
                        </div>
                    </div>
                    <hr>
                    <h5 class="my-2">Description</h5>
                    <p class="mb-1">{{ $service->desc }}</p>
                    <hr>
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
        </div>
        {{-- End: Invoice --}}
        {{-- Button Invocie --}}
        <div class="col-xl-3 col-md-4 col-12 invoice-actions">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-primary btn-outline-secondary d-grid w-100 mb-3 waves-effect" target="_blank"
                        href="{{ route('service-reports.print', $service->id) }}">
                        Print
                    </a>
                    <a href="#" type="button"
                        class="btn btn-outline-secondary d-grid w-100 waves-effect">Download</a>
                </div>
            </div>
        </div>
        {{-- End : Button Invoice --}}
    </div>
@endsection
@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice.css" />
@endpush
