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
    <link href="{{ config('app.cdn') }}/css/bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/css/sweetalert.css" type="text/css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/css/font-awesome.min.css" media="all">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/css/toastr.min.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/css/introjs.min.css">
    <link rel="stylesheet" href="{{ config('app.cdn') }}/css/app.css">

    <!-- Favicon Stuff -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ config('app.cdn') }}/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ config('app.cdn') }}/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ config('app.cdn') }}/favicon-16x16.png">
    <link rel="manifest" href="{{ config('app.cdn') }}/manifest.json">
    <link rel="mask-icon" href="{{ config('app.cdn') }}/safari-pinned-tab.svg" color="#5bbad5">
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
    @if(config('ads.google'))
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
            (adsbygoogle = window.adsbygoogle || []).push({
                google_ad_client: "{!! config('ads.client') !!}",
                enable_page_level_ads: true
            });
        </script>
    @endif
    @yield('head')
</head>
<body>
    <nav class="navbar fixed-top bg-faded nav-transparent nav-fadable navbar-toggleable-sm hidden-print" id="main-nav">
        <button class="navbar-toggler-right btn btn-outline-info my-0 hidden-md-up fade-on-collapse" id="collapseHeader" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
        </button>
        <a class="navbar-brand" href="/">
            <img src="{{ config('app.cdn') }}/img/coasterpoll-navbar.png" alt="CoasterPoll.com" width="30" height="30">
            {{ config('app.name', 'ChV3') }}
        </a>
        @include('layouts._navbar')
    </nav>
    @if(config('app.beta'))
        <div class="beta-notice text-center" style="margin-top: {{ (\Illuminate\Support\Facades\Auth::check()) ? "55px;" : "62px;" }}">
            <div class="bg-primary">
                <a href="{{ config('app.betalink') }}" class="text-white">
                    <i class="fa fa-exclamation-triangle"></i> {{ config('app.betatext') }}
                </a>
            </div>
        </div>
    @else
        <div class="beta-notice" style="margin-top: 55px;"></div>
    @endif
    <div class="@if(isset($_override_container)){{ $_override_container }} @else container @endif" id="headContainer">
        @include('layouts._flash')
        @yield('content')
    </div>
    @include('layouts._footer')
    <!-- Scripts -->
    <script src="{{ config('app.cdn') }}/js/jquery-2.2.3.min.js"></script>
    <script src="{{ config('app.cdn') }}/js/tether.min.js"></script>
    <script src="{{ config('app.cdn') }}/js/bootstrap4.min.js"></script>
    <script src="{{ config('app.cdn') }}/js/toastr.min.js"></script>
    <script src="{{ config('app.cdn') }}/js/bootbox.min.js"></script>
    <script src="{{ config('app.cdn') }}/js/intro.min.js"></script>
    <script src="{{ config('app.cdn') }}/js/app.js"></script>
    @include('coasters._scripts')
    @yield('scripts')
</body>
</html>
