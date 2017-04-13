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
    </script>
</head>
<body>
    <nav class="navbar fixed-top bg-faded nav-transparent nav-fadable navbar-toggleable-sm hidden-print" id="main-nav">
        <button class="navbar-toggler-right btn btn-outline-info my-0 hidden-md-up fade-on-collapse" id="collapseHeader" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
        </button>
        <a class="navbar-brand" href="/">{{ config('app.name', 'ChV3') }}</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto my-auto">
            <li class="nav-item"><a href="#" class="nav-link">Coasters</a></li>
            </ul>
            <ul class="navbar-nav">
                @if (Auth::guest())
                    <li class="nav-item p-1">
                        <a class="btn btn-outline-success" href="{{ route('register') }}"><i class="fa fa-hand-peace-o"></i> Sign Up</a>
                    </li>
                    <li class="nav-item p-1">
                        <a class="btn btn-outline-info" href="{{ route('login') }}"><i class="fa fa-sign-in"></i> Sign In</a>
                    </li>
                @else
                    @role('Admin')
                        <li class="nav-item"><a href="{{ route('admin') }}" class="nav-link">Admin Console</a></li>
                    @endrole
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                            ><i class="fa fa-fw fa-sign-out"></i> Sign Out</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
    <div class="container" id="headContainer">
        @include('layouts._flash')
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-2.2.3.min.js') }}"></script>
    <script src="{{ asset('js/tether.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/bootbox.min.js') }}"></script>
    @yield('scripts')
</body>
</html>
