@extends('layouts.app')

@section('title')
    Preferences
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Account Settings</h2>
                </div>
                <div class="card-block">
                    <form class="form-horizontal" id="form" role="form" method="POST" action="{{ route('user.settings.post') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
                            <label for="name" class="col-md-4 control-label">Name</label>
                            <div class="col-md-6 ">
                                <input id="name" type="text" class="form-control" name="name" value="{{ ($user->name) ?: old('name') }}" required>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ ($user->email) ?: old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" name="edit-password" value="false">
                                    Change
                                </button>
                            </div>
                        </div>
                        <hr class="my-4">
                        <p>Setting a new password is optional.</p>
                        @if($user->password !== null)
                            <div class="form-group{{ $errors->has('old_password') ? ' has-error' : '' }} row">
                                <label for="old_password" class="col-md-4 control-label">Old Password</label>
                                <div class="col-md-6">
                                    <input id="old_password" type="password" class="form-control" name="old_password">
                                    @if ($errors->has('old_password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('old_password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
                            <label for="password" class="col-md-4 control-label">New Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" id="edit-password-btn" class="btn btn-primary" name="edit-password" value="true">
                                    Change Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#password-confirm').on('keypress', function(e) {
            e.preventDefault();
            if(e.keyCode == 13) {
                $('#edit-password-btn').click();
                return false;
            }
        })
    </script>
@endsection