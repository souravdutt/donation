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
    <link rel="stylesheet" href="{{ asset("vendor/datatables-bs-5/DataTables-1.11.3/css/dataTables.bootstrap5.min.css") }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <style>
        .text-italic{
            font-style: italic;
        }
    </style>
    @yield('styles')
</head>

<body>

    <div class="wrapper">
        @include('admin.auth.includes.sidebar')

        <div class="main">
            @include('admin.auth.includes.topbar')

            <main class="content">
                @yield('content')
            </main>

            @include('admin.auth.includes.footer')
        </div>

        @yield('modals')
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/adminkit-3-1-0/static/js/app.js') }}"></script>
    <script src="{{ asset("vendor/datatables-bs-5/datatables.min.js") }}"></script>
    <script src="{{ asset("vendor/datatables-bs-5/DataTables-1.11.3/js/dataTables.bootstrap5.min.js") }}"></script>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $('.alert-autohide').fadeTo(5000, 500).slideUp(500, function() {
                $('.alert-autohide').slideUp(500);
            });

            $(document).on("submit", "form", function() {
                $(this).find(":submit").attr("disabled", true).html("<i class='fa fa-spinner fa-spin'></i> Please wait...");
            });
        });

        function getFormattedDateTime(data) {
            let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            let date = new Date(data);
            let month = months[date.getMonth()];
            let day = date.getDate();
            let year = date.getFullYear();
            let hour = date.getHours().toString().length == 1 ? '0' + date.getHours() : date.getHours();
            let minute = date.getMinutes().toString().length == 1 ? '0' + date.getMinutes() : date.getMinutes();
            let second = date.getSeconds().toString().length == 1 ? '0' + date.getSeconds() : date.getSeconds();
            let time = month + ' ' + day + ', ' + year + ' @ ' + hour + ':' + minute + ':' + second;
            return time;
        }
    </script>
    @yield('scripts')
</body>

</html>
