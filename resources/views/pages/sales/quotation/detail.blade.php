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
                                        <svg width="268" height="150" viewBox="0 0 38 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M30.0944 2.22569C29.0511 0.444187 26.7508 -0.172113 24.9566 0.849138C23.1623 1.87039 22.5536 4.14247 23.5969 5.92397L30.5368 17.7743C31.5801 19.5558 33.8804 20.1721 35.6746 19.1509C37.4689 18.1296 38.0776 15.8575 37.0343 14.076L30.0944 2.22569Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M30.171 2.22569C29.1277 0.444187 26.8274 -0.172113 25.0332 0.849138C23.2389 1.87039 22.6302 4.14247 23.6735 5.92397L30.6134 17.7743C31.6567 19.5558 33.957 20.1721 35.7512 19.1509C37.5455 18.1296 38.1542 15.8575 37.1109 14.076L30.171 2.22569Z"
                                                fill="url(#paint0_linear_2989_100980)" fill-opacity="0.4"></path>
                                            <path
                                                d="M22.9676 2.22569C24.0109 0.444187 26.3112 -0.172113 28.1054 0.849138C29.8996 1.87039 30.5084 4.14247 29.4651 5.92397L22.5251 17.7743C21.4818 19.5558 19.1816 20.1721 17.3873 19.1509C15.5931 18.1296 14.9843 15.8575 16.0276 14.076L22.9676 2.22569Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M14.9558 2.22569C13.9125 0.444187 11.6122 -0.172113 9.818 0.849138C8.02377 1.87039 7.41502 4.14247 8.45833 5.92397L15.3983 17.7743C16.4416 19.5558 18.7418 20.1721 20.5361 19.1509C22.3303 18.1296 22.9391 15.8575 21.8958 14.076L14.9558 2.22569Z"
                                                fill="url(#paint1_linear_2989_100980)" fill-opacity="0.4"></path>
                                            <path
                                                d="M7.82901 2.22569C8.87231 0.444187 11.1726 -0.172113 12.9668 0.849138C14.7611 1.87039 15.3698 4.14247 14.3265 5.92397L7.38656 17.7743C6.34325 19.5558 4.04298 20.1721 2.24875 19.1509C0.454514 18.1296 -0.154233 15.8575 0.88907 14.076L7.82901 2.22569Z"
                                                fill="currentColor"></path>
                                            <defs>
                                                <linearGradient id="paint0_linear_2989_100980" x1="5.36642" y1="0.849138"
                                                    x2="10.532" y2="24.104" gradientUnits="userSpaceOnUse">
                                                    <stop offset="0" stop-opacity="1"></stop>
                                                    <stop offset="1" stop-opacity="0"></stop>
                                                </linearGradient>
                                                <linearGradient id="paint1_linear_2989_100980" x1="5.19475" y1="0.849139"
                                                    x2="10.3357" y2="24.1155" gradientUnits="userSpaceOnUse">
                                                    <stop offset="0" stop-opacity="1"></stop>
                                                    <stop offset="1" stop-opacity="0"></stop>
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </span>
                                </span>
                                <span class="h4 mb-0 app-brand-text fw-bold">Materialize</span>
                            </div>
                            <p class="mb-1">Office 149, 450 South Brand Brooklyn</p>
                            <p class="mb-1">San Diego County, CA 91905, USA</p>
                            <p class="mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p>
                        </div>
                        <div>
                            <h5>INVOICE #86423</h5>
                            <div class="mb-1">
                                <span>Date Issues:</span>
                                <span>April 25, 2021</span>
                            </div>
                            <div>
                                <span>Date Due:</span>
                                <span>May 25, 2021</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap">
                        <div class="my-3">
                            <h6 class="pb-2">Invoice To:</h6>
                            <p class="mb-1">Thomas shelby</p>
                            <p class="mb-1">Shelby Company Limited</p>
                            <p class="mb-1">Small Heath, B10 0HF, UK</p>
                            <p class="mb-1">718-986-6062</p>
                            <p class="mb-0">peakyFBlinders@gmail.com</p>
                        </div>
                        <div class="my-3">
                            <h6 class="pb-2">Bill To:</h6>
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="pe-3 fw-medium">Total Due:</td>
                                        <td>$12,110.55</td>
                                    </tr>
                                    <tr>
                                        <td class="pe-3 fw-medium">Bank name:</td>
                                        <td>American Bank</td>
                                    </tr>
                                    <tr>
                                        <td class="pe-3 fw-medium">Country:</td>
                                        <td>United States</td>
                                    </tr>
                                    <tr>
                                        <td class="pe-3 fw-medium">IBAN:</td>
                                        <td>ETD95476213874685</td>
                                    </tr>
                                    <tr>
                                        <td class="pe-3 fw-medium">SWIFT code:</td>
                                        <td>BR91905</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead class="table-light border-top">
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Cost</th>
                                <th>Qty</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-nowrap">Vuexy Admin Template</td>
                                <td class="text-nowrap">HTML Admin Template</td>
                                <td>$32</td>
                                <td>1</td>
                                <td>$32.00</td>
                            </tr>
                            <tr>
                                <td class="text-nowrap">Frest Admin Template</td>
                                <td class="text-nowrap">Angular Admin Template</td>
                                <td>$22</td>
                                <td>1</td>
                                <td>$22.00</td>
                            </tr>
                            <tr>
                                <td class="text-nowrap">Apex Admin Template</td>
                                <td class="text-nowrap">HTML Admin Template</td>
                                <td>$17</td>
                                <td>2</td>
                                <td>$34.00</td>
                            </tr>
                            <tr>
                                <td class="text-nowrap">Robust Admin Template</td>
                                <td class="text-nowrap">React Admin Template</td>
                                <td>$66</td>
                                <td>1</td>
                                <td>$66.00</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="align-top px-4 py-5">
                                    <p class="mb-2">
                                        <span class="me-1 fw-semibold">Salesperson:</span>
                                        <span>Alfie Solomons</span>
                                    </p>
                                    <span>Thanks for your business</span>
                                </td>
                                <td class="text-end px-4 py-5">
                                    <p class="mb-2">Subtotal:</p>
                                    <p class="mb-2">Discount:</p>
                                    <p class="mb-2">Tax:</p>
                                    <p class="mb-0">Total:</p>
                                </td>
                                <td class="px-4 py-5">
                                    <p class="fw-semibold mb-2 text-end">$154.25</p>
                                    <p class="fw-semibold mb-2 text-end">$00.00</p>
                                    <p class="fw-semibold mb-2 text-end">$50.00</p>
                                    <p class="fw-semibold mb-0 text-end">$204.25</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <span class="fw-bold">Note:</span>
                            <span>It was a pleasure working with you and your team. We hope you will keep us in mind for
                                future freelance projects. Thank You!</span>
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
                    <button class="btn btn-primary d-grid w-100 mb-3 waves-effect waves-light" data-bs-toggle="offcanvas"
                        data-bs-target="#sendInvoiceOffcanvas">
                        <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                class="mdi mdi-send-outline scaleX-n1-rtl me-1"></i>Send Invoice</span>
                    </button>
                    <button class="btn btn-outline-secondary d-grid w-100 mb-3 waves-effect">Download</button>
                    <a class="btn btn-outline-secondary d-grid w-100 mb-3 waves-effect" target="_blank"
                        href="./app-invoice-print.html">
                        Print
                    </a>
                    <a href="./app-invoice-edit.html" class="btn btn-outline-secondary d-grid w-100 mb-3 waves-effect">
                        Edit Invoice
                    </a>
                    <button class="btn btn-success d-grid w-100 waves-effect waves-light" data-bs-toggle="offcanvas"
                        data-bs-target="#addPaymentOffcanvas">
                        <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                class="mdi mdi-currency-usd me-1"></i>Add Payment</span>
                    </button>
                </div>
            </div>
        </div>
        {{-- End : Button Invoice --}}
    </div>
@endsection
@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice.css" />
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/cleavejs/cleave.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/cleavejs/cleave-phone.js"></script>
@endpush

@push('page-script')
    <script src="{{ asset('assets') }}/js/offcanvas-add-payment.js"></script>
    <script src="{{ asset('assets') }}/js/offcanvas-send-invoice.js"></script>
@endpush
