@extends('layouts.sales.app')
@section('title', 'Create Quotation')
@section('content')
    <form action="{{ route('service-reports.store') }}" method="post" enctype="multipart/form-data" id="serviceReports"
        name="service-reports">
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
                                aria-describedby="floatingInputFilledHelp" name="no_service" placeholder="No Service"
                                value="{{ $formattedNumberS . '-S/RJO-' . Auth::user()->code . '/' . $formattedMonthNow . '/' . \Carbon\Carbon::now()->year }}">
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

                        <input type="text" name="technician" id="" value="{{ Auth::user()->id }}"
                            hidden>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-12 col-md-4 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="date"
                                value="{{ now()->format('Y-m-d') }}" disabled>
                            <input type="date" name="date" id="date" value="{{ now()->format('Y-m-d') }}" hidden>
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
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="formFileMultiple" class="form-label">Picture</label>
                            <input class="form-control" type="file" id="formFileMultiple" name="image[]"
                                multiple="" accept="image/*">
                            <div id="image-preview"></div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div id="dynamicInputsContainer">
                            <!-- Elemen input dinamis akan ditambahkan di sini -->
                        </div>
                    </div>
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
    <style>
        #image-preview img {
            max-width: 150px;
            margin-left: 16px;
        }
    </style>
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/select2/select2.js"></script>
@endpush
@push('page-script')
    <script src="{{ asset('assets') }}/js/forms-selects.js"></script>
@endpush
@push('script')
    <script>
        function initNumericInput() {
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
            $('#formFileMultiple').on('change', function() {
                var files = this.files;
                var dynamicInputsContainer = $('#dynamicInputsContainer');
                console.log(dynamicInputsContainer);

                dynamicInputsContainer.empty();

                for (var i = 0; i < files.length; i++) {
                    var dynamicInput =
                        '<input class="form-control mb-2" type="text" name="description[]" placeholder="Deskripsi untuk File ' +
                        (i +
                            1) + '">';
                    dynamicInputsContainer.append(dynamicInput);
                }

                if (files.length !== 2 && files.length !== 4) {
                    alert('Masukan 2 atau 4 Gambar.');
                    this.value = ''; // Menghapus file yang tidak memenuhi syarat
                    dynamicInputsContainer.empty();
                }

                console.log(files);
                const previewContainer = document.getElementById('image-preview');
                previewContainer.innerHTML = '';

                for (const file of files) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const imageElement = document.createElement('img');
                        imageElement.src = e.target.result;
                        previewContainer.appendChild(imageElement);
                    };

                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
