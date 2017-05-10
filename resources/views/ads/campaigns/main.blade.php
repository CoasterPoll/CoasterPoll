@extends('layouts.app')

@section('title')
    {{ $campaign->name }}
@endsection

@section('content')
    <div class="row">
        @include('ads._nav', ['_active' => null])
        <div class="col-9">
            <div class="row">
                <div class="col-12">
                    <h2>{{ $campaign->name }}</h2>
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $campaign->progressAsPercentage()  }}%">
                            {{ \Carbon\Carbon::now()->diffForHumans($campaign->start_at) }}
                        </div>
                    </div>
                    <p class="text-muted">
                        <span class="pull-left">{{ $campaign->start_at->diffForHumans() }}</span>
                        <span class="pull-right">{{ $campaign->end_at->diffForHumans() }}</span>
                    </p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <a href="{{ route('ads.campaign.edit', ['campaign' => $campaign->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Change</a>
                        </div>
                        <div class="btn-group ml-3">
                            <form action="{{ route('ads.campaign.delete') }}" method="post">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" name="campaign" value="{{ $campaign->id }}" class="btn btn-danger confirm-form"><i class="fa fa-trash"></i> Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h3 class="mt-3">Ads in Campaign
                        <span class="pull-right">
                            <div class="btn-toolbar">
                                <div class="btn-group mr-2">
                                    <a href="{{ route('ads.ad.new') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New</a>
                                </div>
                                <div class="btn-group">
                                    <button type="button" id="list-ad-view" class="btn btn-secondary @if($view == "list") active @endif" title="List View"><i class="fa fa-list"></i></button>
                                    <button type="button" id="preview-ad-view" class="btn btn-secondary @if($view == "preview") active @endif" title="Preview View"><i class="fa fa-window-restore"></i></button>
                                </div>
                            </div>
                        </span>
                    </h3>
                </div>
            </div>
            <div class="row mt-2" id="ad-view-section">
                @if($view == "preview")
                    @include('ads.campaigns.preview')
                @elseif($view == "list")
                    @include('ads.campaigns.list')
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#list-ad-view').on('click', function() {
            $.get({
                url: "{{ route('ads.campaign.switch.list', ['campaign' => $campaign->id]) }}",
                success: function(resp) {
                    $('#ad-view-section').fadeOut('fast', function() {
                        $(this).html(resp).fadeIn('fast');
                    });
                    $('#list-ad-view').addClass('active').attr('disabled', true);
                    $('#preview-ad-view').removeClass('active').attr('disabled', false);
                },
                error: function(resp) {
                    toastr.error(resp.statusText);
                }
            });
        });
        $('#preview-ad-view').on('click', function() {
            $.get({
                url: "{{ route('ads.campaign.switch.preview', ['campaign' => $campaign->id]) }}",
                success: function(resp) {
                    $('#ad-view-section').fadeOut('fast', function() {
                        $(this).html(resp).fadeIn('fast');
                    });
                    $('#preview-ad-view').addClass('active').attr('disabled', true);
                    $('#list-ad-view').removeClass('active').attr('disabled', false);
                },
                error: function(resp) {
                    toastr.error(resp.statusText);
                }
            });
        });
    </script>
@endsection