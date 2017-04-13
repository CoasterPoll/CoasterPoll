@extends('layouts.app')

@section('title')
    {{ $manufacturer->name }}
@endsection

@section('content')
    @include('coasters.nav')
    <h1>{{ $manufacturer->name }}</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                @if($manufacturer->img_url !== "")
                    <img class="card-img-top" src="{{ $manufacturer->img_url }}" alt="{{ $manufacturer->name }}">
                @endif
                <div class="card-block">
                    <h4 class="card-title">{{ $manufacturer->abbreviation }}</h4>
                    <h6 class="card-subtitle">{{ $manufacturer->location }}</h6>
                    <p class="card-text">{{ $manufacturer->copyright }}</p>
                    <a class="card-link" href="{{ $manufacturer->website }}">Website</a>
                    @if($manufacturer->rcdb_id !== null)
                        <a class="card-link" href="https://rcdb.com/{{ $manufacturer->rcdb_id }}.htm">View on RCDB</a>
                    @endif
                </div>
            </div>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('coasters._scripts')
@endsection