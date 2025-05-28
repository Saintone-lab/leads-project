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
            body{
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
    @if (Auth::check() && Auth::id() === 22)
        <audio id="bgm" autoplay loop style="display: none;">
            <source src="{{ asset('asset/bgm-sandy.mp3') }}" type="audio/mpeg">
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
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!--  Content  -->
                        @yield('content')
                        <!--  END: Content  -->

                    </div>
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

    @if (Auth::check() && Auth::id() === 22)
        <script>
            const bgm = document.getElementById('bgm');

            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    bgm.pause(); // tab tidak aktif → pause
                } else {
                    bgm.play().catch(() => {
                        console.log("Autoplay diblokir. Perlu interaksi user.");
                    }); // tab aktif kembali → play
                }
            });
        </script>
    @endif
</body>

</html>
