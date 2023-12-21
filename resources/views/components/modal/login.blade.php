<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="vertical-menu-template">

<head>
    @include('includes.sales.meta')
    @section('title', 'Login')
    {{--  css  --}}
    @include('includes.sales.style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendor/css/pages/page-auth.css">
    {{--  laravel style  --}}
    <script src="{{ asset('/assets') }}/vendor/js/helpers.js"></script>
    <script src="{{ asset('/assets') }}/vendor/js/template-customizer.js"></script>
    <script src="{{ asset('assets') }}/js/config.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</head>

<body>
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Login -->
                <div class="card p-2">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                            <span class="app-brand-logo demo">
                                <img class="text-md" src="{{ asset('assets') }}/img/favicon/logo-reftech1.png" alt="ini logo">
                            </span>
                            <span class="app-brand-text demo text-heading fw-bold">REFTECH JAYA OPTIMA</span>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body mt-2">
                        <h4 class="mb-2 fw-semibold">Welcome to Reftech App! 👋</h4>
                        <p class="mb-4">Please sign-in to your account and start the adventure</p>

                        <form id="formAuthentication" class="mb-3 fv-plugins-bootstrap5 fv-plugins-framework"
                            action="index.html" method="POST" novalidate="novalidate">
                            <div class="form-floating form-floating-outline mb-3 fv-plugins-icon-container">
                                <input type="text" class="form-control" id="email" name="email-username"
                                    placeholder="Enter your email or username" autofocus="">
                                <label for="email">Email or Username</label>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <div class="mb-3 fv-plugins-icon-container">
                                <div class="form-password-toggle">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="············" aria-describedby="password">
                                            <label for="password">Password</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer"><i
                                                class="mdi mdi-eye-off-outline"></i></span>
                                    </div>
                                </div>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <div class="mb-3 d-flex justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me">
                                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                                </div>
                                <a href="auth-forgot-password-basic.html" class="float-end mb-1">
                                    <span>Forgot Password?</span>
                                </a>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100 waves-effect waves-light"
                                    type="submit">Sign in</button>
                            </div>
                            <input type="hidden">
                        </form>

                        <p class="text-center">
                            <span>New on our platform?</span>
                            <a href="#">
                                <span>Create an account</span>
                            </a>
                        </p>
                    </div>
                </div>
                <!-- /Login -->
                <img alt="mask" src="{{ asset('assets') }}/img/illustrations/auth-basic-login-mask-light.png"
                    class="authentication-image d-none d-lg-block"
                    data-app-light-img="illustrations/auth-basic-login-mask-light.png"
                    data-app-dark-img="illustrations/auth-basic-login-mask-dark.png">
            </div>
        </div>
    </div>
    @include('includes.sales.script')
</body>

</html>
