@extends('layouts.app')

@section('title')
    {{ $park->name }}
@endsection

@section('content')
    <h1>{{ $park->name }}</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                @if($park->img_url !== "")
                    <img class="card-img-top" src="{{ $park->img_url }}" alt="{{ $park->name }}">
                @endif
                <div class="card-block">
                    <h4 class="card-title">{{ $park->short }}</h4>
                    <h6 class="card-subtitle">{{ $park->city }}, {{ $park->country }}</h6>
                    <p class="card-text">{{ $park->copyright }}</p>
                    <a class="card-link" href="{{ $park->website }}">Website</a>
                    @if($park->rcdb_id !== null)
                        <a class="card-link" href="https://rcdb.com/{{ $park->rcdb_id }}.htm">View on RCDB</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-block">
                    <table class="table">
                        <tbody>
                        @foreach($park->coasters as $coaster)
                            <tr>
                                <th><a href="{{ route('coasters.coaster', ['park' => $park->short, 'coaster' => $coaster->slug]) }}" class="link-unstyled">{{ $coaster->name }}</a></th>
                                <td><a href="{{ route('coasters.manufacturer', ['manufacturer' => $coaster->manufacturer->abbreviation]) }}">{{ $coaster->manufacturer->name }}</a></td>
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