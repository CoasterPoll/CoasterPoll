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
                            <a href="{{ route('contact') }}" class="nav-link active">General</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('contact.coaster') }}" class="nav-link">Coaster</a>
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
                            <label for="message">Your Message</label>
                            <textarea rows="8" name="message" class="form-control" id="message"></textarea>
                        </fieldset>
                        <fieldset class="form-group text-right">
                            {{ csrf_field() }}
                            @if(Auth::check())
                                <input type="hidden" name="user_id" value="{{ \Illuminate\Support\Facades\Auth::id() }}">
                            @else
                                @include('contact._captcha')
                            @endif
                            <button type="submit" class="btn btn-primary disabled"><i class="fa fa-paper-plane"></i> Send</button>
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
    <script>
        $('textarea').on('keyup', function() {
            if($(this).val() !== "") {
                $('button').removeClass('disabled');
            } else {
                $('button').addClass('disabled');
            }
        })
    </script>
@endsection