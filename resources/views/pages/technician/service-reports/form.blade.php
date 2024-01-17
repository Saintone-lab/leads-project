@extends('layouts.sales.app')
@section('title', 'Create Quotation')
@section('content')
    <form action="{{route('service-reports.store')}}" method="post" enctype="multipart/form-data" id="serviceReports" name="service-reports">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Form Service Reports</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control fw-bold fs-3" id="floatingInputFilled"
                                aria-describedby="floatingInputFilledHelp" name="no_service" placeholder="No Service">
                            <label for="floatingInputFilled">Number Service</label>
                            <span class="form-floating-focused"></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="form-floating form-floating-outline">
                            <select id="select2Basic" class="select2 form-select form-select-lg" data-allow-clear="true"
                                name="id_pic">
                                @foreach ($pic as $charge)
                                    <option value="{{ $charge->id }}"
                                        {{ @$quotation->id_pic == $charge->id ? 'selected' : '' }}>
                                        {{ $charge->name_pic }} | {{ $charge->client->company }}</option>
                                @endforeach
                            </select>
                            <label for="select2Basic">Client</label>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-12 col-md-4 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="date" id="date" name="date">
                            <label for="date">Date</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="unit" name="unit"
                                placeholder="Type Unit Type Here ....">
                            <label for="basic-default-fullname">Unit Type</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="serial_number" name="serial_number"
                                placeholder="Type Serial Number Here ....">
                            <label for="basic-default-fullname">Serial Number</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control input-numeric" id="running" name="running"
                                placeholder="Type Running Here...">
                            <label for="basic-default-fullname">Running</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control input-numeric" id="load" name="load"
                                placeholder="Type Load Here...">
                            <label for="basic-default-fullname">Load</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="jobdesc" name="jobdesc"
                                placeholder="Type Job Description Type Here ....">
                            <label for="basic-default-fullname">Job Description</label>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-floating form-floating-outline">
                            <textarea class="form-control" id="description" name="desc" placeholder="Description here..."
                                style="min-height: 100px;"></textarea>
                            <label for="description">Description</label>
                        </div>
                    </div>
                    <!-- Multi  -->
                    <div class="col-12">
                        <div action="{{route('service-reports.store')}}" class="dropzone needsclick" id="dropzone-basic">
                            <div class="dz-message needsclick">
                                Drop Photo here or click to upload
                            </div>
                            <div class="fallback">
                                <input name="image" type="file" multiple />
                            </div>
                        </div>
                    </div>
                    <!-- Multi  -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <a href="{{ route('service-reports.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('after-style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/dropzone/dropzone.css" />
    <script src="{{ asset('assets') }}/vendor/dropzone/dropzone.js"></script>
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
@endpush
@push('page-script')
    <script src="{{ asset('assets') }}/js/forms-selects.js"></script>
@endpush
@push('script')
    <script>
        function initNumericInput(){
            var input = $('.input-numeric')
            for (var i = 0; i < input.length; i++) {
                input[i].addEventListener('input', function() {
                    // Hapus karakter selain angka
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        }
        $(() => {
            initNumericInput();
        });
    </script>
@endpush
