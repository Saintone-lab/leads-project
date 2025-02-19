@extends('layouts.sales.app')
@section('title', 'Monitoring machine')
@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('store.weekly-monitoring', $machine->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @if ($machine->unit->unit->unit != 'REFRIGERANT AIR DRYER')
                    <h5 class="text-center">WEEKLY CHECK AIR COMPRESSOR {{ $machine->unit->brand }}
                        {{ $machine->unit->unit->sku }}</h5>
                    <div class="daily-compressor">
                        <div class="row">
                            <div class="col-12 col-lg-8">
                                <div class="row mb-3">
                                    <div class="col-4 col-lg-2">
                                        Location
                                    </div>
                                    <div class="col-8 col-lg-10">
                                        : {{ $machine->location }}
                                    </div>
                                    <div class="col-4 col-lg-2">
                                        Tag Number
                                    </div>
                                    <div class="col-8 col-lg-10">
                                        : {{ $machine->tag }}
                                    </div>
                                    <div class="col-4 col-lg-2">
                                        Unit
                                    </div>
                                    <div class="col-8 col-lg-10">
                                        : {{ $machine->unit->brand }} {{ $machine->unit->unit->sku }}
                                    </div>
                                    <div class="col-4 col-lg-2">
                                        Date
                                    </div>
                                    <div class="col-8 col-lg-10">
                                        : {{ \Carbon\Carbon::now()->format('d-m-Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <label for="defaultInput" class="form-label">Week</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="weekl" type="number"
                                        placeholder="1" value="{{ $weekNumber }}" disabled>
                                    <input id="defaultInput" class="form-control" name="week" type="number"
                                        placeholder="1" value="{{ $weekNumber }}" hidden>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-lg-6 mb-3">
                                <label for="defaultSelect" class="form-label">Condition</label>
                                <select id="conditionSelect" name="condition" class="form-select">
                                    <option value="Running">Running</option>
                                    <option value="Stand By">Stand By</option>
                                    <option value="Off">Off</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-3 mb-3">
                                <label for="defaultSelect" class="form-label">Cek / Uji Auto Drain</label>
                                <select id="offDisable" name="drain" class="form-select offDisable">
                                    <option value="Ok">Ok</option>
                                    <option value="Not Ok">Not Ok</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-3 mb-3">
                                <label for="defaultInput" class="form-label">Vibration</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control offDisable" name="vibration" type="text"
                                        placeholder="Vibration">
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 mb-3">
                                <label for="defaultInput" class="form-label">Voltage (V) <span class="text-danger">* WAJIB INPUT (R/S/T)</span></label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control offDisable" name="voltage" type="text"
                                        placeholder="R/S/T">
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 mb-3">
                                <label for="defaultInput" class="form-label">Running Ampere (A) <span class="text-danger">* WAJIB INPUT (R/S/T)</span></label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control offDisable" name="ampere" type="text"
                                        placeholder="R/S/T">
                                </div>
                            </div>
                            <div class="col-12 col-lg-2 mb-2">
                                <div class="row">
                                    <div class="col-6 col-lg-12">
                                        <label for="defaultInput" class="form-label">Cleaning Cooler Menggunakan Angin</label>
                                    </div>
                                    <div class="col-6 col-lg-12">
                                        <input class="form-check-input" type="checkbox" name="cooler" value="1" id="defaultCheck1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-2 mb-2">
                                <div class="row">
                                    <div class="col-6 col-lg-12">
                                        <label for="defaultInput" class="form-label">Cek Coupling / V-Belt</label>
                                    </div>
                                    <div class="col-6 col-lg-12">
                                        <input class="form-check-input" type="checkbox" name="coupling" value="1" id="defaultCheck1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-2 mb-2">
                                <div class="row">
                                    <div class="col-6 col-lg-12">
                                        <label for="defaultInput" class="form-label">Cleaning Compressor/Area</label>
                                    </div>
                                    <div class="col-6 col-lg-12">
                                        <input class="form-check-input" type="checkbox" name="area" value="1" id="defaultCheck1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <h5 class="text-center">WEEKLY CHECK AIR DRYER {{ $machine->unit->brand }}
                        {{ $machine->unit->unit->sku }}</h5>
                    <div class="daily-compressor">
                        <div class="row">
                            <div class="col-12 col-lg-8">
                                <div class="row mb-3">
                                    <div class="col-4 col-lg-2">
                                        Location
                                    </div>
                                    <div class="col-8 col-lg-10">
                                        : {{ $machine->location }}
                                    </div>
                                    <div class="col-4 col-lg-2">
                                        Tag Number
                                    </div>
                                    <div class="col-8 col-lg-10">
                                        : {{ $machine->tag }}
                                    </div>
                                    <div class="col-4 col-lg-2">
                                        Unit
                                    </div>
                                    <div class="col-8 col-lg-10">
                                        : {{ $machine->unit->brand }} {{ $machine->unit->unit->sku }}
                                    </div>
                                    <div class="col-4 col-lg-2">
                                        Date
                                    </div>
                                    <div class="col-8 col-lg-10">
                                        : {{ \Carbon\Carbon::now()->format('d-m-Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 mb-3">
                                <label for="defaultInput" class="form-label">Week</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="weekl" type="number"
                                        placeholder="1" value="{{ $weekNumber }}" disabled>
                                    <input id="defaultInput" class="form-control" name="week" type="number"
                                        placeholder="1" value="{{ $weekNumber }}" hidden>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-lg-6 mb-3">
                                <label for="defaultSelect" class="form-label">Condition</label>
                                <select id="conditionSelect" name="condition" class="form-select">
                                    <option value="Running">Running</option>
                                    <option value="Stand By">Stand By</option>
                                    <option value="Off">Off</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-3">
                                <label for="defaultInput" class="form-label">Voltage (V) <span class="text-danger">* WAJIB INPUT (R/S/T)</span></label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control offDisable" name="voltage"
                                        type="text" placeholder="R/S/T">
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 mb-3">
                                <label for="defaultInput" class="form-label">Ampere Refrigerant Compressor (A)</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control offDisable" name="ampere"
                                        type="text" placeholder="R/S/T">
                                </div>
                            </div>
                            {{-- <div class="col-12 col-lg-6">
                                <label for="defaultInput" class="form-label">Dew Point</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="dew" type="text"
                                        placeholder="Dew Point">
                                </div>
                            </div> --}}
                            <div class="col-12 col-lg-3 mb-3">
                                <label for="defaultInput" class="form-label">Pre Filter</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group input-group-merge">
                                        <select name="pre" class="form-select offDisable">
                                            <option value="Oke">Oke</option>
                                            <option value="Change">Change</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 mb-3">
                                <label for="defaultInput" class="form-label">After Filter</label>
                                <div class="input-group input-group-merge">
                                    <select name="after" class="form-select offDisable">
                                        <option value="Oke">Oke</option>
                                        <option value="Change">Change</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 mb-3">
                                <label for="defaultInput" class="form-label">Auto Drain</label>
                                <div class="input-group input-group-merge">
                                    <select name="drain" class="form-select offDisable">
                                        <option value="Oke">Oke</option>
                                        <option value="Not Oke">Not Oke</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 mb-2">
                                <div class="row">
                                    <div class="col-6 col-lg-12">
                                        <label for="defaultInput" class="form-label">Cleaning Kisi Kisi Kondensor</label>
                                    </div>
                                    <div class="col-6 col-lg-12">
                                        <input class="form-check-input" type="checkbox" name="condensor" value="1" id="defaultCheck1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="form-floating form-floating-outline mb-3">
                    <textarea class="form-control h-px-100" id="exampleFormControlTextarea1" name="desc"
                        placeholder="Comments here..."></textarea>
                    <label for="exampleFormControlTextarea1">Desc</label>
                </div>
                <div class="float-end">
                    <a href="{{ route('index.daily-monitoring', $machine->id) }}" type="button"
                        class="btn btn-lg btn-outline-secondary">
                        Back
                    </a>
                    <button :disabled="focused" type="submit" class="btn btn-lg btn-primary">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection()

@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
@endpush

@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
@endpush

@push('page-script')
    <script src="{{ asset('assets') }}/js/tables-datatables-basic.js"></script>
    <script src="{{ asset('assets') }}/includes/table-monitoring-machine.js"></script>
    <script>
        $('#conditionSelect').on('change', function() {
            var condition = $(this).val();
            var disable = $('.offDisable');
            var number = $('#numberInput');

            if (condition == 'Off') {
                disable.prop('disabled', true);
                // number.prop('disabled', true);
            } else {
                // number.prop('disabled', false);
                disable.prop('disabled', false);
            }
            console.log(number);

            console.log(condition);
        });
    </script>
@endpush
