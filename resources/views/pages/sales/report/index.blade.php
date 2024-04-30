@extends('layouts.sales.app')
@section('title', 'report')
@section('content')
    @if (Auth::user()->role == 'Sales')
        <div class="card mb-4">
            <h5 class="card-header">Assigned: {{ Auth::user()->name }}</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Description</th>
                            @php
                                $allWeek = 1;
                            @endphp
                            @foreach ($dataQuote as $week)
                                <th>Week {{ $allWeek }}</th>
                                @php
                                    $allWeek += 1;
                                @endphp
                            @endforeach
                            <th>Total</th>
                            <th>Presentase</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <tr>
                            <td>
                                <strong>Daily Call</strong>
                            </td>
                            @php
                                $totalDCFullWeek = 0;
                            @endphp
                            @foreach ($dataDc as $week)
                                <td>{{ $week['total'] }}</td>
                                @php
                                    $totalDCFullWeek += $week['total'];
                                @endphp
                            @endforeach
                            <td>{{ $totalDCFullWeek }}</td>
                            <td>
                                @php
                                    if (is_array($dataDc)) {
                                        $jumlahData = count($dataDc);
                                    }
                                @endphp
                                @if ($jumlahData > 4)
                                    {{ round(($totalDCFullWeek / ($target->dc + $target->dc / 4)) * 100) }} %
                                @elseif($jumlahData == 4)
                                    {{ round(($totalDCFullWeek / $target->dc) * 100) }} %
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>CRM</strong>
                            </td>
                            @php
                                $totalCrmFullWeek = 0;
                            @endphp
                            @foreach ($dataCRM as $week)
                                <td>{{ $week['total'] }}</td>
                                @php
                                    $totalCrmFullWeek += $week['total'];
                                @endphp
                            @endforeach
                            <td>{{ $totalCrmFullWeek }}</td>
                            <td>
                                @if ($jumlahData > 4)
                                    {{ round(($totalCrmFullWeek / ($target->crm + $target->crm / 4)) * 100) }} %
                                @elseif($jumlahData == 4)
                                    {{ round(($totalCrmFullWeek / $target->crm) * 100) }} %
                                @endif
                            </td>
                        </tr>
                        @if (Auth::user()->detail[0]->area == 'Bekasi')
                            <tr>
                                <td>
                                    <strong>Visit</strong>
                                </td>
                                @php
                                    $totalVisitFullWeek = 0;
                                @endphp
                                @foreach ($dataVisit as $week)
                                    <td>{{ $week['total'] }}</td>
                                    @php
                                        $totalVisitFullWeek += $week['total'];
                                    @endphp
                                @endforeach
                                <td>{{ $totalVisitFullWeek }}</td>
                                <td>
                                    @if ($jumlahData > 4)
                                        {{ round(($totalVisitFullWeek / ($target->visit + $target->visit / 4)) * 100) }} %
                                    @elseif($jumlahData == 4)
                                        {{ round(($totalVisitFullWeek / $target->visit) * 100) }} %
                                    @endif
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>
                                <strong>Quotation</strong>
                            </td>
                            @php
                                $totalQuoteFullWeek = 0;
                            @endphp
                            @foreach ($dataQuote as $week)
                                <td>{{ $week['total'] }}</td>
                                @php
                                    $totalQuoteFullWeek += $week['total'];
                                @endphp
                            @endforeach
                            <td>{{ $totalQuoteFullWeek }}</td>
                            <td>
                                @if ($jumlahData > 4)
                                    {{ round(($totalQuoteFullWeek / ($target->quote + $target->quote / 4)) * 100) }} %
                                @elseif($jumlahData == 4)
                                    {{ round(($totalQuoteFullWeek / $target->quote) * 100) }} %
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Prucashing Order</strong>
                            </td>
                            @php
                                $totalPoFullWeek = 0;
                            @endphp
                            @foreach ($dataPo as $week)
                                <td>{{ $week['total'] }}</td>
                                @php
                                    $totalPoFullWeek += $week['total'];
                                @endphp
                            @endforeach
                            <td>{{ $totalPoFullWeek }}</td>
                            <td>
                                @if ($jumlahData > 4)
                                    {{ round(($totalPoFullWeek / ($target->po + $target->po / 4)) * 100) }} %
                                @elseif($jumlahData == 4)
                                    {{ round(($totalPoFullWeek / $target->po) * 100) }} %
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <p class="fw-semibold m-0"> Total Quotation</p>
                                <p class="text-muted m-0">{{ $totalQuoteFullWeek }}</p>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <p class="fw-semibold m-0"> Total PO</p>
                                <p class="text-muted m-0">{{ $totalPoFullWeek }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <h5 class="card-header">Total PO</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>PO No.</th>
                            <th>Company</th>
                            <th>Title</th>
                            <th>PO Date</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @php
                            $totalP = 0;
                        @endphp
                        @foreach ($quotation as $quote)
                            @php
                                $totalQ = $quote['total_no_tax'];
                                $totalP += $totalQ;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $quote->no_quote }}</strong>
                                </td>
                                <td>{{ $quote->pic->client->company }}</td>
                                <td>{{ $quote->title }}</td>
                                <td>{{ \Carbon\Carbon::parse($quote->estimated_date)->format('d-m-Y') }}</td>
                                <td class="text-end">Rp {{ number_format($quote->total_no_tax, 0, '', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-label-secondary">
                            <td colspan="3">
                            </td>
                            <td><strong>Total</strong></td>
                            <td class="text-end"><strong>Rp {{ number_format($totalP, 0, '', '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @elseif (Auth::user()->role == 'Admin')
        <div class="container-xxl flex-grow-1 container-p-y">
            {{-- Regita --}}
            <div class="card mb-4">
                <h5 class="card-header">Assigned: Miss Regita</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th>Week I</th>
                                <th>Week II</th>
                                <th>Week III</th>
                                <th>Week IV</th>
                                <th>Week V</th>
                                <th>Total</th>
                                <th>Presentase</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td>
                                    <strong>Daily Call</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Presentation / Visit</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Quotation</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Prucashing Order</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <p class="fw-semibold m-0"> Total Quotation</p>
                                    <p class="text-muted m-0">50</p>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <p class="fw-semibold m-0"> Total PO</p>
                                    <p class="text-muted m-0">50</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Yolan --}}
            <div class="card mb-4">
                <h5 class="card-header">Assigned: Miss Yolan</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th>Week I</th>
                                <th>Week II</th>
                                <th>Week III</th>
                                <th>Week IV</th>
                                <th>Week V</th>
                                <th>Total</th>
                                <th>Presentase</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td>
                                    <strong>Daily Call</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Presentation / Visit</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Quotation</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Prucashing Order</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <p class="fw-semibold m-0"> Total Quotation</p>
                                    <p class="text-muted m-0">50</p>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <p class="fw-semibold m-0"> Total PO</p>
                                    <p class="text-muted m-0">50</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Ari --}}
            <div class="card mb-4">
                <h5 class="card-header">Assigned: Mister Ari</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th>Week I</th>
                                <th>Week II</th>
                                <th>Week III</th>
                                <th>Week IV</th>
                                <th>Week V</th>
                                <th>Total</th>
                                <th>Presentase</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td>
                                    <strong>Daily Call</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Presentation / Visit</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Quotation</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Prucashing Order</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <p class="fw-semibold m-0"> Total Quotation</p>
                                    <p class="text-muted m-0">50</p>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <p class="fw-semibold m-0"> Total PO</p>
                                    <p class="text-muted m-0">50</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Yusuf --}}
            <div class="card mb-4">
                <h5 class="card-header">Assigned: Mister Yusuf</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th>Week I</th>
                                <th>Week II</th>
                                <th>Week III</th>
                                <th>Week IV</th>
                                <th>Week V</th>
                                <th>Total</th>
                                <th>Presentase</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td>
                                    <strong>Daily Call</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Presentation / Visit</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Quotation</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Prucashing Order</strong>
                                </td>
                                <td>50</td>
                                <td>51</td>
                                <td>45</td>
                                <td>50</td>
                                <td>55</td>
                                <td>306</td>
                                <td>101%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <p class="fw-semibold m-0"> Total Quotation</p>
                                    <p class="text-muted m-0">50</p>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <p class="fw-semibold m-0"> Total PO</p>
                                    <p class="text-muted m-0">50</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
