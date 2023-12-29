@extends('layouts.sales.app')
@section('title', 'Existing')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title">
                <h5>Existing Table</h5>
            </div>
            <div class="selectYear">
                <form id="yearForm">
                    <div class="row">
                        <div class="col-6">
                            <select id="year" name="year" class="form-select">
                                @for ($i = date('Y'); $i >= 2010; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <select id="half" name="half" class="form-select">
                                <option value="1">1st Half</option>
                                <option value="2">2nd Half</option>
                            </select>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2" colspan="1">No</th>
                        <th rowspan="2" colspan="1">Company</th>
                        <th rowspan="2" colspan="1">Status cust</th>
                        <th rowspan="2" colspan="1">Email</th>
                        <th rowspan="2" colspan="1">Phone</th>
                        <th rowspan="2" colspan="1">Note</th>
                        <th colspan="5">July 2023</th>
                        <th colspan="5">August 2023</th>
                        <th colspan="5">September 2023</th>
                        <th colspan="5">October 2023</th>
                        <th colspan="5">November 2023</th>
                        <th colspan="5">Desember 2023</th>
                    </tr>
                    <tr>
                        <th>Week I</th>
                        <th>Week II</th>
                        <th>Week III</th>
                        <th>Week IV</th>
                        <th>Week V</th>
                        <th>Week I</th>
                        <th>Week II</th>
                        <th>Week III</th>
                        <th>Week IV</th>
                        <th>Week V</th>
                        <th>Week I</th>
                        <th>Week II</th>
                        <th>Week III</th>
                        <th>Week IV</th>
                        <th>Week V</th>
                        <th>Week I</th>
                        <th>Week II</th>
                        <th>Week III</th>
                        <th>Week IV</th>
                        <th>Week V</th>
                        <th>Week I</th>
                        <th>Week II</th>
                        <th>Week III</th>
                        <th>Week IV</th>
                        <th>Week V</th>
                        <th>Week I</th>
                        <th>Week II</th>
                        <th>Week III</th>
                        <th>Week IV</th>
                        <th>Week V</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <tr>
                        <th scope="row">1</th>
                        <td>PT Indospring Tbk.</td>
                        <td>
                            <select id="select2Badge" class="select2-badge form-select">
                                <option value="wordpress2" data-badge="badge badge-dot bg-danger" selected></option>
                                <option value="codepen" data-badge="badge badge-dot bg-warning"></option>
                                <option value="drupal" data-badge="badge badge-dot bg-success"></option>
                                <option value="pinterest2" data-badge="badge badge-dot bg-info"></option>
                            </select>
                        </td>
                        <td>indospring@bussiness.com</td>
                        <td>+6289017234</td>
                        <td>Aktif</td>
                        <td>
                            <span data-bs-toggle="modal" data-bs-target="existingModal">
                                <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-primary"
                                    title="Responded | Send Quotation">
                                    02/07
                                </a>
                            </span>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>23/07</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>PT Teras Adhi</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>PT Rakha Daksa</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
@endpush
@push('page-script')
    <script src="{{ asset('assets') }}/js/forms-selects.js"></script>
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('#options').select2({
                templateResult: formatState
            });
        });

        // Fungsi untuk merender opsi dengan badge
        function formatState(state) {
            if (!state.id) {
                return state.text;
            }
            var badgeClass = $(state.element).data('badge');
            return $(
                '<span class="' + badgeClass + '">' + state.text + '</span>'
            );
        }
    </script>
@endpush
