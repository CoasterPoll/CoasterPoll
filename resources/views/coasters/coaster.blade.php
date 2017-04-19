@extends('layouts.app')

@section('title')
    {{ $coaster->name }}
@endsection

@section('content')
    <h1>{{ $coaster->name }}</h1>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                @if($coaster->img_url !== "")
                    <img class="card-img-top" src="{{ $coaster->img_url }}" alt="{{ $coaster->name }}">
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
        </div>
    </div>
@endsection