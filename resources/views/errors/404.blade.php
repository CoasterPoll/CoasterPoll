@php ($_override_footer_image = "/img/broken-track.png")
@php ($_override_container = "container-fluid")

@extends('layouts.app')

@section('title')
    404 - Not Found
@endsection

@section('content')
    <div class="row mt-4 pt-4">
        <div class="col-md-4 col-sm-6 offset-sm-2 offset-md-6 text-center mt-4 pt-4">
            <h1 class="display-1 mt-4 pt-4">Oops...</h1>
            <h1 class="lead">404 - Track Not Found</h1>
            <p>Sorry.</p>
        </div>
    </div>
    <style>
        #headContainer {
            background-image: url({{ asset('img/broken-track.png') }});
            background-size: cover;
            background-repeat: no-repeat;
            min-height: 80vh;
        }
    </style>
@endsection