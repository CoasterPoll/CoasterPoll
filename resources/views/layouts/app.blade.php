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
    <link href="{{ asset('css/bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sweetalert.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

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
        <a class="navbar-brand" href="/">{{ config('app.name', 'ChV3') }}</a>
        @include('layouts._navbar')
    </nav>
    <div class="@if(isset($_override_container)){{ $_override_container }} @else container @endif" id="headContainer">
        @include('layouts._flash')
        @yield('content')
    </div>
    @include('layouts._footer')
    <!-- Scripts -->
    <script src="{{ asset('js/jquery-2.2.3.min.js') }}"></script>
    <script src="{{ asset('js/tether.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @include('coasters._scripts')
    @yield('scripts')
</body>
</html>
