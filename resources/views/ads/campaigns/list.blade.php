<div class="col-12">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Sponsor Name</th>
                <th>Link</th>
            </tr>
        </thead>
        <tbody>
            @foreach($campaign->ads as $ad)
                <tr>
                    <td><a href="{{ route('ads.ad', ['ad' => $ad->id]) }}">{{ $ad->name }}</a></td>
                    <td>{{ $ad->sponsor }}</td>
                    <td>{{ $ad->link_href }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>