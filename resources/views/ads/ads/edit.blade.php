@extends('layouts.app')

@section('title')
    Edit Ad
@endsection

@section('content')
    <div class="row">
        @include('ads._nav', ['_active' => null])
        <div class="col-9">
            <h2>Edit @null($ad) "{{ $ad->name }}" @else New @endnull</h2>
            <div class="row mt-4">
                <div class="col-md-8">
                    <form action="{{ route('ads.ad.save') }}" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-6">
                                <fieldset class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $ad->name ?? "") }}">
                                    <p class="text-muted">Internal name only.</p>
                                </fieldset>
                            </div>
                            <div class="col-6">
                                <fieldset class="form-group">
                                    <label for="campaign">Campaign</label>
                                    <select name="campaign" class="form-control" id="campaign">
                                        @foreach($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}" @if($ad->campaign ?? 0 == $campaign->id) selected @endif >{{ $campaign->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-muted">Which campaign we should attach this to.</p>
                                </fieldset>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <fieldset class="form-group">
                                    <label for="img_url">Image URL</label>
                                    <input type="text" class="form-control" id="img_url" name="img_url" value="{{ old('img_url', $ad->img_url ?? "") }}">
                                    <p class="text-muted">The url to the image being displayed. Uploading an image (<i class="fa fa-arrow-right"></i>) will fill this in for you.</p>
                                </fieldset>
                            </div>
                            <div class="col-6">
                                <fieldset class="form-group">
                                    <label for="image">Upload Image</label>
                                    <input type="file" class="form-control form-control-file" id="image" name="image">
                                    <p class="text-muted">Uploading an image will overwrite the saved url (<i class="fa fa-arrow-left"></i>).</p>
                                </fieldset>
                            </div>
                        </div>
                        <fieldset class="form-group">
                            <label for="img_href">Image Link</label>
                            <input type="text" id="img_href" class="form-control" name="img_href" value="{{ old('img_href', $ad->img_href ?? "") }}">
                            <p class="text-muted">Where the image links to.</p>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="img_alt">Image Alt Text</label>
                            <input type="text" id="img_alt" class="form-control" name="img_alt" value="{{ old('img_alt', $ad->img_alt ?? "") }}">
                            <p class="text-muted">Text shown when the image doesn't load, or a user hovers over the image.</p>
                        </fieldset>
                        <hr>
                        <fieldset class="form-group">
                            <label for="sponsor">Sponsor Name</label>
                            <input type="text" id="sponsor" class="form-control" name="sponsor" value="{{ old('sponsor', $ad->sponsor ?? "") }}">
                            <p class="text-muted">The name of the organization who sponsored the ad.</p>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="sponsor_href">Sponsor Link</label>
                            <input type="text" id="sponsor_href" class="form-control" name="sponsor_href" value="{{ old('sponsor_href', $ad->sponsor_href ?? "") }}">
                            <p class="text-muted">The sponsor name can link to a separate location than the ad image itself.</p>
                        </fieldset>
                        <fieldset class="form-group">
                            {{ csrf_field() }}
                            @null($ad)
                                <input type="hidden" name="ad" value="{{ $ad->id }}">
                            @endnull
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                        </fieldset>
                    </form>
                    @null($ad)
                        <form action="{{ route('ads.ad.delete') }}" method="post">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <input type="hidden" name="ad" value="{{ $ad->id }}">
                            <button type="submit" class="btn btn-danger confirm-form"><i class="fa fa-trash"></i> Delete</button>
                        </form>
                    @endnull
                </div>
                <div class="col-md-4">
                    <div class="card" id="example-ad" style="position: fixed;">
                        <a href="{{ old('img_href', $ad->img_href ?? "#") }}" id="example-link">
                            <img class="card-img-top" id="example-img" style="max-height: 250px;" src="{{ old('img_url', $ad->img_url ?? "https://placehold.it/300x300?text=Your%20Image%20Here") }}" alt="{{ old('img_alt', $ad->img_alt ?? "Fun Fact...") }}">
                        </a>
                        <div class="card-footer text-muted text-sm small">
                            <i class="fa fa-bullhorn text-primary"></i> Sponsored by <a href="{{ old('sponsor_href', $ad->sponsor_href ?? "#") }}" id="example-sponsor">{{ old('sponsor', $ad->sponsor ?? "You!") }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#sponsor').on('keyup', function() {
            $('#example-sponsor').html($(this).val());
        });
        $('#sponsor_href').on('keyup', function() {
            $('#example-sponsor').attr('href', $(this).val());
        });
        $('#img_url').on('change', function() {
            $('#example-img').attr('src', $(this).val());
        });
        $('#img_href').on('keyup', function() {
            console.log("keypress");
            $('#example-link').attr('href', $(this).val());
        });
        $('#img_alt').on('change', function() {
            $('#example-img').attr('alt', $(this).val());
        });
    </script>
@endsection