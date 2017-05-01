@php ($_override_footer_image = "img/broken-track.png")
@php ($_override_container = "container-fluid")

@extends('layouts.app')

@section('title')
    403 - Not Authorized
@endsection

@section('content')
    <div class="row mt-4 pt-4">
        <div class="col-md-4 col-sm-6 offset-sm-2 offset-md-6 text-center mt-4 pt-4">
            <h1 class="display-3 mt-4 pt-4">WARNING</h1>
            <h4 class="display-4">Do Not Enter</h4>
            <h1 class="lead">403 - Unauthorized</h1>
            <p>Sorry. If we showed you a link that led you here, we'd <a href="{{ route('contact') }}">love to know</a>. Cause we shouldn't have.</p>
        </div>
    </div>
    <style>
        #headContainer {
            background-image: url({{ asset('img/403-raptor.png') }});
            background-size: contain;
            background-position: center bottom;
            background-repeat: no-repeat;
            min-height: 80vh;
        }
    </style>
@endsection