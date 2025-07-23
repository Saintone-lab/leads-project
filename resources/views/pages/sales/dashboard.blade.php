@extends('layouts.sales.app')
@section('title', 'My Dashboard')
@section('content')
    @if (Auth::user()->role == 'Sales' || Auth::user()->role == 'Support')
        @if (Auth::user()->id == 16 || Auth::user()->id == 23)
            <div class="row gy-4 mb-4">
                <!-- Congratulations card -->
                <div class="col-md-3 col-12">
                    <div class="card h-100">
                        <div class="card-body text-nowrap">
                            <h4 class="card-title mb-1 d-flex gap-2 flex-wrap">
                                Congratulations
                                <strong>{{ Auth::user()->name }}</strong> 🎉
                            </h4>
                            <p class="pb-0">Best seller of the month</p>
                            <h4 class="text-primary mb-1">Rp. {{ $formattedTotalPrice }}</h4>
                            @php
                                $jumlah_target = 0;
                                $jumlah_target = ($poTotalPrice / $target->total) * 100;
                                $formatted_jumlah_target = number_format($jumlah_target, 3);
                            @endphp
                            <p class="mb-2 pb-1">{{ $formatted_jumlah_target }}% of target 🚀</p>
                            <a href="javascript:;" class="btn btn-sm btn-primary waves-effect waves-light">View Sales</a>
                        </div>
                        <img src="{{ asset('assets') }}/img/illustrations/trophy.png"
                            class="position-absolute bottom-0 end-0 me-3" height="140" alt="view sales">
                    </div>
                </div>
                <!--/ Congratulations card -->
                <div class="col-md-9 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Key Performance Indicator</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="row mb-3">
                                        <div class="col-4" style="padding-right: 0;">
                                            <div class="card bg-primary text-white w-100 cursor-pointer"
                                                data-bs-toggle="modal" data-bs-target="#newProduct">
                                                <h5 class="card-title text-white text-center my-4">
                                                    <i class="menu-icon tf-icons mdi mdi-reproduction m-0 fs-1"></i>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-8 d-flex flex-column justify-content-between">
                                            <div class="card shadow-none bg-label-primary border-primary border-2">
                                                <h5 class="card-title text-center my-2">
                                                    New Product
                                                </h5>
                                            </div>
                                            <div class="card shadow-none bg-label-primary border-primary border-2 mt-auto"
                                                style="border: dashed;">
                                                <h5 class="card-title text-center my-2">
                                                    {{ $productCount }} / 100
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4" style="padding-right: 0;">
                                            <div class="card bg-warning text-white w-100 cursor-pointer"
                                                data-bs-toggle="modal" data-bs-target="#accurData">
                                                <h5 class="card-title text-white text-center my-4">
                                                    <i
                                                        class="menu-icon tf-icons mdi mdi-package-variant-closed-check m-0 fs-1"></i>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-8 d-flex flex-column justify-content-between">
                                            <div class="card shadow-none bg-label-warning border-warning border-2">
                                                <h5 class="card-title text-center my-2">
                                                    Akurasi Data
                                                </h5>
                                            </div>
                                            <div class="card shadow-none bg-label-warning border-warning border-2 mt-auto"
                                                style="border: dashed;">
                                                <h5 class="card-title text-center my-2">
                                                    @if (@$akurasiCount[0])
                                                        @php
                                                            $dataAkurasi = $akurasiCount->count();
                                                            $persenAkurasi = 0;
                                                            $jumlahAkurasi = 0;
                                                        @endphp
                                                        @foreach ($akurasiCount as $item)
                                                            @php
                                                                $jumlahAkurasi += $item->average;
                                                            @endphp
                                                        @endforeach
                                                        @php
                                                            $jumlahAkurasi / $dataAkurasi;
                                                            $persenAkurasi = ($jumlahAkurasi / 5) * 100;
                                                        @endphp
                                                    @endif
                                                    {{ @$persenAkurasi ?? 0 }} %
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-stretch cursor-pointer">
                                        <div class="col-4" style="padding-right: 0;">
                                            <div class="card border-warning bg-warning border-1 w-100 h-100"
                                                data-bs-toggle="modal" data-bs-target="#delivery">
                                                <h5 class="card-title text-center text-white my-4">
                                                    <i
                                                        class="menu-icon tf-icons mdi mdi-truck-delivery-outline m-0 fs-1"></i>
                                                </h5>
                                            </div>
                                        </div>

                                        <div class="col-8 d-flex flex-column justify-content-between">
                                            <div class="card bg-label-warning border-warning border-1 shadow-none">
                                                <h5 class="card-title text-center my-2">
                                                    Delivery & Success
                                                </h5>
                                            </div>
                                            <div class="card bg-label-warning border-warning border-2 shadow-none mt-auto"
                                                style="border-style: dashed;">
                                                <h5 class="card-title text-center my-2">
                                                    @if (@$deliveryCount[0])
                                                        @php
                                                            $dataDelivery = $deliveryCount->count();
                                                            $persenDelivery = 0;
                                                            $jumlahDelivery = 0;
                                                        @endphp
                                                        @foreach ($deliveryCount as $item)
                                                            @php
                                                                $jumlahDelivery += $item->average;
                                                            @endphp
                                                        @endforeach
                                                        @php
                                                            $jumlahDelivery / $dataDelivery;
                                                            $persenDelivery = ($jumlahDelivery / 5) * 100;
                                                        @endphp
                                                    @endif
                                                    {{ @$persenDelivery ?? 0 }} %
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="row mb-3">
                                        <div class="col-4" style="padding-right: 0;">
                                            <div class="card bg-warning text-white w-100 cursor-pointer"
                                                data-bs-toggle="modal" data-bs-target="#response">
                                                <h5 class="card-title text-white text-center my-4">
                                                    <i
                                                        class="menu-icon tf-icons mdi mdi-account-heart-outline m-0 fs-1"></i>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-8 d-flex flex-column justify-content-between">
                                            <div class="card shadow-none bg-label-warning border-warning border-2">
                                                <h5 class="card-title text-center my-2">
                                                    Response Chat
                                                </h5>
                                            </div>
                                            <div class="card shadow-none bg-label-warning border-warning border-2 mt-auto"
                                                style="border: dashed;">
                                                <h5 class="card-title text-center my-2">
                                                    @if (@$responseCount[0])
                                                        @php
                                                            $dataResponse = $responseCount->count();
                                                            $persenResponse = 0;
                                                            $jumlahResponse = 0;
                                                        @endphp
                                                        @foreach ($responseCount as $item)
                                                            @php
                                                                $jumlahResponse += $item->average;
                                                            @endphp
                                                        @endforeach
                                                        @php

                                                            $persenResponse = $jumlahResponse / $dataResponse;
                                                        @endphp
                                                    @endif
                                                    {{ @$persenResponse ?? 0 }} %
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4" style="padding-right: 0;">
                                            <div class="card bg-warning text-white w-100 cursor-pointer"
                                                data-bs-toggle="modal" data-bs-target="#rating">
                                                <h5 class="card-title text-white text-center my-4">
                                                    <i class="menu-icon tf-icons mdi mdi-monitor-star m-0 fs-1"></i>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-8 d-flex flex-column justify-content-between">
                                            <div class="card shadow-none bg-label-warning border-warning border-2">
                                                <h5 class="card-title text-center my-2">
                                                    Score Toko
                                                </h5>
                                            </div>
                                            <div class="card shadow-none bg-label-warning border-warning border-2 mt-auto"
                                                style="border: dashed;">
                                                <h5 class="card-title text-center my-2">
                                                    @if (@$ratingCount[0])
                                                        @php
                                                            $dataRating = $ratingCount->count();
                                                            $persenRating = 0;
                                                            $jumlahRating = 0;
                                                        @endphp
                                                        @foreach ($ratingCount as $item)
                                                            @php
                                                                $jumlahRating += $item->average;
                                                            @endphp
                                                        @endforeach
                                                        @php
                                                            $persenRating = $jumlahRating / $dataRating;
                                                        @endphp
                                                    @endif
                                                    Rating {{ @$persenRating ?? 0 }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-stretch">
                                        <div class="col-4" style="padding-right: 0;">
                                            <div class="card bg-warning border-warning text-white border-1 w-100 h-100 cursor-pointer"
                                                data-bs-toggle="modal" data-bs-target="#customer">
                                                <h5 class="card-title text-white text-center my-4">
                                                    <i class="menu-icon tf-icons mdi mdi-cart-check m-0 fs-1"></i>
                                                </h5>
                                            </div>
                                        </div>

                                        <div class="col-8 d-flex flex-column justify-content-between">
                                            <div class="card bg-label-warning border-warning border-2 shadow-none">
                                                <h5 class="card-title text-center my-2">
                                                    Customer Care
                                                </h5>
                                            </div>
                                            <div class="card bg-label-warning border-warning border-2 shadow-none mt-auto"
                                                style="border-style: dashed;">
                                                <h5 class="card-title text-center my-2">
                                                    @if (@$customerCount[0])
                                                        @php
                                                            $dataCustomer = $customerCount->count();
                                                            $persenCustomer = 0;
                                                            $jumlahCustomer = 0;
                                                        @endphp
                                                        @foreach ($customerCount as $item)
                                                            @php
                                                                $jumlahCustomer += $item->average;
                                                            @endphp
                                                        @endforeach
                                                        @php
                                                            $jumlahCustomer / $dataCustomer;
                                                            $persenCustomer = ($jumlahCustomer / 5) * 100;
                                                        @endphp
                                                    @endif
                                                    {{ @$persenCustomer ?? 0 }} %
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="row mb-3">
                                        <div class="col-4" style="padding-right: 0;">
                                            <div class="card bg-primary text-white w-100 cursor-pointer"
                                                data-bs-toggle="modal" data-bs-target="#SWin">
                                                <h5 class="card-title text-white text-center my-4">
                                                    <i class="menu-icon tf-icons mdi mdi-whatsapp m-0 fs-1"></i>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-8 d-flex flex-column justify-content-between">
                                            <div class="card shadow-none bg-label-primary border-primary border-2">
                                                <h5 class="card-title text-center my-2">
                                                    Update SW (3/Days)
                                                </h5>
                                            </div>
                                            <div class="card shadow-none bg-label-primary border-primary border-2 mt-auto"
                                                style="border: dashed;">
                                                <h5 class="card-title text-center my-2">
                                                    @if (@$SWCount[0])
                                                        @php
                                                            $dataSW = $SWCount->count();
                                                            $persenSW = 0;
                                                            $jumlahSW = 0;
                                                        @endphp
                                                        @foreach ($SWCount as $item)
                                                            @php
                                                                $jumlahSW += $item->airend;
                                                                $jumlahSW += $item->kojisha;
                                                            @endphp
                                                        @endforeach
                                                        @php
                                                            $persenSW = $jumlahSW / $dataSW;
                                                        @endphp
                                                    @endif
                                                    {{ @$persenSW ?? 0 }} / {{ Auth::user()->id == 16 ? '120' : '60' }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4" style="padding-right: 0;">
                                            <div class="card bg-primary text-white w-100 cursor-pointer"
                                                data-bs-toggle="modal" data-bs-target="#video">
                                                <h5 class="card-title text-white text-center my-4">
                                                    <i class="menu-icon tf-icons mdi mdi-video-outline m-0 fs-1"></i>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-8 d-flex flex-column justify-content-between">
                                            <div class="card shadow-none bg-label-primary border-primary border-2">
                                                <h5 class="card-title text-center my-2">
                                                    Video ( 1/Days )
                                                </h5>
                                            </div>
                                            <div class="card shadow-none bg-label-primary border-primary border-2 mt-auto"
                                                style="border: dashed;">
                                                <h5 class="card-title text-center my-2">
                                                    @if (@$videoCount[0])
                                                        @php
                                                            $dataVideo = $videoCount->count();
                                                            $persenVideo = 0;
                                                            $jumlahVideo = 0;
                                                        @endphp
                                                        @foreach ($videoCount as $item)
                                                            @php
                                                                if ($item->ig) {
                                                                    $jumlahVideo += 30;
                                                                }
                                                                if ($item->tiktok) {
                                                                    $jumlahVideo += 30;
                                                                }
                                                                if ($item->tokped) {
                                                                    $jumlahVideo += 40;
                                                                }
                                                            @endphp
                                                        @endforeach
                                                        @php
                                                            $persenVideo = $jumlahVideo / $dataVideo;
                                                        @endphp
                                                    @endif
                                                    {{ @$persenVideo ?? 0 }} %
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-stretch">
                                        <div class="col-4" style="padding-right: 0;">
                                            <div class="card border-success bg-success border-1 w-100 h-100">
                                                <h5 class="card-title text-white text-center my-4">
                                                    <i class="menu-icon tf-icons mdi mdi-cart-plus  m-0 fs-1"></i>
                                                </h5>
                                            </div>
                                        </div>

                                        <div class="col-8 d-flex flex-column justify-content-between">
                                            <div class="card border-success bg-label-success border-1 shadow-none">
                                                <h5 class="card-title text-center my-2">
                                                    Purchase Order
                                                </h5>
                                            </div>
                                            <div class="card border-success bg-label-success border-2 shadow-none mt-auto"
                                                style="border-style: dashed;">
                                                <h5 class="card-title text-center my-2">
                                                    {{ $POCount }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $salesID = $sales->id;
            @endphp
            @include('components.modal.onlineSales.new-product')
            @include('components.modal.onlineSales.akurasi-data')
            @include('components.modal.onlineSales.delivery')
            @include('components.modal.onlineSales.response')
            @include('components.modal.onlineSales.rating')
            @include('components.modal.onlineSales.customer')
            @include('components.modal.onlineSales.sw')
            @include('components.modal.onlineSales.video')
        @else
            <div class="row gy-4 mb-4">
                <!-- Congratulations card -->
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-8 col-12">
                    <div class="card h-100">
                        <div class="card-body text-nowrap">
                            <h4 class="card-title mb-1 d-flex gap-2 flex-wrap">
                                Congratulations <strong>{{ Auth::user()->name }}</strong> 🎉
                            </h4>
                            <p class="pb-0">Best seller of the month</p>
                            <h4 class="text-primary mb-1">Rp. {{ $formattedTotalPrice }}</h4>
                            @php
                                $jumlah_target = 0;
                                $jumlah_target = ($poTotalPrice / $target->total) * 100;
                                $formatted_jumlah_target = number_format($jumlah_target, 3);
                            @endphp
                            <p class="mb-2 pb-1">{{ $formatted_jumlah_target }}% of target 🚀</p>
                            <a href="javascript:;" class="btn btn-sm btn-primary waves-effect waves-light">View Sales</a>
                        </div>
                        <img src="{{ asset('assets') }}/img/illustrations/trophy.png"
                            class="position-absolute bottom-0 end-0 me-3" height="140" alt="view sales">
                    </div>
                </div>
                <!--/ Congratulations card -->
                <!-- Total New Leads chart -->
                @if (Auth::user()->id != '4')
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-secondary rounded">
                                            <i class="mdi mdi-account-multiple-plus-outline mdi-24px"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-info mt-4 pt-1">
                                    <h4 class="mb-2">
                                        {{ $leads->count() }}

                                        <small class="text-muted fs-tiny">/
                                            {{-- @if ($jumlahData > 4)
                                        {{ round($target->leads + $target->leads / 4) }}
                                    @elseif($jumlahData == 4)
                                        {{ round(num: $target->leads) }}
                                    @endif --}}

                                            {{ $target->leads }}
                                        </small>
                                    </h4>
                                    <div class="badge bg-label-secondary rounded-pill">New Leads</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!--/ Total New Leads chart -->
                <!-- Total Leads -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-info rounded">
                                        <i class="mdi mdi-phone-outline mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">
                                    {{ $dailyCall }}
                                    @if (Auth::user()->id != 3 && Auth::user()->id != 4)
                                        <small class="text-muted fs-tiny">/
                                            {{ $target->dc }}
                                            @php
                                                if (is_array($weekPerMonth)) {
                                                    $jumlahData = count($weekPerMonth);
                                                }
                                            @endphp
                                            {{-- @if ($jumlahData > 4)
                                        {{ round($target->dc + $target->dc / 4) }}
                                    @elseif($jumlahData == 4)
                                        {{ round($target->dc) }}
                                    @endif --}}
                                        </small>
                                    @endif
                                </h4>
                                <div class="badge bg-label-secondary rounded-pill">Daily Call</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Total Leads -->
                <!-- Total Expenses -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded">
                                        <i class="mdi mdi-account-multiple-outline mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">
                                    {{ $customers }}
                                    <small class="text-muted fs-tiny">/
                                        {{ $jumlahCustomer }}
                                        {{-- @if ($jumlahData > 4)
                                        {{ round($target->crm + $target->crm / 4) }}
                                    @elseif($jumlahData == 4)
                                        {{ round($target->crm) }}
                                    @endif --}}
                                    </small>
                                </h4>
                                <div class="badge bg-label-secondary rounded-pill">CRM Existing</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Total Expenses -->
                <!-- Total Profit chart -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-warning rounded">
                                        <i class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">
                                    {{ $quotation->count() }}
                                    <small class="text-muted fs-tiny">/
                                        {{ $target->quote }}
                                        {{-- @if ($jumlahData > 4)
                                        {{ round($target->quote + $target->quote / 4) }}
                                    @elseif($jumlahData == 4)
                                        {{ round($target->quote) }}
                                    @endif --}}
                                    </small>
                                </h4>
                                <div class="badge bg-label-secondary rounded-pill">Quotation</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-success rounded">
                                        <i class="mdi mdi-cart-plus mdi-24px"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-info mt-4 pt-1">
                                <h4 class="mb-2">
                                    {{ $po->count() }}
                                </h4>
                                <div class="badge bg-label-secondary rounded-pill">Purchase Order</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Total Profit chart -->
                {{-- @endif --}}
            </div>
        @endif

        <div class="row gy-4 mb-4">
            {{-- Prospect Table --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-datatable table-responsive pt-0">
                        <table class="datatable-prospect-quote-sales table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Company</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            {{-- End:: Prospect Table --}}
        </div>

        {{-- <div class="card mb-4">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-notulen table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Desc</th>
                            <th>Level</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div> --}}


        <div class="card app-calendar-wrapper">
            <div class="row gy-4">
                <!-- Calendar Sidebar -->
                <div class="col app-calendar-sidebar pt-1" id="app-calendar-sidebar">
                    <div class="p-3 pb-2 my-sm-0 mb-3">
                        <div class="d-grid">
                            <button class="btn btn-primary btn-toggle-sidebar" data-bs-toggle="offcanvas"
                                data-bs-target="#addEventSidebar" aria-controls="addEventSidebar">
                                <i class="mdi mdi-plus me-1"></i>
                                <span class="align-middle">Add Event</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <!-- inline calendar (flatpicker) -->
                        <div class="inline-calendar"></div>

                        <hr class="container-m-nx my-4" />

                        <!-- Filter -->
                        <div class="mb-4">
                            <small class="text-small text-muted text-uppercase align-middle">Filter</small>
                        </div>

                        <div class="form-check form-check-secondary mb-3">
                            <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all"
                                checked />
                            <label class="form-check-label" for="selectAll">View All</label>
                        </div>

                        <div class="app-calendar-events-filter">
                            <div class="form-check form-check-primary mb-3">
                                <input class="form-check-input input-filter" type="checkbox" id="select-business"
                                    data-value="Business" checked />
                                <label class="form-check-label" for="select-business">Leads</label>
                            </div>
                            <div class="form-check form-check-warning mb-3">
                                <input class="form-check-input input-filter" type="checkbox" id="select-holiday"
                                    data-value="Holiday" checked />
                                <label class="form-check-label" for="select-holiday">Customers</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Calendar Sidebar -->

                <!-- Calendar & Modal -->
                <div class="col app-calendar-content">
                    <div class="card shadow-none border-0 border-start rounded-0">
                        <div class="card-body pb-0">
                            <!-- FullCalendar -->
                            <div id="calendar"></div>
                        </div>
                    </div>
                    <div class="app-overlay"></div>
                    <!-- FullCalendar Offcanvas -->
                    <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar"
                        aria-labelledby="addEventSidebarLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="addEventSidebarLabel">Add Event</h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <form class="event-form pt-0" id="eventForm" onsubmit="return false">
                                {{-- <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="eventTitle" name="eventTitle"
                                        placeholder="Event Title" />
                                    <label for="eventTitle">Client</label>
                                </div> --}}
                                <div class="form-floating form-floating-outline mb-4 select2-primary">
                                    <select class="select2 select-event-guests form-select" id="eventClient"
                                        name="eventGuests">
                                        @foreach ($clients as $client)
                                            {{-- data-avatar="1.png" --}}
                                            <option value="{{ $client->id }}">{{ $client->company }}</option>
                                        @endforeach
                                    </select>
                                    <label for="eventGuests">Client</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="eventStartDate" name="eventStartDate"
                                        placeholder="Start Date" />
                                    <label for="eventStartDate">Date</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="eventEndDate" name="eventEndDate"
                                        placeholder="End Date" />
                                    <label for="eventEndDate">Follow Up Date</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <select class="form-select" id="selectAction" aria-label="Default select example"
                                        name="action">
                                        <option disabled>----- Choose Action -----</option>
                                        <option value="Phone Office">Phone Office</option>
                                        <option value="WhatsApp">WhatsApp</option>
                                    </select>
                                    <label for="selectAction">Action</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <select class="form-select" id="selectStatus" aria-label="Default select example"
                                        name="status">
                                        <option disabled>----- Choose Status -----</option>
                                        <option value="Responded">Responded</option>
                                        <option value="Not Respon">Not Responded</option>
                                    </select>
                                    <label for="selectStatus">Status</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <select class="form-select" id="selectIssue" aria-label="Default select example"
                                        name="issues">
                                        @foreach ($issue as $issues)
                                            <option value="{{ $issues->id }}">{{ $issues->issue }}</option>
                                        @endforeach
                                    </select>
                                    <label for="selectIssue">Status</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <textarea class="form-control" name="eventNote" id="eventNote"></textarea>
                                    <label for="eventNote">Note</label>
                                </div>
                                <input class="form-control" type="text" name="eventComp" id="eventComp"
                                    value="" hidden>
                                <div class="form-floating mb-4">
                                    <p id="eventNoteBefore"></p>
                                </div>
                                {{-- <div class="form-floating form-floating-outline mb-4">
                                    <input type="url" class="form-control" id="eventURL" name="eventURL"
                                        placeholder="https://www.google.com" />
                                    <label for="eventURL">Event URL</label>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="eventLocation" name="eventLocation"
                                        placeholder="Enter Location" />
                                    <label for="eventLocation">Location</label>
                                </div> --}}
                                <div class="mb-3 d-flex justify-content-sm-between justify-content-start my-4 gap-2">
                                    <div class="d-flex">
                                        <button type="submit"
                                            class="btn btn-primary btn-add-event me-sm-2 me-1">Add</button>
                                        <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1"
                                            data-bs-dismiss="offcanvas">
                                            Cancel
                                        </button>
                                    </div>
                                    {{-- <button class="btn btn-label-danger btn-delete-event d-none">Delete</button> --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /Calendar & Modal -->
            </div>

        </div>

        @foreach ($prospects as $prospect)
            @include('components.modal.prospect.confirm')
        @endforeach
    @elseif (Auth::user()->role == 'Admin')
        <div class="row gy-4 mb-4">
            <div class="col-12 col-lg-4">
                <div class="card mb-3">
                    <div class="card-body text-nowrap">
                        <h4 class="card-title mb-1 d-flex gap-2 flex-wrap">
                            Sales Results</strong> 🎉
                        </h4>
                        <p class="pb-0">sell of the month</p>
                        <h4 class="text-primary mb-1">Rp. {{ $formattedTotalPriceAdmin }}</h4>
                        @php
                            $jumlah_target = 0;
                            $jumlah_target = ($poTotalPriceAdmin / $targetAllSales) * 100;
                            $formatted_jumlah_target = number_format($jumlah_target, 3);
                        @endphp
                        <p class="mb-2 pb-1">{{ $formatted_jumlah_target }}% of target 🚀</p>
                        @php
                            $today = \Carbon\Carbon::now();
                            $semester = $today->month > 6 ? 2 : 1;

                            $semesterNow = \App\Models\SalesReports::where('semester', $semester)
                                ->where('year', $today->year)
                                ->first();
                        @endphp
                        <a href="{{ route('report.semester', $semesterNow) }}"
                            class="btn btn-sm btn-primary waves-effect waves-light">View Sales</a>
                    </div>
                    <img src="{{ asset('assets') }}/img/illustrations/trophy.png"
                        class="position-absolute bottom-0 end-0 me-3" height="140" alt="view sales">
                </div>
                <div class="card">
                    <div class="card-body pb-0 pt-3">
                        <div class="row d-flex align-items-center">
                            <div class="col-5 col-lg-6 col-xl-5" style="position: relative;">
                                <div class="chart-progress" data-color="primary" data-series="70"
                                    data-icon="../../assets/img/icons/misc/card-icon-laptop.png"
                                    style="min-height: 98px;">
                                    <div id="apexchartss5ddnay7"
                                        class="apexcharts-canvas apexchartss5ddnay7 apexcharts-theme-light"
                                        style="width: 88px; height: 98px;"><svg id="SvgjsSvg1279" width="88"
                                            height="98" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                            class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                            style="background: transparent;">
                                            <g id="SvgjsG1281" class="apexcharts-inner apexcharts-graphical"
                                                transform="translate(-4.5, 0)">
                                                <defs id="SvgjsDefs1280">
                                                    <clipPath id="gridRectMasks5ddnay7">
                                                        <rect id="SvgjsRect1283" width="103" height="99" x="-3"
                                                            y="-1" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMasks5ddnay7"></clipPath>
                                                    <clipPath id="nonForecastMasks5ddnay7"></clipPath>
                                                    <clipPath id="gridRectMarkerMasks5ddnay7">
                                                        <rect id="SvgjsRect1284" width="101" height="101" x="-2"
                                                            y="-2" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                </defs>
                                                <g id="SvgjsG1285" class="apexcharts-radialbar">
                                                    <g id="SvgjsG1286">
                                                        <g id="SvgjsG1287" class="apexcharts-tracks">
                                                            <g id="SvgjsG1288"
                                                                class="apexcharts-radialbar-track apexcharts-track"
                                                                rel="1">
                                                                <path id="apexcharts-radialbarTrack-0"
                                                                    d="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 1 1 48.494278299912935 15.717073670044236"
                                                                    fill="none" fill-opacity="1" stroke="#6d788d29"
                                                                    stroke-opacity="1" stroke-linecap="round"
                                                                    stroke-width="6.165414634146342" stroke-dasharray="0"
                                                                    class="apexcharts-radialbar-area"
                                                                    data:pathOrig="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 1 1 48.494278299912935 15.717073670044236">
                                                                </path>
                                                            </g>
                                                        </g>
                                                        <g id="SvgjsG1290">
                                                            <image id="SvgjsImage1291"
                                                                xlink:href="../../assets/img/icons/misc/card-icon-laptop.png"
                                                                width="18" height="18" x="39.5" y="39.5"></image>
                                                            <g id="SvgjsG1294"
                                                                class="apexcharts-series apexcharts-radial-series"
                                                                seriesName="Progress" rel="1" data:realIndex="0">
                                                                <path id="SvgjsPath1295"
                                                                    d="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 1 1 17.321583815797176 58.63048151559431"
                                                                    fill="none" fill-opacity="0.85"
                                                                    stroke="rgba(102,108,255,0.85)" stroke-opacity="1"
                                                                    stroke-linecap="round"
                                                                    stroke-width="6.356097560975611" stroke-dasharray="0"
                                                                    class="apexcharts-radialbar-area apexcharts-radialbar-slice-0"
                                                                    data:angle="252" data:value="70" index="0" j="0"
                                                                    data:pathOrig="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 1 1 17.321583815797176 58.63048151559431">
                                                                </path>
                                                            </g>
                                                            <circle id="SvgjsCircle1292" r="24.70021951219512"
                                                                cx="48.5" cy="48.5"
                                                                class="apexcharts-radialbar-hollow" fill="transparent">
                                                            </circle>
                                                            <g id="SvgjsG1293" class="apexcharts-datalabels-group"
                                                                transform="translate(0, 0) scale(1)" style="opacity: 1;">
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                                <line id="SvgjsLine1296" x1="0" y1="0" x2="97"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="1" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line id="SvgjsLine1297" x1="0" y1="0" x2="97"
                                                    y2="0" stroke-dasharray="0" stroke-width="0"
                                                    stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                            </g>
                                            <g id="SvgjsG1282" class="apexcharts-annotations"></g>
                                        </svg>
                                        <div class="apexcharts-legend"></div>
                                    </div>
                                </div>
                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 113px; height: 99px;"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                            <div class="col-7 col-lg-6 col-xl-7">
                                <div class="card-info">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h5 class="mb-0">84k</h5>
                                        <div class="d-flex text-danger">
                                            <p class="mb-0">-24%</p>
                                            <div class="mdi mdi-chevron-down"></div>
                                        </div>
                                    </div>
                                    <p class="mb-0 mt-1">Total Impression</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="card-body pt-0 pb-3">
                        <div class="row d-flex align-items-center">
                            <div class="col-5 col-lg-6 col-xl-5" style="position: relative;">
                                <div class="chart-progress" data-color="warning" data-series="40"
                                    data-icon="../../assets/img/icons/misc/card-icon-bag.png" style="min-height: 98px;">
                                    <div id="apexcharts2mo0zjp2k"
                                        class="apexcharts-canvas apexcharts2mo0zjp2k apexcharts-theme-light"
                                        style="width: 88px; height: 98px;"><svg id="SvgjsSvg1298" width="88"
                                            height="98" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                            class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                            style="background: transparent;">
                                            <g id="SvgjsG1300" class="apexcharts-inner apexcharts-graphical"
                                                transform="translate(-4.5, 0)">
                                                <defs id="SvgjsDefs1299">
                                                    <clipPath id="gridRectMask2mo0zjp2k">
                                                        <rect id="SvgjsRect1302" width="103" height="99" x="-3"
                                                            y="-1" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMask2mo0zjp2k"></clipPath>
                                                    <clipPath id="nonForecastMask2mo0zjp2k"></clipPath>
                                                    <clipPath id="gridRectMarkerMask2mo0zjp2k">
                                                        <rect id="SvgjsRect1303" width="101" height="101" x="-2"
                                                            y="-2" rx="0" ry="0" opacity="1"
                                                            stroke-width="0" stroke="none" stroke-dasharray="0"
                                                            fill="#fff"></rect>
                                                    </clipPath>
                                                </defs>
                                                <g id="SvgjsG1304" class="apexcharts-radialbar">
                                                    <g id="SvgjsG1305">
                                                        <g id="SvgjsG1306" class="apexcharts-tracks">
                                                            <g id="SvgjsG1307"
                                                                class="apexcharts-radialbar-track apexcharts-track"
                                                                rel="1">
                                                                <path id="apexcharts-radialbarTrack-0"
                                                                    d="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 1 1 48.494278299912935 15.717073670044236"
                                                                    fill="none" fill-opacity="1" stroke="#6d788d29"
                                                                    stroke-opacity="1" stroke-linecap="round"
                                                                    stroke-width="6.165414634146342" stroke-dasharray="0"
                                                                    class="apexcharts-radialbar-area"
                                                                    data:pathOrig="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 1 1 48.494278299912935 15.717073670044236">
                                                                </path>
                                                            </g>
                                                        </g>
                                                        <g id="SvgjsG1309">
                                                            <image id="SvgjsImage1310"
                                                                xlink:href="../../assets/img/icons/misc/card-icon-bag.png"
                                                                width="18" height="18" x="39.5" y="39.5"></image>
                                                            <g id="SvgjsG1313"
                                                                class="apexcharts-series apexcharts-radial-series"
                                                                seriesName="Progress" rel="1" data:realIndex="0">
                                                                <path id="SvgjsPath1314"
                                                                    d="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 0 1 67.76932091722715 75.02194493022846"
                                                                    fill="none" fill-opacity="0.85"
                                                                    stroke="rgba(253,181,40,0.85)" stroke-opacity="1"
                                                                    stroke-linecap="round"
                                                                    stroke-width="6.356097560975611" stroke-dasharray="0"
                                                                    class="apexcharts-radialbar-area apexcharts-radialbar-slice-0"
                                                                    data:angle="144" data:value="40" index="0" j="0"
                                                                    data:pathOrig="M 48.5 15.717073170731709 A 32.78292682926829 32.78292682926829 0 0 1 67.76932091722715 75.02194493022846">
                                                                </path>
                                                            </g>
                                                            <circle id="SvgjsCircle1311" r="24.70021951219512"
                                                                cx="48.5" cy="48.5"
                                                                class="apexcharts-radialbar-hollow" fill="transparent">
                                                            </circle>
                                                            <g id="SvgjsG1312" class="apexcharts-datalabels-group"
                                                                transform="translate(0, 0) scale(1)" style="opacity: 1;">
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                                <line id="SvgjsLine1315" x1="0" y1="0" x2="97"
                                                    y2="0" stroke="#b6b6b6" stroke-dasharray="0"
                                                    stroke-width="1" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line id="SvgjsLine1316" x1="0" y1="0" x2="97"
                                                    y2="0" stroke-dasharray="0" stroke-width="0"
                                                    stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                            </g>
                                            <g id="SvgjsG1301" class="apexcharts-annotations"></g>
                                        </svg>
                                        <div class="apexcharts-legend"></div>
                                    </div>
                                </div>
                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 113px; height: 99px;"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                            <div class="col-7 col-lg-6 col-xl-7">
                                <div class="card-info">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h5 class="mb-0">22k</h5>
                                        <div class="d-flex text-success">
                                            <p class="mb-0">+15%</p>
                                            <div class="mdi mdi-chevron-up"></div>
                                        </div>
                                    </div>
                                    <p class="mb-0 mt-1">Total Order</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title m-0">
                            <h5 class="mb-0">Sales Overview</h5>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="earningReportsTabsId" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReportsTabsId">
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">View More</a>
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Delete</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-3">
                        <ul class="nav nav-tabs nav-tabs-widget pb-3 gap-2 d-flex flex-nowrap" role="tablist">
                            @foreach ($sales as $user)
                                <li class="nav-item change-sales" role="presentation" style="width: 15%;height: 15%;"
                                    data-id="{{ $user->id }}">
                                    <img class="nav-link btn {{ $user->id == 1 ? 'active' : '' }} d-flex flex-column align-items-center justify-content-center"
                                        role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-sales-{{ $user->id }}"
                                        aria-controls="navs-sales-{{ $user->id }}" aria-selected="true"
                                        src="{{ url('') . '/' . $user->image }}" alt="" srcset=""
                                        style="width : 75px !important; height:75px !important; object-fit: cover; padding: 10px;">
                                </li>
                            @endforeach
                            <span class="tab-slider" style="left: 0px; width: 112px; bottom: 0px;"></span>
                        </ul>
                        <div class="tab-content p-0 ms-0 ms-sm-2">
                            @php
                                $item = 0;
                            @endphp
                            @foreach ($sales as $user)
                                <div class="tab-pane fade{{ $user->id == 1 ? ' show active' : '' }}"
                                    id="navs-sales-{{ $user->id }}" role="tabpanel">
                                    <div class="mb-3">
                                        <div data-id="{{ $item }}">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <div class="d-flex justify-content-between">
                                                            <h4 class="">{{ $user->name }}'s Overview</h4>
                                                        </div>
                                                    </div>
                                                    @if ($user->role == 'Sales')
                                                        <div class="col-4">
                                                            @if ($user->id != 3)
                                                                <div class="d-flex mb-2 gap-2">
                                                                    <a type="button" data-bs-toggle="modal"
                                                                        data-bs-target="#overview-sales-{{ $user->id }}">
                                                                        <div class="avatar">
                                                                            <div
                                                                                class="avatar-initial bg-label-secondary rounded">
                                                                                <i
                                                                                    class="mdi mdi-account-multiple-plus-outline mdi-24px"></i>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <div class="card-info">
                                                                        <h5 class="mb-0">
                                                                            <span class="filtered-leads">
                                                                                {{ $filteredLeads }}
                                                                            </span>
                                                                            <span
                                                                                class="text-muted fs-tiny fw-normal">/{{ $targetSales[$item][0]->leads }}</span>
                                                                        </h5>
                                                                        <small class="text-muted">New Leads</small>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex mb-2 gap-2">
                                                                    <a type="button" data-bs-toggle="modal"
                                                                        data-bs-target="#overview-sales-{{ $user->id }}">
                                                                        <div class="avatar">
                                                                            <button type="button"
                                                                                class="avatar-initial bg-label-info rounded">
                                                                                <i
                                                                                    class="mdi mdi-phone-outline mdi-24px"></i>
                                                                            </button>
                                                                        </div>
                                                                    </a>
                                                                    <div class="card-info">
                                                                        <h5 class="mb-0">
                                                                            <span
                                                                                class="filtered-dc">{{ $filteredDC }}</span>
                                                                            @if ($user->id != 3 && $user->id != 4)
                                                                                <span
                                                                                    class="text-muted fs-tiny fw-normal">/{{ $targetSales[$item][0]->dc }}</span>
                                                                            @endif
                                                                        </h5>
                                                                        <small class="text-muted">Daily Call</small>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex mb-2 gap-2">
                                                                    <div class="avatar">
                                                                        <div
                                                                            class="avatar-initial bg-label-primary rounded">
                                                                            <i
                                                                                class="mdi mdi-account-multiple-outline mdi-24px"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-info">
                                                                        <h5 class="mb-0">
                                                                            <span class=" filtered-crm">
                                                                                {{ $filteredCRM }}
                                                                            </span>
                                                                            <span
                                                                                class="text-muted fs-tiny fw-normal">/{{ $targetCrm[$user->id] ?? 0 }}</span>
                                                                        </h5>
                                                                        <small class="text-muted">CRM</small>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @php
                                                                $lastDetail = $user->detail->last();
                                                            @endphp
                                                            {{-- @if ($lastDetail->area == 'Bekasi' || $lastDetail->area == 'Jabodetabek' || $lastDetail->area == 'Jawa Barat')
                                                                <div class="d-flex mb-2 gap-2">
                                                                    <div class="avatar">
                                                                        <div
                                                                            class="avatar-initial bg-label-danger rounded">
                                                                            <i
                                                                                class="mdi mdi-office-building-marker-outline mdi-24px"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-info">
                                                                        <h5 class="mb-0 filtered-visit">
                                                                            {{ $filteredVisit }}<span
                                                                                class="text-muted fs-tiny fw-normal">/{{ $targetSales[$item][0]->visit }}</span>
                                                                        </h5>
                                                                        <small class="text-muted">Visit</small>
                                                                    </div>
                                                                </div>
                                                            @endif --}}
                                                            <div class="d-flex mb-2 gap-2">
                                                                <a href="{{ route('sales.quotation', $user->id) }}">
                                                                    <div class="avatar">
                                                                        <div
                                                                            class="avatar-initial bg-label-warning rounded">
                                                                            <i
                                                                                class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                                <div class="card-info">
                                                                    <h5 class="mb-0">
                                                                        <span class="filtered-quote">
                                                                            {{ $filteredQuote }}
                                                                        </span>
                                                                        <span
                                                                            class="text-muted fs-tiny fw-normal">/{{ $targetSales[$item][0]->quote }}</span>
                                                                    </h5>
                                                                    <small class="text-muted">Quotation</small>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex mb-2 gap-2">
                                                                <a href="{{ route('sales.po', $user->id) }}">
                                                                    <div class="avatar">
                                                                        <div
                                                                            class="avatar-initial bg-label-success rounded">
                                                                            <i class="mdi mdi-cart-plus mdi-24px"></i>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                                <div class="card-info">
                                                                    <h5 class="mb-0 filtered-po">
                                                                        {{ $filteredPO }}
                                                                    </h5>
                                                                    <small class="text-muted">PO</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="d-flex mb-2 gap-2">
                                                                    <div class="avatar">
                                                                        <div
                                                                            class="avatar-initial bg-label-success rounded">
                                                                            <i class="mdi mdi-cart-plus mdi-24px"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-info">
                                                                        <div
                                                                            class="total-percentage-po d-flex justify-center">
                                                                            <h5 class="mb-0 total-po">Rp
                                                                                {{ number_format($totalPO, 2, ',', '.') }}
                                                                                @php
                                                                                    $jumlah_target =
                                                                                        ($totalPO / $targett->total) *
                                                                                        100;
                                                                                @endphp
                                                                            </h5>
                                                                            <h5 class="text-success mb-0 target-po"
                                                                                style="margin-left: 8px;">
                                                                                {{ number_format($jumlah_target, 3) }}%
                                                                            </h5>
                                                                        </div>
                                                                        <small class="text-muted">Total Sales</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="d-flex mb-2 gap-2">
                                                                    <div class="avatar">
                                                                        <div
                                                                            class="avatar-initial bg-label-primary rounded">
                                                                            <i
                                                                                class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-info">
                                                                        <h5 class="mb-0 total-forecast">
                                                                            Rp
                                                                            {{ number_format($totalForecast, 2, ',', '.') }}
                                                                        </h5>
                                                                        <small class="text-muted">Quotation</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="d-flex mb-2 gap-2">
                                                                    <div class="avatar">
                                                                        <div
                                                                            class="avatar-initial bg-label-warning rounded">
                                                                            <i
                                                                                class="mdi mdi-email-alert-outline mdi-24px"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-info">
                                                                        <h5 class="mb-0 total-prospect">Rp
                                                                            {{ number_format($totalProspect, 2, ',', '.') }}
                                                                        </h5>
                                                                        <small class="text-muted">Hot Prospect</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3">
                                                                @php
                                                                    $month = date('m');
                                                                    $year = date('Y');
                                                                    $dateNow = $month . '-' . $year;
                                                                @endphp
                                                                <div class="col-6">
                                                                    <a class="btn btn-facebook d-grid w-100 waves-effect h-100"
                                                                        href="{{ route('detail-overview.semester', ['sales' => $user->id, 'date' => $dateNow]) }}">
                                                                        Overview {{ date('F') }}
                                                                    </a>
                                                                </div>
                                                                <div class="col-6">
                                                                    <a class="btn btn-secondary d-grid w-100 waves-effect h-100"
                                                                        href="{{ route('overview.semester', $user->id) }}">
                                                                        Overview Semester
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-4">
                                                            <div class="d-flex mb-2 gap-2">
                                                                {{-- <a type="button" data-bs-toggle="modal"
                                                                    data-bs-target="#overview-sales-{{ $item }}"> --}}
                                                                <div class="avatar">
                                                                    <button type="button"
                                                                        class="avatar-initial bg-label-info rounded">
                                                                        <i class="mdi mdi-phone-outline mdi-24px"></i>
                                                                    </button>
                                                                </div>
                                                                {{-- </a> --}}
                                                                <div class="card-info">
                                                                    <h5 class="mb-0 filtered-prospect">{{ $filteredDC }}
                                                                        <span
                                                                            class="text-muted fs-tiny fw-normal">/{{ $targetSales[$item][0]->dc }}</span>
                                                                    </h5>
                                                                    <small class="text-muted">Prospect</small>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex mb-2 gap-2">
                                                                <div class="avatar">
                                                                    <div class="avatar-initial bg-label-primary rounded">
                                                                        <i
                                                                            class="mdi mdi-account-multiple-outline mdi-24px"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="card-info">
                                                                    <h5 class="mb-0 filtered-provide">
                                                                        {{ $filteredCRM }}<span
                                                                            class="text-muted fs-tiny fw-normal">/{{ $targetSales[$item][0]->crm }}</span>
                                                                    </h5>
                                                                    <small class="text-muted">Provided</small>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex mb-2 gap-2">
                                                                <a href="{{ route('sales.quotation', $user->id) }}">
                                                                    <div class="avatar">
                                                                        <div
                                                                            class="avatar-initial bg-label-warning rounded">
                                                                            <i
                                                                                class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                                <div class="card-info">
                                                                    <h5 class="mb-0 filtered-prospect-quotation">
                                                                        {{ $filteredQuote }}<span
                                                                            class="text-muted fs-tiny fw-normal">/{{ $targetSales[$item][0]->quote }}</span>
                                                                    </h5>
                                                                    <small class="text-muted">Quotation</small>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex mb-2 gap-2">
                                                                <a href="{{ route('sales.po', $user->id) }}">
                                                                    <div class="avatar">
                                                                        <div
                                                                            class="avatar-initial bg-label-success rounded">
                                                                            <i class="mdi mdi-cart-plus mdi-24px"></i>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                                <div class="card-info">
                                                                    <h5 class="mb-0 filtered-prospect-po">
                                                                        {{ $filteredPO }}
                                                                    </h5>
                                                                    <small class="text-muted">PO</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="d-flex mb-2 gap-2">
                                                                    <div class="avatar">
                                                                        <div
                                                                            class="avatar-initial bg-label-success rounded">
                                                                            <i
                                                                                class="mdi mdi-account-cart-plus mdi-24px"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-info">
                                                                        <div
                                                                            class="total-percentage-po d-flex justify-center">
                                                                            <h5 class="mb-0 total-prospect-po">Rp
                                                                                {{ number_format($totalPO, 2, ',', '.') }}
                                                                                @php
                                                                                    $jumlah_target =
                                                                                        ($totalPO / $targett->total) *
                                                                                        100;
                                                                                @endphp
                                                                            </h5>
                                                                            <h5 class="text-success mb-0 target-po"
                                                                                style="margin-left: 8px;">
                                                                                {{ number_format($jumlah_target, 3) }}%
                                                                            </h5>
                                                                        </div>
                                                                        <small class="text-muted">Total Sales</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="d-flex mb-2 gap-2">
                                                                    <div class="avatar">
                                                                        <div
                                                                            class="avatar-initial bg-label-primary rounded">
                                                                            <i
                                                                                class="mdi mdi-email-multiple-outline mdi-24px"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-info">
                                                                        <h5 class="mb-0 total-prospect-quotation">
                                                                            Rp
                                                                            {{ number_format($totalForecast, 2, ',', '.') }}
                                                                        </h5>
                                                                        <small class="text-muted">Quotation</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="d-flex align-items-center gap-2">
                                                                <div class="d-flex mb-2 gap-2">
                                                                    <div class="avatar">
                                                                        <div
                                                                            class="avatar-initial bg-label-warning rounded">
                                                                            <i
                                                                                class="mdi mdi-email-alert-outline mdi-24px"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-info">
                                                                        <h5 class="mb-0 total-prospect">Rp
                                                                            {{ number_format($totalProspect, 2, ',', '.') }}
                                                                        </h5>
                                                                        <small class="text-muted">Hot Prospect</small>
                                                                    </div>
                                                                </div>
                                                            </div> --}}
                                                            <div class="row mt-3">
                                                                @php
                                                                    $month = date('m');
                                                                    $year = date('Y');
                                                                    $dateNow = $month . '-' . $year;
                                                                @endphp
                                                                <div class="col-6">
                                                                    <a class="btn btn-facebook d-grid w-100 waves-effect h-100"
                                                                        href="{{ route('detail-overview.semester', ['sales' => $user->id, 'date' => $dateNow]) }}">
                                                                        Overview {{ date('F') }}
                                                                    </a>
                                                                </div>
                                                                <div class="col-6">
                                                                    <a class="btn btn-secondary d-grid w-100 waves-effect h-100"
                                                                        href="{{ route('overview.semester', $user->id) }}">
                                                                        Overview Semester
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $item++;
                                    @endphp
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-datatable table-responsive pt-0" style="font-size: 13px;">
                        <table class="datatable-prospect-quote table table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    {{-- <th></th> --}}
                                    <th>ID</th>
                                    <th>Quote No.</th>
                                    <th>Company</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                    <th>Assigned</th>
                                    {{-- <th>Actions</th> --}}
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="card mb-4">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-notulen table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Desc</th>
                            <th>Level</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div> --}}
        @php
            $item = 0;
        @endphp
        @foreach ($sales as $user)
            @if ($user->role == 'Sales')
                @include('components.modal.overview')
                @php
                    $item++;
                @endphp
            @endif
        @endforeach
    @elseif(Auth::user()->role == 'Logistic')
        <h4 class="fw-bold py-3 mb-4">
            Product
        </h4>
        <div class="card mb-4">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                    <p class="mb-2">Comodity</p>
                                    <h4 class="mb-2">{{ $commodity }}</h4>
                                    <p class="mb-0"><span class="badge rounded-pill bg-label-success"></span></p>
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="mdi mdi-home-outline mdi-24px"></i>
                                    </span>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                                <div>
                                    <p class="mb-2">Equivalent</p>
                                    <h4 class="mb-2">{{ $sproduct }}</h4>
                                    <p class="mb-0"><span class="badge rounded-pill bg-label-success"></span></p>
                                </div>
                                <div class="avatar me-lg-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="mdi mdi-laptop mdi-24px"></i>
                                    </span>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none">
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                <div>
                                    <p class="mb-2">Pruchase Order</p>
                                    <h4 class="mb-2">1</h4>
                                    <p class="mb-0"><span class="badge rounded-pill bg-label-success"></span></p>
                                </div>
                                <div class="avatar me-sm-4">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="mdi mdi-wallet-giftcard mdi-24px"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-2">Loss Order</p>
                                    <h4 class="mb-2">2</h4>
                                    <p class="mb-0"><span class="badge rounded-pill bg-label-danger"></span></p>
                                </div>
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="mdi mdi-currency-usd mdi-24px"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-product table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Part Number</th>
                            <th>Desc</th>
                            <th>Dimension</th>
                            <th>G/O</th>
                            <th>Stock BDG</th>
                            <th>Stock BKS</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-notulen table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Desc</th>
                            <th>Level</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        @include('components.modal.warehouse.product.form')
    @elseif(Auth::user()->role == 'Coordinator')
        <h4 class="fw-3">Request Visit</h4>
        <div class="card mb-3">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-visit-coordinator table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>company</th>
                            <th>Machine</th>
                            <th>Date Request</th>
                            <th>Sales</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <h4 class="fw-3">Visit Schedule</h4>
        <div class="card mb-3">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-visit-accept table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>company</th>
                            <th>Machine</th>
                            <th>Date</th>
                            <th>Sales</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-notulen table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Desc</th>
                            <th>Level</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        @foreach ($visits as $visit)
            @include('components.modal.req-visit.form-accept')
        @endforeach
        @foreach ($visited as $visit)
            @include('components.modal.req-visit.form-visited')
        @endforeach
    @elseif(Auth::user()->role == 'ServiceM')
        <div class="row mb-3">
            <div class="col-12 col-md-3">
                <div class="card mb-3">
                    <div class="card-body">
                        <img src="{{ url('') . '/' . $user->image }}" alt="" srcset=""
                            class="h-100 w-100">
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-9">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-1">
                                <div class="form-check form-check-success">
                                    <input class="form-check-input checkPlanning" type="checkbox" name="planing"
                                        value="1" id="customCheckSuccess"
                                        {{ @$monitoring && @$monitoring->planning ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div
                                class="col alert-planning {{ @$monitoring && @$monitoring->planning ? 'alert-success' : '' }}">
                                <div id="planing">Update Planning Pekerjaan Tim Lapangan</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-1">
                                <div class="form-check form-check-success">
                                    <input class="form-check-input checkSync" type="checkbox" name="sync"
                                        value="1" id="customCheckSuccess"
                                        {{ @$monitoring && @$monitoring->sync ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col alert-sync {{ @$monitoring && @$monitoring->sync ? 'alert-success' : '' }}">
                                <div id="sync">Sinkronisasi Planing Dengan Aktual Pekerjaan</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-1">
                                <div class="form-check form-check-success">
                                    <input class="form-check-input checkAbnormal" type="checkbox" name="abnormal"
                                        value="1" id="customCheckSuccess"
                                        {{ @$monitoring && @$monitoring->abnormal ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div
                                class="col alert-abnormal {{ @$monitoring && @$monitoring->abnormal ? 'alert-success' : '' }}">
                                <div id="sync">Cek Issue / Temuan Abnormal Dilapangan</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-1">
                                <div class="form-check form-check-success">
                                    <input class="form-check-input checkLog" type="checkbox" name="log"
                                        value="1" id="customCheckSuccess"
                                        {{ @$monitoring && @$monitoring->log ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col alert-log {{ @$monitoring && @$monitoring->log ? 'alert-success' : '' }}">
                                <div id="log">Update Maintenance Log pekerjaan & Sinkronisasi Dengan Aktual Activity
                                    di
                                    Lapangan</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-1">
                                <div class="form-check form-check-success">
                                    <input class="form-check-input checkTimeline" type="checkbox" name="timeline"
                                        value="1" id="customCheckSuccess"
                                        {{ @$monitoring && @$monitoring->timeline ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div
                                class="col alert-timeline {{ @$monitoring && @$monitoring->timeline ? 'alert-success' : '' }}">
                                <div id="timeline">Update Timeline Weekly Cleaning Dengan Actual Pekerjaan</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1">
                                <div class="form-check form-check-success">
                                    <input class="form-check-input checkPreventive" type="checkbox" name="preventive"
                                        value="1" id="customCheckSuccess"
                                        {{ @$monitoring && @$monitoring->preventive ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div
                                class="col alert-preventive {{ @$monitoring && @$monitoring->preventive ? 'alert-success' : '' }}">
                                <div id="preventive">Update Timeline Preventive Maintenance ( Pergantian Sparepart )</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-reports-monitor table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>No Service</th>
                            <th>Company</th>
                            <th>Job Desc</th>
                            <th>Unit Type</th>
                            <th>Date</th>
                            <th>Sales</th>
                            <th>Technician</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    @elseif(Auth::user()->role == 'Technician')
        <div class="card mb-3">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatable-notulen table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Desc</th>
                            <th>Level</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    @elseif(Auth::user()->role == 'Client')
        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5> Machine </h5>
                        <div class="card-datatable table-responsive pt-0">
                            <table class="datatable-client-compressor table table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>ID</th>
                                        <th>Brand</th>
                                        <th>Unit</th>
                                        <th>Tag</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @foreach ($notulens as $notulen)
        @include('components.modal.notulen.detail')
    @endforeach
@endsection
@push('after-style')
    {{-- All --}}
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />

    {{-- sales --}}
    @if (Auth::user()->role == 'Sales' || Auth::user()->role == 'Support')
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-calendar.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/swiper/swiper.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/fullcalendar/fullcalendar.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
    @endif

    {{-- admin --}}
    @if (Auth::user()->role == 'Admin')
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/cards-statistics.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/cards-analytics.css" />
        <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/apex-charts/apex-charts.css" />
    @endif

    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />

    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css" />
@endpush

@push('after-script')
    <!-- Vendors JS -->
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    {{-- sales --}}
    {{-- sales --}}
    @if (Auth::user()->role == 'Sales' || Auth::user()->role == 'Support')
        <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/fullcalendar/fullcalendar.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/apex-charts/apexcharts.js"></script>
        <script></script>
    @endif
@endpush
@push('page-script')
    <!-- Page JS -->
    <script src="{{ asset('assets') }}/js/ui-modals.js"></script>

    @if (Auth::user()->role == 'Sales' || Auth::user()->role == 'Support')
        <script src="{{ asset('assets') }}/js/tables-datatables-basic.js"></script>
        <script src="{{ asset('assets') }}/js/dashboards-crm.js"></script>
        <script src="{{ asset('assets') }}/js/app-calendar-events.js"></script>
        <script src="{{ asset('assets') }}/js/app-calendar.js"></script>
        <script src="{{ asset('assets') }}/includes/chart/card-monthly.js"></script>
        <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
        <script src="{{ asset('assets') }}/includes/table-hot-prospect-sales.js"></script>
        <script src="{{ asset('assets') }}/includes/table-req-visit-sales.js"></script>
    @endif

    @if (Auth::user()->role == 'Coordinator')
        <script src="{{ asset('assets') }}/includes/table-req-visit-accept.js"></script>
        <script src="{{ asset('assets') }}/includes/table-req-visit-service.js"></script>
    @endif
    <script src="{{ asset('assets') }}/includes/table-hot-prospect.js"></script>

    <script src="{{ asset('assets') }}/includes/table-product-sales.js"></script>
    <script src="{{ asset('assets') }}/includes/table-product-logistic.js"></script>

    <script src="{{ asset('assets') }}/includes/table-reports-admin.js"></script>

    <script src="{{ asset('assets') }}/includes/table-reports.js"></script>
    <script src="{{ asset('assets') }}/includes/table-reports-monitor.js"></script>
    <script src="{{ asset('assets') }}/includes/table-notulen.js"></script>

    <script src="{{ asset('assets') }}/includes/table-client-compressor.js"></script>
    {{-- @if (Auth::user()->role == 'Admin') --}}
    <script>
        function formatNumber(n) {
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        }

        function validateFloatInputAkurasi(input) {
            let value = input.value;

            // Ganti titik ke koma otomatis
            value = value.replace('.', ',');

            // Hapus karakter selain angka dan koma
            value = value.replace(/[^0-9,]/g, '');

            // Hapus koma lebih dari satu
            let parts = value.split(',');
            if (parts.length > 2) {
                value = parts[0] + ',' + parts[1];
            }

            // Batasi maksimum 5,0
            if (value !== '') {
                let number = parseFloat(value.replace(',', '.'));
                if (!isNaN(number) && number > 5) {
                    value = '5,0';
                }
            }

            input.value = value;

            // Ambil nilai dari kedua input
            let airendEl = document.getElementById('airend');
            let kojishaEl = document.getElementById('kojisha');
            let averageEl = document.getElementById('average');
            let averageTextEl = document.getElementById('averageText');

            if (airendEl && kojishaEl && averageEl) {
                let airend = airendEl.value.replace(',', '.');
                let kojisha = kojishaEl.value.replace(',', '.');

                let a = parseFloat(airend);
                let b = parseFloat(kojisha);

                if (!isNaN(a) && !isNaN(b)) {
                    let avg = (a + b) / 2;
                    let avgStr = avg.toFixed(1).replace('.', ',');
                    averageEl.value = avgStr;
                    averageTextEl.textContent = avgStr;
                } else {
                    averageEl.value = '';
                    averageTextEl.textContent = '';
                }
            }
        }

        function validateFloatInputDelivery(input) {
            let value = input.value;

            // Ganti titik ke koma otomatis
            value = value.replace('.', ',');

            // Hapus karakter selain angka dan koma
            value = value.replace(/[^0-9,]/g, '');

            // Hapus koma lebih dari satu
            let parts = value.split(',');
            if (parts.length > 2) {
                value = parts[0] + ',' + parts[1];
            }

            // Batasi maksimum 5,0
            if (value !== '') {
                let number = parseFloat(value.replace(',', '.'));
                if (!isNaN(number) && number > 5) {
                    value = '5,0';
                }
            }

            input.value = value;

            // Ambil nilai dari kedua input
            let airendEl = document.getElementById('airendDelivery');
            let kojishaEl = document.getElementById('kojishaDelivery');
            let averageEl = document.getElementById('averageDelivery');
            let averageTextEl = document.getElementById('averageDeliveryText');

            if (airendEl && kojishaEl && averageEl) {
                let airend = airendEl.value.replace(',', '.');
                let kojisha = kojishaEl.value.replace(',', '.');

                let a = parseFloat(airend);
                let b = parseFloat(kojisha);

                if (!isNaN(a) && !isNaN(b)) {
                    let avg = (a + b) / 2;
                    let avgStr = avg.toFixed(1).replace('.', ',');
                    averageEl.value = avgStr;
                    averageTextEl.textContent = avgStr;
                } else {
                    averageEl.value = '';
                    averageTextEl.textContent = '';
                }
            }
        }

        function validateFloatInputResponse(input) {
            let value = input.value;

            // Ganti titik ke koma otomatis
            value = value.replace('.', ',');

            // Hapus karakter selain angka dan koma
            value = value.replace(/[^0-9,]/g, '');

            // Hapus koma lebih dari satu
            let parts = value.split(',');
            if (parts.length > 2) {
                value = parts[0] + ',' + parts[1];
            }

            // Batasi maksimum 5,0
            if (value !== '') {
                let number = parseFloat(value.replace(',', '.'));
                if (!isNaN(number) && number > 100) {
                    value = '100';
                }
            }

            input.value = value;

            // Ambil nilai dari kedua input
            let airendEl = document.getElementById('airendResponse');
            let kojishaEl = document.getElementById('kojishaResponse');
            let averageEl = document.getElementById('averageResponse');
            let averageTextEl = document.getElementById('averageResponseText');

            if (airendEl && kojishaEl && averageEl) {
                let airend = airendEl.value.replace(',', '.');
                let kojisha = kojishaEl.value.replace(',', '.');

                let a = parseFloat(airend);
                let b = parseFloat(kojisha);

                if (!isNaN(a) && !isNaN(b)) {
                    let avg = (a + b) / 2;
                    let avgStr = avg.toFixed(1).replace('.', ',');
                    averageEl.value = avgStr;
                    averageTextEl.textContent = avgStr + '%';
                } else {
                    averageEl.value = '';
                    averageTextEl.textContent = '';
                }
            }
        }

        function validateFloatInputRating(input) {
            let value = input.value;

            // Ganti titik ke koma otomatis
            value = value.replace('.', ',');

            // Hapus karakter selain angka dan koma
            value = value.replace(/[^0-9,]/g, '');

            // Hapus koma lebih dari satu
            let parts = value.split(',');
            if (parts.length > 2) {
                value = parts[0] + ',' + parts[1];
            }

            // Batasi maksimum 5,0
            if (value !== '') {
                let number = parseFloat(value.replace(',', '.'));
                if (!isNaN(number) && number > 5) {
                    value = '5,0';
                }
            }

            input.value = value;

            // Ambil nilai dari kedua input
            let airendEl = document.getElementById('airendRating');
            let kojishaEl = document.getElementById('kojishaRating');
            let averageEl = document.getElementById('averageRating');
            let averageTextEl = document.getElementById('averageRatingText');

            if (airendEl && kojishaEl && averageEl) {
                let airend = airendEl.value.replace(',', '.');
                let kojisha = kojishaEl.value.replace(',', '.');

                let a = parseFloat(airend);
                let b = parseFloat(kojisha);

                if (!isNaN(a) && !isNaN(b)) {
                    let avg = (a + b) / 2;
                    let avgStr = avg.toFixed(1).replace('.', ',');
                    averageEl.value = avgStr;
                    averageTextEl.textContent = avgStr;
                } else {
                    averageEl.value = '';
                    averageTextEl.textContent = '';
                }
            }
        }

        function validateFloatInputCustomer(input) {
            let value = input.value;

            // Ganti titik ke koma otomatis
            value = value.replace('.', ',');

            // Hapus karakter selain angka dan koma
            value = value.replace(/[^0-9,]/g, '');

            // Hapus koma lebih dari satu
            let parts = value.split(',');
            if (parts.length > 2) {
                value = parts[0] + ',' + parts[1];
            }

            // Batasi maksimum 5,0
            if (value !== '') {
                let number = parseFloat(value.replace(',', '.'));
                if (!isNaN(number) && number > 5) {
                    value = '5,0';
                }
            }

            input.value = value;

            // Ambil nilai dari kedua input
            let airendEl = document.getElementById('airendCustomer');
            let kojishaEl = document.getElementById('kojishaCustomer');
            let averageEl = document.getElementById('averageCustomer');
            let averageTextEl = document.getElementById('averageCustomerText');

            if (airendEl && kojishaEl && averageEl) {
                let airend = airendEl.value.replace(',', '.');
                let kojisha = kojishaEl.value.replace(',', '.');

                let a = parseFloat(airend);
                let b = parseFloat(kojisha);

                if (!isNaN(a) && !isNaN(b)) {
                    let avg = (a + b) / 2;
                    let avgStr = avg.toFixed(1).replace('.', ',');
                    averageEl.value = avgStr;
                    averageTextEl.textContent = avgStr;
                } else {
                    averageEl.value = '';
                    averageTextEl.textContent = '';
                }
            }
        }

        function validateMaxInput(input) {
            let value = input.value;

            // Ganti titik ke koma otomatis
            value = value.replace('.', ',');

            // Hapus karakter selain angka dan koma
            value = value.replace(/[^0-9,]/g, '');

            // Hapus koma lebih dari satu
            let parts = value.split(',');
            if (parts.length > 2) {
                value = parts[0] + ',' + parts[1];
            }

            // Batasi maksimum 5,0
            if (value !== '') {
                let number = parseFloat(value.replace(',', '.'));
                if (!isNaN(number) && number > 3) {
                    value = 3;
                }
            }

            input.value = value;
        }

        $('.change-sales').on('click', function(ev) {
            var id = $(this).data('id');
            // console.log( 'sales ini ber id : ' + id);
            $.ajax({
                url: '/dashboard/totalPo/' + id,
                type: 'GET',
                success: function(response) {
                    total = formatNumber(response);
                    $(`.total-po`).text('Rp ' + total);
                }
            });
            $.ajax({
                url: '/dashboard/target/' + id,
                type: 'GET',
                success: function(response) {
                    var targetPercentage = (response / 100).toFixed(3);
                    $(`.target-po`).text(targetPercentage + '%');
                    console.log(targetPercentage);
                }
            });
            $.ajax({
                url: '/dashboard/totalProspect/' + id,
                type: 'GET',
                success: function(response) {
                    total = formatNumber(response);
                    $(`.total-prospect`).text('Rp ' + total);
                }
            });
            $.ajax({
                url: '/dashboard/totalForecast/' + id,
                type: 'GET',
                success: function(response) {
                    total = formatNumber(response);
                    $(`.total-forecast`).text('Rp ' + total);
                }
            });
            $.ajax({
                url: '/dashboard/filteredLeads/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.filtered-leads`).text(response);
                }
            });
            $.ajax({
                url: '/dashboard/filteredPo/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.filtered-po`).text(response);
                }
            });
            $.ajax({
                url: '/dashboard/filteredDc/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.filtered-dc`).text(response);
                }
            });
            $.ajax({
                url: '/dashboard/filteredCRM/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.filtered-crm`).text(response);
                }
            });
            $.ajax({
                url: '/dashboard/filteredTargetCRM/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.filtered-target-crm`).text('/' + response);
                    console.log(response);

                }
            });
            $.ajax({
                url: '/dashboard/filteredVisit/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.filtered-visit`).text(response);
                }
            });
            $.ajax({
                url: '/dashboard/filteredQuote/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.filtered-quote`).text(response);
                }
            });
            $.ajax({
                url: '/dashboard/filteredProspect/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.filtered-prospect`).text(response);
                }
            });
            $.ajax({
                url: '/dashboard/filteredProvide/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.filtered-provide`).text(response);
                }
            });
            $.ajax({
                url: '/dashboard/filteredProspectQuote/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.filtered-prospect-quotation`).text(response);
                }
            });
            $.ajax({
                url: '/dashboard/filteredProspectPO/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.filtered-prospect-po`).text(response);
                }
            });
            $.ajax({
                url: '/dashboard/totalProspectPO/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.total-prospect-po`).text('Rp ' + response);
                }
            });
            $.ajax({
                url: '/dashboard/totalProspectQuote/' + id,
                type: 'GET',
                success: function(response) {
                    $(`.total-prospect-quotation`).text('Rp ' + response);
                }
            });


        });

        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
            $('.checkPlanning').on('change', function() {
                let isChecked = $(this).is(':checked');

                // Tambah atau hapus class alert-success
                if (isChecked) {
                    $('.alert-planning').addClass('alert-success');
                } else {
                    $('.alert-planning').removeClass('alert-success');
                }

                $.ajax({
                    url: '/check-planning', // sesuaikan dengan route kamu
                    type: 'POST',
                    data: {
                        planing: isChecked ? 1 : 0,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Planning status updated:', response);
                    },
                    error: function(xhr) {
                        console.error('Terjadi error:', xhr.responseText);
                    }
                });
            });

            $('.checkSync').on('change', function() {
                let isChecked = $(this).is(':checked');

                // Tambah atau hapus class alert-success
                if (isChecked) {
                    $('.alert-sync').addClass('alert-success');
                } else {
                    $('.alert-sync').removeClass('alert-success');
                }

                $.ajax({
                    url: '/check-sync', // sesuaikan dengan route kamu
                    type: 'POST',
                    data: {
                        sync: isChecked ? 1 : 0,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('sync status updated:', response);
                    },
                    error: function(xhr) {
                        console.error('Terjadi error:', xhr.responseText);
                    }
                });
            });

            $('.checkAbnormal').on('change', function() {
                let isChecked = $(this).is(':checked');

                // Tambah atau hapus class alert-success
                if (isChecked) {
                    $('.alert-abnormal').addClass('alert-success');
                } else {
                    $('.alert-abnormal').removeClass('alert-success');
                }

                $.ajax({
                    url: '/check-abnormal', // sesuaikan dengan route kamu
                    type: 'POST',
                    data: {
                        abnormal: isChecked ? 1 : 0,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('abnormal status updated:', response);
                    },
                    error: function(xhr) {
                        console.error('Terjadi error:', xhr.responseText);
                    }
                });
            });

            $('.checkLog').on('change', function() {
                let isChecked = $(this).is(':checked');

                // Tambah atau hapus class alert-success
                if (isChecked) {
                    $('.alert-log').addClass('alert-success');
                } else {
                    $('.alert-log').removeClass('alert-success');
                }

                $.ajax({
                    url: '/check-log', // sesuaikan dengan route kamu
                    type: 'POST',
                    data: {
                        log: isChecked ? 1 : 0,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('log status updated:', response);
                    },
                    error: function(xhr) {
                        console.error('Terjadi error:', xhr.responseText);
                    }
                });
            });

            $('.checkTimeline').on('change', function() {
                let isChecked = $(this).is(':checked');

                // Tambah atau hapus class alert-success
                if (isChecked) {
                    $('.alert-timeline').addClass('alert-success');
                } else {
                    $('.alert-timeline').removeClass('alert-success');
                }

                $.ajax({
                    url: '/check-timeline', // sesuaikan dengan route kamu
                    type: 'POST',
                    data: {
                        timeline: isChecked ? 1 : 0,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('timeline status updated:', response);
                    },
                    error: function(xhr) {
                        console.error('Terjadi error:', xhr.responseText);
                    }
                });
            });

            $('.checkPreventive').on('change', function() {
                let isChecked = $(this).is(':checked');

                // Tambah atau hapus class alert-success
                if (isChecked) {
                    $('.alert-preventive').addClass('alert-success');
                } else {
                    $('.alert-preventive').removeClass('alert-success');
                }

                $.ajax({
                    url: '/check-preventive', // sesuaikan dengan route kamu
                    type: 'POST',
                    data: {
                        preventive: isChecked ? 1 : 0,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('preventive status updated:', response);
                    },
                    error: function(xhr) {
                        console.error('Terjadi error:', xhr.responseText);
                    }
                });
            });

        });
    </script>
    {{-- @endif --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> --}}

@endpush
