@extends('layouts.app')

@section('title')
    Coasters You've Ridden
@endsection

@section('content')
    @if($coasters->count() > 0)
        <h1>Coasters We Know You've Ridden</h1>
    @endif
    <div class="row">
        <div class="col-md-10 offset-md-1 text-center">
            @if($coasters->count() > 0)
                <table class="table">
                    <tbody>
                    @foreach($coasters as $coaster)
                        <tr>
                            <th><a href="{{ route('coasters.coaster', ['park' => $coaster->park->short, 'coaster' => $coaster->slug]) }}" class="link-unstyled">{{ $coaster->name }}</a></th>
                            <td><a href="{{ route('coasters.park', ['park' => $coaster->park->short]) }}">{{ $coaster->park->name }}</a></td>
                            <td><a href="{{ route('coasters.manufacturer', ['manufacturer' => $coaster->manufacturer->abbreviation]) }}">{{ $coaster->manufacturer->name }}</a></td>
                            @can('Can track coasters')
                                <td>@include('coasters._ridden', ['ridden_coaster_id' => $coaster->id])</td>
                            @endcan
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h1 class="display-4 mt-4 pt-4">No Coasters Yet?</h1>
                <p class="lead">You can add more from the <a href="{{ route('coasters.search') }}">search</a> bar above, or from the <a href="{{ route('coasters.coasters') }}">big list of coasters</a>.</p>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    @include('coasters._scripts')
@endsection