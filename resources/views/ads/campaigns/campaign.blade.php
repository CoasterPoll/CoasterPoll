@extends('layouts.app')

@section('title')
    Edit Campaign
@endsection

@section('content')
    <div class="row">
        @include('ads._nav', ['_active' => null])
        <div class="col-9">
            <h2>Edit Campaign @null($campaign) "{{ $campaign->name }}" @endnull</h2>
            <form action="{{ route('ads.campaign.save') }}" method="post">
                <div class="row">
                    <div class="col-sm-12">
                        <fieldset class="form-group">
                            <label for="name">Campaign Name</label>
                            <input type="text" name="name" class="form-control" id="name" @null($campaign) value="{{ $campaign->name }}" @endnull>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <fieldset class="form-group">
                            <label for="start_at">Starts At</label>
                            <input type="datetime" name="start_at" class="form-control" id="start_at" @null($campaign) value="{{ $campaign->start_at->toDateTimeString() }}" @endnull placeholder="YYYY-MM-DD HH:MM:SS">
                            <p class="help-block">When to start showing ads in this campaign.</p>
                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                        <fieldset class="form-group">
                            <label for="end_at">Ends At</label>
                            <input type="datetime" name="end_at" class="form-control" id="end_at" @null($campaign) value="{{ $campaign->end_at->toDateTimeString() }}" @endnull placeholder="YYYY-MM-DD HH:MM:SS">
                            <p class="help-block">When to stop showing ads in this campaign.</p>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <fieldset class="form-group">
                            @null($campaign)
                                <input type="hidden" name="campaign" value="{{ $campaign->id }}">
                            @endnull
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                        <p>Dates should be formatted like:<br> <code>YYYY-MM-DD HH:MM:SS</code></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')

@endsection