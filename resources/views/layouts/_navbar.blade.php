<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto my-auto">
        <li class="nav-item"><a href="{{ route('coasters.coasters') }}" class="nav-link">List</a></li>
        @can('Can track coasters')
            <li class="nav-item"><a href="{{ route('coasters.ridden') }}" class="nav-link">Ridden</a></li>
        @endcan
        @can('Can rank coasters')
            <li class="nav-item"><a href="{{ route('coasters.rank') }}" class="nav-link">Ranking</a></li>
        @endcan
        @can('Can manage coasters')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    New
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('coasters.park.new') }}">Park</a>
                </div>
            </li>
        @endcan
    </ul>
    <ul class="navbar-nav">
        <form class="form-inline justify-content-end mr-2" action="{{ route('coasters.search') }}" method="get">
            <input type="text" id="coaster-search" placeholder="&#xf002;" title="Search for something" class="form-control" name="q" style="font-family:Arial, FontAwesome" autocomplete="off">
            <button class="btn btn-outline-secondary my-2 my-sm-0 sr-only" type="submit" title="Search for a Coaster" style="height: 36px" >Go</button>
        </form>
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