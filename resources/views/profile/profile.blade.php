@extends('layouts.app')

@section('title')
    /u/{{ $user->handle }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h1 class="display-3">{{ $user->handle }} <small class="lead">{{ $user->name }}</small></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Top Coasters</h2>
                </div>
                <div class="card-block">
                    <table class="table">
                        @foreach($topCoasters as $rank)
                            <tr>
                                <th><a href="{{ route('coasters.coaster', ['park' => $rank->coaster->park->short, 'coaster' => $rank->coaster->slug]) }}" class="link-unstyled">{{ $rank->coaster->name }} at {{ $rank->coaster->park->short }}</a></th>
                                <td>#{{ $rank->rank }}</td>
                            </tr>
                        @endforeach
                    </table>
                    @if($current && \Illuminate\Support\Facades\Auth::user()->can('Can rank coasters'))
                        <a href="{{ route('coasters.rank') }}" class="card-link">See Your Top Coasters</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Recent Links</h2>
                </div>
                <div class="card-block">
                    <table class="table table-sm">
                        @foreach($user->links as $link)
                            <tr>
                                <td><a href="{{ $link->getLink() }}">{{ $link->title }}</a></td>
                                <td>@null($link->linkable) {{ $link->linkable->name }} @endnull</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-block">
                    <p class="card-text">A user since {{ $user->created_at->format("M 'y") }}.</p>
                    @if($user->can('Can track coasters'))
                        <p class="card-text">Ridden {{ $user->ridden()->count() }} coasters.</p>
                        <p class="card-text">Visited {{ $parks->count() }} parks. <small class="text-muted"><i class="fa fa-info-circle" title="Based on what parks the coasters {{ $user->handle }}'s ridden."></i></small></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection