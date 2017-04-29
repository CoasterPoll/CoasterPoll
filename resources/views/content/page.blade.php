@extends('layouts.app')

@section('title')
    {{ $page->title }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-9">
            <h1>{{ $page->title }}</h1>
            @isset($page->subtitle)<h2 class="lead">{{ $page->subtitle }}</h2> @endisset
            <hr>
            {!! $page->body !!}
        </div>
        <div class="col-md-3">
            <div class="card card-block card-outline-info">
                <nav class="nav flex-column">
                    @foreach($links as $link)
                        <a class="nav-link" href="{{ $link->href }}">{{ $link->text }}</a>
                    @endforeach
                </nav>
            </div>
        </div>
    </div>
@endsection