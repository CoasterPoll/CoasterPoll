@extends('layouts.app')

@section('title')
    {{ $user->handle }}'s Rankings
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2><a href="{{ route('profile', ['handle' => $user->handle])  }}">{{ $user->handle }}</a>'s Rankings @if($user->handle !== null) <span class="pull-right"><a href="{{ route('profile.rankings', ['handle' => $user->handle]) }}?shared" class="btn btn-outline-primary btn-sm"><i class="fa fa-share"></i> Share Link</a></span>@endif </h2>
            <table class="table table-striped">
                <tbody>
                    @foreach($rankings as $ranking)
                        <tr>
                            <td><a href="{{ route('coasters.coaster', ['park' => $ranking->coaster->park->short, 'coaster' => $ranking->coaster->slug]) }}">{{ $ranking->coaster->name }}</a> <small>{{ $ranking->coaster->manufacturer->abbreviation }}</small></td>
                            <td><a href="{{ route('coasters.park', ['park' => $ranking->coaster->park->short]) }}">{{ $ranking->coaster->park->name }}</a></td>
                            <td>{{ $ranking->rank }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')

@endsection