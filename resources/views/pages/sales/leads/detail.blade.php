@extends('layouts.sales.app')
@section('title', 'Detail + Name Leads')
@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Clients / Leads /</span> Details PT. Teras Adhi Kharisma
    </h4>
    <h5 class="fw-bold pb-1 mb-3">
        Details
    </h5>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
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
                            PIC
                        </div>
                        <div class="col-9">
                            : {{ $leads->pic->name_pic }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            WhatsApp
                        </div>
                        <div class="col-9">
                            : {{ $leads->pic->phone_pic }}
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-3">
                            Machine
                        </div>
                        <div class="col-9">
                            : {{ $leads->detail_client[0]->detail_compressor->serial_number }} ||
                            {{ $leads->detail_client[0]->detail_compressor->compressor->compressor_brand }},Type
                            {{ $leads->detail_client[0]->detail_compressor->compressor->series }} ,
                            {{ $leads->detail_client[0]->detail_compressor->hp }} HP</option>
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
                                <th>R/U</th>
                                <th>Status</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($callhis as $callhistory)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($callhistory->date)->toFormattedDateString() }}
                                    </td>
                                    <td><span class="badge bg-primary">U</span></td>
                                    <td>
                                        {{ $callhistory->status }}
                                    </td>
                                    <td>
                                        {{ $callhistory->client->address }}
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
                                <th>R/U</th>
                                <th>Status</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($quote as $quotation)
                                <tr>
                                    <td>
                                        02-11-2023
                                    </td>
                                    <td><span class="badge bg-primary">U</span></td>
                                    <td>
                                        Introduction Phone
                                    </td>
                                    <td>
                                        {{ $quotation->client->address }}
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
@endsection()
