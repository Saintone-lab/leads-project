@extends('layouts.sales.app')
@section('title', 'My Setting')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User /</span> Account Settings</h4>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.show', Auth::user()) }}"><i
                                class="mdi mdi-account-outline me-1 mdi-20px"></i>Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i
                                class="mdi mdi-cog-outline me-1 mdi-20px"></i>Account Settings</a>
                    </li>
                    @if (Auth::user()->role == 'Admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.create') }}"><i
                                    class="mdi mdi-account-multiple-outline me-1 mdi-20px"></i>Create Account</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages-profile-connections.html"><i
                                    class="mdi mdi-tools me-1 mdi-20px"></i>Tools</a>
                        </li>
                    @endif
                </ul>
                <div class="card mb-4">
                    <form action="{{route('profile.update', Auth::user()->id)}}" id="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <h4 class="card-header">Profile Details</h4>
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img src="{{url('').'/'.Auth::user()->image}}" alt="user-avatar"
                                    class="d-block w-px-120 h-px-120 rounded" id="uploadedAvatar">
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-3 waves-effect waves-light"
                                        tabindex="0">
                                        <span class="d-none d-sm-block">Upload new photo</span>
                                        <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                        <input type="file" id="upload" class="account-file-input" name="image"
                                            hidden="" accept="image/png, image/jpeg">
                                    </label>
                                    <button type="button"
                                        class="btn btn-outline-secondary account-image-reset mb-3 waves-effect">
                                        <i class="mdi mdi-reload d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>

                                    <div class="text-muted small">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-2 mt-1">
                            <div class="row mt-2 gy-4">
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="name" name="name"
                                            value="{{ old('name', Auth::user()->name) }}" autofocus />
                                        <label for="name">Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="email" name="email"
                                            value="{{ old('email', Auth::user()->email) }}"
                                            placeholder="john.doe@example.com" />
                                        <label for="email">E-mail</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="area" name="area"
                                            placeholder="Put Area here..." value="{{ old('area', Auth::user()->area) }}" />
                                        <label for="area">Area</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">+62</span>
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control" placeholder="8123094857"
                                                id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                                                <label for="phone">Phone Number</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                            </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
    </div>
    <!-- / Content -->
@endsection
@push('after-style')
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
@endpush
@push('after-script')
    <!-- Vendors JS -->
    <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/cleavejs/cleave.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
@endpush
@push('page-script')
    <!-- Page JS -->
    <script src="{{ asset('assets') }}/js/pages-account-settings-account.js"></script>
@endpush
