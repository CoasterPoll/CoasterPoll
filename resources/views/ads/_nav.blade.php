<div class="col-3">
    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a class="nav-link @if($_active == "dashboard") active @endif" href="{{ route('ads') }}">Sponsor Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if($_active == "campaigns") active @endif" href="{{ route('ads.campaigns') }}">Campaigns</a>
        </li>
    </ul>
</div>