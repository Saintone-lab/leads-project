@extends('layouts.sales.app')
@section('title', 'My Dashboard')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row gy-4 mb-4">
            <!-- Congratulations card -->
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-8 col-12">
                <div class="card h-100">
                    <div class="card-body text-nowrap">
                        <h4 class="card-title mb-1 d-flex gap-2 flex-wrap">
                            Congratulations <strong>Norris!</strong> 🎉
                        </h4>
                        <p class="pb-0">Best seller of the month</p>
                        <h4 class="text-primary mb-1">$42.8k</h4>
                        <p class="mb-2 pb-1">78% of target 🚀</p>
                        <a href="javascript:;" class="btn btn-sm btn-primary waves-effect waves-light">View Sales</a>
                    </div>
                    <img src="{{ asset('assets') }}/img/illustrations/trophy.png" class="position-absolute bottom-0 end-0 me-3"
                        height="140" alt="view sales">
                </div>
            </div>
            <!--/ Congratulations card -->

            <!-- Total Profit -->
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-cart-plus mdi-24px"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <p class="mb-0 text-success me-1">+22%</p>
                                <i class="mdi mdi-chevron-up text-success"></i>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1">
                            <h5 class="mb-2">155k</h5>
                            <p class="text-muted">Total Order</p>
                            <div class="badge bg-label-secondary rounded-pill">Last 4 Month</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Total Profit -->

            <!-- Total Expenses -->
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded">
                                    <i class="mdi mdi-currency-usd mdi-24px"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <p class="mb-0 text-success me-1">+38%</p>
                                <i class="mdi mdi-chevron-up text-success"></i>
                            </div>
                        </div>
                        <div class="card-info mt-4 pt-1">
                            <h5 class="mb-2">$13.4k</h5>
                            <p class="text-muted">Total Sales</p>
                            <div class="badge bg-label-secondary rounded-pill">Last Six Month</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Total Expenses -->

            <!-- Total Profit chart -->
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-end mb-1 flex-wrap gap-2">
                            <h4 class="mb-0 me-2">$88.5k</h4>
                            <p class="mb-0 text-danger">-18%</p>
                        </div>
                        <span class="d-block mb-2 text-muted">Total Profit</span>
                    </div>
                    <div class="card-body" style="position: relative;">
                        <div id="totalProfitChart" style="min-height: 100px;">
                            <div id="apexchartsishykzm4" class="apexcharts-canvas apexchartsishykzm4 apexcharts-theme-light"
                                style="width: 142px; height: 100px;"><svg id="SvgjsSvg2210" width="142" height="100"
                                    xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                    class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                    style="background: transparent;">
                                    <g id="SvgjsG2212" class="apexcharts-inner apexcharts-graphical"
                                        transform="translate(0, 9)">
                                        <defs id="SvgjsDefs2211">
                                            <linearGradient id="SvgjsLinearGradient2216" x1="0" y1="0"
                                                x2="0" y2="1">
                                                <stop id="SvgjsStop2217" stop-opacity="0.4"
                                                    stop-color="rgba(216,227,240,0.4)" offset="0"></stop>
                                                <stop id="SvgjsStop2218" stop-opacity="0.5"
                                                    stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                                <stop id="SvgjsStop2219" stop-opacity="0.5"
                                                    stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                            </linearGradient>
                                            <clipPath id="gridRectMaskishykzm4">
                                                <rect id="SvgjsRect2221" width="147" height="86" x="-2.5" y="-0.5"
                                                    rx="0" ry="0" opacity="1" stroke-width="0"
                                                    stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                            </clipPath>
                                            <clipPath id="forecastMaskishykzm4"></clipPath>
                                            <clipPath id="nonForecastMaskishykzm4"></clipPath>
                                            <clipPath id="gridRectMarkerMaskishykzm4">
                                                <rect id="SvgjsRect2222" width="146" height="89" x="-2" y="-2"
                                                    rx="0" ry="0" opacity="1" stroke-width="0"
                                                    stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                            </clipPath>
                                        </defs>
                                        <rect id="SvgjsRect2220" width="0" height="85" x="0" y="0"
                                            rx="0" ry="0" opacity="1" stroke-width="0"
                                            stroke-dasharray="3" fill="url(#SvgjsLinearGradient2216)"
                                            class="apexcharts-xcrosshairs" y2="85" filter="none"
                                            fill-opacity="0.9"></rect>
                                        <g id="SvgjsG2238" class="apexcharts-xaxis" transform="translate(0, 0)">
                                            <g id="SvgjsG2239" class="apexcharts-xaxis-texts-g"
                                                transform="translate(0, 2.75)"></g>
                                        </g>
                                        <g id="SvgjsG2248" class="apexcharts-grid">
                                            <g id="SvgjsG2249" class="apexcharts-gridlines-horizontal"
                                                style="display: none;">
                                                <line id="SvgjsLine2251" x1="0" y1="0" x2="142"
                                                    y2="0" stroke="#e0e0e0" stroke-dasharray="0"
                                                    stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2252" x1="0" y1="17" x2="142"
                                                    y2="17" stroke="#e0e0e0" stroke-dasharray="0"
                                                    stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2253" x1="0" y1="34" x2="142"
                                                    y2="34" stroke="#e0e0e0" stroke-dasharray="0"
                                                    stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2254" x1="0" y1="51" x2="142"
                                                    y2="51" stroke="#e0e0e0" stroke-dasharray="0"
                                                    stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2255" x1="0" y1="68" x2="142"
                                                    y2="68" stroke="#e0e0e0" stroke-dasharray="0"
                                                    stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2256" x1="0" y1="85" x2="142"
                                                    y2="85" stroke="#e0e0e0" stroke-dasharray="0"
                                                    stroke-linecap="butt" class="apexcharts-gridline"></line>
                                            </g>
                                            <g id="SvgjsG2250" class="apexcharts-gridlines-vertical"
                                                style="display: none;"></g>
                                            <line id="SvgjsLine2258" x1="0" y1="85" x2="142"
                                                y2="85" stroke="transparent" stroke-dasharray="0"
                                                stroke-linecap="butt"></line>
                                            <line id="SvgjsLine2257" x1="0" y1="1" x2="0"
                                                y2="85" stroke="transparent" stroke-dasharray="0"
                                                stroke-linecap="butt"></line>
                                        </g>
                                        <g id="SvgjsG2223" class="apexcharts-bar-series apexcharts-plot-series">
                                            <g id="SvgjsG2224" class="apexcharts-series" seriesName="PRODUCTxA"
                                                rel="1" data:realIndex="0">
                                                <path id="SvgjsPath2226"
                                                    d="M 10.224 46L 10.224 18.6Q 10.224 13.600000000000001 15.224 13.600000000000001L 12.175999999999998 13.600000000000001Q 17.176 13.600000000000001 17.176 18.6L 17.176 18.6L 17.176 46Q 17.176 51 12.175999999999998 51L 15.224 51Q 10.224 51 10.224 46z"
                                                    fill="rgba(109,120,141,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="1"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskishykzm4)"
                                                    pathTo="M 10.224 46L 10.224 18.6Q 10.224 13.600000000000001 15.224 13.600000000000001L 12.175999999999998 13.600000000000001Q 17.176 13.600000000000001 17.176 18.6L 17.176 18.6L 17.176 46Q 17.176 51 12.175999999999998 51L 15.224 51Q 10.224 51 10.224 46z"
                                                    pathFrom="M 10.224 46L 10.224 46L 17.176 46L 17.176 46L 17.176 46L 17.176 46L 17.176 46L 10.224 46"
                                                    cy="13.600000000000001" cx="38.123999999999995" j="0" val="44"
                                                    barHeight="37.4" barWidth="7.951999999999999"></path>
                                                <path id="SvgjsPath2227"
                                                    d="M 38.623999999999995 46L 38.623999999999995 38.150000000000006Q 38.623999999999995 33.150000000000006 43.623999999999995 33.150000000000006L 40.57599999999999 33.150000000000006Q 45.57599999999999 33.150000000000006 45.57599999999999 38.150000000000006L 45.57599999999999 38.150000000000006L 45.57599999999999 46Q 45.57599999999999 51 40.57599999999999 51L 43.623999999999995 51Q 38.623999999999995 51 38.623999999999995 46z"
                                                    fill="rgba(109,120,141,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="1"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskishykzm4)"
                                                    pathTo="M 38.623999999999995 46L 38.623999999999995 38.150000000000006Q 38.623999999999995 33.150000000000006 43.623999999999995 33.150000000000006L 40.57599999999999 33.150000000000006Q 45.57599999999999 33.150000000000006 45.57599999999999 38.150000000000006L 45.57599999999999 38.150000000000006L 45.57599999999999 46Q 45.57599999999999 51 40.57599999999999 51L 43.623999999999995 51Q 38.623999999999995 51 38.623999999999995 46z"
                                                    pathFrom="M 38.623999999999995 46L 38.623999999999995 46L 45.57599999999999 46L 45.57599999999999 46L 45.57599999999999 46L 45.57599999999999 46L 45.57599999999999 46L 38.623999999999995 46"
                                                    cy="33.150000000000006" cx="66.524" j="1" val="21"
                                                    barHeight="17.849999999999998" barWidth="7.951999999999999"></path>
                                                <path id="SvgjsPath2228"
                                                    d="M 67.024 46L 67.024 8.399999999999999Q 67.024 3.3999999999999986 72.024 3.3999999999999986L 68.976 3.3999999999999986Q 73.976 3.3999999999999986 73.976 8.399999999999999L 73.976 8.399999999999999L 73.976 46Q 73.976 51 68.976 51L 72.024 51Q 67.024 51 67.024 46z"
                                                    fill="rgba(109,120,141,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="1"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskishykzm4)"
                                                    pathTo="M 67.024 46L 67.024 8.399999999999999Q 67.024 3.3999999999999986 72.024 3.3999999999999986L 68.976 3.3999999999999986Q 73.976 3.3999999999999986 73.976 8.399999999999999L 73.976 8.399999999999999L 73.976 46Q 73.976 51 68.976 51L 72.024 51Q 67.024 51 67.024 46z"
                                                    pathFrom="M 67.024 46L 67.024 46L 73.976 46L 73.976 46L 73.976 46L 73.976 46L 73.976 46L 67.024 46"
                                                    cy="3.3999999999999986" cx="94.924" j="2" val="56"
                                                    barHeight="47.6" barWidth="7.951999999999999"></path>
                                                <path id="SvgjsPath2229"
                                                    d="M 95.424 46L 95.424 27.1Q 95.424 22.1 100.424 22.1L 97.376 22.1Q 102.376 22.1 102.376 27.1L 102.376 27.1L 102.376 46Q 102.376 51 97.376 51L 100.424 51Q 95.424 51 95.424 46z"
                                                    fill="rgba(109,120,141,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="1"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskishykzm4)"
                                                    pathTo="M 95.424 46L 95.424 27.1Q 95.424 22.1 100.424 22.1L 97.376 22.1Q 102.376 22.1 102.376 27.1L 102.376 27.1L 102.376 46Q 102.376 51 97.376 51L 100.424 51Q 95.424 51 95.424 46z"
                                                    pathFrom="M 95.424 46L 95.424 46L 102.376 46L 102.376 46L 102.376 46L 102.376 46L 102.376 46L 95.424 46"
                                                    cy="22.1" cx="123.32400000000001" j="3" val="34"
                                                    barHeight="28.9" barWidth="7.951999999999999"></path>
                                                <path id="SvgjsPath2230"
                                                    d="M 123.82400000000001 46L 123.82400000000001 16.050000000000004Q 123.82400000000001 11.050000000000004 128.824 11.050000000000004L 125.77600000000001 11.050000000000004Q 130.776 11.050000000000004 130.776 16.050000000000004L 130.776 16.050000000000004L 130.776 46Q 130.776 51 125.77600000000001 51L 128.824 51Q 123.82400000000001 51 123.82400000000001 46z"
                                                    fill="rgba(109,120,141,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="1"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskishykzm4)"
                                                    pathTo="M 123.82400000000001 46L 123.82400000000001 16.050000000000004Q 123.82400000000001 11.050000000000004 128.824 11.050000000000004L 125.77600000000001 11.050000000000004Q 130.776 11.050000000000004 130.776 16.050000000000004L 130.776 16.050000000000004L 130.776 46Q 130.776 51 125.77600000000001 51L 128.824 51Q 123.82400000000001 51 123.82400000000001 46z"
                                                    pathFrom="M 123.82400000000001 46L 123.82400000000001 46L 130.776 46L 130.776 46L 130.776 46L 130.776 46L 130.776 46L 123.82400000000001 46"
                                                    cy="11.050000000000004" cx="151.72400000000002" j="4" val="47"
                                                    barHeight="39.949999999999996" barWidth="7.951999999999999"></path>
                                            </g>
                                            <g id="SvgjsG2231" class="apexcharts-series" seriesName="PRODUCTxB"
                                                rel="2" data:realIndex="1">
                                                <path id="SvgjsPath2233"
                                                    d="M 10.224 61L 10.224 73.95Q 10.224 78.95 15.224 78.95L 12.175999999999998 78.95Q 17.176 78.95 17.176 73.95L 17.176 73.95L 17.176 61Q 17.176 56 12.175999999999998 56L 15.224 56Q 10.224 56 10.224 61z"
                                                    fill="rgba(255,77,73,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="1"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="1"
                                                    clip-path="url(#gridRectMaskishykzm4)"
                                                    pathTo="M 10.224 61L 10.224 73.95Q 10.224 78.95 15.224 78.95L 12.175999999999998 78.95Q 17.176 78.95 17.176 73.95L 17.176 73.95L 17.176 61Q 17.176 56 12.175999999999998 56L 15.224 56Q 10.224 56 10.224 61z"
                                                    pathFrom="M 10.224 61L 10.224 61L 17.176 61L 17.176 61L 17.176 61L 17.176 61L 17.176 61L 10.224 61"
                                                    cy="68.95" cx="38.123999999999995" j="0" val="-27"
                                                    barHeight="-22.95" barWidth="7.951999999999999"></path>
                                                <path id="SvgjsPath2234"
                                                    d="M 38.623999999999995 61L 38.623999999999995 65.45Q 38.623999999999995 70.45 43.623999999999995 70.45L 40.57599999999999 70.45Q 45.57599999999999 70.45 45.57599999999999 65.45L 45.57599999999999 65.45L 45.57599999999999 61Q 45.57599999999999 56 40.57599999999999 56L 43.623999999999995 56Q 38.623999999999995 56 38.623999999999995 61z"
                                                    fill="rgba(255,77,73,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="1"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="1"
                                                    clip-path="url(#gridRectMaskishykzm4)"
                                                    pathTo="M 38.623999999999995 61L 38.623999999999995 65.45Q 38.623999999999995 70.45 43.623999999999995 70.45L 40.57599999999999 70.45Q 45.57599999999999 70.45 45.57599999999999 65.45L 45.57599999999999 65.45L 45.57599999999999 61Q 45.57599999999999 56 40.57599999999999 56L 43.623999999999995 56Q 38.623999999999995 56 38.623999999999995 61z"
                                                    pathFrom="M 38.623999999999995 61L 38.623999999999995 61L 45.57599999999999 61L 45.57599999999999 61L 45.57599999999999 61L 45.57599999999999 61L 45.57599999999999 61L 38.623999999999995 61"
                                                    cy="60.45" cx="66.524" j="1" val="-17"
                                                    barHeight="-14.45" barWidth="7.951999999999999"></path>
                                                <path id="SvgjsPath2235"
                                                    d="M 67.024 61L 67.024 77.35Q 67.024 82.35 72.024 82.35L 68.976 82.35Q 73.976 82.35 73.976 77.35L 73.976 77.35L 73.976 61Q 73.976 56 68.976 56L 72.024 56Q 67.024 56 67.024 61z"
                                                    fill="rgba(255,77,73,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="1"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="1"
                                                    clip-path="url(#gridRectMaskishykzm4)"
                                                    pathTo="M 67.024 61L 67.024 77.35Q 67.024 82.35 72.024 82.35L 68.976 82.35Q 73.976 82.35 73.976 77.35L 73.976 77.35L 73.976 61Q 73.976 56 68.976 56L 72.024 56Q 67.024 56 67.024 61z"
                                                    pathFrom="M 67.024 61L 67.024 61L 73.976 61L 73.976 61L 73.976 61L 73.976 61L 73.976 61L 67.024 61"
                                                    cy="72.35" cx="94.924" j="2" val="-31"
                                                    barHeight="-26.349999999999998" barWidth="7.951999999999999"></path>
                                                <path id="SvgjsPath2236"
                                                    d="M 95.424 61L 95.424 70.55Q 95.424 75.55 100.424 75.55L 97.376 75.55Q 102.376 75.55 102.376 70.55L 102.376 70.55L 102.376 61Q 102.376 56 97.376 56L 100.424 56Q 95.424 56 95.424 61z"
                                                    fill="rgba(255,77,73,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="1"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="1"
                                                    clip-path="url(#gridRectMaskishykzm4)"
                                                    pathTo="M 95.424 61L 95.424 70.55Q 95.424 75.55 100.424 75.55L 97.376 75.55Q 102.376 75.55 102.376 70.55L 102.376 70.55L 102.376 61Q 102.376 56 97.376 56L 100.424 56Q 95.424 56 95.424 61z"
                                                    pathFrom="M 95.424 61L 95.424 61L 102.376 61L 102.376 61L 102.376 61L 102.376 61L 102.376 61L 95.424 61"
                                                    cy="65.55" cx="123.32400000000001" j="3" val="-23"
                                                    barHeight="-19.55" barWidth="7.951999999999999"></path>
                                                <path id="SvgjsPath2237"
                                                    d="M 123.82400000000001 61L 123.82400000000001 77.35Q 123.82400000000001 82.35 128.824 82.35L 125.77600000000001 82.35Q 130.776 82.35 130.776 77.35L 130.776 77.35L 130.776 61Q 130.776 56 125.77600000000001 56L 128.824 56Q 123.82400000000001 56 123.82400000000001 61z"
                                                    fill="rgba(255,77,73,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="1"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="1"
                                                    clip-path="url(#gridRectMaskishykzm4)"
                                                    pathTo="M 123.82400000000001 61L 123.82400000000001 77.35Q 123.82400000000001 82.35 128.824 82.35L 125.77600000000001 82.35Q 130.776 82.35 130.776 77.35L 130.776 77.35L 130.776 61Q 130.776 56 125.77600000000001 56L 128.824 56Q 123.82400000000001 56 123.82400000000001 61z"
                                                    pathFrom="M 123.82400000000001 61L 123.82400000000001 61L 130.776 61L 130.776 61L 130.776 61L 130.776 61L 130.776 61L 123.82400000000001 61"
                                                    cy="72.35" cx="151.72400000000002" j="4" val="-31"
                                                    barHeight="-26.349999999999998" barWidth="7.951999999999999"></path>
                                            </g>
                                            <g id="SvgjsG2225" class="apexcharts-datalabels" data:realIndex="0"></g>
                                            <g id="SvgjsG2232" class="apexcharts-datalabels" data:realIndex="1"></g>
                                        </g>
                                        <line id="SvgjsLine2259" x1="0" y1="0" x2="142"
                                            y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                            stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                        <line id="SvgjsLine2260" x1="0" y1="0" x2="142"
                                            y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                            class="apexcharts-ycrosshairs-hidden"></line>
                                        <g id="SvgjsG2261" class="apexcharts-yaxis-annotations"></g>
                                        <g id="SvgjsG2262" class="apexcharts-xaxis-annotations"></g>
                                        <g id="SvgjsG2263" class="apexcharts-point-annotations"></g>
                                    </g>
                                    <g id="SvgjsG2247" class="apexcharts-yaxis" rel="0"
                                        transform="translate(-18, 0)"></g>
                                    <g id="SvgjsG2213" class="apexcharts-annotations"></g>
                                </svg>
                                <div class="apexcharts-legend" style="max-height: 50px;"></div>
                            </div>
                        </div>
                        <div class="resize-triggers">
                            <div class="expand-trigger">
                                <div style="width: 183px; height: 124px;"></div>
                            </div>
                            <div class="contract-trigger"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Total Profit chart -->

            <!-- Total Growth chart -->
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-end mb-1 flex-wrap gap-2">
                            <h4 class="mb-0 me-2">$27.9k</h4>
                            <p class="mb-0 text-success">+16%</p>
                        </div>
                        <span class="d-block mb-2 text-muted">Total Growth</span>
                    </div>
                    <div class="card-body" style="position: relative;">
                        <div id="totalGrowthChart" style="min-height: 99.55px;">
                            <div id="apexchartsla1jyfin"
                                class="apexcharts-canvas apexchartsla1jyfin apexcharts-theme-light"
                                style="width: 142px; height: 99.55px;"><svg id="SvgjsSvg2264" width="142"
                                    height="99.55" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                    class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                    style="background: transparent;">
                                    <g id="SvgjsG2266" class="apexcharts-inner apexcharts-graphical"
                                        transform="translate(20.5, 0)">
                                        <defs id="SvgjsDefs2265">
                                            <clipPath id="gridRectMaskla1jyfin">
                                                <rect id="SvgjsRect2268" width="112" height="130" x="-4.5" y="-2.5"
                                                    rx="0" ry="0" opacity="1" stroke-width="0"
                                                    stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                            </clipPath>
                                            <clipPath id="forecastMaskla1jyfin"></clipPath>
                                            <clipPath id="nonForecastMaskla1jyfin"></clipPath>
                                            <clipPath id="gridRectMarkerMaskla1jyfin">
                                                <rect id="SvgjsRect2269" width="107" height="129" x="-2" y="-2"
                                                    rx="0" ry="0" opacity="1" stroke-width="0"
                                                    stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                            </clipPath>
                                        </defs>
                                        <g id="SvgjsG2270" class="apexcharts-pie">
                                            <g id="SvgjsG2271" transform="translate(0, 0) scale(1)">
                                                <circle id="SvgjsCircle2272" r="28.870731707317077" cx="51.5"
                                                    cy="51.5" fill="transparent"></circle>
                                                <g id="SvgjsG2273" class="apexcharts-slices">
                                                    <g id="SvgjsG2274" class="apexcharts-series apexcharts-pie-series"
                                                        seriesName="2023" rel="1" data:realIndex="0">
                                                        <path id="SvgjsPath2275"
                                                            d="M 51.5 10.256097560975604 A 41.243902439024396 41.243902439024396 0 0 1 76.21654961148019 84.51744483909985 L 68.80158472803613 74.6122113873699 A 28.870731707317077 28.870731707317077 0 0 0 51.5 22.629268292682923 L 51.5 10.256097560975604 z"
                                                            fill="rgba(102,108,255,1)" fill-opacity="1"
                                                            stroke-opacity="1" stroke-linecap="butt" stroke-width="5"
                                                            stroke-dasharray="0"
                                                            class="apexcharts-pie-area apexcharts-donut-slice-0"
                                                            index="0" j="0" data:angle="143.1818181818182"
                                                            data:startAngle="0" data:strokeWidth="5" data:value="35"
                                                            data:pathOrig="M 51.5 10.256097560975604 A 41.243902439024396 41.243902439024396 0 0 1 76.21654961148019 84.51744483909985 L 68.80158472803613 74.6122113873699 A 28.870731707317077 28.870731707317077 0 0 0 51.5 22.629268292682923 L 51.5 10.256097560975604 z"
                                                            stroke="#ffffff"></path>
                                                    </g>
                                                    <g id="SvgjsG2276" class="apexcharts-series apexcharts-pie-series"
                                                        seriesName="2022" rel="2" data:realIndex="1">
                                                        <path id="SvgjsPath2277"
                                                            d="M 76.21654961148019 84.51744483909985 A 41.243902439024396 41.243902439024396 0 0 1 10.361182297416121 54.44230631194883 L 22.702827608191285 53.559614418364184 A 28.870731707317077 28.870731707317077 0 0 0 68.80158472803613 74.6122113873699 L 76.21654961148019 84.51744483909985 z"
                                                            fill="rgba(114,225,40,1)" fill-opacity="1" stroke-opacity="1"
                                                            stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                            class="apexcharts-pie-area apexcharts-donut-slice-1"
                                                            index="0" j="1" data:angle="122.72727272727275"
                                                            data:startAngle="143.1818181818182" data:strokeWidth="5"
                                                            data:value="30"
                                                            data:pathOrig="M 76.21654961148019 84.51744483909985 A 41.243902439024396 41.243902439024396 0 0 1 10.361182297416121 54.44230631194883 L 22.702827608191285 53.559614418364184 A 28.870731707317077 28.870731707317077 0 0 0 68.80158472803613 74.6122113873699 L 76.21654961148019 84.51744483909985 z"
                                                            stroke="#ffffff"></path>
                                                    </g>
                                                    <g id="SvgjsG2278" class="apexcharts-series apexcharts-pie-series"
                                                        seriesName="2021" rel="3" data:realIndex="2">
                                                        <path id="SvgjsPath2279"
                                                            d="M 10.361182297416121 54.44230631194883 A 41.243902439024396 41.243902439024396 0 0 1 51.49280158109722 10.256098189156155 L 51.49496110676805 22.62926873240931 A 28.870731707317077 28.870731707317077 0 0 0 22.702827608191285 53.559614418364184 L 10.361182297416121 54.44230631194883 z"
                                                            fill="rgba(109,120,141,1)" fill-opacity="1"
                                                            stroke-opacity="1" stroke-linecap="butt" stroke-width="5"
                                                            stroke-dasharray="0"
                                                            class="apexcharts-pie-area apexcharts-donut-slice-2"
                                                            index="0" j="2" data:angle="94.09090909090907"
                                                            data:startAngle="265.90909090909093" data:strokeWidth="5"
                                                            data:value="23"
                                                            data:pathOrig="M 10.361182297416121 54.44230631194883 A 41.243902439024396 41.243902439024396 0 0 1 51.49280158109722 10.256098189156155 L 51.49496110676805 22.62926873240931 A 28.870731707317077 28.870731707317077 0 0 0 22.702827608191285 53.559614418364184 L 10.361182297416121 54.44230631194883 z"
                                                            stroke="#ffffff"></path>
                                                    </g>
                                                </g>
                                            </g>
                                            <g id="SvgjsG2280" class="apexcharts-datalabels-group"
                                                transform="translate(0, 0) scale(1)"><text id="SvgjsText2281"
                                                    font-family="Inter" x="51.5" y="55.5" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="1rem" font-weight="600"
                                                    fill="#828393" class="apexcharts-text apexcharts-datalabel-value"
                                                    style="font-family: Inter;">12%</text></g>
                                        </g>
                                        <line id="SvgjsLine2282" x1="0" y1="0" x2="103"
                                            y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                            stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                        <line id="SvgjsLine2283" x1="0" y1="0" x2="103"
                                            y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                            class="apexcharts-ycrosshairs-hidden"></line>
                                    </g>
                                    <g id="SvgjsG2267" class="apexcharts-annotations"></g>
                                </svg>
                                <div class="apexcharts-legend"></div>
                                <div class="apexcharts-tooltip apexcharts-theme-dark">
                                    <div class="apexcharts-tooltip-series-group" style="order: 1;"><span
                                            class="apexcharts-tooltip-marker"
                                            style="background-color: rgb(102, 108, 255);"></span>
                                        <div class="apexcharts-tooltip-text"
                                            style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                            <div class="apexcharts-tooltip-y-group"><span
                                                    class="apexcharts-tooltip-text-y-label"></span><span
                                                    class="apexcharts-tooltip-text-y-value"></span></div>
                                            <div class="apexcharts-tooltip-goals-group"><span
                                                    class="apexcharts-tooltip-text-goals-label"></span><span
                                                    class="apexcharts-tooltip-text-goals-value"></span></div>
                                            <div class="apexcharts-tooltip-z-group"><span
                                                    class="apexcharts-tooltip-text-z-label"></span><span
                                                    class="apexcharts-tooltip-text-z-value"></span></div>
                                        </div>
                                    </div>
                                    <div class="apexcharts-tooltip-series-group" style="order: 2;"><span
                                            class="apexcharts-tooltip-marker"
                                            style="background-color: rgb(114, 225, 40);"></span>
                                        <div class="apexcharts-tooltip-text"
                                            style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                            <div class="apexcharts-tooltip-y-group"><span
                                                    class="apexcharts-tooltip-text-y-label"></span><span
                                                    class="apexcharts-tooltip-text-y-value"></span></div>
                                            <div class="apexcharts-tooltip-goals-group"><span
                                                    class="apexcharts-tooltip-text-goals-label"></span><span
                                                    class="apexcharts-tooltip-text-goals-value"></span></div>
                                            <div class="apexcharts-tooltip-z-group"><span
                                                    class="apexcharts-tooltip-text-z-label"></span><span
                                                    class="apexcharts-tooltip-text-z-value"></span></div>
                                        </div>
                                    </div>
                                    <div class="apexcharts-tooltip-series-group" style="order: 3;"><span
                                            class="apexcharts-tooltip-marker"
                                            style="background-color: rgb(109, 120, 141);"></span>
                                        <div class="apexcharts-tooltip-text"
                                            style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                            <div class="apexcharts-tooltip-y-group"><span
                                                    class="apexcharts-tooltip-text-y-label"></span><span
                                                    class="apexcharts-tooltip-text-y-value"></span></div>
                                            <div class="apexcharts-tooltip-goals-group"><span
                                                    class="apexcharts-tooltip-text-goals-label"></span><span
                                                    class="apexcharts-tooltip-text-goals-value"></span></div>
                                            <div class="apexcharts-tooltip-z-group"><span
                                                    class="apexcharts-tooltip-text-z-label"></span><span
                                                    class="apexcharts-tooltip-text-z-value"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="resize-triggers">
                            <div class="expand-trigger">
                                <div style="width: 183px; height: 124px;"></div>
                            </div>
                            <div class="contract-trigger"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Total Sales chart -->
        </div>
        <div class="row gy-4">
            <!-- Organic Sessions Chart-->
            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-header pb-1">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">Organic Sessions</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="organicSessionsDropdown"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="organicSessionsDropdown">
                                    <a class="dropdown-item waves-effect" href="javascript:void(0);">Last 28 Days</a>
                                    <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Month</a>
                                    <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Year</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="position: relative;">
                        <div id="organicSessionsChart" style="min-height: 265.45px;">
                            <div id="apexchartsh0pqtegz"
                                class="apexcharts-canvas apexchartsh0pqtegz apexcharts-theme-light"
                                style="width: 348px; height: 265.45px;"><svg id="SvgjsSvg2343" width="348"
                                    height="265.4499992370605" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                    class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                    style="background: transparent;">
                                    <foreignObject x="0" y="0" width="348" height="265.4499992370605">
                                        <div class="apexcharts-legend apexcharts-align-center apx-legend-position-bottom"
                                            xmlns="http://www.w3.org/1999/xhtml"
                                            style="inset: auto 0px 15px; position: absolute; max-height: 150px;">
                                            <div class="apexcharts-legend-series" rel="1" seriesname="USA"
                                                data:collapsed="false" style="margin: 2px 10px;"><span
                                                    class="apexcharts-legend-marker" rel="1"
                                                    data:collapsed="false"
                                                    style="background: rgb(253, 181, 40) !important; color: rgb(253, 181, 40); height: 12px; width: 12px; left: -5px; top: 0px; border-width: 0px; border-color: rgb(255, 255, 255); border-radius: 12px;"></span><span
                                                    class="apexcharts-legend-text" rel="1" i="0"
                                                    data:default-text="USA" data:collapsed="false"
                                                    style="color: rgb(99, 101, 120); font-size: 15px; font-weight: 400; font-family: Inter;">USA</span>
                                            </div>
                                            <div class="apexcharts-legend-series" rel="2" seriesname="India"
                                                data:collapsed="false" style="margin: 2px 10px;"><span
                                                    class="apexcharts-legend-marker" rel="2"
                                                    data:collapsed="false"
                                                    style="background: rgba(253, 181, 40, 0.8) !important; color: rgba(253, 181, 40, 0.8); height: 12px; width: 12px; left: -5px; top: 0px; border-width: 0px; border-color: rgb(255, 255, 255); border-radius: 12px;"></span><span
                                                    class="apexcharts-legend-text" rel="2" i="1"
                                                    data:default-text="India" data:collapsed="false"
                                                    style="color: rgb(99, 101, 120); font-size: 15px; font-weight: 400; font-family: Inter;">India</span>
                                            </div>
                                            <div class="apexcharts-legend-series" rel="3" seriesname="Canada"
                                                data:collapsed="false" style="margin: 2px 10px;"><span
                                                    class="apexcharts-legend-marker" rel="3"
                                                    data:collapsed="false"
                                                    style="background: rgba(253, 181, 40, 0.6) !important; color: rgba(253, 181, 40, 0.6); height: 12px; width: 12px; left: -5px; top: 0px; border-width: 0px; border-color: rgb(255, 255, 255); border-radius: 12px;"></span><span
                                                    class="apexcharts-legend-text" rel="3" i="2"
                                                    data:default-text="Canada" data:collapsed="false"
                                                    style="color: rgb(99, 101, 120); font-size: 15px; font-weight: 400; font-family: Inter;">Canada</span>
                                            </div>
                                            <div class="apexcharts-legend-series" rel="4" seriesname="Japan"
                                                data:collapsed="false" style="margin: 2px 10px;"><span
                                                    class="apexcharts-legend-marker" rel="4"
                                                    data:collapsed="false"
                                                    style="background: rgba(253, 181, 40, 0.4) !important; color: rgba(253, 181, 40, 0.4); height: 12px; width: 12px; left: -5px; top: 0px; border-width: 0px; border-color: rgb(255, 255, 255); border-radius: 12px;"></span><span
                                                    class="apexcharts-legend-text" rel="4" i="3"
                                                    data:default-text="Japan" data:collapsed="false"
                                                    style="color: rgb(99, 101, 120); font-size: 15px; font-weight: 400; font-family: Inter;">Japan</span>
                                            </div>
                                            <div class="apexcharts-legend-series" rel="5" seriesname="France"
                                                data:collapsed="false" style="margin: 2px 10px;"><span
                                                    class="apexcharts-legend-marker" rel="5"
                                                    data:collapsed="false"
                                                    style="background: rgba(253, 181, 40, 0.16) !important; color: rgba(253, 181, 40, 0.16); height: 12px; width: 12px; left: -5px; top: 0px; border-width: 0px; border-color: rgb(255, 255, 255); border-radius: 12px;"></span><span
                                                    class="apexcharts-legend-text" rel="5" i="4"
                                                    data:default-text="France" data:collapsed="false"
                                                    style="color: rgb(99, 101, 120); font-size: 15px; font-weight: 400; font-family: Inter;">France</span>
                                            </div>
                                        </div>
                                        <style type="text/css">
                                            .apexcharts-legend {
                                                display: flex;
                                                overflow: auto;
                                                padding: 0 10px;
                                            }

                                            .apexcharts-legend.apx-legend-position-bottom,
                                            .apexcharts-legend.apx-legend-position-top {
                                                flex-wrap: wrap
                                            }

                                            .apexcharts-legend.apx-legend-position-right,
                                            .apexcharts-legend.apx-legend-position-left {
                                                flex-direction: column;
                                                bottom: 0;
                                            }

                                            .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                            .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                            .apexcharts-legend.apx-legend-position-right,
                                            .apexcharts-legend.apx-legend-position-left {
                                                justify-content: flex-start;
                                            }

                                            .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                            .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                justify-content: center;
                                            }

                                            .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                            .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                justify-content: flex-end;
                                            }

                                            .apexcharts-legend-series {
                                                cursor: pointer;
                                                line-height: normal;
                                            }

                                            .apexcharts-legend.apx-legend-position-bottom .apexcharts-legend-series,
                                            .apexcharts-legend.apx-legend-position-top .apexcharts-legend-series {
                                                display: flex;
                                                align-items: center;
                                            }

                                            .apexcharts-legend-text {
                                                position: relative;
                                                font-size: 14px;
                                            }

                                            .apexcharts-legend-text *,
                                            .apexcharts-legend-marker * {
                                                pointer-events: none;
                                            }

                                            .apexcharts-legend-marker {
                                                position: relative;
                                                display: inline-block;
                                                cursor: pointer;
                                                margin-right: 3px;
                                                border-style: solid;
                                            }

                                            .apexcharts-legend.apexcharts-align-right .apexcharts-legend-series,
                                            .apexcharts-legend.apexcharts-align-left .apexcharts-legend-series {
                                                display: inline-block;
                                            }

                                            .apexcharts-legend-series.apexcharts-no-click {
                                                cursor: auto;
                                            }

                                            .apexcharts-legend .apexcharts-hidden-zero-series,
                                            .apexcharts-legend .apexcharts-hidden-null-series {
                                                display: none !important;
                                            }

                                            .apexcharts-inactive-legend {
                                                opacity: 0.45;
                                            }
                                        </style>
                                    </foreignObject>
                                    <g id="SvgjsG2345" class="apexcharts-inner apexcharts-graphical"
                                        transform="translate(12, 0)">
                                        <defs id="SvgjsDefs2344">
                                            <clipPath id="gridRectMaskh0pqtegz">
                                                <rect id="SvgjsRect2347" width="333" height="213" x="-3.5" y="-1.5"
                                                    rx="0" ry="0" opacity="1" stroke-width="0"
                                                    stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                            </clipPath>
                                            <clipPath id="forecastMaskh0pqtegz"></clipPath>
                                            <clipPath id="nonForecastMaskh0pqtegz"></clipPath>
                                            <clipPath id="gridRectMarkerMaskh0pqtegz">
                                                <rect id="SvgjsRect2348" width="330" height="214" x="-2" y="-2"
                                                    rx="0" ry="0" opacity="1" stroke-width="0"
                                                    stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                            </clipPath>
                                        </defs>
                                        <g id="SvgjsG2349" class="apexcharts-pie">
                                            <g id="SvgjsG2350" transform="translate(16.299999999999983, 10.5) scale(0.9)">
                                                <circle id="SvgjsCircle2351" r="79.21439024390246" cx="163"
                                                    cy="105" fill="transparent"></circle>
                                                <g id="SvgjsG2352" class="apexcharts-slices">
                                                    <g id="SvgjsG2353" class="apexcharts-series apexcharts-pie-series"
                                                        seriesName="USA" rel="1" data:realIndex="0">
                                                        <path id="SvgjsPath2354"
                                                            d="M 89.88946570915704 166.34702235862022 A 95.43902439024392 95.43902439024392 0 0 1 67.62042817972875 108.36818288447802 L 83.83495538917485 107.79559179411676 A 79.21439024390246 79.21439024390246 0 0 0 102.31825653860034 155.9180285576548 L 89.88946570915704 166.34702235862022 z"
                                                            fill="rgba(253,181,40,1)" fill-opacity="1" stroke-opacity="1"
                                                            stroke-linecap="round" stroke-width="3" stroke-dasharray="0"
                                                            class="apexcharts-pie-area apexcharts-donut-slice-0"
                                                            index="0" j="0" data:angle="37.97752808988764"
                                                            data:startAngle="-130" data:strokeWidth="3" data:value="13"
                                                            data:pathOrig="M 89.88946570915704 166.34702235862022 A 95.43902439024392 95.43902439024392 0 0 1 67.62042817972875 108.36818288447802 L 83.83495538917485 107.79559179411676 A 79.21439024390246 79.21439024390246 0 0 0 102.31825653860034 155.9180285576548 L 89.88946570915704 166.34702235862022 z"
                                                            stroke="#ffffff"></path>
                                                    </g>
                                                    <g id="SvgjsG2355" class="apexcharts-series apexcharts-pie-series"
                                                        seriesName="India" rel="2" data:realIndex="1">
                                                        <path id="SvgjsPath2356"
                                                            d="M 67.62042817972875 108.36818288447802 A 95.43902439024392 95.43902439024392 0 0 1 102.37278006521481 31.29146874655038 L 112.6794074541283 43.821919059636805 A 79.21439024390246 79.21439024390246 0 0 0 83.83495538917485 107.79559179411676 L 67.62042817972875 108.36818288447802 z"
                                                            fill="#fdb528cc" fill-opacity="1" stroke-opacity="1"
                                                            stroke-linecap="round" stroke-width="3" stroke-dasharray="0"
                                                            class="apexcharts-pie-area apexcharts-donut-slice-1"
                                                            index="0" j="1" data:angle="52.58426966292135"
                                                            data:startAngle="-92.02247191011236" data:strokeWidth="3"
                                                            data:value="18"
                                                            data:pathOrig="M 67.62042817972875 108.36818288447802 A 95.43902439024392 95.43902439024392 0 0 1 102.37278006521481 31.29146874655038 L 112.6794074541283 43.821919059636805 A 79.21439024390246 79.21439024390246 0 0 0 83.83495538917485 107.79559179411676 L 67.62042817972875 108.36818288447802 z"
                                                            stroke="#ffffff"></path>
                                                    </g>
                                                    <g id="SvgjsG2357" class="apexcharts-series apexcharts-pie-series"
                                                        seriesName="Canada" rel="3" data:realIndex="2">
                                                        <path id="SvgjsPath2358"
                                                            d="M 102.37278006521481 31.29146874655038 A 95.43902439024392 95.43902439024392 0 0 1 184.70611134311926 12.062106184174368 L 181.016072414789 27.861548132864712 A 79.21439024390246 79.21439024390246 0 0 0 112.6794074541283 43.821919059636805 L 102.37278006521481 31.29146874655038 z"
                                                            fill="#fdb52899" fill-opacity="1" stroke-opacity="1"
                                                            stroke-linecap="round" stroke-width="3" stroke-dasharray="0"
                                                            class="apexcharts-pie-area apexcharts-donut-slice-2"
                                                            index="0" j="2" data:angle="52.58426966292135"
                                                            data:startAngle="-39.43820224719101" data:strokeWidth="3"
                                                            data:value="18"
                                                            data:pathOrig="M 102.37278006521481 31.29146874655038 A 95.43902439024392 95.43902439024392 0 0 1 184.70611134311926 12.062106184174368 L 181.016072414789 27.861548132864712 A 79.21439024390246 79.21439024390246 0 0 0 112.6794074541283 43.821919059636805 L 102.37278006521481 31.29146874655038 z"
                                                            stroke="#ffffff"></path>
                                                    </g>
                                                    <g id="SvgjsG2359" class="apexcharts-series apexcharts-pie-series"
                                                        seriesName="Japan" rel="4" data:realIndex="3">
                                                        <path id="SvgjsPath2360"
                                                            d="M 184.70611134311926 12.062106184174368 A 95.43902439024392 95.43902439024392 0 0 1 257.7791335330296 93.79628529059612 L 241.66668083241458 95.70091679119477 A 79.21439024390246 79.21439024390246 0 0 0 181.016072414789 27.861548132864712 L 184.70611134311926 12.062106184174368 z"
                                                            fill="#fdb52866" fill-opacity="1" stroke-opacity="1"
                                                            stroke-linecap="round" stroke-width="3" stroke-dasharray="0"
                                                            class="apexcharts-pie-area apexcharts-donut-slice-3"
                                                            index="0" j="3" data:angle="70.11235955056179"
                                                            data:startAngle="13.146067415730343" data:strokeWidth="3"
                                                            data:value="24"
                                                            data:pathOrig="M 184.70611134311926 12.062106184174368 A 95.43902439024392 95.43902439024392 0 0 1 257.7791335330296 93.79628529059612 L 241.66668083241458 95.70091679119477 A 79.21439024390246 79.21439024390246 0 0 0 181.016072414789 27.861548132864712 L 184.70611134311926 12.062106184174368 z"
                                                            stroke="#ffffff"></path>
                                                    </g>
                                                    <g id="SvgjsG2361" class="apexcharts-series apexcharts-pie-series"
                                                        seriesName="France" rel="5" data:realIndex="4">
                                                        <path id="SvgjsPath2362"
                                                            d="M 257.7791335330296 93.79628529059612 A 95.43902439024392 95.43902439024392 0 0 1 236.12124025251603 166.33426122890376 L 223.6906294095883 155.90743681999012 A 79.21439024390246 79.21439024390246 0 0 0 241.66668083241458 95.70091679119477 L 257.7791335330296 93.79628529059612 z"
                                                            fill="#fdb52829" fill-opacity="1" stroke-opacity="1"
                                                            stroke-linecap="round" stroke-width="3" stroke-dasharray="0"
                                                            class="apexcharts-pie-area apexcharts-donut-slice-4"
                                                            index="0" j="4" data:angle="46.741573033707866"
                                                            data:startAngle="83.25842696629213" data:strokeWidth="3"
                                                            data:value="16"
                                                            data:pathOrig="M 257.7791335330296 93.79628529059612 A 95.43902439024392 95.43902439024392 0 0 1 236.12124025251603 166.33426122890376 L 223.6906294095883 155.90743681999012 A 79.21439024390246 79.21439024390246 0 0 0 241.66668083241458 95.70091679119477 L 257.7791335330296 93.79628529059612 z"
                                                            stroke="#ffffff"></path>
                                                    </g>
                                                </g>
                                            </g>
                                            <g id="SvgjsG2363" class="apexcharts-datalabels-group"
                                                transform="translate(16.299999999999983, 10.5) scale(0.9)"><text
                                                    id="SvgjsText2364" font-family="Inter" x="163" y="130"
                                                    text-anchor="middle" dominant-baseline="auto" font-size="1rem"
                                                    font-weight="400" fill="#bbbcc4"
                                                    class="apexcharts-text apexcharts-datalabel-label"
                                                    style="font-family: Inter;">2022</text><text id="SvgjsText2365"
                                                    font-family="Inter" x="163" y="106" text-anchor="middle"
                                                    dominant-baseline="auto" font-size="2.125rem" font-weight="500"
                                                    fill="#636578" class="apexcharts-text apexcharts-datalabel-value"
                                                    style="font-family: Inter;">89K</text></g>
                                        </g>
                                        <line id="SvgjsLine2366" x1="0" y1="0" x2="326"
                                            y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                            stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                        <line id="SvgjsLine2367" x1="0" y1="0" x2="326"
                                            y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                            class="apexcharts-ycrosshairs-hidden"></line>
                                    </g>
                                    <g id="SvgjsG2346" class="apexcharts-annotations"></g>
                                </svg></div>
                        </div>
                        <div class="resize-triggers">
                            <div class="expand-trigger">
                                <div style="width: 389px; height: 288px;"></div>
                            </div>
                            <div class="contract-trigger"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Organic Sessions Chart-->

            <!-- Project Timeline Chart-->
            <div class="col-lg-8 col-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-8 col-12">
                            <div class="card-header">
                                <h5 class="mb-1">Project Timeline</h5>
                                <small class="mb-0 text-body">Total 840 Task Completed</small>
                            </div>
                            <div class="card-body px-2" style="position: relative;">
                                <div id="projectTimelineChart" style="min-height: 230px;">
                                    <div id="apexcharts82drpn5s"
                                        class="apexcharts-canvas apexcharts82drpn5s apexcharts-theme-light"
                                        style="width: 509px; height: 230px;"><svg id="SvgjsSvg1891" width="509"
                                            height="230" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                            class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS"
                                            transform="translate(0, 0)" style="background: transparent;">
                                            <g id="SvgjsG1893" class="apexcharts-inner apexcharts-graphical"
                                                transform="translate(96.12617492675781, -2)">
                                                <defs id="SvgjsDefs1892">
                                                    <clipPath id="gridRectMask82drpn5s">
                                                        <rect id="SvgjsRect1902" width="400.8738250732422"
                                                            height="192.99519938278198" x="-3" y="-1" rx="0"
                                                            ry="0" opacity="1" stroke-width="0"
                                                            stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMask82drpn5s"></clipPath>
                                                    <clipPath id="nonForecastMask82drpn5s"></clipPath>
                                                    <clipPath id="gridRectMarkerMask82drpn5s">
                                                        <rect id="SvgjsRect1903" width="398.8738250732422"
                                                            height="194.99519938278198" x="-2" y="-2" rx="0"
                                                            ry="0" opacity="1" stroke-width="0"
                                                            stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                </defs>
                                                <line id="SvgjsLine1898" x1="0" y1="0"
                                                    x2="0" y2="190.99519938278198" stroke-dasharray="3"
                                                    stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0"
                                                    width="1" height="190.99519938278198" fill="#b1b9c4"
                                                    filter="none" fill-opacity="0.9" stroke-width="0"></line>
                                                <g id="SvgjsG1980" class="apexcharts-yaxis apexcharts-xaxis-inversed"
                                                    rel="0">
                                                    <g id="SvgjsG1981"
                                                        class="apexcharts-yaxis-texts-g apexcharts-xaxis-inversed-texts-g"
                                                        transform="translate(-66.12617492675781, 0)"><text
                                                            id="SvgjsText1982"
                                                            font-family="Helvetica, Arial, sans-serif" x="-15"
                                                            y="20.835839932667128" text-anchor="start"
                                                            dominant-baseline="auto" font-size="0.875rem"
                                                            font-weight="400" fill="#636578"
                                                            class="apexcharts-text apexcharts-yaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1983">Catherine</tspan>
                                                            <title>Catherine</title>
                                                        </text><text id="SvgjsText1984"
                                                            font-family="Helvetica, Arial, sans-serif" x="-15"
                                                            y="59.034879809223526" text-anchor="start"
                                                            dominant-baseline="auto" font-size="0.875rem"
                                                            font-weight="400" fill="#636578"
                                                            class="apexcharts-text apexcharts-yaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1985">Janelle</tspan>
                                                            <title>Janelle</title>
                                                        </text><text id="SvgjsText1986"
                                                            font-family="Helvetica, Arial, sans-serif" x="-15"
                                                            y="97.23391968577992" text-anchor="start"
                                                            dominant-baseline="auto" font-size="0.875rem"
                                                            font-weight="400" fill="#636578"
                                                            class="apexcharts-text apexcharts-yaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1987">Wellington</tspan>
                                                            <title>Wellington</title>
                                                        </text><text id="SvgjsText1988"
                                                            font-family="Helvetica, Arial, sans-serif" x="-15"
                                                            y="135.43295956233632" text-anchor="start"
                                                            dominant-baseline="auto" font-size="0.875rem"
                                                            font-weight="400" fill="#636578"
                                                            class="apexcharts-text apexcharts-yaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1989">Blake</tspan>
                                                            <title>Blake</title>
                                                        </text><text id="SvgjsText1990"
                                                            font-family="Helvetica, Arial, sans-serif" x="-15"
                                                            y="173.6319994388927" text-anchor="start"
                                                            dominant-baseline="auto" font-size="0.875rem"
                                                            font-weight="400" fill="#636578"
                                                            class="apexcharts-text apexcharts-yaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1991">Quinn</tspan>
                                                            <title>Quinn</title>
                                                        </text></g>
                                                </g>
                                                <g id="SvgjsG1938" class="apexcharts-xaxis apexcharts-yaxis-inversed">
                                                    <g id="SvgjsG1939" class="apexcharts-xaxis-texts-g"
                                                        transform="translate(0, -8)"><text id="SvgjsText1941"
                                                            font-family="Helvetica, Arial, sans-serif"
                                                            x="40.940518183593746" y="220.99519938278198"
                                                            text-anchor="middle" dominant-baseline="auto"
                                                            font-size="12px" font-weight="400" fill="#bbbcc4"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1943">Jan</tspan>
                                                            <title>Jan</title>
                                                        </text><text id="SvgjsText1945"
                                                            font-family="Helvetica, Arial, sans-serif"
                                                            x="83.24572030664062" y="220.99519938278198"
                                                            text-anchor="middle" dominant-baseline="auto"
                                                            font-size="12px" font-weight="400" fill="#bbbcc4"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1947">Feb</tspan>
                                                            <title>Feb</title>
                                                        </text><text id="SvgjsText1949"
                                                            font-family="Helvetica, Arial, sans-serif"
                                                            x="121.45687061132813" y="220.99519938278198"
                                                            text-anchor="middle" dominant-baseline="auto"
                                                            font-size="12px" font-weight="400" fill="#bbbcc4"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1951">Mar</tspan>
                                                            <title>Mar</title>
                                                        </text><text id="SvgjsText1953"
                                                            font-family="Helvetica, Arial, sans-serif"
                                                            x="163.762072734375" y="220.99519938278198"
                                                            text-anchor="middle" dominant-baseline="auto"
                                                            font-size="12px" font-weight="400" fill="#bbbcc4"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1955">Apr</tspan>
                                                            <title>Apr</title>
                                                        </text><text id="SvgjsText1957"
                                                            font-family="Helvetica, Arial, sans-serif"
                                                            x="204.70259091796876" y="220.99519938278198"
                                                            text-anchor="middle" dominant-baseline="auto"
                                                            font-size="12px" font-weight="400" fill="#bbbcc4"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1959">May</tspan>
                                                            <title>May</title>
                                                        </text><text id="SvgjsText1961"
                                                            font-family="Helvetica, Arial, sans-serif"
                                                            x="247.00779304101565" y="220.99519938278198"
                                                            text-anchor="middle" dominant-baseline="auto"
                                                            font-size="12px" font-weight="400" fill="#bbbcc4"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1963">Jun</tspan>
                                                            <title>Jun</title>
                                                        </text><text id="SvgjsText1965"
                                                            font-family="Helvetica, Arial, sans-serif"
                                                            x="287.94831122460937" y="220.99519938278198"
                                                            text-anchor="middle" dominant-baseline="auto"
                                                            font-size="12px" font-weight="400" fill="#bbbcc4"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1967">Jul</tspan>
                                                            <title>Jul</title>
                                                        </text><text id="SvgjsText1969"
                                                            font-family="Helvetica, Arial, sans-serif"
                                                            x="330.2535133476562" y="220.99519938278198"
                                                            text-anchor="middle" dominant-baseline="auto"
                                                            font-size="12px" font-weight="400" fill="#bbbcc4"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1971">Aug</tspan>
                                                            <title>Aug</title>
                                                        </text><text id="SvgjsText1973"
                                                            font-family="Helvetica, Arial, sans-serif"
                                                            x="372.5587154707031" y="220.99519938278198"
                                                            text-anchor="middle" dominant-baseline="auto"
                                                            font-size="12px" font-weight="400" fill="#bbbcc4"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1975">Sep</tspan>
                                                            <title>Sep</title>
                                                        </text><text id="SvgjsText1977"
                                                            font-family="Helvetica, Arial, sans-serif"
                                                            x="413.4992336542968" y="220.99519938278198"
                                                            text-anchor="middle" dominant-baseline="auto"
                                                            font-size="12px" font-weight="400" fill="#bbbcc4"
                                                            class="apexcharts-text apexcharts-xaxis-label "
                                                            style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1979"></tspan>
                                                            <title></title>
                                                        </text></g>
                                                </g>
                                                <g id="SvgjsG1992" class="apexcharts-grid">
                                                    <g id="SvgjsG1993" class="apexcharts-gridlines-horizontal"></g>
                                                    <g id="SvgjsG1994" class="apexcharts-gridlines-vertical">
                                                        <line id="SvgjsLine1995" x1="40.940518183593746"
                                                            y1="0" x2="40.940518183593746"
                                                            y2="190.99519938278198" stroke="#eaeaec"
                                                            stroke-dasharray="6" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line id="SvgjsLine1996" x1="83.24572030664062" y1="0"
                                                            x2="83.24572030664062" y2="190.99519938278198"
                                                            stroke="#eaeaec" stroke-dasharray="6"
                                                            stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                        <line id="SvgjsLine1997" x1="121.45687061132813"
                                                            y1="0" x2="121.45687061132813"
                                                            y2="190.99519938278198" stroke="#eaeaec"
                                                            stroke-dasharray="6" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line id="SvgjsLine1998" x1="163.762072734375" y1="0"
                                                            x2="163.762072734375" y2="190.99519938278198"
                                                            stroke="#eaeaec" stroke-dasharray="6"
                                                            stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                        <line id="SvgjsLine1999" x1="204.70259091796876"
                                                            y1="0" x2="204.70259091796876"
                                                            y2="190.99519938278198" stroke="#eaeaec"
                                                            stroke-dasharray="6" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line id="SvgjsLine2000" x1="247.00779304101565"
                                                            y1="0" x2="247.00779304101565"
                                                            y2="190.99519938278198" stroke="#eaeaec"
                                                            stroke-dasharray="6" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line id="SvgjsLine2001" x1="287.94831122460937"
                                                            y1="0" x2="287.94831122460937"
                                                            y2="190.99519938278198" stroke="#eaeaec"
                                                            stroke-dasharray="6" stroke-linecap="butt"
                                                            class="apexcharts-gridline"></line>
                                                        <line id="SvgjsLine2002" x1="330.2535133476562" y1="0"
                                                            x2="330.2535133476562" y2="190.99519938278198"
                                                            stroke="#eaeaec" stroke-dasharray="6"
                                                            stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                        <line id="SvgjsLine2003" x1="372.5587154707031" y1="0"
                                                            x2="372.5587154707031" y2="190.99519938278198"
                                                            stroke="#eaeaec" stroke-dasharray="6"
                                                            stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                    </g>
                                                    <line id="SvgjsLine2005" x1="0" y1="190.99519938278198"
                                                        x2="394.8738250732422" y2="190.99519938278198"
                                                        stroke="transparent" stroke-dasharray="0"
                                                        stroke-linecap="butt"></line>
                                                    <line id="SvgjsLine2004" x1="0" y1="1"
                                                        x2="0" y2="190.99519938278198" stroke="transparent"
                                                        stroke-dasharray="0" stroke-linecap="butt"></line>
                                                </g>
                                                <g id="SvgjsG1904"
                                                    class="apexcharts-rangebar-series apexcharts-plot-series">
                                                    <g id="SvgjsG1905" class="apexcharts-series" seriesName="seriesx1"
                                                        rel="1" data:realIndex="0">
                                                        <path id="SvgjsPath1909"
                                                            d="M 54.98018504101492 5.72985598148346L 190.10694171484283 5.72985598148346Q 205.10694171484283 5.72985598148346 205.10694171484283 20.72985598148346L 205.10694171484283 17.469183895072938Q 205.10694171484283 32.46918389507294 190.10694171484283 32.46918389507294L 190.10694171484283 32.46918389507294L 54.98018504101492 32.46918389507294L 54.98018504101492 32.46918389507294Q 39.98018504101492 32.46918389507294 39.98018504101492 17.469183895072938L 39.98018504101492 20.72985598148346Q 39.98018504101492 5.72985598148346 54.98018504101492 5.72985598148346z"
                                                            fill="rgba(102,108,255,0.85)" fill-opacity="1"
                                                            stroke="#ffffff" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="2"
                                                            stroke-dasharray="0" class="apexcharts-rangebar-area"
                                                            index="0" clip-path="url(#gridRectMask82drpn5s)"
                                                            pathTo="M 54.98018504101492 5.72985598148346L 190.10694171484283 5.72985598148346Q 205.10694171484283 5.72985598148346 205.10694171484283 20.72985598148346L 205.10694171484283 17.469183895072938Q 205.10694171484283 32.46918389507294 190.10694171484283 32.46918389507294L 190.10694171484283 32.46918389507294L 54.98018504101492 32.46918389507294L 54.98018504101492 32.46918389507294Q 39.98018504101492 32.46918389507294 39.98018504101492 17.469183895072938L 39.98018504101492 20.72985598148346Q 39.98018504101492 5.72985598148346 54.98018504101492 5.72985598148346z"
                                                            pathFrom="M 54.98018504101492 5.72985598148346L 54.98018504101492 5.72985598148346L 54.98018504101492 32.46918389507294L 54.98018504101492 32.46918389507294L 54.98018504101492 32.46918389507294L 54.98018504101492 32.46918389507294L 54.98018504101492 32.46918389507294L 54.98018504101492 5.72985598148346"
                                                            data-range-y1="1672531200000" data-range-y2="1682985600000"
                                                            cy="5.72985598148346" cx="205.10694171484283" j="0"
                                                            val="1682985600000" barHeight="26.739327913589477"
                                                            barWidth="165.1267566738279"></path>
                                                        <path id="SvgjsPath1915"
                                                            d="M 120.48501413476333 43.92889585803986L 228.31809201953 43.92889585803986Q 243.31809201953 43.92889585803986 243.31809201953 58.92889585803986L 243.31809201953 55.66822377162933Q 243.31809201953 70.66822377162933 228.31809201953 70.66822377162933L 228.31809201953 70.66822377162933L 120.48501413476333 70.66822377162933L 120.48501413476333 70.66822377162933Q 105.48501413476333 70.66822377162933 105.48501413476333 55.66822377162933L 105.48501413476333 58.92889585803986Q 105.48501413476333 43.92889585803986 120.48501413476333 43.92889585803986z"
                                                            fill="rgba(114,225,40,0.85)" fill-opacity="1"
                                                            stroke="#ffffff" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="2"
                                                            stroke-dasharray="0" class="apexcharts-rangebar-area"
                                                            index="0" clip-path="url(#gridRectMask82drpn5s)"
                                                            pathTo="M 120.48501413476333 43.92889585803986L 228.31809201953 43.92889585803986Q 243.31809201953 43.92889585803986 243.31809201953 58.92889585803986L 243.31809201953 55.66822377162933Q 243.31809201953 70.66822377162933 228.31809201953 70.66822377162933L 228.31809201953 70.66822377162933L 120.48501413476333 70.66822377162933L 120.48501413476333 70.66822377162933Q 105.48501413476333 70.66822377162933 105.48501413476333 55.66822377162933L 105.48501413476333 58.92889585803986Q 105.48501413476333 43.92889585803986 120.48501413476333 43.92889585803986z"
                                                            pathFrom="M 120.48501413476333 43.92889585803986L 120.48501413476333 43.92889585803986L 120.48501413476333 70.66822377162933L 120.48501413476333 70.66822377162933L 120.48501413476333 70.66822377162933L 120.48501413476333 70.66822377162933L 120.48501413476333 70.66822377162933L 120.48501413476333 43.92889585803986"
                                                            data-range-y1="1676678400000" data-range-y2="1685404800000"
                                                            cy="43.92889585803986" cx="243.31809201953" j="1"
                                                            val="1685404800000" barHeight="26.739327913589477"
                                                            barWidth="137.83307788476668"></path>
                                                        <path id="SvgjsPath1921"
                                                            d="M 105.47349080078129 82.12793573459626L 229.68277595898326 82.12793573459626Q 244.68277595898326 82.12793573459626 244.68277595898326 97.12793573459626L 244.68277595898326 93.86726364818574Q 244.68277595898326 108.86726364818574 229.68277595898326 108.86726364818574L 229.68277595898326 108.86726364818574L 105.47349080078129 108.86726364818574L 105.47349080078129 108.86726364818574Q 90.47349080078129 108.86726364818574 90.47349080078129 93.86726364818574L 90.47349080078129 97.12793573459626Q 90.47349080078129 82.12793573459626 105.47349080078129 82.12793573459626z"
                                                            fill="rgba(109,120,141,0.85)" fill-opacity="1"
                                                            stroke="#ffffff" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="2"
                                                            stroke-dasharray="0" class="apexcharts-rangebar-area"
                                                            index="0" clip-path="url(#gridRectMask82drpn5s)"
                                                            pathTo="M 105.47349080078129 82.12793573459626L 229.68277595898326 82.12793573459626Q 244.68277595898326 82.12793573459626 244.68277595898326 97.12793573459626L 244.68277595898326 93.86726364818574Q 244.68277595898326 108.86726364818574 229.68277595898326 108.86726364818574L 229.68277595898326 108.86726364818574L 105.47349080078129 108.86726364818574L 105.47349080078129 108.86726364818574Q 90.47349080078129 108.86726364818574 90.47349080078129 93.86726364818574L 90.47349080078129 97.12793573459626Q 90.47349080078129 82.12793573459626 105.47349080078129 82.12793573459626z"
                                                            pathFrom="M 105.47349080078129 82.12793573459626L 105.47349080078129 82.12793573459626L 105.47349080078129 108.86726364818574L 105.47349080078129 108.86726364818574L 105.47349080078129 108.86726364818574L 105.47349080078129 108.86726364818574L 105.47349080078129 108.86726364818574L 105.47349080078129 82.12793573459626"
                                                            data-range-y1="1675728000000" data-range-y2="1685491200000"
                                                            cy="82.12793573459626" cx="244.68277595898326" j="2"
                                                            val="1685491200000" barHeight="26.739327913589477"
                                                            barWidth="154.20928515820196"></path>
                                                        <path id="SvgjsPath1927"
                                                            d="M 72.72107625390345 120.32697561115265L 270.6232941425769 120.32697561115265Q 285.6232941425769 120.32697561115265 285.6232941425769 135.32697561115265L 285.6232941425769 132.06630352474212Q 285.6232941425769 147.06630352474212 270.6232941425769 147.06630352474212L 270.6232941425769 147.06630352474212L 72.72107625390345 147.06630352474212L 72.72107625390345 147.06630352474212Q 57.72107625390345 147.06630352474212 57.72107625390345 132.06630352474212L 57.72107625390345 135.32697561115265Q 57.72107625390345 120.32697561115265 72.72107625390345 120.32697561115265z"
                                                            fill="rgba(38,198,249,0.85)" fill-opacity="1"
                                                            stroke="#ffffff" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="2"
                                                            stroke-dasharray="0" class="apexcharts-rangebar-area"
                                                            index="0" clip-path="url(#gridRectMask82drpn5s)"
                                                            pathTo="M 72.72107625390345 120.32697561115265L 270.6232941425769 120.32697561115265Q 285.6232941425769 120.32697561115265 285.6232941425769 135.32697561115265L 285.6232941425769 132.06630352474212Q 285.6232941425769 147.06630352474212 270.6232941425769 147.06630352474212L 270.6232941425769 147.06630352474212L 72.72107625390345 147.06630352474212L 72.72107625390345 147.06630352474212Q 57.72107625390345 147.06630352474212 57.72107625390345 132.06630352474212L 57.72107625390345 135.32697561115265Q 57.72107625390345 120.32697561115265 72.72107625390345 120.32697561115265z"
                                                            pathFrom="M 72.72107625390345 120.32697561115265L 72.72107625390345 120.32697561115265L 72.72107625390345 147.06630352474212L 72.72107625390345 147.06630352474212L 72.72107625390345 147.06630352474212L 72.72107625390345 147.06630352474212L 72.72107625390345 147.06630352474212L 72.72107625390345 120.32697561115265"
                                                            data-range-y1="1673654400000" data-range-y2="1688083200000"
                                                            cy="120.32697561115265" cx="285.6232941425769" j="3"
                                                            val="1688083200000" barHeight="26.739327913589477"
                                                            barWidth="227.90221788867348"></path>
                                                        <path id="SvgjsPath1933"
                                                            d="M 177.80173959179592 158.52601548770906L 312.92849626562383 158.52601548770906Q 327.92849626562383 158.52601548770906 327.92849626562383 173.52601548770906L 327.92849626562383 170.26534340129854Q 327.92849626562383 185.26534340129854 312.92849626562383 185.26534340129854L 312.92849626562383 185.26534340129854L 177.80173959179592 185.26534340129854L 177.80173959179592 185.26534340129854Q 162.80173959179592 185.26534340129854 162.80173959179592 170.26534340129854L 162.80173959179592 173.52601548770906Q 162.80173959179592 158.52601548770906 177.80173959179592 158.52601548770906z"
                                                            fill="rgba(253,181,40,0.85)" fill-opacity="1"
                                                            stroke="#ffffff" stroke-opacity="1"
                                                            stroke-linecap="square" stroke-width="2"
                                                            stroke-dasharray="0" class="apexcharts-rangebar-area"
                                                            index="0" clip-path="url(#gridRectMask82drpn5s)"
                                                            pathTo="M 177.80173959179592 158.52601548770906L 312.92849626562383 158.52601548770906Q 327.92849626562383 158.52601548770906 327.92849626562383 173.52601548770906L 327.92849626562383 170.26534340129854Q 327.92849626562383 185.26534340129854 312.92849626562383 185.26534340129854L 312.92849626562383 185.26534340129854L 177.80173959179592 185.26534340129854L 177.80173959179592 185.26534340129854Q 162.80173959179592 185.26534340129854 162.80173959179592 170.26534340129854L 162.80173959179592 173.52601548770906Q 162.80173959179592 158.52601548770906 177.80173959179592 158.52601548770906z"
                                                            pathFrom="M 177.80173959179592 158.52601548770906L 177.80173959179592 158.52601548770906L 177.80173959179592 185.26534340129854L 177.80173959179592 185.26534340129854L 177.80173959179592 185.26534340129854L 177.80173959179592 185.26534340129854L 177.80173959179592 185.26534340129854L 177.80173959179592 158.52601548770906"
                                                            data-range-y1="1680307200000" data-range-y2="1690761600000"
                                                            cy="158.52601548770906" cx="327.92849626562383" j="4"
                                                            val="1690761600000" barHeight="26.739327913589477"
                                                            barWidth="165.1267566738279"></path>
                                                        <g id="SvgjsG1907" class="apexcharts-rangebar-goals-markers"
                                                            style="pointer-events: none">
                                                            <g id="SvgjsG1908" className="apexcharts-bar-goals-groups">
                                                            </g>
                                                            <g id="SvgjsG1914" className="apexcharts-bar-goals-groups">
                                                            </g>
                                                            <g id="SvgjsG1920" className="apexcharts-bar-goals-groups">
                                                            </g>
                                                            <g id="SvgjsG1926" className="apexcharts-bar-goals-groups">
                                                            </g>
                                                            <g id="SvgjsG1932" className="apexcharts-bar-goals-groups">
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <g id="SvgjsG1906" class="apexcharts-datalabels"
                                                        data:realIndex="0">
                                                        <g id="SvgjsG1911" class="apexcharts-data-labels"
                                                            transform="rotate(0)"><text id="SvgjsText1913"
                                                                font-family="Helvetica, Arial, sans-serif"
                                                                x="122.54356337792888" y="22.899520129013062"
                                                                text-anchor="middle" dominant-baseline="auto"
                                                                font-size="12px" font-weight="400" fill="#ffffff"
                                                                class="apexcharts-datalabel" cx="122.54356337792888"
                                                                cy="22.899520129013062"
                                                                style="font-family: Helvetica, Arial, sans-serif;">Development
                                                                Apps</text></g>
                                                        <g id="SvgjsG1917" class="apexcharts-data-labels"
                                                            transform="rotate(0)"><text id="SvgjsText1919"
                                                                font-family="Helvetica, Arial, sans-serif"
                                                                x="174.40155307714667" y="61.09856000556945"
                                                                text-anchor="middle" dominant-baseline="auto"
                                                                font-size="12px" font-weight="400" fill="#ffffff"
                                                                class="apexcharts-datalabel" cx="174.40155307714667"
                                                                cy="61.09856000556945"
                                                                style="font-family: Helvetica, Arial, sans-serif;">UI
                                                                Design</text></g>
                                                        <g id="SvgjsG1923" class="apexcharts-data-labels"
                                                            transform="rotate(0)"><text id="SvgjsText1925"
                                                                font-family="Helvetica, Arial, sans-serif"
                                                                x="167.57813337988227" y="99.29759988212587"
                                                                text-anchor="middle" dominant-baseline="auto"
                                                                font-size="12px" font-weight="400" fill="#ffffff"
                                                                class="apexcharts-datalabel" cx="167.57813337988227"
                                                                cy="99.29759988212587"
                                                                style="font-family: Helvetica, Arial, sans-serif;">IOS
                                                                Application</text></g>
                                                        <g id="SvgjsG1929" class="apexcharts-data-labels"
                                                            transform="rotate(0)"><text id="SvgjsText1931"
                                                                font-family="Helvetica, Arial, sans-serif"
                                                                x="171.6721851982402" y="137.49663975868225"
                                                                text-anchor="middle" dominant-baseline="auto"
                                                                font-size="12px" font-weight="400" fill="#ffffff"
                                                                class="apexcharts-datalabel" cx="171.6721851982402"
                                                                cy="137.49663975868225"
                                                                style="font-family: Helvetica, Arial, sans-serif;">Web App
                                                                Wireframing</text></g>
                                                        <g id="SvgjsG1935" class="apexcharts-data-labels"
                                                            transform="rotate(0)"><text id="SvgjsText1937"
                                                                font-family="Helvetica, Arial, sans-serif"
                                                                x="245.36511792870988" y="175.69567963523866"
                                                                text-anchor="middle" dominant-baseline="auto"
                                                                font-size="12px" font-weight="400" fill="#ffffff"
                                                                class="apexcharts-datalabel" cx="245.36511792870988"
                                                                cy="175.69567963523866"
                                                                style="font-family: Helvetica, Arial, sans-serif;">Prototyping</text>
                                                        </g>
                                                    </g>
                                                </g>
                                                <line id="SvgjsLine2006" x1="0" y1="0"
                                                    x2="394.8738250732422" y2="0" stroke="#b6b6b6"
                                                    stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs"></line>
                                                <line id="SvgjsLine2007" x1="0" y1="0"
                                                    x2="394.8738250732422" y2="0" stroke-dasharray="0"
                                                    stroke-width="0" stroke-linecap="butt"
                                                    class="apexcharts-ycrosshairs-hidden"></line>
                                                <g id="SvgjsG2008" class="apexcharts-yaxis-annotations"></g>
                                                <g id="SvgjsG2009" class="apexcharts-xaxis-annotations"></g>
                                                <g id="SvgjsG2010" class="apexcharts-point-annotations"></g>
                                                <rect id="SvgjsRect2011" width="0" height="0" x="0" y="0"
                                                    rx="0" ry="0" opacity="1" stroke-width="0"
                                                    stroke="none" stroke-dasharray="0" fill="#fefefe"
                                                    class="apexcharts-zoom-rect"></rect>
                                                <rect id="SvgjsRect2012" width="0" height="0" x="0" y="0"
                                                    rx="0" ry="0" opacity="1" stroke-width="0"
                                                    stroke="none" stroke-dasharray="0" fill="#fefefe"
                                                    class="apexcharts-selection-rect"></rect>
                                            </g>
                                            <rect id="SvgjsRect1897" width="0" height="0" x="0" y="0"
                                                rx="0" ry="0" opacity="1" stroke-width="0"
                                                stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                                            <g id="SvgjsG1894" class="apexcharts-annotations"></g>
                                        </svg>
                                        <div class="apexcharts-legend" style="max-height: 115px;"></div>
                                    </div>
                                </div>
                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 526px; height: 253px;"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 border-start">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-1">Project List</h5>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="projectTimeline"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="projectTimeline">
                                            <a class="dropdown-item waves-effect" href="javascript:void(0);">Refresh</a>
                                            <a class="dropdown-item waves-effect" href="javascript:void(0);">Share</a>
                                            <a class="dropdown-item waves-effect" href="javascript:void(0);">Update</a>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-body mb-0">4 Ongoing Project</small>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3 pb-1">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-primary rounded">
                                            <i class="mdi mdi-cellphone mdi-24px"></i>
                                        </div>
                                    </div>
                                    <div class="ms-3 d-flex flex-column">
                                        <h6 class="mb-1 fw-semibold">IOS Application</h6>
                                        <small class="text-muted">Task 840/2.5K</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3 pb-1">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-success rounded">
                                            <i class="mdi mdi-creation mdi-24px"></i>
                                        </div>
                                    </div>
                                    <div class="ms-3 d-flex flex-column">
                                        <h6 class="mb-1 fw-semibold">Web Application</h6>
                                        <small class="text-muted">Task 99/1.42k</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3 pb-1">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-secondary rounded">
                                            <i class="mdi mdi-credit-card-outline mdi-24px"></i>
                                        </div>
                                    </div>
                                    <div class="ms-3 d-flex flex-column">
                                        <h6 class="mb-1 fw-semibold">Bank Dashboard</h6>
                                        <small class="text-muted">Task 58/100</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-info rounded">
                                            <i class="mdi mdi-pencil-ruler-outline mdi-24px"></i>
                                        </div>
                                    </div>
                                    <div class="ms-3 d-flex flex-column">
                                        <h6 class="mb-1 fw-semibold">UI Kit Design</h6>
                                        <small class="text-muted">Task 120/350</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Project Timeline Chart-->

            <!-- Weekly Overview Chart -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">Weekly Overview</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="weeklyOverviewDropdown"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="weeklyOverviewDropdown">
                                    <a class="dropdown-item waves-effect" href="javascript:void(0);">Last 28 Days</a>
                                    <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Month</a>
                                    <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Year</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="position: relative;">
                        <div id="weeklyOverviewChart" style="min-height: 178px;">
                            <div id="apexchartsx1jvpdwc"
                                class="apexcharts-canvas apexchartsx1jvpdwc apexcharts-theme-light"
                                style="width: 348px; height: 178px;"><svg id="SvgjsSvg2013" width="348"
                                    height="178" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                    class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS"
                                    transform="translate(-16, -9)" style="background: transparent;">
                                    <g id="SvgjsG2015" class="apexcharts-inner apexcharts-graphical"
                                        transform="translate(70.18359859254625, 30)">
                                        <defs id="SvgjsDefs2014">
                                            <clipPath id="gridRectMaskx1jvpdwc">
                                                <rect id="SvgjsRect2021" width="294.01706504821783" height="145"
                                                    x="-19.200663640764027" y="-1" rx="0" ry="0"
                                                    opacity="1" stroke-width="0" stroke="none"
                                                    stroke-dasharray="0" fill="#fff"></rect>
                                            </clipPath>
                                            <clipPath id="forecastMaskx1jvpdwc"></clipPath>
                                            <clipPath id="nonForecastMaskx1jvpdwc"></clipPath>
                                            <clipPath id="gridRectMarkerMaskx1jvpdwc">
                                                <rect id="SvgjsRect2022" width="273.61573776668973" height="161"
                                                    x="-9" y="-9" rx="0" ry="0" opacity="1"
                                                    stroke-width="0" stroke="none" stroke-dasharray="0"
                                                    fill="#fff"></rect>
                                            </clipPath>
                                        </defs>
                                        <line id="SvgjsLine2020" x1="0" y1="0" x2="0"
                                            y2="143" stroke="#b6b6b6" stroke-dasharray="3"
                                            stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0"
                                            width="1" height="143" fill="#b1b9c4" filter="none"
                                            fill-opacity="0.9" stroke-width="1"></line>
                                        <g id="SvgjsG2059" class="apexcharts-xaxis" transform="translate(0, 0)">
                                            <g id="SvgjsG2060" class="apexcharts-xaxis-texts-g"
                                                transform="translate(0, -4)"></g>
                                        </g>
                                        <g id="SvgjsG2078" class="apexcharts-grid">
                                            <g id="SvgjsG2079" class="apexcharts-gridlines-horizontal">
                                                <line id="SvgjsLine2081" x1="-16.200663640764027" y1="0"
                                                    x2="271.8164014074538" y2="0" stroke="#eaeaec"
                                                    stroke-dasharray="10" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2082" x1="-16.200663640764027"
                                                    y1="47.666666666666664" x2="271.8164014074538"
                                                    y2="47.666666666666664" stroke="#eaeaec" stroke-dasharray="10"
                                                    stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2083" x1="-16.200663640764027"
                                                    y1="95.33333333333333" x2="271.8164014074538"
                                                    y2="95.33333333333333" stroke="#eaeaec" stroke-dasharray="10"
                                                    stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2084" x1="-16.200663640764027" y1="143"
                                                    x2="271.8164014074538" y2="143" stroke="#eaeaec"
                                                    stroke-dasharray="10" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                            </g>
                                            <g id="SvgjsG2080" class="apexcharts-gridlines-vertical"></g>
                                            <line id="SvgjsLine2086" x1="0" y1="143"
                                                x2="255.61573776668973" y2="143" stroke="transparent"
                                                stroke-dasharray="0" stroke-linecap="butt"></line>
                                            <line id="SvgjsLine2085" x1="0" y1="1" x2="0"
                                                y2="143" stroke="transparent" stroke-dasharray="0"
                                                stroke-linecap="butt"></line>
                                        </g>
                                        <g id="SvgjsG2023" class="apexcharts-bar-series apexcharts-plot-series">
                                            <g id="SvgjsG2024" class="apexcharts-series" rel="1"
                                                seriesName="Sales" data:realIndex="0">
                                                <path id="SvgjsPath2028"
                                                    d="M -7.455459018195117 134L -7.455459018195117 20.122222222222234Q -7.455459018195117 11.122222222222234 1.5445409818048832 11.122222222222234L -1.5445409818048832 11.122222222222234Q 7.455459018195117 11.122222222222234 7.455459018195117 20.122222222222234L 7.455459018195117 20.122222222222234L 7.455459018195117 134Q 7.455459018195117 143 -1.5445409818048832 143L 1.5445409818048832 143Q -7.455459018195117 143 -7.455459018195117 134z"
                                                    fill="rgba(244,244,246,0.85)" fill-opacity="1" stroke-opacity="1"
                                                    stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                    class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskx1jvpdwc)"
                                                    pathTo="M -7.455459018195117 134L -7.455459018195117 20.122222222222234Q -7.455459018195117 11.122222222222234 1.5445409818048832 11.122222222222234L -1.5445409818048832 11.122222222222234Q 7.455459018195117 11.122222222222234 7.455459018195117 20.122222222222234L 7.455459018195117 20.122222222222234L 7.455459018195117 134Q 7.455459018195117 143 -1.5445409818048832 143L 1.5445409818048832 143Q -7.455459018195117 143 -7.455459018195117 134z"
                                                    pathFrom="M -7.455459018195117 134L -7.455459018195117 134L 7.455459018195117 134L 7.455459018195117 134L 7.455459018195117 134L 7.455459018195117 134L 7.455459018195117 134L -7.455459018195117 134"
                                                    cy="11.122222222222234" cx="7.455459018195117" j="0"
                                                    val="83" barHeight="131.87777777777777"
                                                    barWidth="14.910918036390234"></path>
                                                <path id="SvgjsPath2030"
                                                    d="M 35.14716394291984 134L 35.14716394291984 43.95555555555556Q 35.14716394291984 34.95555555555556 44.14716394291984 34.95555555555556L 41.058081979310074 34.95555555555556Q 50.058081979310074 34.95555555555556 50.058081979310074 43.95555555555556L 50.058081979310074 43.95555555555556L 50.058081979310074 134Q 50.058081979310074 143 41.058081979310074 143L 44.14716394291984 143Q 35.14716394291984 143 35.14716394291984 134z"
                                                    fill="rgba(244,244,246,0.85)" fill-opacity="1" stroke-opacity="1"
                                                    stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                    class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskx1jvpdwc)"
                                                    pathTo="M 35.14716394291984 134L 35.14716394291984 43.95555555555556Q 35.14716394291984 34.95555555555556 44.14716394291984 34.95555555555556L 41.058081979310074 34.95555555555556Q 50.058081979310074 34.95555555555556 50.058081979310074 43.95555555555556L 50.058081979310074 43.95555555555556L 50.058081979310074 134Q 50.058081979310074 143 41.058081979310074 143L 44.14716394291984 143Q 35.14716394291984 143 35.14716394291984 134z"
                                                    pathFrom="M 35.14716394291984 134L 35.14716394291984 134L 50.058081979310074 134L 50.058081979310074 134L 50.058081979310074 134L 50.058081979310074 134L 50.058081979310074 134L 35.14716394291984 134"
                                                    cy="34.95555555555556" cx="50.058081979310074" j="1"
                                                    val="68" barHeight="108.04444444444444"
                                                    barWidth="14.910918036390234"></path>
                                                <path id="SvgjsPath2032"
                                                    d="M 77.74978690403479 134L 77.74978690403479 63.022222222222226Q 77.74978690403479 54.022222222222226 86.74978690403479 54.022222222222226L 83.66070494042502 54.022222222222226Q 92.66070494042502 54.022222222222226 92.66070494042502 63.022222222222226L 92.66070494042502 63.022222222222226L 92.66070494042502 134Q 92.66070494042502 143 83.66070494042502 143L 86.74978690403479 143Q 77.74978690403479 143 77.74978690403479 134z"
                                                    fill="rgba(244,244,246,0.85)" fill-opacity="1" stroke-opacity="1"
                                                    stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                    class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskx1jvpdwc)"
                                                    pathTo="M 77.74978690403479 134L 77.74978690403479 63.022222222222226Q 77.74978690403479 54.022222222222226 86.74978690403479 54.022222222222226L 83.66070494042502 54.022222222222226Q 92.66070494042502 54.022222222222226 92.66070494042502 63.022222222222226L 92.66070494042502 63.022222222222226L 92.66070494042502 134Q 92.66070494042502 143 83.66070494042502 143L 86.74978690403479 143Q 77.74978690403479 143 77.74978690403479 134z"
                                                    pathFrom="M 77.74978690403479 134L 77.74978690403479 134L 92.66070494042502 134L 92.66070494042502 134L 92.66070494042502 134L 92.66070494042502 134L 92.66070494042502 134L 77.74978690403479 134"
                                                    cy="54.022222222222226" cx="92.66070494042502" j="2"
                                                    val="56" barHeight="88.97777777777777"
                                                    barWidth="14.910918036390234"></path>
                                                <path id="SvgjsPath2034"
                                                    d="M 120.35240986514975 134L 120.35240986514975 48.72222222222223Q 120.35240986514975 39.72222222222223 129.35240986514975 39.72222222222223L 126.26332790153998 39.72222222222223Q 135.26332790153998 39.72222222222223 135.26332790153998 48.72222222222223L 135.26332790153998 48.72222222222223L 135.26332790153998 134Q 135.26332790153998 143 126.26332790153998 143L 129.35240986514975 143Q 120.35240986514975 143 120.35240986514975 134z"
                                                    fill="rgba(244,244,246,0.85)" fill-opacity="1" stroke-opacity="1"
                                                    stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                    class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskx1jvpdwc)"
                                                    pathTo="M 120.35240986514975 134L 120.35240986514975 48.72222222222223Q 120.35240986514975 39.72222222222223 129.35240986514975 39.72222222222223L 126.26332790153998 39.72222222222223Q 135.26332790153998 39.72222222222223 135.26332790153998 48.72222222222223L 135.26332790153998 48.72222222222223L 135.26332790153998 134Q 135.26332790153998 143 126.26332790153998 143L 129.35240986514975 143Q 120.35240986514975 143 120.35240986514975 134z"
                                                    pathFrom="M 120.35240986514975 134L 120.35240986514975 134L 135.26332790153998 134L 135.26332790153998 134L 135.26332790153998 134L 135.26332790153998 134L 135.26332790153998 134L 120.35240986514975 134"
                                                    cy="39.72222222222223" cx="135.26332790153998" j="3"
                                                    val="65" barHeight="103.27777777777777"
                                                    barWidth="14.910918036390234"></path>
                                                <path id="SvgjsPath2036"
                                                    d="M 162.9550328262647 134L 162.9550328262647 48.72222222222223Q 162.9550328262647 39.72222222222223 171.9550328262647 39.72222222222223L 168.86595086265493 39.72222222222223Q 177.86595086265493 39.72222222222223 177.86595086265493 48.72222222222223L 177.86595086265493 48.72222222222223L 177.86595086265493 134Q 177.86595086265493 143 168.86595086265493 143L 171.9550328262647 143Q 162.9550328262647 143 162.9550328262647 134z"
                                                    fill="rgba(244,244,246,0.85)" fill-opacity="1" stroke-opacity="1"
                                                    stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                    class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskx1jvpdwc)"
                                                    pathTo="M 162.9550328262647 134L 162.9550328262647 48.72222222222223Q 162.9550328262647 39.72222222222223 171.9550328262647 39.72222222222223L 168.86595086265493 39.72222222222223Q 177.86595086265493 39.72222222222223 177.86595086265493 48.72222222222223L 177.86595086265493 48.72222222222223L 177.86595086265493 134Q 177.86595086265493 143 168.86595086265493 143L 171.9550328262647 143Q 162.9550328262647 143 162.9550328262647 134z"
                                                    pathFrom="M 162.9550328262647 134L 162.9550328262647 134L 177.86595086265493 134L 177.86595086265493 134L 177.86595086265493 134L 177.86595086265493 134L 177.86595086265493 134L 162.9550328262647 134"
                                                    cy="39.72222222222223" cx="177.86595086265493" j="4"
                                                    val="65" barHeight="103.27777777777777"
                                                    barWidth="14.910918036390234"></path>
                                                <path id="SvgjsPath2038"
                                                    d="M 205.55765578737967 134L 205.55765578737967 72.55555555555556Q 205.55765578737967 63.55555555555556 214.55765578737967 63.55555555555556L 211.4685738237699 63.55555555555556Q 220.4685738237699 63.55555555555556 220.4685738237699 72.55555555555556L 220.4685738237699 72.55555555555556L 220.4685738237699 134Q 220.4685738237699 143 211.4685738237699 143L 214.55765578737967 143Q 205.55765578737967 143 205.55765578737967 134z"
                                                    fill="rgba(102,108,255,0.85)" fill-opacity="1" stroke-opacity="1"
                                                    stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                    class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskx1jvpdwc)"
                                                    pathTo="M 205.55765578737967 134L 205.55765578737967 72.55555555555556Q 205.55765578737967 63.55555555555556 214.55765578737967 63.55555555555556L 211.4685738237699 63.55555555555556Q 220.4685738237699 63.55555555555556 220.4685738237699 72.55555555555556L 220.4685738237699 72.55555555555556L 220.4685738237699 134Q 220.4685738237699 143 211.4685738237699 143L 214.55765578737967 143Q 205.55765578737967 143 205.55765578737967 134z"
                                                    pathFrom="M 205.55765578737967 134L 205.55765578737967 134L 220.4685738237699 134L 220.4685738237699 134L 220.4685738237699 134L 220.4685738237699 134L 220.4685738237699 134L 205.55765578737967 134"
                                                    cy="63.55555555555556" cx="220.4685738237699" j="5" val="50"
                                                    barHeight="79.44444444444444" barWidth="14.910918036390234"></path>
                                                <path id="SvgjsPath2040"
                                                    d="M 248.16027874849462 134L 248.16027874849462 90.03333333333333Q 248.16027874849462 81.03333333333333 257.16027874849465 81.03333333333333L 254.07119678488488 81.03333333333333Q 263.0711967848849 81.03333333333333 263.0711967848849 90.03333333333333L 263.0711967848849 90.03333333333333L 263.0711967848849 134Q 263.0711967848849 143 254.07119678488488 143L 257.16027874849465 143Q 248.16027874849462 143 248.16027874849462 134z"
                                                    fill="rgba(244,244,246,0.85)" fill-opacity="1" stroke-opacity="1"
                                                    stroke-linecap="butt" stroke-width="0" stroke-dasharray="0"
                                                    class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskx1jvpdwc)"
                                                    pathTo="M 248.16027874849462 134L 248.16027874849462 90.03333333333333Q 248.16027874849462 81.03333333333333 257.16027874849465 81.03333333333333L 254.07119678488488 81.03333333333333Q 263.0711967848849 81.03333333333333 263.0711967848849 90.03333333333333L 263.0711967848849 90.03333333333333L 263.0711967848849 134Q 263.0711967848849 143 254.07119678488488 143L 257.16027874849465 143Q 248.16027874849462 143 248.16027874849462 134z"
                                                    pathFrom="M 248.16027874849462 134L 248.16027874849462 134L 263.0711967848849 134L 263.0711967848849 134L 263.0711967848849 134L 263.0711967848849 134L 263.0711967848849 134L 248.16027874849462 134"
                                                    cy="81.03333333333333" cx="263.0711967848849" j="6" val="39"
                                                    barHeight="61.96666666666667" barWidth="14.910918036390234"></path>
                                                <g id="SvgjsG2026" class="apexcharts-bar-goals-markers"
                                                    style="pointer-events: none">
                                                    <g id="SvgjsG2027" className="apexcharts-bar-goals-groups"></g>
                                                    <g id="SvgjsG2029" className="apexcharts-bar-goals-groups"></g>
                                                    <g id="SvgjsG2031" className="apexcharts-bar-goals-groups"></g>
                                                    <g id="SvgjsG2033" className="apexcharts-bar-goals-groups"></g>
                                                    <g id="SvgjsG2035" className="apexcharts-bar-goals-groups"></g>
                                                    <g id="SvgjsG2037" className="apexcharts-bar-goals-groups"></g>
                                                    <g id="SvgjsG2039" className="apexcharts-bar-goals-groups"></g>
                                                </g>
                                            </g>
                                        </g>
                                        <g id="SvgjsG2041" class="apexcharts-line-series apexcharts-plot-series">
                                            <g id="SvgjsG2042" class="apexcharts-series" seriesName="Sales"
                                                data:longestSeries="true" rel="1" data:realIndex="1">
                                                <path id="SvgjsPath2058"
                                                    d="M 0 42.900000000000006L 42.60262296111495 82.62222222222222L 85.2052459222299 93.74444444444444L 127.80786888334487 71.5L 170.4104918444598 69.91111111111111L 213.0131148055748 100.1L 255.61573776668973 114.4"
                                                    fill="none" fill-opacity="1" stroke="rgba(102,108,255,0.85)"
                                                    stroke-opacity="1" stroke-linecap="butt" stroke-width="2"
                                                    stroke-dasharray="0" class="apexcharts-line" index="1"
                                                    clip-path="url(#gridRectMaskx1jvpdwc)"
                                                    pathTo="M 0 42.900000000000006L 42.60262296111495 82.62222222222222L 85.2052459222299 93.74444444444444L 127.80786888334487 71.5L 170.4104918444598 69.91111111111111L 213.0131148055748 100.1L 255.61573776668973 114.4"
                                                    pathFrom="M -1 143L -1 143L 42.60262296111495 143L 85.2052459222299 143L 127.80786888334487 143L 170.4104918444598 143L 213.0131148055748 143L 255.61573776668973 143">
                                                </path>
                                                <g id="SvgjsG2043" class="apexcharts-series-markers-wrap"
                                                    data:realIndex="1">
                                                    <g id="SvgjsG2045" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskx1jvpdwc)">
                                                        <circle id="SvgjsCircle2046" r="3.5" cx="0"
                                                            cy="42.900000000000006" class="apexcharts-marker w87fq8dznl"
                                                            stroke="#666cff" fill="#ffffff" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="1" rel="0" j="0"
                                                            index="1" default-marker-size="3.5"></circle>
                                                        <circle id="SvgjsCircle2047" r="3.5" cx="42.60262296111495"
                                                            cy="82.62222222222222" class="apexcharts-marker wu998j8wi"
                                                            stroke="#666cff" fill="#ffffff" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="1" rel="1" j="1"
                                                            index="1" default-marker-size="3.5"></circle>
                                                    </g>
                                                    <g id="SvgjsG2048" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskx1jvpdwc)">
                                                        <circle id="SvgjsCircle2049" r="3.5" cx="85.2052459222299"
                                                            cy="93.74444444444444" class="apexcharts-marker w49kmovl3"
                                                            stroke="#666cff" fill="#ffffff" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="1" rel="2" j="2"
                                                            index="1" default-marker-size="3.5"></circle>
                                                    </g>
                                                    <g id="SvgjsG2050" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskx1jvpdwc)">
                                                        <circle id="SvgjsCircle2051" r="3.5" cx="127.80786888334487"
                                                            cy="71.5" class="apexcharts-marker wwvptssl8"
                                                            stroke="#666cff" fill="#ffffff" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="1" rel="3" j="3"
                                                            index="1" default-marker-size="3.5"></circle>
                                                    </g>
                                                    <g id="SvgjsG2052" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskx1jvpdwc)">
                                                        <circle id="SvgjsCircle2053" r="3.5" cx="170.4104918444598"
                                                            cy="69.91111111111111" class="apexcharts-marker ws5vh43wl"
                                                            stroke="#666cff" fill="#ffffff" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="1" rel="4" j="4"
                                                            index="1" default-marker-size="3.5"></circle>
                                                    </g>
                                                    <g id="SvgjsG2054" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskx1jvpdwc)">
                                                        <circle id="SvgjsCircle2055" r="3.5" cx="213.0131148055748"
                                                            cy="100.1" class="apexcharts-marker wdqe55i1"
                                                            stroke="#666cff" fill="#ffffff" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="1" rel="5" j="5"
                                                            index="1" default-marker-size="3.5"></circle>
                                                    </g>
                                                    <g id="SvgjsG2056" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskx1jvpdwc)">
                                                        <circle id="SvgjsCircle2057" r="3.5" cx="255.61573776668973"
                                                            cy="114.4" class="apexcharts-marker w3942xshc"
                                                            stroke="#666cff" fill="#ffffff" fill-opacity="1"
                                                            stroke-width="2" stroke-opacity="1" rel="6" j="6"
                                                            index="1" default-marker-size="3.5"></circle>
                                                    </g>
                                                </g>
                                            </g>
                                            <g id="SvgjsG2025" class="apexcharts-datalabels" data:realIndex="0"></g>
                                            <g id="SvgjsG2044" class="apexcharts-datalabels" data:realIndex="1"></g>
                                        </g>
                                        <line id="SvgjsLine2087" x1="-16.200663640764027" y1="0"
                                            x2="271.8164014074538" y2="0" stroke="#b6b6b6"
                                            stroke-dasharray="0" stroke-width="1" stroke-linecap="butt"
                                            class="apexcharts-ycrosshairs"></line>
                                        <line id="SvgjsLine2088" x1="-16.200663640764027" y1="0"
                                            x2="271.8164014074538" y2="0" stroke-dasharray="0"
                                            stroke-width="0" stroke-linecap="butt"
                                            class="apexcharts-ycrosshairs-hidden"></line>
                                        <g id="SvgjsG2089" class="apexcharts-yaxis-annotations"></g>
                                        <g id="SvgjsG2090" class="apexcharts-xaxis-annotations"></g>
                                        <g id="SvgjsG2091" class="apexcharts-point-annotations"></g>
                                        <rect id="SvgjsRect2092" width="0" height="0" x="0" y="0"
                                            rx="0" ry="0" opacity="1" stroke-width="0"
                                            stroke="none" stroke-dasharray="0" fill="#fefefe"
                                            class="apexcharts-zoom-rect"></rect>
                                        <rect id="SvgjsRect2093" width="0" height="0" x="0" y="0"
                                            rx="0" ry="0" opacity="1" stroke-width="0"
                                            stroke="none" stroke-dasharray="0" fill="#fefefe"
                                            class="apexcharts-selection-rect"></rect>
                                    </g>
                                    <rect id="SvgjsRect2019" width="0" height="0" x="0" y="0"
                                        rx="0" ry="0" opacity="1" stroke-width="0"
                                        stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                                    <g id="SvgjsG2068" class="apexcharts-yaxis" rel="0"
                                        transform="translate(19.982934951782227, 0)">
                                        <g id="SvgjsG2069" class="apexcharts-yaxis-texts-g"><text id="SvgjsText2070"
                                                font-family="Inter" x="20" y="31.3" text-anchor="end"
                                                dominant-baseline="auto" font-size="0.75rem" font-weight="400"
                                                fill="#bbbcc4" class="apexcharts-text apexcharts-yaxis-label "
                                                style="font-family: Inter;">
                                                <tspan id="SvgjsTspan2071">90K</tspan>
                                                <title>90K</title>
                                            </text><text id="SvgjsText2072" font-family="Inter" x="20"
                                                y="78.96666666666665" text-anchor="end" dominant-baseline="auto"
                                                font-size="0.75rem" font-weight="400" fill="#bbbcc4"
                                                class="apexcharts-text apexcharts-yaxis-label "
                                                style="font-family: Inter;">
                                                <tspan id="SvgjsTspan2073">60K</tspan>
                                                <title>60K</title>
                                            </text><text id="SvgjsText2074" font-family="Inter" x="20"
                                                y="126.63333333333331" text-anchor="end" dominant-baseline="auto"
                                                font-size="0.75rem" font-weight="400" fill="#bbbcc4"
                                                class="apexcharts-text apexcharts-yaxis-label "
                                                style="font-family: Inter;">
                                                <tspan id="SvgjsTspan2075">30K</tspan>
                                                <title>30K</title>
                                            </text><text id="SvgjsText2076" font-family="Inter" x="20"
                                                y="174.29999999999998" text-anchor="end" dominant-baseline="auto"
                                                font-size="0.75rem" font-weight="400" fill="#bbbcc4"
                                                class="apexcharts-text apexcharts-yaxis-label "
                                                style="font-family: Inter;">
                                                <tspan id="SvgjsTspan2077">0K</tspan>
                                                <title>0K</title>
                                            </text></g>
                                    </g>
                                    <g id="SvgjsG2016" class="apexcharts-annotations"></g>
                                </svg>
                                <div class="apexcharts-legend" style="max-height: 89px;"></div>
                                <div class="apexcharts-tooltip apexcharts-theme-light">
                                    <div class="apexcharts-tooltip-title"
                                        style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                    <div class="apexcharts-tooltip-series-group" style="order: 1;"><span
                                            class="apexcharts-tooltip-marker"
                                            style="background-color: rgb(244, 244, 246);"></span>
                                        <div class="apexcharts-tooltip-text"
                                            style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                            <div class="apexcharts-tooltip-y-group"><span
                                                    class="apexcharts-tooltip-text-y-label"></span><span
                                                    class="apexcharts-tooltip-text-y-value"></span></div>
                                            <div class="apexcharts-tooltip-goals-group"><span
                                                    class="apexcharts-tooltip-text-goals-label"></span><span
                                                    class="apexcharts-tooltip-text-goals-value"></span></div>
                                            <div class="apexcharts-tooltip-z-group"><span
                                                    class="apexcharts-tooltip-text-z-label"></span><span
                                                    class="apexcharts-tooltip-text-z-value"></span></div>
                                        </div>
                                    </div>
                                    <div class="apexcharts-tooltip-series-group" style="order: 2;"><span
                                            class="apexcharts-tooltip-marker"
                                            style="background-color: rgb(244, 244, 246);"></span>
                                        <div class="apexcharts-tooltip-text"
                                            style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                            <div class="apexcharts-tooltip-y-group"><span
                                                    class="apexcharts-tooltip-text-y-label"></span><span
                                                    class="apexcharts-tooltip-text-y-value"></span></div>
                                            <div class="apexcharts-tooltip-goals-group"><span
                                                    class="apexcharts-tooltip-text-goals-label"></span><span
                                                    class="apexcharts-tooltip-text-goals-value"></span></div>
                                            <div class="apexcharts-tooltip-z-group"><span
                                                    class="apexcharts-tooltip-text-z-label"></span><span
                                                    class="apexcharts-tooltip-text-z-value"></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light">
                                    <div class="apexcharts-xaxistooltip-text"
                                        style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                </div>
                                <div
                                    class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                    <div class="apexcharts-yaxistooltip-text"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <div class="d-flex align-items-center gap-3">
                                <h3 class="mb-0">62%</h3>
                                <p class="mb-0 text-muted">Your sales performance is 35% 😎 better compared to last month
                                </p>
                            </div>
                            <div class="d-grid mt-3">
                                <button class="btn btn-primary waves-effect waves-light" type="button">Details</button>
                            </div>
                        </div>
                        <div class="resize-triggers">
                            <div class="expand-trigger">
                                <div style="width: 389px; height: 303px;"></div>
                            </div>
                            <div class="contract-trigger"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Weekly Overview Chart -->

            <!-- Social Network Visits -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Social Network Visits</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="socialNetworkList" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="socialNetworkList">
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last 28 Days</a>
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Month</a>
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Year</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <h4 class="mb-0">28,468</h4>
                                <span class="text-success ms-2 fw-semibold">
                                    <i class="mdi mdi-menu-up"></i>
                                    <small>62%</small>
                                </span>
                            </div>
                            <small class="text-muted">Last 1 Year Visits</small>
                        </div>
                        <ul class="p-0 m-0">
                            <li class="d-flex pb-1 mb-3">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('assets') }}/img/icons/brands/facebook-rounded.png" alt="facebook"
                                        class="me-3" height="34">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Facebook</h6>
                                        <small class="text-muted">Social Media</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold text-heading">12,348</span>
                                        <div class="ms-3 badge bg-label-success rounded-pill">+12%</div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex pb-1 mb-3">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('assets') }}/img/icons/brands/dribbble-rounded.png" alt="dribbble"
                                        class="me-3" height="34">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Dribbble</h6>
                                        <small class="text-muted">Community</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold text-heading">8,450</span>
                                        <div class="ms-3 badge bg-label-success rounded-pill">+32%</div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex pb-1 mb-3">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('assets') }}/img/icons/brands/twitter-rounded.png" alt="facebook"
                                        class="me-3" height="34">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Twitter</h6>
                                        <small class="text-muted">Social Media</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold text-heading">350</span>
                                        <div class="ms-3 badge bg-label-danger rounded-pill">-18%</div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex pb-1">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('assets') }}/img/icons/brands/instagram-rounded.png" alt="instagram"
                                        class="me-3" height="34">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Instagram</h6>
                                        <small class="text-muted">Social Media</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold text-heading">25,566</span>
                                        <div class="ms-3 badge bg-label-success rounded-pill">+42%</div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Social Network Visits -->

            <!-- Monthly Budget Chart-->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card h-100">
                    <div class="card-header pb-1">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">Monthly Budget</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="monthlyBudgetDropdown"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="monthlyBudgetDropdown">
                                    <a class="dropdown-item waves-effect" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item waves-effect" href="javascript:void(0);">Update</a>
                                    <a class="dropdown-item waves-effect" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="position: relative;">
                        <div id="monthlyBudgetChart" style="min-height: 235px;">
                            <div id="apexchartsrza8p8v4"
                                class="apexcharts-canvas apexchartsrza8p8v4 apexcharts-theme-light"
                                style="width: 348px; height: 235px;"><svg id="SvgjsSvg2284" width="348"
                                    height="235" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                    class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS"
                                    transform="translate(0, -8)" style="background: transparent;">
                                    <g id="SvgjsG2286" class="apexcharts-inner apexcharts-graphical"
                                        transform="translate(10, 30)">
                                        <defs id="SvgjsDefs2285">
                                            <clipPath id="gridRectMaskrza8p8v4">
                                                <rect id="SvgjsRect2291" width="335" height="195" x="-4.5"
                                                    y="-2.5" rx="0" ry="0" opacity="1"
                                                    stroke-width="0" stroke="none" stroke-dasharray="0"
                                                    fill="#fff"></rect>
                                            </clipPath>
                                            <clipPath id="forecastMaskrza8p8v4"></clipPath>
                                            <clipPath id="nonForecastMaskrza8p8v4"></clipPath>
                                            <clipPath id="gridRectMarkerMaskrza8p8v4">
                                                <rect id="SvgjsRect2292" width="334" height="198" x="-4" y="-4"
                                                    rx="0" ry="0" opacity="1" stroke-width="0"
                                                    stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                            </clipPath>
                                            <linearGradient id="SvgjsLinearGradient2312" x1="0" y1="0"
                                                x2="0" y2="1">
                                                <stop id="SvgjsStop2313" stop-opacity="0.6" stop-color="#72e128"
                                                    offset="0"></stop>
                                                <stop id="SvgjsStop2314" stop-opacity="0.1" stop-color="#ffffff"
                                                    offset="1"></stop>
                                            </linearGradient>
                                        </defs>
                                        <line id="SvgjsLine2290" x1="0" y1="0" x2="0"
                                            y2="190" stroke="#b6b6b6" stroke-dasharray="3"
                                            stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0"
                                            width="1" height="190" fill="#b1b9c4" filter="none"
                                            fill-opacity="0.9" stroke-width="1"></line>
                                        <g id="SvgjsG2317" class="apexcharts-xaxis" transform="translate(0, 0)">
                                            <g id="SvgjsG2318" class="apexcharts-xaxis-texts-g"
                                                transform="translate(0, -4)"></g>
                                        </g>
                                        <g id="SvgjsG2326" class="apexcharts-grid">
                                            <g id="SvgjsG2327" class="apexcharts-gridlines-horizontal"
                                                style="display: none;">
                                                <line id="SvgjsLine2329" x1="0" y1="0"
                                                    x2="326" y2="0" stroke="#e0e0e0"
                                                    stroke-dasharray="0" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2330" x1="0" y1="47.5"
                                                    x2="326" y2="47.5" stroke="#e0e0e0"
                                                    stroke-dasharray="0" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2331" x1="0" y1="95"
                                                    x2="326" y2="95" stroke="#e0e0e0"
                                                    stroke-dasharray="0" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2332" x1="0" y1="142.5"
                                                    x2="326" y2="142.5" stroke="#e0e0e0"
                                                    stroke-dasharray="0" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2333" x1="0" y1="190"
                                                    x2="326" y2="190" stroke="#e0e0e0"
                                                    stroke-dasharray="0" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                            </g>
                                            <g id="SvgjsG2328" class="apexcharts-gridlines-vertical"
                                                style="display: none;"></g>
                                            <line id="SvgjsLine2335" x1="0" y1="190" x2="326"
                                                y2="190" stroke="transparent" stroke-dasharray="0"
                                                stroke-linecap="butt"></line>
                                            <line id="SvgjsLine2334" x1="0" y1="1" x2="0"
                                                y2="190" stroke="transparent" stroke-dasharray="0"
                                                stroke-linecap="butt"></line>
                                        </g>
                                        <g id="SvgjsG2293" class="apexcharts-area-series apexcharts-plot-series">
                                            <g id="SvgjsG2294" class="apexcharts-series" seriesName="seriesx1"
                                                data:longestSeries="true" rel="1" data:realIndex="0">
                                                <path id="SvgjsPath2315"
                                                    d="M 0 190L 0 190C 16.299999999999997 190 30.271428571428572 145.13888888888889 46.57142857142857 145.13888888888889C 62.87142857142857 145.13888888888889 76.84285714285714 176.80555555555554 93.14285714285714 176.80555555555554C 109.44285714285714 176.80555555555554 123.4142857142857 124.02777777777777 139.7142857142857 124.02777777777777C 156.0142857142857 124.02777777777777 169.98571428571427 142.5 186.28571428571428 142.5C 202.58571428571426 142.5 216.55714285714285 58.05555555555554 232.85714285714283 58.05555555555554C 249.15714285714282 58.05555555555554 263.1285714285714 84.44444444444444 279.4285714285714 84.44444444444444C 295.7285714285714 84.44444444444444 309.7 5.2777777777777715 326 5.2777777777777715C 326 5.2777777777777715 326 5.2777777777777715 326 190M 326 5.2777777777777715z"
                                                    fill="url(#SvgjsLinearGradient2312)" fill-opacity="1"
                                                    stroke-opacity="1" stroke-linecap="butt" stroke-width="0"
                                                    stroke-dasharray="0" class="apexcharts-area" index="0"
                                                    clip-path="url(#gridRectMaskrza8p8v4)"
                                                    pathTo="M 0 190L 0 190C 16.299999999999997 190 30.271428571428572 145.13888888888889 46.57142857142857 145.13888888888889C 62.87142857142857 145.13888888888889 76.84285714285714 176.80555555555554 93.14285714285714 176.80555555555554C 109.44285714285714 176.80555555555554 123.4142857142857 124.02777777777777 139.7142857142857 124.02777777777777C 156.0142857142857 124.02777777777777 169.98571428571427 142.5 186.28571428571428 142.5C 202.58571428571426 142.5 216.55714285714285 58.05555555555554 232.85714285714283 58.05555555555554C 249.15714285714282 58.05555555555554 263.1285714285714 84.44444444444444 279.4285714285714 84.44444444444444C 295.7285714285714 84.44444444444444 309.7 5.2777777777777715 326 5.2777777777777715C 326 5.2777777777777715 326 5.2777777777777715 326 190M 326 5.2777777777777715z"
                                                    pathFrom="M -1 190L -1 190L 46.57142857142857 190L 93.14285714285714 190L 139.7142857142857 190L 186.28571428571428 190L 232.85714285714283 190L 279.4285714285714 190L 326 190">
                                                </path>
                                                <path id="SvgjsPath2316"
                                                    d="M 0 190C 16.299999999999997 190 30.271428571428572 145.13888888888889 46.57142857142857 145.13888888888889C 62.87142857142857 145.13888888888889 76.84285714285714 176.80555555555554 93.14285714285714 176.80555555555554C 109.44285714285714 176.80555555555554 123.4142857142857 124.02777777777777 139.7142857142857 124.02777777777777C 156.0142857142857 124.02777777777777 169.98571428571427 142.5 186.28571428571428 142.5C 202.58571428571426 142.5 216.55714285714285 58.05555555555554 232.85714285714283 58.05555555555554C 249.15714285714282 58.05555555555554 263.1285714285714 84.44444444444444 279.4285714285714 84.44444444444444C 295.7285714285714 84.44444444444444 309.7 5.2777777777777715 326 5.2777777777777715"
                                                    fill="none" fill-opacity="1" stroke="#72e128"
                                                    stroke-opacity="1" stroke-linecap="butt" stroke-width="5"
                                                    stroke-dasharray="0" class="apexcharts-area" index="0"
                                                    clip-path="url(#gridRectMaskrza8p8v4)"
                                                    pathTo="M 0 190C 16.299999999999997 190 30.271428571428572 145.13888888888889 46.57142857142857 145.13888888888889C 62.87142857142857 145.13888888888889 76.84285714285714 176.80555555555554 93.14285714285714 176.80555555555554C 109.44285714285714 176.80555555555554 123.4142857142857 124.02777777777777 139.7142857142857 124.02777777777777C 156.0142857142857 124.02777777777777 169.98571428571427 142.5 186.28571428571428 142.5C 202.58571428571426 142.5 216.55714285714285 58.05555555555554 232.85714285714283 58.05555555555554C 249.15714285714282 58.05555555555554 263.1285714285714 84.44444444444444 279.4285714285714 84.44444444444444C 295.7285714285714 84.44444444444444 309.7 5.2777777777777715 326 5.2777777777777715"
                                                    pathFrom="M -1 190L -1 190L 46.57142857142857 190L 93.14285714285714 190L 139.7142857142857 190L 186.28571428571428 190L 232.85714285714283 190L 279.4285714285714 190L 326 190">
                                                </path>
                                                <g id="SvgjsG2295" class="apexcharts-series-markers-wrap"
                                                    data:realIndex="0">
                                                    <g id="SvgjsG2297" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskrza8p8v4)">
                                                        <circle id="SvgjsCircle2298" r="1" cx="-5"
                                                            cy="191"
                                                            class="apexcharts-marker no-pointer-events wf4pqp0qyj"
                                                            stroke="transparent" fill="transparent" fill-opacity="1"
                                                            stroke-width="4" stroke-opacity="1" rel="0" j="0"
                                                            index="0" default-marker-size="1"></circle>
                                                        <circle id="SvgjsCircle2299" r="1" cx="41.57142857142857"
                                                            cy="146.13888888888889"
                                                            class="apexcharts-marker no-pointer-events w2hur6f05"
                                                            stroke="transparent" fill="transparent" fill-opacity="1"
                                                            stroke-width="4" stroke-opacity="1" rel="1" j="1"
                                                            index="0" default-marker-size="1"></circle>
                                                    </g>
                                                    <g id="SvgjsG2300" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskrza8p8v4)">
                                                        <circle id="SvgjsCircle2301" r="1" cx="88.14285714285714"
                                                            cy="177.80555555555554"
                                                            class="apexcharts-marker no-pointer-events wtek19ecg"
                                                            stroke="transparent" fill="transparent" fill-opacity="1"
                                                            stroke-width="4" stroke-opacity="1" rel="2" j="2"
                                                            index="0" default-marker-size="1"></circle>
                                                    </g>
                                                    <g id="SvgjsG2302" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskrza8p8v4)">
                                                        <circle id="SvgjsCircle2303" r="1" cx="134.7142857142857"
                                                            cy="125.02777777777777"
                                                            class="apexcharts-marker no-pointer-events w5b4tqet3k"
                                                            stroke="transparent" fill="transparent" fill-opacity="1"
                                                            stroke-width="4" stroke-opacity="1" rel="3" j="3"
                                                            index="0" default-marker-size="1"></circle>
                                                    </g>
                                                    <g id="SvgjsG2304" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskrza8p8v4)">
                                                        <circle id="SvgjsCircle2305" r="1" cx="181.28571428571428"
                                                            cy="143.5"
                                                            class="apexcharts-marker no-pointer-events wgypndpiw"
                                                            stroke="transparent" fill="transparent" fill-opacity="1"
                                                            stroke-width="4" stroke-opacity="1" rel="4" j="4"
                                                            index="0" default-marker-size="1"></circle>
                                                    </g>
                                                    <g id="SvgjsG2306" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskrza8p8v4)">
                                                        <circle id="SvgjsCircle2307" r="1" cx="227.85714285714283"
                                                            cy="59.05555555555554"
                                                            class="apexcharts-marker no-pointer-events wafi92p6h"
                                                            stroke="transparent" fill="transparent" fill-opacity="1"
                                                            stroke-width="4" stroke-opacity="1" rel="5" j="5"
                                                            index="0" default-marker-size="1"></circle>
                                                    </g>
                                                    <g id="SvgjsG2308" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskrza8p8v4)">
                                                        <circle id="SvgjsCircle2309" r="1" cx="274.4285714285714"
                                                            cy="85.44444444444444"
                                                            class="apexcharts-marker no-pointer-events wyj9hyr1yk"
                                                            stroke="transparent" fill="transparent" fill-opacity="1"
                                                            stroke-width="4" stroke-opacity="1" rel="6" j="6"
                                                            index="0" default-marker-size="1"></circle>
                                                    </g>
                                                    <g id="SvgjsG2310" class="apexcharts-series-markers"
                                                        clip-path="url(#gridRectMarkerMaskrza8p8v4)">
                                                        <circle id="SvgjsCircle2311" r="7" cx="321"
                                                            cy="6.2777777777777715"
                                                            class="apexcharts-marker no-pointer-events wnnpn5p21"
                                                            stroke="#72e128" fill="#ffffff" fill-opacity="1"
                                                            stroke-width="4" stroke-opacity="1" rel="7" j="7"
                                                            index="0" default-marker-size="7"></circle>
                                                    </g>
                                                </g>
                                            </g>
                                            <g id="SvgjsG2296" class="apexcharts-datalabels" data:realIndex="0"></g>
                                        </g>
                                        <line id="SvgjsLine2336" x1="0" y1="0" x2="326"
                                            y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                            stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                        <line id="SvgjsLine2337" x1="0" y1="0" x2="326"
                                            y2="0" stroke-dasharray="0" stroke-width="0"
                                            stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                        <g id="SvgjsG2338" class="apexcharts-yaxis-annotations"></g>
                                        <g id="SvgjsG2339" class="apexcharts-xaxis-annotations"></g>
                                        <g id="SvgjsG2340" class="apexcharts-point-annotations"></g>
                                        <rect id="SvgjsRect2341" width="0" height="0" x="0" y="0"
                                            rx="0" ry="0" opacity="1" stroke-width="0"
                                            stroke="none" stroke-dasharray="0" fill="#fefefe"
                                            class="apexcharts-zoom-rect"></rect>
                                        <rect id="SvgjsRect2342" width="0" height="0" x="0" y="0"
                                            rx="0" ry="0" opacity="1" stroke-width="0"
                                            stroke="none" stroke-dasharray="0" fill="#fefefe"
                                            class="apexcharts-selection-rect"></rect>
                                    </g>
                                    <rect id="SvgjsRect2289" width="0" height="0" x="0" y="0"
                                        rx="0" ry="0" opacity="1" stroke-width="0"
                                        stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                                    <g id="SvgjsG2325" class="apexcharts-yaxis" rel="0"
                                        transform="translate(-18, 0)"></g>
                                    <g id="SvgjsG2287" class="apexcharts-annotations"></g>
                                </svg>
                                <div class="apexcharts-legend" style="max-height: 117.5px;"></div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="mb-0 text-muted">
                                Last month you had $2.42 expense transactions, 12 savings entries and 4 bills.
                            </p>
                        </div>
                        <div class="resize-triggers">
                            <div class="expand-trigger">
                                <div style="width: 389px; height: 323px;"></div>
                            </div>
                            <div class="contract-trigger"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Monthly Budget Chart-->

            <!-- Meeting Schedule -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 me-2">Meeting Schedule</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="meetingSchedule" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="meetingSchedule">
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last 28 Days</a>
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Month</a>
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Year</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{ asset('assets') }}/img/avatars/4.png" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">Call with Woods</h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>21 Jul | 08:20-10:30</span>
                                        </small>
                                    </div>
                                    <div class="badge bg-label-primary rounded-pill">Business</div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{ asset('assets') }}/img/avatars/5.png" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">Conference call</h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>21 Jul | 08:20-10:30</span>
                                        </small>
                                    </div>
                                    <div class="badge bg-label-warning rounded-pill">Dinner</div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{ asset('assets') }}/img/avatars/3.png" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">Meeting with Mark</h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>21 Jul | 08:20-10:30</span>
                                        </small>
                                    </div>
                                    <div class="badge bg-label-secondary rounded-pill">Meetup</div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{ asset('assets') }}/img/avatars/14.png" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">Meeting in Oakland</h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>21 Jul | 08:20-10:30</span>
                                        </small>
                                    </div>
                                    <div class="badge bg-label-danger rounded-pill">Dinner</div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{ asset('assets') }}/img/avatars/8.png" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">Call with hilda</h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>21 Jul | 08:20-10:30</span>
                                        </small>
                                    </div>
                                    <div class="badge bg-label-success rounded-pill">Meditation</div>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{ asset('assets') }}/img/avatars/1.png" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">Meeting with Carl</h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>21 Jul | 08:20-10:30</span>
                                        </small>
                                    </div>
                                    <div class="badge bg-label-primary rounded-pill">Business</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Meeting Schedule -->

            <!-- External Links Chart -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">External Links</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="externalLinksDropdown"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical mdi-24px"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="externalLinksDropdown">
                                    <a class="dropdown-item waves-effect" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item waves-effect" href="javascript:void(0);">Update</a>
                                    <a class="dropdown-item waves-effect" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="position: relative;">
                        <div id="externalLinksChart" style="min-height: 330px;">
                            <div id="apexchartsd0amupf0h"
                                class="apexcharts-canvas apexchartsd0amupf0h apexcharts-theme-light"
                                style="width: 348px; height: 330px;"><svg id="SvgjsSvg2153" width="348"
                                    height="330" xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
                                    class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                    style="background: transparent;">
                                    <g id="SvgjsG2155" class="apexcharts-inner apexcharts-graphical"
                                        transform="translate(-4, 18)">
                                        <defs id="SvgjsDefs2154">
                                            <linearGradient id="SvgjsLinearGradient2158" x1="0" y1="0"
                                                x2="0" y2="1">
                                                <stop id="SvgjsStop2159" stop-opacity="0.4"
                                                    stop-color="rgba(216,227,240,0.4)" offset="0"></stop>
                                                <stop id="SvgjsStop2160" stop-opacity="0.5"
                                                    stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                                <stop id="SvgjsStop2161" stop-opacity="0.5"
                                                    stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                            </linearGradient>
                                            <clipPath id="gridRectMaskd0amupf0h">
                                                <rect id="SvgjsRect2163" width="367" height="298" x="-5" y="-3"
                                                    rx="0" ry="0" opacity="1" stroke-width="0"
                                                    stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                            </clipPath>
                                            <clipPath id="forecastMaskd0amupf0h"></clipPath>
                                            <clipPath id="nonForecastMaskd0amupf0h"></clipPath>
                                            <clipPath id="gridRectMarkerMaskd0amupf0h">
                                                <rect id="SvgjsRect2164" width="361" height="296" x="-2" y="-2"
                                                    rx="0" ry="0" opacity="1" stroke-width="0"
                                                    stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                            </clipPath>
                                        </defs>
                                        <rect id="SvgjsRect2162" width="0" height="292" x="0" y="0"
                                            rx="0" ry="0" opacity="1" stroke-width="0"
                                            stroke-dasharray="3" fill="url(#SvgjsLinearGradient2158)"
                                            class="apexcharts-xcrosshairs" y2="292" filter="none"
                                            fill-opacity="0.9"></rect>
                                        <g id="SvgjsG2184" class="apexcharts-xaxis" transform="translate(0, 0)">
                                            <g id="SvgjsG2185" class="apexcharts-xaxis-texts-g"
                                                transform="translate(0, -4)"></g>
                                        </g>
                                        <g id="SvgjsG2194" class="apexcharts-grid">
                                            <g id="SvgjsG2195" class="apexcharts-gridlines-horizontal">
                                                <line id="SvgjsLine2197" x1="0" y1="0"
                                                    x2="357" y2="0" stroke="#eaeaec"
                                                    stroke-dasharray="10" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2198" x1="0" y1="58.4"
                                                    x2="357" y2="58.4" stroke="#eaeaec"
                                                    stroke-dasharray="10" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2199" x1="0" y1="116.8"
                                                    x2="357" y2="116.8" stroke="#eaeaec"
                                                    stroke-dasharray="10" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2200" x1="0" y1="175.2"
                                                    x2="357" y2="175.2" stroke="#eaeaec"
                                                    stroke-dasharray="10" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2201" x1="0" y1="233.6"
                                                    x2="357" y2="233.6" stroke="#eaeaec"
                                                    stroke-dasharray="10" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                                <line id="SvgjsLine2202" x1="0" y1="292"
                                                    x2="357" y2="292" stroke="#eaeaec"
                                                    stroke-dasharray="10" stroke-linecap="butt"
                                                    class="apexcharts-gridline"></line>
                                            </g>
                                            <g id="SvgjsG2196" class="apexcharts-gridlines-vertical"></g>
                                            <line id="SvgjsLine2204" x1="0" y1="292" x2="357"
                                                y2="292" stroke="transparent" stroke-dasharray="0"
                                                stroke-linecap="butt"></line>
                                            <line id="SvgjsLine2203" x1="0" y1="1" x2="0"
                                                y2="292" stroke="transparent" stroke-dasharray="0"
                                                stroke-linecap="butt"></line>
                                        </g>
                                        <g id="SvgjsG2165" class="apexcharts-bar-series apexcharts-plot-series">
                                            <g id="SvgjsG2166" class="apexcharts-series" seriesName="GooglexAnalytics"
                                                rel="1" data:realIndex="0">
                                                <path id="SvgjsPath2168"
                                                    d="M 15.3 282L 15.3 211.48000000000002Q 15.3 201.48000000000002 25.3 201.48000000000002L 19.700000000000003 201.48000000000002Q 29.700000000000003 201.48000000000002 29.700000000000003 211.48000000000002L 29.700000000000003 211.48000000000002L 29.700000000000003 282Q 29.700000000000003 292 19.700000000000003 292L 25.3 292Q 15.3 292 15.3 282z"
                                                    fill="rgba(102,108,255,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 15.3 282L 15.3 211.48000000000002Q 15.3 201.48000000000002 25.3 201.48000000000002L 19.700000000000003 201.48000000000002Q 29.700000000000003 201.48000000000002 29.700000000000003 211.48000000000002L 29.700000000000003 211.48000000000002L 29.700000000000003 282Q 29.700000000000003 292 19.700000000000003 292L 25.3 292Q 15.3 292 15.3 282z"
                                                    pathFrom="M 15.3 282L 15.3 282L 29.700000000000003 282L 29.700000000000003 282L 29.700000000000003 282L 29.700000000000003 282L 29.700000000000003 282L 15.3 282"
                                                    cy="201.48000000000002" cx="63.3" j="0" val="155"
                                                    barHeight="90.52" barWidth="20.4"></path>
                                                <path id="SvgjsPath2169"
                                                    d="M 66.3 282L 66.3 223.16Q 66.3 213.16 76.3 213.16L 70.69999999999999 213.16Q 80.69999999999999 213.16 80.69999999999999 223.16L 80.69999999999999 223.16L 80.69999999999999 282Q 80.69999999999999 292 70.69999999999999 292L 76.3 292Q 66.3 292 66.3 282z"
                                                    fill="rgba(102,108,255,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 66.3 282L 66.3 223.16Q 66.3 213.16 76.3 213.16L 70.69999999999999 213.16Q 80.69999999999999 213.16 80.69999999999999 223.16L 80.69999999999999 223.16L 80.69999999999999 282Q 80.69999999999999 292 70.69999999999999 292L 76.3 292Q 66.3 292 66.3 282z"
                                                    pathFrom="M 66.3 282L 66.3 282L 80.69999999999999 282L 80.69999999999999 282L 80.69999999999999 282L 80.69999999999999 282L 80.69999999999999 282L 66.3 282"
                                                    cy="213.16" cx="114.3" j="1" val="135"
                                                    barHeight="78.84" barWidth="20.4"></path>
                                                <path id="SvgjsPath2170"
                                                    d="M 117.3 282L 117.3 115.12Q 117.3 105.12 127.3 105.12L 121.69999999999999 105.12Q 131.7 105.12 131.7 115.12L 131.7 115.12L 131.7 282Q 131.7 292 121.69999999999999 292L 127.3 292Q 117.3 292 117.3 282z"
                                                    fill="rgba(102,108,255,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 117.3 282L 117.3 115.12Q 117.3 105.12 127.3 105.12L 121.69999999999999 105.12Q 131.7 105.12 131.7 115.12L 131.7 115.12L 131.7 282Q 131.7 292 121.69999999999999 292L 127.3 292Q 117.3 292 117.3 282z"
                                                    pathFrom="M 117.3 282L 117.3 282L 131.7 282L 131.7 282L 131.7 282L 131.7 282L 131.7 282L 117.3 282"
                                                    cy="105.12" cx="165.3" j="2" val="320"
                                                    barHeight="186.88" barWidth="20.4"></path>
                                                <path id="SvgjsPath2171"
                                                    d="M 168.3 282L 168.3 243.6Q 168.3 233.6 178.3 233.6L 172.70000000000002 233.6Q 182.70000000000002 233.6 182.70000000000002 243.6L 182.70000000000002 243.6L 182.70000000000002 282Q 182.70000000000002 292 172.70000000000002 292L 178.3 292Q 168.3 292 168.3 282z"
                                                    fill="rgba(102,108,255,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 168.3 282L 168.3 243.6Q 168.3 233.6 178.3 233.6L 172.70000000000002 233.6Q 182.70000000000002 233.6 182.70000000000002 243.6L 182.70000000000002 243.6L 182.70000000000002 282Q 182.70000000000002 292 172.70000000000002 292L 178.3 292Q 168.3 292 168.3 282z"
                                                    pathFrom="M 168.3 282L 168.3 282L 182.70000000000002 282L 182.70000000000002 282L 182.70000000000002 282L 182.70000000000002 282L 182.70000000000002 282L 168.3 282"
                                                    cy="233.6" cx="216.3" j="3" val="100"
                                                    barHeight="58.4" barWidth="20.4"></path>
                                                <path id="SvgjsPath2172"
                                                    d="M 219.3 282L 219.3 214.39999999999998Q 219.3 204.39999999999998 229.3 204.39999999999998L 223.70000000000002 204.39999999999998Q 233.70000000000002 204.39999999999998 233.70000000000002 214.39999999999998L 233.70000000000002 214.39999999999998L 233.70000000000002 282Q 233.70000000000002 292 223.70000000000002 292L 229.3 292Q 219.3 292 219.3 282z"
                                                    fill="rgba(102,108,255,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 219.3 282L 219.3 214.39999999999998Q 219.3 204.39999999999998 229.3 204.39999999999998L 223.70000000000002 204.39999999999998Q 233.70000000000002 204.39999999999998 233.70000000000002 214.39999999999998L 233.70000000000002 214.39999999999998L 233.70000000000002 282Q 233.70000000000002 292 223.70000000000002 292L 229.3 292Q 219.3 292 219.3 282z"
                                                    pathFrom="M 219.3 282L 219.3 282L 233.70000000000002 282L 233.70000000000002 282L 233.70000000000002 282L 233.70000000000002 282L 233.70000000000002 282L 219.3 282"
                                                    cy="204.39999999999998" cx="267.3" j="4" val="150"
                                                    barHeight="87.60000000000001" barWidth="20.4"></path>
                                                <path id="SvgjsPath2173"
                                                    d="M 270.3 282L 270.3 106.35999999999999Q 270.3 96.35999999999999 280.3 96.35999999999999L 274.7 96.35999999999999Q 284.7 96.35999999999999 284.7 106.35999999999999L 284.7 106.35999999999999L 284.7 282Q 284.7 292 274.7 292L 280.3 292Q 270.3 292 270.3 282z"
                                                    fill="rgba(102,108,255,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 270.3 282L 270.3 106.35999999999999Q 270.3 96.35999999999999 280.3 96.35999999999999L 274.7 96.35999999999999Q 284.7 96.35999999999999 284.7 106.35999999999999L 284.7 106.35999999999999L 284.7 282Q 284.7 292 274.7 292L 280.3 292Q 270.3 292 270.3 282z"
                                                    pathFrom="M 270.3 282L 270.3 282L 284.7 282L 284.7 282L 284.7 282L 284.7 282L 284.7 282L 270.3 282"
                                                    cy="96.35999999999999" cx="318.3" j="5" val="335"
                                                    barHeight="195.64000000000001" barWidth="20.4"></path>
                                                <path id="SvgjsPath2174"
                                                    d="M 321.3 282L 321.3 208.56Q 321.3 198.56 331.3 198.56L 325.7 198.56Q 335.7 198.56 335.7 208.56L 335.7 208.56L 335.7 282Q 335.7 292 325.7 292L 331.3 292Q 321.3 292 321.3 282z"
                                                    fill="rgba(102,108,255,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="0"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 321.3 282L 321.3 208.56Q 321.3 198.56 331.3 198.56L 325.7 198.56Q 335.7 198.56 335.7 208.56L 335.7 208.56L 335.7 282Q 335.7 292 325.7 292L 331.3 292Q 321.3 292 321.3 282z"
                                                    pathFrom="M 321.3 282L 321.3 282L 335.7 282L 335.7 282L 335.7 282L 335.7 282L 335.7 282L 321.3 282"
                                                    cy="198.56" cx="369.3" j="6" val="160"
                                                    barHeight="93.44" barWidth="20.4"></path>
                                            </g>
                                            <g id="SvgjsG2175" class="apexcharts-series" seriesName="FacebookxAds"
                                                rel="2" data:realIndex="1">
                                                <path id="SvgjsPath2177"
                                                    d="M 15.3 191.48000000000002L 15.3 147.24Q 15.3 137.24 25.3 137.24L 19.700000000000003 137.24Q 29.700000000000003 137.24 29.700000000000003 147.24L 29.700000000000003 147.24L 29.700000000000003 191.48000000000002Q 29.700000000000003 201.48000000000002 19.700000000000003 201.48000000000002L 25.3 201.48000000000002Q 15.3 201.48000000000002 15.3 191.48000000000002z"
                                                    fill="rgba(109,120,141,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="1"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 15.3 191.48000000000002L 15.3 147.24Q 15.3 137.24 25.3 137.24L 19.700000000000003 137.24Q 29.700000000000003 137.24 29.700000000000003 147.24L 29.700000000000003 147.24L 29.700000000000003 191.48000000000002Q 29.700000000000003 201.48000000000002 19.700000000000003 201.48000000000002L 25.3 201.48000000000002Q 15.3 201.48000000000002 15.3 191.48000000000002z"
                                                    pathFrom="M 15.3 191.48000000000002L 15.3 191.48000000000002L 29.700000000000003 191.48000000000002L 29.700000000000003 191.48000000000002L 29.700000000000003 191.48000000000002L 29.700000000000003 191.48000000000002L 29.700000000000003 191.48000000000002L 15.3 191.48000000000002"
                                                    cy="137.24" cx="63.3" j="0" val="110"
                                                    barHeight="64.24" barWidth="20.4"></path>
                                                <path id="SvgjsPath2178"
                                                    d="M 66.3 203.16L 66.3 85.91999999999999Q 66.3 75.91999999999999 76.3 75.91999999999999L 70.69999999999999 75.91999999999999Q 80.69999999999999 75.91999999999999 80.69999999999999 85.91999999999999L 80.69999999999999 85.91999999999999L 80.69999999999999 203.16Q 80.69999999999999 213.16 70.69999999999999 213.16L 76.3 213.16Q 66.3 213.16 66.3 203.16z"
                                                    fill="rgba(109,120,141,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="1"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 66.3 203.16L 66.3 85.91999999999999Q 66.3 75.91999999999999 76.3 75.91999999999999L 70.69999999999999 75.91999999999999Q 80.69999999999999 75.91999999999999 80.69999999999999 85.91999999999999L 80.69999999999999 85.91999999999999L 80.69999999999999 203.16Q 80.69999999999999 213.16 70.69999999999999 213.16L 76.3 213.16Q 66.3 213.16 66.3 203.16z"
                                                    pathFrom="M 66.3 203.16L 66.3 203.16L 80.69999999999999 203.16L 80.69999999999999 203.16L 80.69999999999999 203.16L 80.69999999999999 203.16L 80.69999999999999 203.16L 66.3 203.16"
                                                    cy="75.91999999999999" cx="114.3" j="1" val="235"
                                                    barHeight="137.24" barWidth="20.4"></path>
                                                <path id="SvgjsPath2179"
                                                    d="M 117.3 95.12L 117.3 42.120000000000005Q 117.3 32.120000000000005 127.3 32.120000000000005L 121.69999999999999 32.120000000000005Q 131.7 32.120000000000005 131.7 42.120000000000005L 131.7 42.120000000000005L 131.7 95.12Q 131.7 105.12 121.69999999999999 105.12L 127.3 105.12Q 117.3 105.12 117.3 95.12z"
                                                    fill="rgba(109,120,141,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="1"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 117.3 95.12L 117.3 42.120000000000005Q 117.3 32.120000000000005 127.3 32.120000000000005L 121.69999999999999 32.120000000000005Q 131.7 32.120000000000005 131.7 42.120000000000005L 131.7 42.120000000000005L 131.7 95.12Q 131.7 105.12 121.69999999999999 105.12L 127.3 105.12Q 117.3 105.12 117.3 95.12z"
                                                    pathFrom="M 117.3 95.12L 117.3 95.12L 131.7 95.12L 131.7 95.12L 131.7 95.12L 131.7 95.12L 131.7 95.12L 117.3 95.12"
                                                    cy="32.120000000000005" cx="165.3" j="2" val="125"
                                                    barHeight="73" barWidth="20.4"></path>
                                                <path id="SvgjsPath2180"
                                                    d="M 168.3 223.6L 168.3 109.28Q 168.3 99.28 178.3 99.28L 172.70000000000002 99.28Q 182.70000000000002 99.28 182.70000000000002 109.28L 182.70000000000002 109.28L 182.70000000000002 223.6Q 182.70000000000002 233.6 172.70000000000002 233.6L 178.3 233.6Q 168.3 233.6 168.3 223.6z"
                                                    fill="rgba(109,120,141,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="1"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 168.3 223.6L 168.3 109.28Q 168.3 99.28 178.3 99.28L 172.70000000000002 99.28Q 182.70000000000002 99.28 182.70000000000002 109.28L 182.70000000000002 109.28L 182.70000000000002 223.6Q 182.70000000000002 233.6 172.70000000000002 233.6L 178.3 233.6Q 168.3 233.6 168.3 223.6z"
                                                    pathFrom="M 168.3 223.6L 168.3 223.6L 182.70000000000002 223.6L 182.70000000000002 223.6L 182.70000000000002 223.6L 182.70000000000002 223.6L 182.70000000000002 223.6L 168.3 223.6"
                                                    cy="99.28" cx="216.3" j="3" val="230"
                                                    barHeight="134.32" barWidth="20.4"></path>
                                                <path id="SvgjsPath2181"
                                                    d="M 219.3 194.39999999999998L 219.3 88.83999999999997Q 219.3 78.83999999999997 229.3 78.83999999999997L 223.70000000000002 78.83999999999997Q 233.70000000000002 78.83999999999997 233.70000000000002 88.83999999999997L 233.70000000000002 88.83999999999997L 233.70000000000002 194.39999999999998Q 233.70000000000002 204.39999999999998 223.70000000000002 204.39999999999998L 229.3 204.39999999999998Q 219.3 204.39999999999998 219.3 194.39999999999998z"
                                                    fill="rgba(109,120,141,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="1"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 219.3 194.39999999999998L 219.3 88.83999999999997Q 219.3 78.83999999999997 229.3 78.83999999999997L 223.70000000000002 78.83999999999997Q 233.70000000000002 78.83999999999997 233.70000000000002 88.83999999999997L 233.70000000000002 88.83999999999997L 233.70000000000002 194.39999999999998Q 233.70000000000002 204.39999999999998 223.70000000000002 204.39999999999998L 229.3 204.39999999999998Q 219.3 204.39999999999998 219.3 194.39999999999998z"
                                                    pathFrom="M 219.3 194.39999999999998L 219.3 194.39999999999998L 233.70000000000002 194.39999999999998L 233.70000000000002 194.39999999999998L 233.70000000000002 194.39999999999998L 233.70000000000002 194.39999999999998L 233.70000000000002 194.39999999999998L 219.3 194.39999999999998"
                                                    cy="78.83999999999997" cx="267.3" j="4" val="215"
                                                    barHeight="125.56" barWidth="20.4"></path>
                                                <path id="SvgjsPath2182"
                                                    d="M 270.3 86.35999999999999L 270.3 39.19999999999999Q 270.3 29.19999999999999 280.3 29.19999999999999L 274.7 29.19999999999999Q 284.7 29.19999999999999 284.7 39.19999999999999L 284.7 39.19999999999999L 284.7 86.35999999999999Q 284.7 96.35999999999999 274.7 96.35999999999999L 280.3 96.35999999999999Q 270.3 96.35999999999999 270.3 86.35999999999999z"
                                                    fill="rgba(109,120,141,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="1"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 270.3 86.35999999999999L 270.3 39.19999999999999Q 270.3 29.19999999999999 280.3 29.19999999999999L 274.7 29.19999999999999Q 284.7 29.19999999999999 284.7 39.19999999999999L 284.7 39.19999999999999L 284.7 86.35999999999999Q 284.7 96.35999999999999 274.7 96.35999999999999L 280.3 96.35999999999999Q 270.3 96.35999999999999 270.3 86.35999999999999z"
                                                    pathFrom="M 270.3 86.35999999999999L 270.3 86.35999999999999L 284.7 86.35999999999999L 284.7 86.35999999999999L 284.7 86.35999999999999L 284.7 86.35999999999999L 284.7 86.35999999999999L 270.3 86.35999999999999"
                                                    cy="29.19999999999999" cx="318.3" j="5" val="115"
                                                    barHeight="67.16" barWidth="20.4"></path>
                                                <path id="SvgjsPath2183"
                                                    d="M 321.3 188.56L 321.3 91.76Q 321.3 81.76 331.3 81.76L 325.7 81.76Q 335.7 81.76 335.7 91.76L 335.7 91.76L 335.7 188.56Q 335.7 198.56 325.7 198.56L 331.3 198.56Q 321.3 198.56 321.3 188.56z"
                                                    fill="rgba(109,120,141,0.85)" fill-opacity="1" stroke="#ffffff"
                                                    stroke-opacity="1" stroke-linecap="round" stroke-width="6"
                                                    stroke-dasharray="0" class="apexcharts-bar-area" index="1"
                                                    clip-path="url(#gridRectMaskd0amupf0h)"
                                                    pathTo="M 321.3 188.56L 321.3 91.76Q 321.3 81.76 331.3 81.76L 325.7 81.76Q 335.7 81.76 335.7 91.76L 335.7 91.76L 335.7 188.56Q 335.7 198.56 325.7 198.56L 331.3 198.56Q 321.3 198.56 321.3 188.56z"
                                                    pathFrom="M 321.3 188.56L 321.3 188.56L 335.7 188.56L 335.7 188.56L 335.7 188.56L 335.7 188.56L 335.7 188.56L 321.3 188.56"
                                                    cy="81.76" cx="369.3" j="6" val="200"
                                                    barHeight="116.8" barWidth="20.4"></path>
                                            </g>
                                            <g id="SvgjsG2167" class="apexcharts-datalabels" data:realIndex="0"></g>
                                            <g id="SvgjsG2176" class="apexcharts-datalabels" data:realIndex="1"></g>
                                        </g>
                                        <line id="SvgjsLine2205" x1="0" y1="0" x2="357"
                                            y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                            stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                        <line id="SvgjsLine2206" x1="0" y1="0" x2="357"
                                            y2="0" stroke-dasharray="0" stroke-width="0"
                                            stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                        <g id="SvgjsG2207" class="apexcharts-yaxis-annotations"></g>
                                        <g id="SvgjsG2208" class="apexcharts-xaxis-annotations"></g>
                                        <g id="SvgjsG2209" class="apexcharts-point-annotations"></g>
                                    </g>
                                    <g id="SvgjsG2193" class="apexcharts-yaxis" rel="0"
                                        transform="translate(-18, 0)"></g>
                                    <g id="SvgjsG2156" class="apexcharts-annotations"></g>
                                </svg>
                                <div class="apexcharts-legend" style="max-height: 165px;"></div>
                            </div>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="text-start pb-0 ps-0">
                                            <div class="d-flex align-items-center">
                                                <div class="badge badge-dot bg-primary me-2"></div>
                                                <h6 class="mb-0 fw-semibold">Google Analytics</h6>
                                            </div>
                                        </td>
                                        <td class="pb-0">
                                            <p class="mb-0 text-muted">$845k</p>
                                        </td>
                                        <td class="pe-0 pb-0">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <h6 class="mb-0 fw-semibold me-2">82%</h6>
                                                <i class="mdi mdi-chevron-up text-success"></i>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start pb-0 ps-0">
                                            <div class="d-flex align-items-center">
                                                <div class="badge badge-dot bg-secondary me-2"></div>
                                                <h6 class="mb-0 fw-semibold">Facebook Ads</h6>
                                            </div>
                                        </td>
                                        <td class="pb-0">
                                            <p class="mb-0 text-muted">$12.5k</p>
                                        </td>
                                        <td class="pe-0 pb-0">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <h6 class="mb-0 fw-semibold me-2">52%</h6>
                                                <i class="mdi mdi-chevron-down text-danger"></i>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="resize-triggers">
                            <div class="expand-trigger">
                                <div style="width: 389px; height: 418px;"></div>
                            </div>
                            <div class="contract-trigger"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ External Links Chart -->

            <!-- Payment History -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Payment History</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="paymentHistory" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="paymentHistory">
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last 28 Days</a>
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Month</a>
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Year</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th class="text-capitalize text-body fw-medium fs-6">Card</th>
                                    <th class="text-capitalize text-body fw-medium fs-6">Date</th>
                                    <th class="text-end text-capitalize text-body fw-medium fs-6">Spend</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                <tr>
                                    <td class="d-flex">
                                        <div class="px-2 rounded bg-lighter d-flex align-items-center h-px-30">
                                            <img src="{{ asset('assets') }}//img/icons/payments/logo-visa.png" alt="credit-card"
                                                width="30">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">*4399</h6>
                                            <small class="text-muted">Credit Card</small>
                                        </div>
                                    </td>
                                    <td class="text-muted small">05/Jan</td>

                                    <td class="text-end">
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">-$2,820</h6>
                                            <small class="text-muted">$10,450</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="d-flex">
                                        <div class="px-2 rounded bg-lighter d-flex align-items-center h-px-30">
                                            <img src="{{ asset('assets') }}//img/icons/payments/logo-mastercard.png"
                                                alt="debit-card" width="30">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">*5545</h6>
                                            <small class="text-muted">Debit Card</small>
                                        </div>
                                    </td>
                                    <td class="text-muted small">12/Feb</td>

                                    <td class="text-end">
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">-$345</h6>
                                            <small class="text-muted">$8,709</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="d-flex">
                                        <div class="px-2 rounded bg-lighter d-flex align-items-center h-px-30">
                                            <img src="{{ asset('assets') }}//img/icons/payments/logo-american-express.png"
                                                alt="atm-card" width="30">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">*9860</h6>
                                            <small class="text-muted">ATM Card</small>
                                        </div>
                                    </td>
                                    <td class="text-muted small">24/Feb</td>

                                    <td class="text-end">
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">-$999</h6>
                                            <small class="text-muted">$25,900</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="d-flex">
                                        <div class="px-2 rounded bg-lighter d-flex align-items-center h-px-30">
                                            <img src="{{ asset('assets') }}//img/icons/payments/logo-visa.png" alt="debit-card"
                                                width="30">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">*4300</h6>
                                            <small class="text-muted">Credit Card</small>
                                        </div>
                                    </td>
                                    <td class="text-muted small">08/Mar</td>

                                    <td class="text-end">
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">-$8,453</h6>
                                            <small class="text-muted">$9,233</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="d-flex">
                                        <div class="px-2 rounded bg-lighter d-flex align-items-center h-px-30">
                                            <img src="{{ asset('assets') }}//img/icons/payments/logo-mastercard.png"
                                                alt="credit-card" width="30">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">*5545</h6>
                                            <small class="text-muted">Debit Card</small>
                                        </div>
                                    </td>
                                    <td class="text-muted small">15/Apr</td>

                                    <td class="text-end">
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">-$24</h6>
                                            <small class="text-muted">$500</small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="d-flex">
                                        <div class="px-2 rounded bg-lighter d-flex align-items-center h-px-30">
                                            <img src="{{ asset('assets') }}//img/icons/payments/logo-visa.png" alt="credit-card"
                                                width="30">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">*4399</h6>
                                            <small class="text-muted">Credit Card</small>
                                        </div>
                                    </td>
                                    <td class="text-muted small">28/Apr</td>

                                    <td class="text-end">
                                        <div class="ms-2">
                                            <h6 class="mb-0 fw-semibold">-$299</h6>
                                            <small class="text-muted">$1,380</small>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/ Payment History -->

            <!-- Most Sales in Countries -->
            <div class="col-lg-4 col-12">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Most Sales in Countries</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="mostSales" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-24px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="mostSales">
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last 28 Days</a>
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Month</a>
                                <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Year</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mt-1">
                            <div class="d-flex align-items-center">
                                <h1 class="mb-0 me-3 display-3">22,842</h1>
                                <div class="badge bg-label-success rounded-pill">+42%</div>
                            </div>
                            <small class="text-muted mt-1">Sales Last 90 Days</small>
                        </div>
                    </div>
                    <div class="table-responsive text-nowrap border-top">
                        <table class="table">
                            <tbody class="table-border-bottom-0">
                                <tr>
                                    <td class="pe-5"><span class="text-heading">Australia</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span class="text-heading fw-semibold">18,879</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <span class="text-heading fw-semibold me-2">15%</span>
                                            <i class="mdi mdi-chevron-down mdi-20px text-danger"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">Canada</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span class="text-heading fw-semibold">10,357</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <span class="text-heading fw-semibold me-2">85%</span>
                                            <i class="mdi mdi-chevron-up mdi-20px text-success"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">India</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span class="text-heading fw-semibold">4,860</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <span class="text-heading fw-semibold me-2">48%</span>
                                            <i class="mdi mdi-chevron-up mdi-20px text-success"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">France</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span class="text-heading fw-semibold">2,560</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <span class="text-heading fw-semibold me-2">36%</span>
                                            <i class="mdi mdi-chevron-up mdi-20px text-success"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">United State</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span class="text-heading fw-semibold">899</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <span class="text-heading fw-semibold me-2">16%</span>
                                            <i class="mdi mdi-chevron-down mdi-20px text-danger"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">Japan</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span class="text-heading fw-semibold">43</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <span class="text-heading fw-semibold me-2">35%</span>
                                            <i class="mdi mdi-chevron-up mdi-20px text-success"></i>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-5"><span class="text-heading">Brazil</span></td>
                                    <td class="ps-5 d-flex justify-content-end">
                                        <span class="text-heading fw-semibold">18</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-end">
                                            <span class="text-heading fw-semibold me-2">12%</span>
                                            <i class="mdi mdi-chevron-up mdi-20px text-success"></i>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--/ Most Sales in Countries -->

            <!-- Roles Datatables -->
            <div class="col-lg-8 col-12">
                <div class="card">
                    <div class="table-responsive rounded-3">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <table class="datatables-crm table table-sm dataTable no-footer" id="DataTables_Table_0"
                                style="width: 801px;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3 sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                            rowspan="1" colspan="1"
                                            aria-label="User: activate to sort column ascending" style="width: 154px;">
                                            User</th>
                                        <th class="py-3 sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                            rowspan="1" colspan="1"
                                            aria-label="Email: activate to sort column ascending" style="width: 172px;">
                                            Email</th>
                                        <th class="py-3 sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                            rowspan="1" colspan="1"
                                            aria-label="Role: activate to sort column ascending" style="width: 153px;">
                                            Role</th>
                                        <th class="py-3 sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                            rowspan="1" colspan="1"
                                            aria-label="Status: activate to sort column ascending"
                                            style="width: 202px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="odd">
                                        <td valign="top" colspan="4" class="dataTables_empty">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/swiper/swiper.css" />
    
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/cards-statistics.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/cards-analytics.css" />
@endpush

@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/swiper/swiper.js"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets') }}/js/dashboards-crm.js"></script>
@endpush