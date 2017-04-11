<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'ChV3') }} Console</title>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sweetalert.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-info">
        <button class="navbar-toggler navbar-toggler-right hidden-lg-up" type="button" data-toggle="collapse" data-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="navbar-brand">
            <a class="navbar-brand" href="{{ route('admin') }}" style="align-self: flex-start;">{{ config('app.name', 'ChV3') }} Console</a>
            <button role="button" class="navbar-toggler btn btn-outline-success hidden-sm-up" id="show-xs-sidebar">Show Sidebar</button>
        </span>
        <div class="navbar-collapse collapse" id="main-nav">
            <ul class="navbar-nav mr-auto my-auto">
                <li class="nav-item">
                    <a href="/" class="nav-link"><i class="fa fa-backward"></i> Back to {{ config('app.name', 'ChV3') }}</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <form class="form-inline mr-sm-2" action="{{ route('admin.search') }}" method="get">
                    <div class="input-group">
                        <input class="form-control" type="search" name="q" placeholder="Search">
                        <span class="input-group-btn">
                            <button class="btn btn-success my-2 my-sm-0" type="submit" title="Search"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="fa fa-fw fa-sign-out"></i> Sign Out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-sm-3 col-md-2 hidden-xs-down bg-faded sidebar" id="sidebar">
                <ul class="nav nav-pills flex-column">
                    <li><span class="nav-link lead pb-0"><i class="fa fa-sitemap fa-fw"></i> Main Site</span></li>
                </ul>
                <ul class="nav nav-pills flex-column">
                    <li><span class="nav-link lead pb-0"><i class="fa fa-users fa-fw"></i> Users</span></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.user.roles') }}">User Roles</a></li>
                </ul>
            </nav>
            <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
                <h1>@yield('title')</h1>
                @include('layouts._flash')
                @yield('content')
            </main>
        </div>
    </div>

<!-- Scripts -->
<script src="{{ asset('js/jquery-2.2.3.min.js') }}"></script>
<script src="{{ asset('js/tether.min.js') }}"></script>
<script src="{{ asset('js/bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('js/bootbox.min.js') }}"></script>
</body>
</html>
