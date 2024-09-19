@extends('layouts.sales.app')
@section('title', 'Detail Prospect')
@section('content')
    <h3>
        Prospect {{ $client->company }}
    </h3>
    <div class="row invoice-preview">
        {{-- Invoice --}}
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="fw-bold pb-1 mb-3">
                                    Details
                                </h5>
                                <p class="card-text">
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Address
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->address }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Sub Address
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->subAddress }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Area
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->area }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Phone
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->phone }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Email
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->email }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Mobile
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->mobile }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        R/U
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->ru }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Source
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->source }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Machine
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->machine }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        Assigned
                                    </div>
                                    <div class="col-9">
                                        : {{ $client->sales->name }}
                                    </div>
                                </div>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="fw-bold pb-1 mb-3">
                                    PIC
                                </h5>
                                <p class="card-text">
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Name
                                    </div>
                                    <div class="col-9">
                                        : {{ $pic->name_pic }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Phone
                                    </div>
                                    <div class="col-9">
                                        : {{ $pic->phone_pic }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Position
                                    </div>
                                    <div class="col-9">
                                        : {{ $pic->position }}
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-3">
                                        Email
                                    </div>
                                    <div class="col-9">
                                        : {{ $pic->email_pic }}
                                    </div>
                                </div>
                                </p>
                                <div class="prospect my-3">
                                    <h5>Prospect</h5>
                                    <div class="row">
                                        <div class="col-3">
                                            Prospect
                                        </div>
                                        <div class="col-9">
                                            : {{ $prospect->kebutuhan }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- End: Invoice --}}
        {{-- Button Invocie --}}
        @if (Auth::user()->role != 'Support')
            <div class="col-xl-3 col-md-4 col-12 invoice-actions">
                <div class="card">
                    <form action="{{ route('add_sales.prospect', $prospect->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-floating form-floating-outline">
                                <select class="form-select" id="selectSales" aria-label="Default select example"
                                    name="sales" {{ @$prospect->id_sales ? 'disabled' : '' }}>
                                    <option disabled="">----- Choose Sales -----</option>
                                    @foreach ($sales as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <label for="selectSales">Sales</label>
                            </div>
                        </div>
                        <div class="card-footer float-end">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
        {{-- End : Button Invoice --}}
    </div>
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
    <script></script>
@endpush
