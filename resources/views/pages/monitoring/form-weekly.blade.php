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
                                    <input id="defaultInput" class="form-control" name="week" type="number"
                                        placeholder="1">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-lg-6">
                                <label for="defaultInput" class="form-label">Voltage (V)</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="voltage" type="text"
                                        placeholder="Voltage (V)">
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <label for="defaultInput" class="form-label">Ampere Load (A)</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="ampere" type="text"
                                        placeholder="Ampere Load (A)">
                                </div>
                            </div>
                            <div class="col-12 col-lg-3">
                                <label for="defaultInput" class="form-label">Ampere Idle (A)</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="idle" type="text"
                                        placeholder="Ampere Idle (A)">
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
                            <div class="col-12 col-lg-4">
                                <label for="defaultInput" class="form-label">Week</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="week" type="number"
                                        placeholder="1">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-lg-3">
                                <label for="defaultInput" class="form-label">Voltage (V)</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="voltage" type="text"
                                        placeholder="Voltage (V)">
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <label for="defaultInput" class="form-label">Ampere (A)</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="ampere" type="text"
                                        placeholder="Ampere (A)">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="defaultInput" class="form-label">Dew Point</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="dew" type="text"
                                        placeholder="Dew Point">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-lg-6">
                                <label for="defaultInput" class="form-label">Auto Drain</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="drain" type="text"
                                        placeholder="Auto Drain">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label for="defaultInput" class="form-label">Pre Filter</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="pre" type="text"
                                        placeholder="Pre Filter">
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="defaultInput" class="form-label">After Filter</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="after" type="text"
                                        placeholder="After Filter">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row mb-3">
                    <div class="col-12 col-lg-6">
                        <label for="defaultInput" class="form-label">Perventive Maintenance</label>
                        <div class="input-group input-group-merge">
                            <input id="defaultInput" class="form-control" name="pm" type="text"
                                placeholder="Perventive Maintenance">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label for="defaultInput" class="form-label">Remark</label>
                        <div class="input-group input-group-merge">
                            <input id="defaultInput" class="form-control" name="remark" type="text"
                                placeholder="Remark">
                        </div>
                    </div>
                </div>
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
@endpush
