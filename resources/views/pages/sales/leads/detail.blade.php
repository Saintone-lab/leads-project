@extends('layouts.sales.app')
@section('title', 'Detail Leads')
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Clients / Leads /</span> Details PT. Teras Adhi Kharisma
    </h4>
    <div class="row mb-4">
        <div class="col-md-6">
            <h5 class="fw-bold pb-1 mb-3">
                Details
            </h5>
            <div class="card">
                <div class="card-header pb-0">
                    <div class="text-end text-muted">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#updateLeads{{ $leads->id }}">
                            <button type="button" class="btn btn-sm btn-label-primary">Edit</button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text">
                    <div class="row mb-1">
                        <div class="col-3">
                            Adress
                        </div>
                        <div class="col-9">
                            : {{ $leads->address }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            Phone
                        </div>
                        <div class="col-9">
                            : {{ $leads->phone }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            Email
                        </div>
                        <div class="col-9">
                            : {{ $leads->email }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            Mobile
                        </div>
                        <div class="col-9">
                            : {{ $leads->mobile }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            R/U
                        </div>
                        <div class="col-9">
                            : {{ $leads->ru }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            Source
                        </div>
                        <div class="col-9">
                            : {{ $leads->source }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            Machine
                        </div>
                        <div class="col-9">
                            : {{ $leads->machine }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            Assigned
                        </div>
                        <div class="col-9">
                            : {{ $leads->sales->name }}
                        </div>
                    </div>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold pb-1 mb-3">
                    PIC
                </h5>
                <a type="button" data-bs-toggle="modal" data-bs-target="#createPIC">
                    <button type="button" class="btn btn-primary">
                        + Create New PIC
                    </button>
                </a>
            </div>
            @foreach ($charge as $pic)
                <div class="card mb-2">
                    <div class="card-header pb-0">
                        <div class="text-end text-muted">
                            <a type="button" data-bs-toggle="modal" data-bs-target="#updatePic{{ $pic->id }}">
                                <button type="button" class="btn btn-sm btn-label-primary">
                                    <i class="menu-icon tf-icons mdi mdi-14px mdi-account-edit-outline"></i>Edit
                                </button>
                            </a>
                            <button type="button" class="btn btn-sm btn-label-danger">
                                <i class="menu-icon tf-icons mdi mdi-14px mdi-delete-outline"></i>Delete
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
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
                                Email
                            </div>
                            <div class="col-9">
                                : {{ $pic->email_pic }}
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-3">
                                Machine
                            </div>
                            <div class="col-9">
                                : {{ $pic->machine }}
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-3">
                                Area
                            </div>
                            <div class="col-9">
                                : {{ $pic->area }}
                            </div>
                        </div>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 my-3">
            <h5 class="fw-bold pb-1 mb-2">
                Daily Call History
            </h5>
            <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($callhis as $callhistory)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($callhistory->date)->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        {{ $callhistory->action }}
                                    </td>
                                    <td>
                                        {{ $callhistory->status }}
                                    </td>
                                    <td>
                                        {{ $callhistory->client->area }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        Kamu belum punya Call History.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 my-3">
            <h5 class="fw-bold pb-1 mb-2">
                Quotation
            </h5>
            <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Number Quote</th>
                                <th>Status</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($quote as $quotation)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($quotation->estimated_date)->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        {{ $quotation->no_quote }}
                                    </td>
                                    <td><span
                                            class="badge bg-label-{{ $quotation->status == '25' ? 'info' : ($quotation->status == '50' ? 'warning' : ($quotation->status == '75' ? 'primary' : ($quotation->status == '100' ? 'success' : ($quotation->status == '0' ? 'danger' : '')))) }}">{{ $quotation->status }}%</span>
                                    </td>
                                    <td>
                                        RP {{ number_format($quotation->harga_total, 0, '', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        Kamu belum punya Quotation.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('pages.sales.leads.form')
@endsection()
