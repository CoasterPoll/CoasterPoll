@extends('layouts.app')

@section('title')
    Sign Up
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Sign Up</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">Sign In</a>
                        </li>
                    </ul>
                </div>
                <div class="card-block">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('handle') ? ' has-error' : '' }}">
                            <label for="handle" class="col-md-4 control-label">Handle</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-addon">/u/</div>
                                    <input id="handle" type="text" class="form-control" name="handle" value="{{ old('handle') }}" required>
                                </div>

                                @if ($errors->has('handle'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('handle') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Sign Up
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Social Sign In</h2>
                </div>
                <div class="card-block">
                    <div class="nav nav-pills flex-column">
                        @foreach(config('social.services') as $name => $service)
                            <a class="nav-link btn btn-outline-primary mb-2" href="{{ route('auth.social', ['service' => $name]) }}"><i class="fa {{ $service['class'] }} fa-fw"></i> {{ $service['name'] }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        window.hadFocus = false;
        $('#name').on('keyup', function() {
            var handle = $('#handle');
            if(!window.hadFocus) {
                handle.val(slugify($(this).val()));
            }
        });
        $('#handle').on('keyup', function() {
            window.hadFocus = true;
        });
    </script>
@endsection