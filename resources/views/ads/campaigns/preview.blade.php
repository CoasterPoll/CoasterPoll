@foreach($campaign->ads as $ad)
    <div class="col-sm-4 mb-3">
        <div class="card sponsor">
            <a href="{{ route('ads.ad', ['ad' => $ad->id]) }}">
                <img class="card-img-top img-fluid" style="max-height: 300px;" src="{{ $ad->img_url }}" alt="{{ $ad->img_alt }}">
            </a>
            <div class="card-footer text-muted text-sm small">
                <i class="fa fa-bullhorn text-primary"></i> Sponsored by <a href="{{ $ad->sponsor_href }}">{{ $ad->sponsor }}</a>
            </div>
        </div>
    </div>
@endforeach