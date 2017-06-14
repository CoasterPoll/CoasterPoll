@extends('layouts.app')

@section('title')
    {{ $link->title }}
@endsection

@section('content')
    <div class="row">
        <div class="col-9">
            <ol class="breadcrumb">
                @if(in_array($link->linkable_type, ['ChaseH\Models\Coasters\Coaster']))
                    <li class="breadcrumb-item"><a href="{{ $link->linkable->park->getLink() }}">{{ $link->linkable->park->short }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ $link->linkable->getLink() }}">{{ $link->linkable->name }}</a></li>
                @else
                    <li class="breadcrumb-item"><a href="{{ route('links') }}">Links</a></li>
                @endif
                <li class="breadcrumb-item active">{{ $link->title }}</li>
            </ol>
            <div class="card card-block">
                <h1 class="lead"><a href="{{ $link->out() }}" target="_blank">{{ $link->title }}</a></h1>
                <small class="text-muted">Posted by
                    <a href="{{ $link->getPoster()->getProfileLink() }}">{{ $link->getPoster()->handle }}</a>
                    at
                    <span title="{{ $link->created_at->toDateTimeString() }}">{{ $link->created_at->diffForHumans() }}</span>
                </small>
                @null($link->body)
                    {!! $link->body() !!}
                    <hr>
                @endnull
                <a href="{{ url($link->getLink()) }}" class="small">Permalink</a>
            </div>
        </div>
        <div class="col-3">
            @include('sharing.sidebar')
        </div>
    </div>
@endsection

@section('scripts')

@endsection