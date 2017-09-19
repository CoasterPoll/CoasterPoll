<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto my-auto">
        <li class="nav-item"><a href="{{ route('coasters.coasters') }}" class="nav-link">Coasters</a></li>
        @if(config('app.links'))
            <li class="nav-item"><a href="{{ route('links') }}" class="nav-link">Links</a></li>
        @endif
        @can('Can track coasters')
            <li class="nav-item"><a href="{{ route('coasters.ridden') }}" class="nav-link">Ridden</a></li>
        @endcan
        @can('Can rank coasters')
            <li class="nav-item"><a href="{{ route('coasters.rank') }}" class="nav-link">Ranking</a></li>
        @endcan
        @if(\Illuminate\Support\Facades\Cache::get('has-results') === true && !\Illuminate\Support\Facades\Auth::check())
            <li class="nav-item"><a href="{{ route('coasters.results') }}" class="nav-link">Results</a></li>
        @endif
        @can('Can run results')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    Results
                </a>
                <div class="dropdown-menu">
                    @if(\Illuminate\Support\Facades\Cache::get('has-results') === true)
                        <a class="dropdown-item" href="{{ route('coasters.results') }}">View Results</a>
                    @endif
                    <a class="dropdown-item" href="{{ route('coasters.results.manage') }}">Manage</a>
                </div>
            </li>
        @endcan
        @can('Can manage coasters')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    New
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('coasters.coaster.new') }}">Coaster</a>
                    <a class="dropdown-item" href="{{ route('coasters.park.new') }}">Park</a>
                    <a class="dropdown-item" href="{{ route('coasters.manufacturer.new') }}">Manufacturer</a>
                </div>
            </li>
        @endcan
        @isset($_navbar_links)
            @foreach($_navbar_links as $_link)
                <li class="nav-item"><a href="{{ $_link->href }}" class="nav-link">{{ $_link->text }}</a></li>
            @endforeach
        @endisset
    </ul>
    <ul class="navbar-nav">
        <form class="form-inline justify-content-end mr-2" action="{{ route('coasters.search') }}" method="get">
            <input type="text" id="coaster-search" placeholder="&#xf002; Search for a coaster" title="Search for something" class="form-control" name="q" style="font-family:Arial, FontAwesome" autocomplete="off">
            <button class="btn btn-outline-secondary my-2 my-sm-0 sr-only" type="submit" title="Search for a Coaster" style="height: 36px" >Go</button>
        </form>
        @if(Auth::guest())
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
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" id="notifications-dropdown" data-count="{{ $_notifications->count() }}">
                    @if($_notifications->count() > 0)<span class="badge badge-success" id="notification-badge">{{ $_notifications->count() }}</span>@endif
                    <i class="fa @if($_notifications->count() > 0) fa-bell text-warning @else fa-bell-o @endif" id="notifications-icon"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    @foreach($_notifications as $notification)
                        <a class="dropdown-item notification py-0 my-1" href="{{ $notification->data['link'] }}" data-notification="{{ $notification->id }}">
                            <div class="container-fluid mx-0 px-1 notification-container">
                                <div class="card card-block p-1">
                                    <h6 class="lead">@if($notification->read_at == null)<i class="fa fa-circle-o text-info unread-dot"></i> @endif{{ $notification->data['title'] }}</h6>
                                    <p class="my-1">{{ $notification->data['body'] }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                    <a class="dropdown-item py-0 my-1" href="{{ route('notifications') }}">
                        <div class="container-fluid mx-0 px-1 notification-container">
                            <div class="card card-block p-1 text-center">
                                <p class="lead mb-0">All Notifications</p>
                            </div>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('profile', ['handle' => \Illuminate\Support\Facades\Auth::user()->handle]) }}">Your Profile</a>
                    <a class="dropdown-item" href="{{ route('user.settings') }}">Account Settings</a>
                    @if(config('app.subscriptions'))
                        <a class="dropdown-item" href="{{ route('subs.manage') }}">Manage Subscriptions</a>
                    @endif
                    <a class="dropdown-item" href="{{ route('user.demographics') }}">Demographics</a>
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