@extends('layouts.app')

@section('title')
    Coasters You've Ridden
@endsection

@section('content')
    <h1>Coasters We Know You've Ridden</h1>
    <div class="row">
        <div class="col-md-10 offset-md-1">
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
        </div>
    </div>
@endsection

@section('scripts')
    @include('coasters._scripts')
@endsection