<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card mb-3">
            @if($coaster->hasImg())
                <img class="card-img-top" src="{{ $coaster->getImg() }}" alt="{{ $coaster->name }}" style="width: 100%">
            @endif
            <div class="card-block">
                <h4 class="card-title"><a href="{{ route('coasters.park', ['park' => $coaster->park->short]) }}" class="lead-unstyled">{{ $coaster->park->name }}</a></h4>
                <h6 class="card-subtitle">{{ $coaster->park->city }}</h6>
                <p class="card-text mt-2">Made by <a href="{{ route('coasters.manufacturer', ['manufacturer' => $coaster->manufacturer->abbreviation]) }}">{{ $coaster->manufacturer->name }}</a></p>
                <p class="card-text">
                <ul>
                    @foreach($coaster->categories as $category)
                        <li><a href="{{ route('coasters.search') }}?q={{ $category->name }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
                </p>
                <p class="card-text">{{ $coaster->copyright }}</p>
                @if(config('app.links'))
                    <a class="card-link" href="{{ route('links.submit.on', ['on' => "C".$coaster->id]) }}">Post To</a>
                @endif
                <a class="card-link" href="{{ route('contact.coaster', ['id' => $coaster->id]) }}">Report</a>
                @if($coaster->rcdb_id !== null)
                    <a class="card-link" href="https://rcdb.com/{{ $coaster->rcdb_id }}.htm">View on RCDB</a>
                @endif
                @can('Can track coasters')
                    <div class="text-right">
                        @include('coasters._ridden', ['ridden_coaster_id' => $coaster->id])
                    </div>
                @endcan
            </div>
        </div>
        @if(config('app.links'))
            @each('sharing.link-card', $coaster->links, 'link')
        @endif
    </div>
</div>