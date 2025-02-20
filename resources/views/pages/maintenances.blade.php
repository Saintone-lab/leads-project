@extends('layouts.sales.app')
@section('title', 'Under Maintenance')
<div class="misc-wrapper">
    <h3 class="mb-2 mx-2">Under Maintenance! 🚧</h3>
    <p class="mb-4 mx-2">Sorry for the inconvenience but we're performing some maintenance at the moment</p>
    <div class="d-flex justify-content-center mt-5">
        <img src="{{ asset('assets') }}/img/illustrations/misc-under-maintenance-object.png" alt="misc-under-maintenance"
            class="img-fluid misc-object d-none d-lg-inline-block" width="170">
        <img src="{{ asset('assets') }}/img/illustrations/misc-bg-light.png" alt="misc-under-maintenance"
            class="misc-bg d-none d-lg-inline-block" data-app-light-img="illustrations/misc-bg-light.png"
            data-app-dark-img="illustrations/misc-bg-dark.png">
        <div class="d-flex flex-column align-items-center">
            <img src="{{ asset('assets') }}/img/illustrations/misc-under-maintenance-illustration.png"
                alt="misc-under-maintenance" class="img-fluid zindex-1" width="290">
            <div>
                <a href="index.html" class="btn btn-primary text-center my-5 waves-effect waves-light">Back to home</a>
            </div>
        </div>
    </div>
</div>
@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/page-misc.css" />
    <link rel="stylesheet" href="style.css">
@endpush
@push('after-script')
@endpush
@push('script')
@endpush
