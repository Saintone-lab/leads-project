@extends('layouts.sales.app')
@section('title', 'Monitoring machine')
@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('store.daily-monitoring', $machine->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @if ($machine->unit->unit->unit != 'REFRIGERANT AIR DRYER')
                    <h5 class="text-center">DAILY CHECK AIR COMPRESSOR {{ $machine->unit->brand }}
                        {{ $machine->unit->unit->sku }}</h5>
                    <div class="daily-compressor">
                        <div class="row">
                            <div class="col-12 col-lg-6">
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
                        </div>
                        <div class="row mb-3">
                            <div class="col-6 col-lg-3">
                                <label for="defaultSelect" class="form-label">Condition</label>
                                <select id="defaultSelect" name="condition" class="form-select">
                                    <option value="Running">Running</option>
                                    <option value="Stand By">Stand By</option>
                                    <option value="Off">Off</option>
                                </select>
                            </div>
                            <div class="col-6 col-lg-3">
                                <label for="defaultSelect" class="form-label">Oil Level</label>
                                <select id="defaultSelect" name="oil" class="form-select">
                                    <option value="OK">OK</option>
                                    <option value="Kurang">Kurang</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="defaultInput" class="form-label">Pressure</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="pressure" type="number"
                                        placeholder="Bar">
                                    <span class="input-group-text">Bar</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-lg-6">
                                <label for="defaultInput" class="form-label">Temperature</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="temperature" type="number"
                                        placeholder="°C">
                                    <span class="input-group-text">°C</span>
                                </div>
                            </div>
                            <div class="col col-lg-3">
                                <label for="defaultInput" class="form-label">Running</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="running" type="number"
                                        placeholder="Hr" min="1">
                                    <span class="input-group-text">Hours</span>
                                </div>
                            </div>
                            <div class="col col-lg-3">
                                <label for="defaultInput" class="form-label">Loading Hr</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="loading" type="number"
                                        placeholder="Hr" min="1">
                                    <span class="input-group-text">Hours</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <textarea class="form-control h-px-100" id="exampleFormControlTextarea1" name="desc" placeholder="Comments here..."></textarea>
                            <label for="exampleFormControlTextarea1">Desc</label>
                        </div>
                        <div class="mb-4">
                            <label for="formFile" class="form-label">Input Bukti Photo</label>
                            <input class="form-control" type="file" name="picture" id="formFile" accept="image/*">
                            <p class="text-muted">Note :Format photo harus ada bukti tanggal pada Photonya</p>
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
                    </div>
                @else
                    <h5 class="text-center">DAILY CHECK AIR DRYER {{ $machine->unit->brand }}
                        {{ $machine->unit->unit->sku }}</h5>
                    <div class="daily-compressor">
                        <div class="row">
                            <div class="col-12 col-lg-6">
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
                        </div>
                        <div class="row mb-3">
                            <div class="col-6 col-lg-3">
                                <label for="defaultSelect" class="form-label">Condition</label>
                                <select id="defaultSelect" name="condition" class="form-select">
                                    <option value="Running">Running</option>
                                    <option value="Stand By">Stand By</option>
                                    <option value="Off">Off</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-3">
                                <label for="defaultInput" class="form-label">Dew Point</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="dew" type="text"
                                        placeholder="Dew Point">
                                </div>
                            </div>
                            <div class="col-12 col-lg-3">
                                <label for="defaultSelect" class="form-label">Auto Drain</label>
                                <select id="defaultSelect" name="drain" class="form-select">
                                    <option value="Ok">Ok</option>
                                    <option value="Not Ok">Not Ok</option>
                                </select>
                            </div>
                            <div class="col-12 col-lg-3">
                                <label for="defaultInput" class="form-label">Cleaning Unit</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="cleaning" type="text"
                                        placeholder="Cleaning Unit">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-lg-6">
                                <label for="defaultInput" class="form-label">Temperature In</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="temperature_in" type="number"
                                        placeholder="°C">
                                    <span class="input-group-text">°C</span>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="defaultInput" class="form-label">Temperature Out</label>
                                <div class="input-group input-group-merge">
                                    <input id="defaultInput" class="form-control" name="temperature_out" type="number"
                                        placeholder="°C">
                                    <span class="input-group-text">°C</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating form-floating-outline mb-3">
                            <textarea class="form-control h-px-100" id="exampleFormControlTextarea1" name="desc"
                                placeholder="Comments here..."></textarea>
                            <label for="exampleFormControlTextarea1">Desc</label>
                        </div>
                        <div class="mb-4">
                            <label for="formFile" class="form-label">Input Bukti Photo</label>
                            <input class="form-control" type="file" name="picture" id="formFile" accept="image/*">
                            <p class="text-muted">Note :Format photo harus ada bukti tanggal pada Photonya</p>
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
                    </div>
                @endif
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
