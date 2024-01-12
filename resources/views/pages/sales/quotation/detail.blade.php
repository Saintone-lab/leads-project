@extends('layouts.sales.app')
@section('title', 'Detail Quotation')
@section('content')
    <div class="row invoice-preview">
        {{-- Invoice --}}
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column">
                        <div class="mb-xl-0 pb-3">
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
                            <p>
                                <i class="mdi mdi-phone-outline scaleX-n1-rtl me-1"></i>022 54417653
                            </p>
                        </div>
                        <div>
                            <h3 class="fw-bold">QUOTATION</h3>
                            <div>
                                <span class="fw-bolder">#{{ $quote->no_quote }}</span>
                            </div>
                            <div class="mt-1">
                                <span
                                    class="text-muted">{{ $quote->status == '25' ? 'DRAFT' : ($quote->status == '50' ? 'SEND' : ($quote->status == '75' ? 'NEGOTIATION' : ($quote->status == '100' ? 'DONE PO' : ($quote->status == '0' ? 'LOSS' : '')))) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body mb-3">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 my-3">
                            <h6 class="pb-2 fw-semibold fs-4">Quote To:</h6>
                        </div>
                        <div class="col-md-6 my-3">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 fw-medium">
                            <p class="mb-1">Name PIC</p>
                            <p class="mb-1">Company </p>
                            <p class="mb-1">Phone </p>
                            <p class="mb-1">Email </p>
                            <p class="mb-1">No PR</p>
                        </div>
                        <div class="col-4">
                            <p class="mb-1">: {{ $quote->pic->name_pic }}</p>
                            <p class="mb-1">: {{ $quote->pic->client->company }}</p>
                            <p class="mb-1">: {{ $quote->no_pr ?? '-' }}</p>
                            <p class="mb-1">: {{ $quote->pic->client->phone }}</p>
                            <p class="mb-1">: {{ $quote->pic->client->email }}</p>
                        </div>
                        <div class="col-3 fw-medium text-end">
                            <p class="mb-1">Sales :</p>
                            <p class="mb-1">Phone Sales :</p>
                            <p class="mb-1">Title :</p>
                            <p class="mb-1">Date Estimated :</p>
                            <p class="mb-1">Date Expired :</p>
                        </div>
                        <div class="col-3 text-end">
                            <p class="mb-1"> {{ $quote->sales->name }}</p>
                            <p class="mb-1"> {{ $quote->sales->phone }}</p>
                            <p class="mb-1"> {{ $quote->title }}</p>
                            <p class="mb-1"> {{ \Carbon\Carbon::parse($quote->estimated_date)->toFormattedDateString() }}
                            </p>
                            <p class="mb-1"> {{ \Carbon\Carbon::parse($quote->expired_date)->toFormattedDateString() }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead class="table-light border-top">
                            <tr>
                                <th>No.</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Discount</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 0;
                            @endphp
                            @foreach ($dquote as $product)
                                @php
                                    $no++;
                                @endphp
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td class="text-nowrap">{{ $product->product }}</td>
                                    <td class="text-nowrap">{{ $product->detail_product }}</td>
                                    <td>RP {{ number_format($product->price, 0, '', '.') }}</td>
                                    <td>{{ $product->qty }}</td>
                                    <td>{{ $product->disc }}%</td>
                                    <td>RP {{ number_format($product->amount, 0, '', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="align-top px-4 py-5">
                                    <span>Thanks for your business</span>
                                </td>
                                <td colspan="2" class="text-end px-4 py-5">
                                    <p class="mb-2">Subtotal:</p>
                                    <p class="mb-2">Tax:</p>
                                    <p class="mb-2">Discount Quote:</p>
                                    <p class="mb-2">Shipping Cost:</p>
                                    <p class="mb-0">Total:</p>
                                </td>
                                <td colspan="2" class="px-4 py-5">
                                    <p class="fw-semibold mb-2 text-end">RP
                                        {{ number_format($quote->subtotal, 0, '', '.') }}</p>
                                    <p class="fw-semibold mb-2 text-end">{{ $quote->tax }}%</p>
                                    <p class="fw-semibold mb-2 text-end">RP {{ number_format($quote->diskon, 0, '', '.') }}
                                    </p>
                                    <p class="fw-semibold mb-2 text-end">RP
                                        {{ number_format($quote->shipping, 0, '', '.') }}</p>
                                    <p class="fw-semibold mb-0 text-end">RP
                                        {{ number_format($quote->harga_total, 0, '', '.') }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    <h5 class="my-4">Term & Condition</h5>
                    <div class="row">
                        <div class="col-3 fw-medium">
                            <p class="mb-1">Validity Of Quotation</p>
                            <p class="mb-1">Price </p>
                            <p class="mb-1">Delivery Process </p>
                            <p class="mb-1">Payment </p>
                            <p class="mb-1">Note </p>
                        </div>
                        <div class="col">
                            <p class="mb-1">: {{ $quote->termncon[0]->validity }}</p>
                            <p class="mb-1">: {{ $quote->termncon[0]->pricing }}</p>
                            <p class="mb-1">: {{ $quote->termncon[0]->delivery_process }}</p>
                            <p class="mb-1">: {{ $quote->termncon[0]->payment }}</p>
                            <p class="mb-1">: {{ $quote->termncon[0]->note }}</p>
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
                        href="{{ route('print.quotation', $quote->id) }}">
                        Print
                    </a>
                    @if ($quote->status != '100')
                            <button type="button" class="btn btn-secondary d-grid w-100 waves-effect mb-3" data-bs-toggle="modal"
                                data-bs-target="#changeStatus-{{$quote->id}}">Change Status</button>
                    @endif
                    <a href="{{ route('pdf.quotation', $quote->id) }}" type="button"
                        class="btn btn-outline-secondary d-grid w-100 waves-effect">Download</a>
                </div>
            </div>
        </div>
        {{-- End : Button Invoice --}}
        @include('pages.sales.quotation.modal-status')
    </div>
@endsection
@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice.css" />
@endpush
