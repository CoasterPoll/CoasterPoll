<div class="card sponsor">
    <a href="{{ $ad->img_href ?? "" }}">
        <img class="card-img-top" style="max-height: 300px;" src="{{ $ad->img_url }}" alt="{{ $ad->img_alt }}">
    </a>
    <div class="card-footer text-muted text-sm small">
        <i class="fa fa-bullhorn text-primary"></i> Sponsored by <a href="{{ $ad->sponsor_href }}">{{ $ad->sponsor }}</a>
    </div>
</div>