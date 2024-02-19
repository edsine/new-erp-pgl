<!DOCTYPE html>
@php
    // $logo=asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo/');

    $company_logo = Utility::getValByName('company_logo_dark');

    $company_logos = Utility::getValByName('company_logo_light');
    $company_favicon = Utility::getValByName('company_favicon');
    $setting = \App\Models\Utility::colorset();
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';
    $company_logo = \App\Models\Utility::GetLogo();
    $SITE_RTL = isset($setting['SITE_RTL']) ? $setting['SITE_RTL'] : 'off';
    $mode_setting = \App\Models\Utility::mode_layout();

@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ isset($setting['SITE_RTL']) && $setting['SITE_RTL'] == 'on' ? 'rtl' : '' }}">

<head>
    <title>
        {{ Utility::getValByName('title_text') ? Utility::getValByName('title_text') : config('app.name', 'ERPGO') }}
        - @yield('page-title')</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Dashboard Template Description" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="Rajodiya Infotech" />

    <!-- Favicon icon -->
    <link rel="icon"
        href="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
        type="image/x-icon" />

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    @if ($setting['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
    @endif
    @if ($setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">

    <style>
        .new-back{
            background: linear-gradient(270deg, transparent, rgba(183, 244, 161, 0.3), rgba(70, 255, 9, 0.2), rgba(255, 255, 255), rgba(255, 255, 255)), url('{{asset("/assets/images/auth/login-img-background.jpg")}}');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            width: 100%;
        }
        .form-div{
            padding: 2.5rem;
        }
    </style>

</head>

<body class="{{ $color }}">
    {{-- <div class="auth-wrapper auth-v3 new-back">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content"> 
        </div>
    </div> --}}

    <div class="new-back">
        <div class="container bg-light bg-opacity-10" style="min-height: 100vh">
            <nav class="navbar navbar-expand-lg navbar-light ">
                <div class="container pe-2">
                    <a class="navbar-brand" href="#">
                        {{-- @if ($setting['cust_darklayout'] && $setting['cust_darklayout'] == 'on')
                            <img src="{{ $logo . '/' . (isset($company_logos) && !empty($company_logos) ? $company_logos : 'logo-dark.png') }}"
                                alt="{{ config('app.name', 'ERPGo') }}" class="logo w-50">
                        @else
                            <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"
                                alt="{{ config('app.name', 'ERPGo') }}" class="logo w-50">
                        @endif --}}

                        <img class="d-inline-block align-text-top" src="{{ asset('assets/images/auth/PGL-logo.png') }}" alt="" width="250px" height="130px">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse bg-white rounded-pill px-4" id="navbarTogglerDemo01" style="flex-grow: 0 !important;">
                        <ul class="navbar-nav align-items-center ms-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">Support</a>
                            </li>
                            <li class="nav-item px-3">
                                <a class="nav-link" href="#">Terms</a>
                            </li>
                            <li class="nav-item px-3">
                                <a class="nav-link" href="#">Privacy</a>
                            </li>
                            @yield('auth-topbar')
                        </ul>
                    </div>
                </div>
            </nav>
    
            <div class="container">
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h3>Welcome to Pyrich Group ERP</h3>
                        <p><i><b> ...where precision meets performance!</b></i></p>
                        <h6>
                            Experience the synergy of accuracy and efficiency,<br>
                            making every operation a masterpiece in the realm of <br>
                            Pyrich Groups ERP
                        </h6>
                    </div>
                </div>

                <div class="row" style="margin-top: 4rem">
                    <div class="col-xl-6">
                        <div class="shadow bg-success bg-opacity-50 rounded-3 mb-3 shadow form-div">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6">
                            <p class="">
                                {{ Utility::getValByName('footer_text') ? Utility::getValByName('footer_text') : __('Copyright ERPGO') }}
                                {{ date('Y') }}
                            </p>
                        </div>
    
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <!-- [ auth-signup ] end -->

    <!-- Required Js -->
    <script src="{{ asset('assets/js/vendor-all.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>


    <script>
        feather.replace();
        var pctoggle = document.querySelector("#pct-toggler");
        if (pctoggle) {
            pctoggle.addEventListener("click", function() {
                if (
                    !document.querySelector(".pct-customizer").classList.contains("active")
                ) {
                    document.querySelector(".pct-customizer").classList.add("active");
                } else {
                    document.querySelector(".pct-customizer").classList.remove("active");
                }
            });
        }

        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];

            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }



        var custthemebg = document.querySelector("#cust-theme-bg");
        custthemebg.addEventListener("click", function() {
            if (custthemebg.checked) {
                document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.add("transprent-bg");
            } else {
                document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.remove("transprent-bg");
            }
        });

        var custdarklayout = document.querySelector("#cust-darklayout");
        custdarklayout.addEventListener("click", function() {
            if (custdarklayout.checked) {
                document
                    .querySelector(".m-header > .b-brand > .logo-lg")
                    .setAttribute("src", "{{ asset('assets/images/logo.svg') }}");
                document
                    .querySelector("#main-style-link")
                    .setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
            } else {
                document
                    .querySelector(".m-header > .b-brand > .logo-lg")
                    .setAttribute("src", "{{ asset('assets/images/logo-dark.png') }}");
                document
                    .querySelector("#main-style-link")
                    .setAttribute("href", "{{ asset('assets/css/style.css') }}");
            }
        });

        function removeClassByPrefix(node, prefix) {
            for (let i = 0; i < node.classList.length; i++) {
                let value = node.classList[i];
                if (value.startsWith(prefix)) {
                    node.classList.remove(value);
                }
            }
        }
    </script>
    @stack('custom-scripts')
</body>

</html>
