@extends('layouts.app')

@section('title')
    Submit
@endsection

@section('content')
    <div class="row">
        <div class="col-9">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('links') }}">Links</a></li>
                <li class="breadcrumb-item active">Submit</li>
            </ol>
            <form action="{{ route('links.submit.post') }}" method="post">
                <fieldset class="form-group {{ $errors->has('title') ? 'has-danger' : '' }}">
                    <label for="title" class="sr-only">Title</label>
                    <input type="text" class="form-control form-control-lg" name="title" id="title" placeholder="An interesting title perhaps?" autofocus tabindex="1" required value="{{ old('input') }}">
                    @if($errors->has('title'))
                        <div class="form-control-feedback">{{ $errors->first('title') }}</div>
                    @endif
                </fieldset>
                @null($on->thing)
                    <fieldset class="form-group">
                        <label for="on" class="sr-only">Submit On</label>
                        <input type="text" class="form-control" id="on" value="{{ $on->getNameOrTitle() }}" readonly>
                    </fieldset>
                @endnull
                <fieldset class="form-group {{ $errors->has('link') ? 'has-danger' : '' }}">
                    <label for="link" class="sr-only">Link</label>
                    <input type="url" class="form-control form-control-feedback" name="link" id="link" tabindex="2" placeholder="Got a cool link to share?">
                    @if($errors->has('link'))
                        <div class="form-control-feedback">{{ $errors->first('link') }}</div>
                    @endif
                </fieldset>
                <fieldset class="form-group {{ $errors->has('body') ? 'has-danger' : '' }}">
                    <label for="body" class="sr-only">body</label>
                    <textarea class="form-control" name="body" id="body" rows="8" tabindex="3" placeholder="And anything to say?"></textarea>
                    @if($errors->has('link'))
                        <div class="form-control-feedback">{{ $errors->first('body') }}</div>
                    @endif
                </fieldset>
                <fieldset class="form-group">
                    <input type="hidden" name="on" value="{{ $on }}">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-primary" tabindex="4"><i class="fa fa-share"></i> Post</button>
                </fieldset>
            </form>
        </div>
        <div class="col-3">
            @include('sharing.sidebar', ['submitBtnActive' => true])
        </div>
    </div>
@endsection

@section('scripts')

@endsection