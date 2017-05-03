@php ($_footer_image = "/img/closed-moose.png")

@extends('layouts.app')

@section('title')
    503 - Not Available
@endsection

@section('content')
    <div class="row mt-4 pt-4">
        <div class="col-8 offset-2 text-center mt-4 pt-4">
            <h1 class="display-1 mt-4 pt-4 hidden-xs-down">Sorry Folks...</h1>
            <h1 class="display-4 mt-4 pt-4 hidden-sm-up">Sorry Folks...</h1>
            <h1 class="lead">We're closed to clean and repair {{ config('app.name') }}.</h1>
            <p>We'll try and be fast. Please don't bring a gun.</p>
        </div>
    </div>
@endsection