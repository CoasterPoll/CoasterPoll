@extends('layouts.app')

@section('title')
    Coasters
@endsection

@section('content')
    @include('coasters.nav')
    Howdy!
@endsection

@section('scripts')
    @include('coasters._scripts')
@endsection