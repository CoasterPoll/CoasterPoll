<div class="row">
    <div class="col-md-6">
        <div class="card">
            @if($park->hasImg())
                <img class="card-img-top" src="{{ $park->getImg() }}" alt="{{ $park->name }}">
            @endif
            <div class="card-block">
                <h4 class="card-title">{{ $park->short }}</h4>
                <h6 class="card-subtitle">{{ $park->city }}, {{ $park->country }}</h6>
                <p class="card-text">{{ $park->copyright }}</p>
                <a class="card-link" href="{{ route('links.submit.on', ['on' => "P".$park->id]) }}">Post To</a>
                @if($park->website !== null)
                    <a class="card-link" href="{{ $park->website }}">Website</a>
                @endif
                @if($park->rcdb_id !== null)
                    <a class="card-link" href="https://rcdb.com/{{ $park->rcdb_id }}.htm">View on RCDB</a>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-block">
                @if($park->coasters->count() > 0)
                    <table class="table">
                        <tbody>
                        @foreach($park->coasters as $coaster)
                            <tr>
                                <th><a href="{{ route('coasters.coaster', ['park' => $park->short, 'coaster' => $coaster->slug]) }}" class="link-unstyled">{{ $coaster->name }}</a></th>
                                <td><a href="{{ route('coasters.manufacturer', ['manufacturer' => $coaster->manufacturer->abbreviation]) }}">{{ $coaster->manufacturer->name }}</a></td>
                                @can('Can track coasters')
                                    <td>@include('coasters._ridden', ['ridden_coaster_id' => $coaster->id])</td>
                                @endcan
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="lead"><em>They don't have any coasters. :'(</em></p>
                @endif
            </div>
        </div>
    </div>
</div>