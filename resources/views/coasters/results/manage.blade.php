@extends('layouts.app')

@section('title')
    Manage Results
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form class="card" action="{{ route('coasters.results.run') }}" method="post">
                <div class="card-header">
                    <h1 class="card-title">Start Processing</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <p class="p-2">Should we constrain the coasters we search?</p>
                        </div>
                    </div>
                    <div class="row px-3">
                        <div class="col-sm-6">
                            <fieldset class="form-group">
                                <label for="park">Park</label>
                                <select name="park[]" class="form-control" id="park" multiple>
                                    @foreach($parks as $park)
                                        <option value="{{ $park->id }}" @if(isset($coaster->park) && $coaster->park->id == $park->id) selected @endif >{{ $park->name }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="type">Type</label>
                                <select id="type" name="type[]" class="form-control" multiple>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" @if(isset($coaster->type) && $coaster->type->id == $type->id) selected @endif>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-sm-6">
                            <fieldset class="form-group">
                                <label for="manufacturer">Manufacturer</label>
                                <select name="manufacturer[]" class="form-control" id="manufacturer" multiple>
                                    @foreach($manufacturers as $manufacturer)
                                        <option value="{{ $manufacturer->id }}" @if(isset($coaster->manufacturer) && $coaster->manufacturer->id == $manufacturer->id) selected @endif >{{ $manufacturer->name }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="categories">Categories</label>
                                <select id="categories" name="categories[]" class="form-control" multiple>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-sm-12">
                            <fieldset class="form-group">
                                <label for="group">Group</label>
                                <input type="text" class="form-control" id="group" name="group">
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    {{ csrf_field() }}
                    <button type="reset" class="btn btn-secondary"><i class="fa fa-undo"></i> Reset</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-wheelchair"></i> Run</button>
                </div>
            </form>
            <form class="card my-3" action="{{ route('coasters.results.group.delete') }}" method="post">
                <div class="card-header">
                    <h1 class="card-title">Processed Groups</h1>
                </div>
                <div class="card-block">
                    <table class="table table-sm">
                        <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <th>{{ $group->group }}</th>
                                    <td class="text-right">
                                        <button type="submit" class="btn btn-outline-danger btn-sm" name="group" value="{{ $group->group }}"><i class="fa fa-trash"></i> Remove All</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title">Result Pages</h1>
                </div>
                <div class="card-block">
                    <table class="table table-sm">
                        <tbody>
                            @foreach($pages as $page)
                                <tr>
                                    <th>{{ $page->name }}</th>
                                    <td><code>{{ $page->group }}</code></td>
                                    <td>{{ $page->run_at->toDateTimeString() }}</td>
                                    <td>@if($page->public) <a href="{{ route('coasters.results', ['url' => $page->url]) }}"><i class="fa fa-eye"></i></a> @else <i class="fa fa-eye-slash"></i> @endif @if($page->default) <i class="fa fa-star text-warning"></i> @endif </td>
                                    <td><a href="{{ route('coasters.results.manage', ['page' => $page->id]) }}" class="btn btn-sm btn-info">Edit</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <form class="card my-3" action="{{ route('coasters.results.page.post') }}" method="post">
                <div class="card-header">
                    <h1 class="card-title">Edit Page</h1>
                </div>
                <div class="card-block">
                    <fieldset class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" @isset($edit_page) value="{{ $edit_page->name }}" @endisset>
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="url">URL</label>
                        <input type="text" class="form-control" id="url" name="url" @isset($edit_page) value="{{ $edit_page->url }}" @endisset>
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="group">Group</label>
                        <select name="group" id="group" class="form-control">
                            @foreach($groups as $group)
                                <option value="{{ $group->group }}">{{ $group->group }}</option>
                            @endforeach
                        </select>
                    </fieldset>
                    <fieldset class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description">@isset($edit_page) {{ $edit_page->description }} @endisset </textarea>
                    </fieldset>
                    <fieldset class="form-group">
                        <div class="btn-toolbar">
                            <div class="btn-group mr-2" data-toggle="buttons">
                                <label class="btn btn-primary @if(isset($edit_page) && $edit_page->public) active @endif">
                                    <input type="radio" name="public" value="1" id="public" autocomplete="off" @if(isset($edit_page) && $edit_page->public) checked @endif> Public
                                </label>
                                <label class="btn btn-primary @if(isset($edit_page) && !$edit_page->public) active @endif">
                                    <input type="radio" name="public" value="0" id="private" autocomplete="off" @if(isset($edit_page) && !$edit_page->public) checked @endif> Private
                                </label>
                            </div>
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-primary @if(isset($edit_page) && $edit_page->default) active @endif">
                                    <input type="checkbox" name="default" @if(isset($edit_page) && $edit_page->default) checked @endif autocomplete="off"> Default
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="card-footer text-right">
                    {{ csrf_field() }}
                    @isset($edit_page)
                        <input type="hidden" name="page" value="{{ $edit_page->id }}">
                        <button type="button" class="btn btn-danger" onclick="$('#delete-form').submit()"><i class="fa fa-trash"></i> Delete</button>
                    @endisset
                    <a href="{{ route('coasters.results.manage') }}" class="btn btn-secondary"><i class="fa fa-plus"></i> New</a>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
            @isset($edit_page)
                <form id="delete-form" action="{{ route('coasters.results.page.delete') }}" method="post">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <input type="hidden" name="page" value="{{ $edit_page->id }}">
                </form>
            @endisset
        </div>
    </div>
@endsection