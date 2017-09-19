@extends('layouts.admin')

@section('title')
    Edit Homepage
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="{{ route('admin.general.homepage.post') }}" method="post">
                <fieldset class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control form-control-lg" name="title" id="title" value="{{ config('app.name') }}" readonly>
                </fieldset>
                <fieldset class="form-group">
                    <label for="subtitle">Subtitle</label>
                    <input type="text" class="form-control" name="subtitle" id="subtitle" disabled>
                </fieldset>
                <fieldset class="form-group">
                    <label for="url">URL/Slug</label>
                    <input type="text" class="form-control" name="url" id="url" value="/" readonly>
                </fieldset>
                <fieldset class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" name="content" id="content" rows="12">@isset($content) {!! $content !!} @endisset</textarea>
                </fieldset>
                <fieldset class="form-group">
                    {{ csrf_field() }}
                    <div class="alert alert-danger"><i class="fa fa-lg fa-warning"></i> There is no protection for what is or isn't entered here. Everything is displayed on the homepage as HTML exactly as entered.</div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                </fieldset>
            </form>
        </div>
    </div>
@endsection

@section('scripts')

@endsection