@extends('layouts.admin')

@section('title')
    Links
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('admin.content.links') }}" class="btn btn-sm btn-secondary mb-4"><i class="fa fa-plus"></i> New</a>
            <table class="table table-sm">
                @foreach($links->groupBy('location') as $location)
                    @foreach($location as $link)
                        <tr>
                            <td>{{ $link->order }}</td>
                            <td><a href="{{ route('admin.content.links', ['link' => $link->id]) }}">{{ $link->text }}</a></td>
                            <td>{{ $link->location }}</td>
                            <td>
                                <form action="{{ route('admin.content.link.delete') }}" method="post">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <input type="hidden" name="link" value="{{ $link->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
        </div>
        <div class="col-md-5">
            <form action="{{ route('admin.content.link.post') }}" method="post">
                <div class="row">
                    <fieldset class="form-group col-sm-8">
                        <label for="text">Text</label>
                        <input type="text" name="text" id="text" class="form-control" @isset($lnk) value="{{ $lnk->text }}" @endisset>
                    </fieldset>
                    <fieldset class="form-group col-sm-4">
                        <label for="order">Order</label>
                        <input type="number" name="order" id="order" class="form-control" @isset($lnk) value="{{ $lnk->order }}" @endisset>
                    </fieldset>
                </div>
                <fieldset class="form-group">
                    <label for="href">Link</label>
                    <input type="text" name="href" id="href" class="form-control" @isset($lnk) value="{{ $lnk->href }}" @endisset>
                </fieldset>
                <fieldset class="form-group">
                    <label for="location">Location</label>
                    <select class="form-control" id="location" name="location">
                        <option value="content" @if(isset($lnk) && $lnk->location == "content") selected @endif>Content</option>
                        <option value="navbar" @if(isset($lnk) && $lnk->location == "navbar") selected @endif>Navbar</option>
                        <option value="footer" @if(isset($lnk) && $lnk->location == "footer") selected @endif>Footer</option>
                    </select>
                </fieldset>
                <fieldset class="form-group">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                </fieldset>
            </form>
        </div>
        <div class="col-md-3">
            <div class="card card-block card-outline-info">
                <nav class="nav flex-column">
                    @foreach($links as $link)
                        @if($link->location == "content")
                            <a class="nav-link" href="{{ $link->href }}">{{ $link->text }}</a>
                        @endif
                    @endforeach
                </nav>
            </div>
        </div>
    </div>
@endsection