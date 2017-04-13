<nav class="navbar navbar-toggleable-sm navbar-light bg-faded" style="background-color: #e3f2fd; margin-bottom: 15px">
    <a class="navbar-brand" href="{{ route('coasters') }}">Coasters</a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('coasters.coasters') }}">All Coasters</a>
        </li>
    </ul>
    <form class="form-inline justify-content-end" action="{{ route('coasters.search') }}" method="get">
        <input type="text" id="coaster-search" placeholder="&#xf002;" title="Search for something" class="form-control" name="q" style="font-family:Arial, FontAwesome" autocomplete="off">
        <button class="btn btn-secondary" type="submit" hidden="hidden" title="Search for a Coaster" style="height: 36px" >Go</button>
    </form>
</nav>