@extends('layouts.app')

@section('title')
    Shared Links
@endsection

@section('content')
    <div class="row">
        <div class="col-9">
            <h2>Top Recent Links</h2>
            @each('sharing.link-card', $links, 'link')
        </div>
        <div class="col-3">
            @include('sharing.sidebar')
        </div>
    </div>
@endsection

@section('scripts')

@endsection