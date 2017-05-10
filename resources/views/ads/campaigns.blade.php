@extends('layouts.app')

@section('title')
    Your Campaigns
@endsection

@section('content')
    <div class="row">
        @include('ads._nav', ['_active' => "campaigns"])
        <div class="col-9">
            <h2>Your Campaigns <span class="pull-right"><a href="{{ route('ads.campaign.new') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Create New</a></span></h2>
            <table class="table table-striped mt-4">
                <thead>
                    <tr>
                        <th></th>
                        <th>Starts</th>
                        <th>Ends</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $campaign)
                        <tr @if($campaign->isActive()) class="table-success" @endif >
                            <th><a href="{{ route('ads.campaign', ['campaign' => $campaign->id]) }}">{{ $campaign->name }}</a></th>
                            <td>{{ $campaign->start_at->diffForHumans() }} ({{ $campaign->start_at->format('m-d-Y g:ia') }})</td>
                            <td>{{ $campaign->end_at->diffForHumans() }} ({{ $campaign->end_at->format('m-d-Y g:ia') }})</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection