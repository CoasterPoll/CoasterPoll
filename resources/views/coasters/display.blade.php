@extends('layouts.app')

@section('title')
    Big List of Coasters
@endsection

@section('content')
    <h1>Massive List of Coasters</h1>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped">
                <tbody>
                    @foreach($coasters as $coaster)
                        <tr>
                            <td><a href="{{ route('coasters.coaster', ['park' => $coaster->park->short, 'coaster' => $coaster->slug]) }}" class="link-unstyled">{{ $coaster->name }}</a></td>
                            <td><a href="{{ route('coasters.park', ['park' => $coaster->park->short]) }}">{{ $coaster->park->name }}</a> <small>{{ $coaster->park->city }}</small></td>
                            <td><a href="{{ route('coasters.manufacturer', ['manufacturer' => $coaster->manufacturer->abbreviation]) }}">{{ $coaster->manufacturer->name }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $coasters->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection

@section('scripts')
    @include('coasters._scripts')
@endsection