<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../../assets/"
  data-template="vertical-menu-template">

<head>
    @include('includes.sales.meta')

    {{--  css  --}}
    @stack('before-style')

    @include('includes.sales.style')

    @stack('after-style')
    {{--  laravel style  --}}
    <script src="{{ asset('/assets') }}/vendor/js/helpers.js"></script>

    {{--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section --}}
    {{--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  --}}
    <script src="{{ asset('/assets') }}/vendor/js/template-customizer.js"></script>

    {{--  ? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.   --}}
    <script src="{{ asset('assets') }}/js/config.js"></script>

    {{--  Place this tag in your head or just before your close body tag.  --}}
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</head>

<body>
    {{--  Layout wrapper  --}}
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            {{--  Side bar  --}}
            @include('layouts.sales.sidebar')
            {{--  END: Side Bar  --}}

            <div class="layout-page">

                {{--  Navbar  --}}
                @include('layouts.sales.navbar')
                {{--  END: Navbar  --}}

                {{-- Content wrapper --}}
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        {{--  Content  --}}
                        @yield('content')
                        {{--  END: Content  --}}

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--  javascript --}}
    @stack('before-script')

    @include('includes.sales.script')

    @stack('after-script')
</body>

</html>
