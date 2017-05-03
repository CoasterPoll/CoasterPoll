@php ($_override_footer_image = "/img/500-tower.png")
@php ($_override_container = "container-fluid")

@extends('layouts.app')

@section('title')
    500 - Server Error
@endsection

@section('content')
    <div class="row mt-4 pt-4">
        <div class="col-md-4 col-sm-6 offset-md-2 text-center mt-4 pt-4">
            <h1 class="display-3 mt-4 pt-4">That's Not Right</h1>
            <h1 class="lead">500 - We screwed up</h1>
            <p>Sorry. We'll take a look and try to fix it.</p>
        </div>
    </div>
    <style>
        #headContainer {
            background-image: url({{ asset('img/500-tower.png') }});
            background-size: contain;
            background-position: center bottom;
            background-repeat: no-repeat;
            min-height: 80vh;
        }
    </style>
@endsection