<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="vertical-menu-template">

<head>
    @include('includes.sales.meta')

    {{--  css  --}}
    @stack('before-style')

    @include('includes.sales.style')

    @stack('after-style')


    {{--  laravel style  --}}
    <script src="{{ asset('/assets') }}/vendor/js/helpers.js"></script>

    {{-- ! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section --}}
    {{-- ? Template customizer: To hide customizer set displayCustomizer value false in config.js.  --}}
    <script src="{{ asset('/assets') }}/vendor/js/template-customizer.js"></script>

    {{--  ? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.   --}}
    <script src="{{ asset('assets') }}/js/config.js"></script>
    @routes
    @if (Auth::check() && Auth::id() === 22)
        <style>
            body::before {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: url('{{ asset('asset/bg-shandy.gif') }}');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                filter: blur(8px);
                opacity: 0.9;
                z-index: -1;
            }

            body {
                cursor: url('{{ asset('asset/cursor-sandy.ico') }}'), auto;
            }
        </style>
    @endif
    @if ((Auth::check() && Auth::id() === 23) || Auth::id() === 16 || Auth::id() === 18)
        <style>
            body::before {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: url('{{ asset('asset/bg-ari.jpg') }}');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                filter: blur(8px);
                opacity: 0.9;
                z-index: -1;
            }
        </style>
    @endif
</head>

<body>
    @if (Auth::check() && Auth::id() === 16)
        <audio id="bgm" autoplay loop style="display: none;">
            <source src="{{ asset('asset/sound-ari.mp3') }}" type="audio/mpeg">
        </audio>
    @endif
    <!--  Layout wrapper  -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!--  Side bar  -->
            @include('components.dashboard.sidebar')
            <!--  END: Side Bar  -->

            <!-- Layout Page -->
            <div class="layout-page">

                <!--  Navbar  -->
                @include('layouts.sales.navbar')
                <!--  END: Navbar  -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    @if (!View::hasSection('no-container'))
                        <div class="container-xxl flex-grow-1 container-p-y">
                            <!--  Content  -->
                            @yield('content')
                            <!--  END: Content  -->
                        </div>
                    @else
                        <!--  Content  -->
                        @yield('content')
                        <!--  END: Content  -->
                    @endif
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- END : Content Wrapper -->

            </div>
            <!-- End : Layout Page -->
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>

    {{--  javascript --}}
    @stack('before-script')

    @include('includes.sales.script')

    @stack('after-script')

    {{-- Main JS --}}
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

    @stack('page-script')

    @stack('script')

    {{-- Modal NPWP Error (dipakai saat klik Upload PO dengan NPWP tidak valid) --}}
    <div class="modal fade" id="modalNpwpError" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content text-center">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <i class="mdi mdi-alert-circle text-danger" style="font-size: 56px;"></i>
                    </div>
                    <h5 class="fw-bold text-danger mb-2">NPWP Belum Lengkap!</h5>
                    <p class="text-muted mb-4">TOLONG ISI NPWP CLIENT TERLEBIH DAHULU (minimal 10 angka).</p>
                    <button type="button" class="btn btn-danger waves-effect waves-light px-5"
                        data-bs-dismiss="modal">OK, Mengerti</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-upload-po').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var npwp = (this.dataset.npwp || '').replace(/[^0-9]/g, '');
                    if (npwp.length <= 10) {
                        new bootstrap.Modal(document.getElementById('modalNpwpError')).show();
                    } else {
                        new bootstrap.Modal(document.getElementById('uploadPo')).show();
                    }
                });
            });
        });
    </script>

</body>

</html>
