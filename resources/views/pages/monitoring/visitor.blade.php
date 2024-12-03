<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="vertical-menu-template">

<head>
    @include('includes.sales.meta')
    @include('includes.sales.style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('assets') }}/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/animate-css/animate.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />


    {{--  laravel style  --}}
    <script src="{{ asset('/assets') }}/vendor/js/helpers.js"></script>

    {{-- ! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section --}}
    {{-- ? Template customizer: To hide customizer set displayCustomizer value false in config.js.  --}}
    <script src="{{ asset('/assets') }}/vendor/js/template-customizer.js"></script>

    {{--  ? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.   --}}
    <script src="{{ asset('assets') }}/js/config.js"></script>
    @routes
</head>

<body>
    <!--  Layout wrapper  -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="container">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-12 col-lg-4">
                                    <img src="" alt="Photo Name Plat" class="w-100 h-100">
                                </div>
                                <div class="col-12 col-lg-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-4">
                                                    <p class="mb-1">Brand </p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-1">: {{ $machine->brand }}</p>
                                                </div>
                                                <div class="col-4">
                                                    <p class="mb-1">Type </p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-1">: {{ $machine->type }}</p>
                                                </div>
                                                <div class="col-4">
                                                    <p class="mb-1">Serial Number </p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-1">: {{ $machine->serial_number }}</p>
                                                </div>
                                                <div class="col-4">
                                                    <p class="mb-1">Bar </p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-1">: {{ $machine->bar }}</p>
                                                </div>
                                                <div class="col-4">
                                                    <p class="mb-1">Running Hour </p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-1">: {{ $machine->running }}</p>
                                                </div>
                                                <div class="col-4">
                                                    <p class="mb-1">Customer </p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-1">: {{ $client->company }}</p>
                                                </div>
                                                <div class="col-4">
                                                    <p class="mb-1">Address </p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-1">: {{ $client->address }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5>Result Monitoring </h5>

                            <div class="card-datatable table-responsive pt-0">
                                <table class="datatable-monitoring table table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Condition</th>
                                            <th>Oil Level</th>
                                            <th>Running</th>
                                            <th>Load</th>
                                            <th>Pressure</th>
                                            <th>Temperature</th>
                                            {{-- <th>PIC</th> --}}
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            {{-- <div class="form-floating form-floating-outline mb-5">
                                <select class="form-select" id="selectMonth" aria-label="Default select example">
                                    <option value="">August 2024</option>
                                    <option value="">September 2024</option>
                                    <option value="">October 2024</option>
                                    <option selected="">November 2024</option>
                                    <option value="1">Desember 2024</option>
                                    <option value="2">January 2025</option>
                                    <option value="3">February 2025</option>
                                </select>
                                <label for="selectMonth">Month - Years</label>
                            </div>
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <th>Date</th>
                                    <th>Running</th>
                                    <th>Load</th>
                                    <th>Pressure</th>
                                    <th>Temperature</th>
                                    <th>Condition</th>
                                    <th>Oil Level</th>
                                    <th>Description</th>
                                </thead>
                                <tbody>
                                    @foreach ($result as $item)
                                        <tr>
                                            <td>{{ $item['date'] }}</td>
                                            <td>{{ $item['running'] }}</td>
                                            <td>{{ $item['load'] }}</td>
                                            <td>{{ $item['pressure'] }}</td>
                                            <td>{{ $item['temp'] }}</td>
                                            <td>{{ $item['condition'] }}</td>
                                            <td>{{ $item['oil_level'] }}</td>
                                            <td>{{ $item['desc'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-backdrop fade"></div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>

    {{--  javascript --}}
    @stack('before-script')

    @include('includes.sales.script')

    <script src="{{ asset('assets') }}/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="{{ asset('assets') }}/js/main.js"></script>

    <script>
        $(document).on('click', '.view-quote', function(e) {
            e.preventDefault(); // Mencegah perubahan halaman segera

            var id = $(this).data('id');
            var idQ = $(this).data('quotation');
            var href = $(this).attr('href'); // Ambil URL tujuan

            $.ajax({
                url: '{{ url('quotation') }}/' + id + '/view_comment',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Token CSRF
                },
                success: function(response) {
                    console.log(response); // Lakukan apa yang perlu dilakukan setelah AJAX sukses

                    // Arahkan ke halaman baru setelah AJAX selesai
                    window.location.href = href;
                },
                error: function(xhr) {
                    console.error("Error:", xhr.responseText); // Tangani error jika ada
                }
            });
        });
        $(document).on('click', '.view-quotation', function(e) {
            e.preventDefault(); // Mencegah perubahan halaman segera

            var id = $(this).data('id');
            var idQ = $(this).data('quotation');
            var href = $(this).attr('href'); // Ambil URL tujuan

            console.log(id);

            $.ajax({
                url: '{{ url('quotation') }}/' + id + '/view_comment',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Token CSRF
                },
                success: function(response) {
                    console.log(response); // Lakukan apa yang perlu dilakukan setelah AJAX sukses

                    // Arahkan ke halaman baru setelah AJAX selesai
                    window.location.href = href;
                },
                error: function(xhr) {
                    console.error("Error:", xhr.responseText); // Tangani error jika ada
                }
            });
        });
        $(document).on('click', '.view-prospect', function(e) {
            e.preventDefault(); // Mencegah perubahan halaman segera

            var id = $(this).data('id');
            var idQ = $(this).data('quotation');
            var href = $(this).attr('href'); // Ambil URL tujuan    

            $.ajax({
                url: '{{ url('prospect') }}/' + id + '/view_comment',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Token CSRF
                },
                success: function(response) {
                    console.log(response); // Lakukan apa yang perlu dilakukan setelah AJAX sukses

                    // Arahkan ke halaman baru setelah AJAX selesai
                    window.location.href = href;
                },
                error: function(xhr) {
                    console.error("Error:", xhr.responseText); // Tangani error jika ada
                }
            });
        });
    </script>

    <script src="{{ asset('assets') }}/js/tables-datatables-basic.js"></script>
    <script src="{{ asset('assets') }}/includes/table-monitoring-machine-visit.js"></script>

    @stack('script')
</body>

</html>
