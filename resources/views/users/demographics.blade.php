@extends('layouts.app')

@section('title')
    Demographics
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Demographics</h2>
                </div>
                <div class="card-block">
                    <p>We use this information to help provide some insight into trends, and the community that fills out the poll. It is not directly shared with anyone.</p>
                    <p>All fields are optional, but we would appreciate you filling it out.</p>
                    <form action="{{ route('user.demographics.post') }}" method="post">
                        <fieldset class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="age_range">Age</label>
                                    <select class="form-control" id="age_range" name="age_range">
                                        @foreach(\ChaseH\Models\Analytics\Demographic::$age_ranges as $key => $range)
                                            <option value="{{ $key }}" @if($user->demographics->age_range == $key)selected @endif>{{ $range }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender">
                                        @foreach(\ChaseH\Models\Analytics\Demographic::$genders as $key => $gender)
                                            <option value="{{ $key }}" @if($user->demographics->gender == $key)selected @endif>{{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="location">City, State (Or local equivalent)</label>
                            <input type="text" id="location" class="form-control" name="location" value="{{ $user->demographics->city }}">
                        </fieldset>
                        <fieldset class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="visits">Annual Park Visits</label>
                                    <input type="number" id="visits" name="park_visits" class="form-control" value="{{ number_format($user->demographics->park_visits) }}">
                                    <small class="text-muted">How many times you visit a park (any park).</small>
                                </div>
                                <div class="col-sm-6">
                                    <label for="unique">Unique Parks</label>
                                    <input type="number" id="unique" name="unique_parks" class="form-control" value="{{ number_format($user->demographics->unique_parks) }}">
                                    <small class="text-muted">How many different parks you visit during the season.</small>
                                </div>
                            </div>
                         </fieldset>
                        <fieldset class="form-group">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection