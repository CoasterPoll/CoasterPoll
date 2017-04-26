@extends('layouts.app')

@section('title')
    Contact Us
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a href="{{ route('contact') }}" class="nav-link" data-toggle="tab" role="tab">General</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('contact.coaster') }}" class="nav-link active">Coaster</a>
                        </li>
                    </ul>
                </div>
                <div class="card-block">
                    <form action="{{ route('contact.post') }}" method="post">
                        <fieldset class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" name="name" class="form-control" id="name" @auth value="{{ \Illuminate\Support\Facades\Auth::user()->name }}" @endauth>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="email">Your Email</label>
                            <input type="email" name="email" class="form-control" id="email" @auth value="{{ \Illuminate\Support\Facades\Auth::user()->email }}" @endauth>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="message">What we got wrong</label>
                            <textarea rows="8" name="message" class="form-control" id="message"></textarea>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="coaster_name">Coaster Name</label>
                            <input type="text" id="coaster_name" class="form-control" name="coaster_name" @isset($coaster->name)value="{{ $coaster->name }}" @endisset>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="slug">Short Name</label>
                            <input type="text" id="slug" class="form-control" name="slug" @isset($coaster->slug)value="{{ $coaster->slug }}" @endisset>
                        </fieldset>
                        <fieldset class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="type">Type</label>
                                    <select id="type" name="type" class="form-control">
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}" @if(isset($coaster->type) && $coaster->type->id == $type->id) selected @endif>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="rcdb_id">RCDB ID</label>
                                    <input type="number" id="rcdb_id" class="form-control" name="rcdb_id" @isset($coaster->rcdb_id)value="{{ $coaster->rcdb_id }}" @endisset>
                                    <p class="text-muted">Only the numbers in the URL (before the .htm).</p>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-group" style="overflow-y: scroll; max-height: 400px;">
                            <table class="table table-sm">
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <th>{{ $category->name }}</th>
                                        <td class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]}" value="{{ $category->name }}"
                                                   @if(isset($coaster) && $coaster->categories->contains('id', $category->id)) checked @endif>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </fieldset>
                        <fieldset class="form-group text-right">
                            {{ csrf_field() }}
                            @if(Auth::check())
                                <input type="hidden" name="user_id" value="{{ \Illuminate\Support\Facades\Auth::id() }}">
                            @else
                                @include('contact._captcha')
                            @endif
                            @if($coaster !== null)
                                <input type="hidden" name="coaster" value="{{ $coaster->id }}">
                            @endif
                            <input type="hidden" name="type" value="coaster">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if(!Auth::check())
        @include('contact._captcha_scripts')
    @endif
@endsection