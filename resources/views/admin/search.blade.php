@extends('layouts.admin')

@section('title')
    Search
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <form class="card card-block" action="{{ route('admin.search') }}" method="get">
                <fieldset class="form-group">
                    <label for="query">Search Terms</label>
                    <input type="search" id="query" name="q" class="form-control" @isset($query) value="{{ $query }}" @endisset autofocus>
                </fieldset>
                <fieldset class="form-group text-right">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                </fieldset>
            </form>
        </div>
        @isset($results)
            <div class="col-md-8">
                @foreach($results as $result)
                    <div class="card @if($loop->first) mb-4 @else my-4 @endif @if($result->deleted_at) card-outline-danger @endif ">
                        @if($result instanceof Chaseh\Models\User)
                            <div class="card-block">
                                <h4 class="card-title"><a href="{{ route('admin.user', ['id' => $result->id]) }}" class="link-unstyled">{{ $result->name }}</a> <small>User</small></h4>
                                <ul class="list-unstyled flex-row">
                                    <li><a href="{{ route('admin.user', ['id' => $result->id]) }}">Edit</a></li>
                                    <li><a href="mailto:{{ $result->email }}">{{ $result->email }}</a></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection