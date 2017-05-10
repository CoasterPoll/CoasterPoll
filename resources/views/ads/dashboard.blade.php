@extends('layouts.app')

@section('title')
    Sponsor Dashboard
@endsection

@section('content')
    <div class="row">
        @include('ads._nav', ['_active' => 'dashboard'])
        <div class="col-9">
            <h2>Sponsor Dashboard</h2>
            <h4 class="lead">Thank You! <small>-{{ config('app.name', "CoasterPoll.com") }}</small></h4>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Campaigns</h5>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach($campaigns as $campaign)
                                <a class="list-group-item list-group-item-action @if($campaign->isActive()) list-group-item-success @endif" href="{{ route('ads.campaign', ['campaign' => $campaign->id]) }}">{{ $campaign->name }}</a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection