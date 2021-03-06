<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            @if($manufacturer->hasImg())
                <img class="card-img-top" src="{{ $manufacturer->getImg() }}" alt="{{ $manufacturer->name }}">
            @endif
            <div class="card-block">
                <h4 class="card-title">{{ $manufacturer->abbreviation }}</h4>
                <h6 class="card-subtitle">{{ $manufacturer->location }}</h6>
                <p class="card-text">{{ $manufacturer->copyright }}</p>
                @if(config('app.links'))
                    <a class="card-link" href="{{ route('links.submit.on', ['on' => "M".$manufacturer->id]) }}">Post To</a>
                @endif
                @if($manufacturer->website !== null || $manufacturer->website !== "")
                    <a class="card-link" href="{{ $manufacturer->website }}">Website</a>
                @endif
                @if($manufacturer->rcdb_id !== null)
                    <a class="card-link" href="https://rcdb.com/{{ $manufacturer->rcdb_id }}.htm">View on RCDB</a>
                @endif
            </div>
        </div>
        @if(config('app.links'))
            @each('sharing.link-card', $manufacturer->links, 'link')
        @endif
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-block">
                <table class="table">
                    <tbody>
                    @foreach($manufacturer->coasters as $coaster)
                        <tr>
                            <th><a href="{{ route('coasters.coaster', ['park' => $coaster->park->short, 'coaster' => $coaster->slug]) }}" class="link-unstyled">{{ $coaster->name }}</a></th>
                            <td><a href="{{ route('coasters.park', ['park' => $coaster->park->short]) }}">{{ $coaster->park->name }}</a></td>
                            @can('Can track coasters')
                                <td>@include('coasters._ridden', ['ridden_coaster_id' => $coaster->id])</td>
                            @endcan
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>