<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@if (!empty($settings->title)) {{ $settings->title }} @else {{ env('APP_NAME') }} @endif</title>
    <meta name="description" content="@if (!empty($settings->description)) {{ $settings->description }} @else {{ env('APP_NAME') }} @endif">
    <meta name="author" content="{{ env('APP_NAME') }}">
    <meta name="keywords" content="@if (!empty($settings->keywords)) {{ $settings->keywords }} @else {{ env('APP_NAME') }} @endif">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-5.1.3/css/bootstrap.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('vendor/adminkit-3-1-0/static/img/icons/icon-48x48.png') }}" />
    <link href="{{ asset('vendor/fontawesome-5.15.4-web/css/all.min.css') }}" rel="stylesheet">

    <link href="{{ asset('vendor/adminkit-3-1-0/static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    @yield('styles')
</head>

<body>

    <main class="d-flex w-100">
        @yield('content')
    </main>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-5.1.3/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/fontawesome-5.15.4-web/js/all.min.css') }}"></script>
    <script src="{{ asset('vendor/adminkit-3-1-0/static/js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.alert-autohide').fadeTo(5000, 500).slideUp(500, function() {
                $('.alert-autohide').slideUp(500);
            });

            $(document).on("submit", "form", function() {
                $(this).find(":submit").attr("disabled", true).html("<i class='fa fa-spinner fa-spin'></i> Please wait...");
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
