@extends('layouts.app')

@section('title')
    About Sponsorships
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Sponsorship?</h1>
            <p>Yes, we know. Pretty much everyone hates (and blocks) them. But this place is expensive to keep running.
                Since they are seemingly unavoidable, our concession is that they are hosted by us, we ensure they
                meet our standards, and are unobtrusive.</p>
            <hr>
            @cannot('Can sponsor')
                <p>I'm sorry. You are not eligible to join this program.</p>
            @else
                <p>Just click below to get started!</p>
                <form action="{{ route('ads.join') }}" method="post">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-lg btn-primary">Join Program</button>
                </form>
            @endcannot
        </div>
    </div>
@endsection

@section('scripts')

@endsection