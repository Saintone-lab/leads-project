@extends('layouts.sales.app')
@section('title', 'Detail Audit Tools')
@section('content')
    <div class="row invoice-preview">
        <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-row flex-column">
                        <div class="mb-xl-0 pb-1">
                            <div class="d-flex svg-illustration align-items-center gap-2 mb-4">
                                <span class="app-brand-logo demo">
                                    <span style="color: var(--bs-primary)">
                                        <img class="text-md"
                                            src="{{ url('https://reftech.id/wp-content/uploads/2021/10/Reftech-Logo-Hitam.png') }}"
                                            alt="" srcset="" width="60%">
                                    </span>
                                </span>
                            </div>
                            <p class="mb-1 fw-bolder">PT Reftech Jaya Optima</p>
                            <div style="font-size: 10px">
                                <p class="mb-1">Taman Kopo Indah V, Ruko Sommerville No. 31</p>
                                <p class="mb-1">Bandung – Jawa Barat 40218</p>
                                <p class="mb-1">
                                    <i class="mdi mdi-phone-outline scaleX-n1-rtl me-1 mdi-14px"></i>022 54417653
                                </p>
                                <p class="mb-1">
                                    <i class="mdi mdi-email-outline scaleX-n1-rtl me-1 mdi-14px"></i>service@reftech.id
                                </p>
                            </div>
                        </div>
                        <div>
                            <h3 class="fw-bold">SERVICE REPORT</h3>
                            <div>
                                <span class="fw-bolder">#{{ $service->no_service }}</span>
                            </div>
                            <div class="mt-1">
                                <span class="text-muted">{{ $service->date }}</span>
                            </div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row mb-3">
                        <div class="col-lg-2 col-4 fw-medium">
                            <p class="mb-1">Customers </p>
                            <p class="mb-1">Address </p>
                            <p class="mb-1">PIC </p>
                        </div>
                        <div class="col-lg-4 col-8">
                            <p class="mb-1">: {{ $service->pic->client->company }}</p>
                            <p class="mb-1">: {{ $service->pic->client->area }}</p>
                            <p class="mb-1">: {{ $service->pic->name_pic }}</p>
                        </div>
                        <div class="col-lg-2 col-4 fw-medium">
                            <p class="mb-1">Unit Type </p>
                            <p class="mb-1">Serial Number </p>
                            <p class="mb-1">Running & Load </p>
                        </div>
                        <div class="col-lg-4 col-8">
                            <p class="mb-1">: {{ $service->unit }}</p>
                            <p class="mb-1">: {{ $service->serial_number }}</p>
                            <p class="mb-1">: {{ $service->running }} | {{ $service->load }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-4 fw-medium">
                            <p class="mb-1">Job Description </p>
                        </div>
                        <div class="col-lg-10 col-8 d-flex gap-1">
                            <p>: </p>
                            <p class="mb-1"> {{ $service->jobdesc }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <h5 class="my-2">Description</h5>
                            <pre class="mb-1"
                                style="font-family: 'Inter', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $service->desc }}</pre>
                        </div>
                        <div class="col-lg-6 col-12">
                            <h5 class="my-2">Recomendation</h5>
                            <pre class="mb-1"
                                style="font-family: 'Inter', Tahoma, Geneva, Verdana, sans-serif; max-width: 100%; overflow-x: auto; white-space: pre-wrap;">{{ $service->recomendation }}</pre>
                        </div>
                    </div>
                    <hr>
                    <h5 class="my-4">Picture</h5>
                    <div class="row mb-5">
                        @foreach ($pict as $picture)
                            <div class="col-lg-4 col-12 text-center">
                                <img src="{{ url('') . '/' . $picture->picture }}" alt="" srcset=""
                                    style="max-width : 200px;">
                                <p>{{ $picture->keterangan }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="row mt-5">
                        <div class="col-4 mt-5 text-center">
                            <p>PT Reftech Jaya Optima</p>
                            @if (isset($service->technician->sign))
                                <img src="{{ url('') . '/' . $service->technician->sign }}" alt="" srcset=""
                                    height="100">
                            @else
                                <div class="pb-5"></div>
                            @endif
                            <p class="pt-3">( {{ $service->technician->name }} )</p>
                        </div>
                        <div class="col-4"></div>
                        <div class="col-4 mt-5 text-center">
                            <p class="">{{ $service->pic->client->company }}</p>
                            @if (isset($service->sign_client))
                                <img src="{{ url('') . '/' . $service->sign_client }}" alt="" srcset=""
                                    height="100">
                            @else
                                <div class="pb-5"></div>
                            @endif
                            <p class="pt-3">( {{ $service->pic->name_pic }} )</p>
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
                    <a class="btn btn-primary btn-outline-secondary d-grid w-100 mb-3 waves-effect" target="_blank"
                        href="{{ route('service-reports.print', $service->id) }}">
                        Print
                    </a>
                    <a href="#" type="button"
                        class="btn btn-outline-secondary d-grid w-100 waves-effect mb-3">Download</a>
                    @if (isset($service->sign_client))
                        <a href="#" class="btn btn-danger d-grid w-100 waves-effect delete-hand-sign mb-3"
                            data-id="{{ $service->id }}">Delete Hand Sign</a>
                    @else
                        <a type="button" data-bs-toggle="modal" data-bs-target="#inputSign-{{ $service->id }}"
                            class="d-grid w-100 waves-effect mb-3">
                            <button type="button" class="btn btn-secondary">
                                Input Hand Sign
                            </button>
                        </a>
                    @endif
                    @if (Auth::user()->role == 'Technician')
                        <a href="#" class="btn btn-outline-danger d-grid w-100 waves-effect delete-service"
                            data-id="{{ $service->id }}">Delete</a>
                    @endif
                </div>
            </div>
        </div>
        {{-- End : Button Invoice --}}
    </div>
    @include('components.modal.service.sign')
@endsection
@push('after-style')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/app-invoice.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.css" />
@endpush
@push('after-script')
    <script src="{{ asset('assets') }}/vendor/libs/sweetalert2/sweetalert2.js"></script>
@endpush
@push('page-script')
    <script src="{{ asset('assets') }}/js/extended-ui-sweetalert2.js"></script>
@endpush
@push('script')
    <script>
        $(() => {
            $('#formFileMultiple').on('change', function() {
                var files = this.files;
                var dynamicInputsContainer = $('#dynamicInputsContainer');
                dynamicInputsContainer.empty();

                // Hanya mengambil satu file (file pertama)
                var file = files[0];
                console.log(file);
                const previewContainer = document.getElementById('image-preview');
                previewContainer.innerHTML = '';

                const reader = new FileReader();

                reader.onload = function(e) {
                    const imageContainer = document.createElement('div');
                    const imageElement = document.createElement('img');
                    imageContainer.className = 'image-container'; // Tambahkan kelas sesuai kebutuhan

                    // Set maksimum lebar dan tinggi untuk gambar
                    imageElement.style.maxWidth =
                        '800px'; // Ganti dengan nilai maksimum lebar yang Anda inginkan
                    imageElement.style.maxHeight =
                        '500px'; // Ganti dengan nilai maksimum tinggi yang Anda inginkan

                    imageElement.src = e.target.result;

                    imageContainer.appendChild(imageElement);
                    previewContainer.appendChild(imageContainer);
                };

                reader.readAsDataURL(file);
            });
        });
        $(document).on('click', '.delete-hand-sign', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('service-reports') }}/del-sign/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: "Your file has been deleted.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/service-reports/' + id;
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Delete!'
                                });
                            }
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Cancelled",
                        text: "Your imaginary file is safe :)",
                        icon: "error",
                        customClass: {
                            confirmButton: "btn btn-success waves-effect",
                        },
                    });
                }
            });
        });
        $(document).on('click', '.delete-service', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect",
                },
                buttonsStyling: false,
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        'url': '{{ url('service-reports') }}/' + id,
                        'type': 'POST',
                        'data': {
                            '_method': 'DELETE',
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response == 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: "Your file has been deleted.",
                                    customClass: {
                                        confirmButton: "btn btn-success waves-effect",
                                    },
                                })
                                window.setTimeout(function() {
                                    window.location.href = '/service-reports';
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Data Failed to Delete!'
                                });
                            }
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Cancelled",
                        text: "Your imaginary file is safe :)",
                        icon: "error",
                        customClass: {
                            confirmButton: "btn btn-success waves-effect",
                        },
                    });
                }
            });
            // Swal.fire({
            //     title: "Are you sure?",
            //     text: "You won't be able to revert this!",
            //     icon: "warning",
            //     showCancelButton: true,
            //     confirmButtonColor: "#3085d6",
            //     cancelButtonColor: "#d33",
            //     confirmButtonText: "Yes, delete it!"
            // }).then((result) => {
            //     if (result.isConfirmed) {
            //         $.ajax({
            //             'url': '{{ url('leads') }}/' + id,
            //             'type': 'POST',
            //             'data': {
            //                 '_method': 'DELETE',
            //                 '_token': '{{ csrf_token() }}'
            //             },
            //             success: function(response) {
            //                 if (response == 1) {
            //                     Swal.fire({
            //                         title: "Deleted!",
            //                         text: "Your file has been deleted.",
            //                         icon: "success"
            //                     })
            //                     window.setTimeout(function() {
            //                         location.reload();
            //                     }, 2000);
            //                 } else {
            //                     Swal.fire({
            //                         icon: 'error',
            //                         title: 'Oops...',
            //                         text: 'Data Failed to Delete!'
            //                     });
            //                 }
            //             }
            //         });
            //     }
            // });
        });
    </script>
@endpush
