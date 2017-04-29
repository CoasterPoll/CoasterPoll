@extends('layouts.admin')

@section('title')
    Edit Page
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="{{ route('admin.content.page.post') }}" method="post">
                <fieldset class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control form-control-lg" name="title" id="title" @isset($page) value="{{ $page->title }} @endisset">
                </fieldset>
                <fieldset class="form-group">
                    <label for="subtitle">Subtitle</label>
                    <input type="text" class="form-control" name="subtitle" id="subtitle" @isset($page)value="{{ $page->subtitle }} @endisset ">
                </fieldset>
                <fieldset class="form-group">
                    <label for="url">URL/Slug</label>
                    <input type="text" class="form-control" name="url" id="url" @isset($page)value="{{ $page->url }} @endisset ">
                </fieldset>
                <fieldset class="form-group">
                    <label for="body">Content</label>
                    <textarea class="form-control" name="body" id="body" rows="12">@isset($page) {!! $page->body !!} @endisset</textarea>
                </fieldset>
                <fieldset class="form-group">
                    {{ csrf_field() }}
                    @isset($page)
                        <input type="hidden" name="page" value="{{ $page->id }}">
                    @endisset
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                </fieldset>
            </form>
        </div>
    </div>
@endsection