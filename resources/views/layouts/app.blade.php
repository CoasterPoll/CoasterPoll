<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'ChV3') }}</title>

    <!-- Styles -->
    <link href="{{ env('CDN_URL', env('APP_URL')) }}/css/bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ env('CDN_URL', env('APP_URL')) }}/css/sweetalert.css" type="text/css">
    <link rel="stylesheet" href="{{ env('CDN_URL', env('APP_URL')) }}/css/font-awesome.min.css" media="all">
    <link rel="stylesheet" href="{{ env('CDN_URL', env('APP_URL')) }}/css/toastr.min.css">
    <link rel="stylesheet" href="{{ env('CDN_URL', env('APP_URL')) }}/css/introjs.min.css">
    <link rel="stylesheet" href="{{ env('CDN_URL', env('APP_URL')) }}/css/app.css">

    <!-- Favicon Stuff -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="CoasterPoll">
    <meta name="application-name" content="CoasterPoll">
    <meta name="theme-color" content="#ffffff">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
        @isset($_new_url)
            window.history.replaceState({}, '', '{!! $_new_url !!}');
        @endisset
        @isset($_hash)
            window.location.hash = '{!! $_hash !!}';
        @endisset
    </script>
</head>
<body>
    <nav class="navbar fixed-top bg-faded nav-transparent nav-fadable navbar-toggleable-sm hidden-print" id="main-nav">
        <button class="navbar-toggler-right btn btn-outline-info my-0 hidden-md-up fade-on-collapse" id="collapseHeader" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
        </button>
        <a class="navbar-brand" href="/">
            <img src="{{ env('CDN_URL', env('APP_URL')) }}/img/CoasterPoll-Navbar.png" alt="CoasterPoll.com" width="30" height="30">
            {{ config('app.name', 'ChV3') }}
        </a>
        @include('layouts._navbar')
    </nav>
    <div class="@if(isset($_override_container)){{ $_override_container }} @else container @endif" id="headContainer">
        @include('layouts._flash')
        @yield('content')
    </div>
    @include('layouts._footer')
    <!-- Scripts -->
    <script src="{{ env('CDN_URL', env('APP_URL')) }}/js/jquery-2.2.3.min.js"></script>
    <script src="{{ env('CDN_URL', env('APP_URL')) }}/js/tether.min.js"></script>
    <script src="{{ env('CDN_URL', env('APP_URL')) }}/js/bootstrap4.min.js"></script>
    <script src="{{ env('CDN_URL', env('APP_URL')) }}/js/toastr.min.js"></script>
    <script src="{{ env('CDN_URL', env('APP_URL')) }}/js/bootbox.min.js"></script>
    <script src="{{ env('CDN_URL', env('APP_URL')) }}/js/intro.min.js"></script>
    <script src="{{ env('CDN_URL', env('APP_URL')) }}/js/app.js"></script>
    @include('coasters._scripts')
    @yield('scripts')
</body>
</html>
