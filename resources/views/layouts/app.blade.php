<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.108.0">
    <title>{{ env("APP_NAME", "Charity") }}</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/cover/">
    <link href="{{ asset('vendor/fontawesome-5.15.4-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 960px;
        }

        .select2-container .select2-selection{
            height: 36px;
            border: var(--bs-border-width) solid var(--bs-border-color);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{
            line-height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow{
            height: 36px;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default .select2-selection--multiple {
            height: auto!important;
        }
        .text-italic{
            font-style: italic;
        }
    </style>
    @yield('css')

</head>

<body class="bg-light">

    <nav class="d-flex navbar border-bottom bg-white navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home.index') }}">{{ env('APP_NAME') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('home.index')) active  @endif" href="{{ route('home.index') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('home.donate')) active @endif" href="{{ route('home.donate') }}">Donate</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('home.about')) active  @endif" href="{{ route('home.about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('home.albums')) active @endif" href="{{ route('home.albums') }}">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(Route::is('home.contact')) active @endif" href="{{ route('home.contact') }}">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <ul class="list-inline">
            <li class="list-inline-item"><a href="{{ route('home.privacy-policy') }}" class="text-decoration-none">Privacy Policy</a></li>
        </ul>
        <p class="mb-1">&copy; Copyright 2023</p>
    </footer>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @yield('javascript')
</body>

</html>
