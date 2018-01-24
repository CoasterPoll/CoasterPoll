@extends('layouts.app')

@section('title')
    Big List of Coasters
@endsection

@section('content')
    <div class="row collapse pb-3" id="editQuery">
        <div class="col-md-12">
            <div class="card card-block">
                <form action="{{ route('coasters.coasters') }}" method="get">
                    <div class="row">
                        <div class="col-sm-4">
                            <fieldset class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control" name="type">
                                    <option value="0">Choose Type...</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" @if($type->id == $request->get('type'))selected="selected" @endif>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="categories">Categories</label>
                                <select name="category" class="form-control">
                                    <option value="0">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @if($request->get('category') == $category->id)selected="selected" @endif>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-sm-4">
                            <fieldset class="form-group">
                                <label for="park">Park</label>
                                <select class="form-control" name="park">
                                    <option value="0">Choose Park...</option>
                                    @foreach($parks as $park)
                                        <option value="{{ $park->id }}" @if($request->get('park') == $park->id) selected="selected" @endif>{{ $park->name }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="manufacturer">Manufacturer</label>
                                <select class="form-control" name="manufacturer">
                                    <option value="0">Choose Manufacturer...</option>
                                    @foreach($manufacturers as $manufacturer)
                                        <option value="{{ $manufacturer->id }}" @if($request->get('$manufacturer') == $manufacturer->id) selected="selected" @endif>{{ $manufacturer->name }}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-sm-4">
                            <fieldset class="form-group">
                                <label for="sort">Sort Order</label>
                                <select name="sort" class="form-control">
                                    <option value="coaster" @if($request->get('sort') == "coaster") selected="selected" @endif>Coaster</option>
                                    <option value="park" @if($request->get('sort') == "park") selected="selected" @endif>Park</option>
                                    <option value="manufacturer" @if($request->get('sort') == "manufacturer") selected="selected" @endif>Manufacturer</option>
                                </select>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="direction">Sort Direction</label>
                                <select name="direction" class="form-control">
                                    <option value="asc" @if($request->get('direction') == "asc") selected="selected" @endif>A -> Z</option>
                                    <option value="desc" @if($request->get('direction') == "desc") selected="selected" @endif>Z -> A</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 float-xs-right">
                            <fieldset class="form-group float-xs-right">
                                <input type="hidden" name="limit" value="yes">
                                <button type="submit" class="btn btn-outline-primary">Update</button>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h1>@if($request->get('limit') == "yes") (Not So) @endif Massive List of Coasters <span class="pull-right"><button class="btn btn-outline-info float-right" data-toggle="collapse" data-target="#editQuery" aria-expanded="false" aria-controls="editQuery">Change List</button></span> </h1>
            <table class="table table-striped">
                <tbody>
                    @foreach($coasters as $coaster)
                        <tr>
                            <td><a href="{{ route('coasters.coaster', ['park' => $coaster->park->short, 'coaster' => $coaster->slug]) }}" class="link-unstyled">{{ $coaster->name }}</a></td>
                            <td><a href="{{ route('coasters.park', ['park' => $coaster->park->short]) }}">{{ $coaster->park->name }}</a>
                                <small><a class="link-unstyled" href="https://google.com/maps/search/{{ urlencode($coaster->park->city) }}">{{ $coaster->park->city }}</a></small>
                            </td>
                            <td><a href="{{ route('coasters.manufacturer', ['manufacturer' => $coaster->manufacturer->abbreviation]) }}">{{ $coaster->manufacturer->name }}</a></td>
                            @auth
                                <td>@include('coasters._ridden', ['ridden_coaster_id' => $coaster->id])</td>
                            @endauth
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($coasters->links())
                {{ $coasters->appends($request->all(['limit', 'type', 'park', 'manufacturer', 'category', 'direction', 'sort']))->links('vendor.pagination.bootstrap-4') }}
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    @include('coasters._scripts')
@endsection