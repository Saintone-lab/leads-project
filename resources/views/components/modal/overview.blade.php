<div class="modal animate__animated animate__fadeIn" id="overview-sales-{{ $item }}" tabindex="-1"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="exampleModalLabel5"> Report
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-4">
                    <h5 class="card-header">Assigned: {{ $user->name }}</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Description</th>
                                    @php
                                        $allWeek = 1;
                                    @endphp
                                    @foreach ($dataQuote[$user->name] as $week)
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
                                    @foreach ($dataDc[$user->name] as $week)
                                        <td>{{ $week }}</td>
                                        @php
                                            $totalDCFullWeek += $week;
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
                                            {{ round(($totalDCFullWeek / ($user->target[0]->dc + $user->target[0]->dc / 4)) * 100) }} %
                                        @elseif($jumlahData == 4)
                                            {{ round(($totalDCFullWeek / $user->target[0]->dc) * 100) }} %
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Presentation / Visit</strong>
                                    </td>
                                    @foreach ($dataDc[$user->name] as $week)
                                        <td>0</td>
                                    @endforeach
                                    <td>0</td>
                                    <td>0%</td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Quotation</strong>
                                    </td>
                                    @php
                                        $totalQuoteFullWeek = 0;
                                    @endphp
                                    @foreach ($dataQuote[$user->name] as $week)
                                        <td>{{ $week }}</td>
                                        @php
                                            $totalQuoteFullWeek += $week;
                                        @endphp
                                    @endforeach
                                    <td>{{ $totalQuoteFullWeek }}</td>
                                    <td>
                                        @if ($jumlahData > 4)
                                            {{ round(($totalQuoteFullWeek / ($user->target[0]->quote + $user->target[0]->quote / 4)) * 100) }}
                                            %
                                        @elseif($jumlahData == 4)
                                            {{ round(($totalQuoteFullWeek / $user->target[0]->quote) * 100) }} %
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
                                    @foreach ($dataPO[$user->name] as $week)
                                        <td>{{ $week }}</td>
                                        @php
                                            $totalPoFullWeek += $week;
                                        @endphp
                                    @endforeach
                                    <td>{{ $totalPoFullWeek }}</td>
                                    <td>
                                        @if ($jumlahData > 4)
                                            {{ round(($totalPoFullWeek / ($user->target[0]->po + $user->target[0]->po / 4)) * 100) }} %
                                        @elseif($jumlahData == 4)
                                            {{ round(($totalPoFullWeek / $user->target[0]->po) * 100) }} %
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
            </div>
        </div>
    </div>
</div>
