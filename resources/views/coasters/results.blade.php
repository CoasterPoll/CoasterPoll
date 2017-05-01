@extends('layouts.app')

@section('title')
    {{ $page->name }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-9">
            <h2>{{ $page->name }}</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>% Wins</th>
                        <th>Coaster</th>
                        <th>Park</th>
                        @auth
                        <td></td>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                        <tr>
                            <td>{{ $result->percentage }}%</td>
                            <td><a href="{{ route('coasters.coaster', ['park' => $result->coaster->park->short, 'coaster' => $result->coaster->short]) }}">{{ $result->coaster->name }}</a></td>
                            <td><a href="{{ route('coasters.park', ['park' => $result->coaster->park->short]) }}">{{ $result->coaster->park->name }}</a></td>
                            @auth
                            <td>@include('coasters._ridden', ['ridden_coaster_id' => $result->coaster_id])</td>
                            @endauth
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $results->links('vendor.pagination.bootstrap-4') }}
        </div>
        <div class="col-md-3">
            <div class="card card-block">
                <p>{{ $page->description }}</p>
                <p>Results run at: {{ $page->run_at->toDateTimeString() }}</p>
                @can('Can run results')
                    <a href="{{ route('coasters.results.manage', ['page' => $page->id]) }}">Edit Page</a>
                @endcan
            </div>
        </div>
    </div>
@endsection