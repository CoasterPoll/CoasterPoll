@extends('layouts.app')

@section('title')
    Shared Links
@endsection

@section('content')
    <div class="row">
        <div class="col-9">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Links</li>
            </ol>
            <h2>Top Recent Links</h2>
            @each('sharing.link-card', $links, 'link')
            {{ $links->links('vendor.pagination.bootstrap-4') }}
        </div>
        <div class="col-3">
            @include('sharing.sidebar')
        </div>
    </div>
@endsection

@section('scripts')

@endsection