@extends('layouts.app')

@section('title')
    Plans
@endsection

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Our Plans</h2>
                </div>
                <div class="card-block">
                    @foreach($plans as $plan)
                        @if(!$loop->first)
                            <hr>
                        @endif
                        <div class="row">
                            <div class="col-10">
                                <h4 class="lead align-middle">{{ $plan->name }} <span class="badge badge-success">${{ $plan->cost() }}</span> </h4>
                                <p>{!! Parsedown::instance()->text($plan->description) !!}</p>
                            </div>
                            <div class="col-2 text-right">
                                @if(!\Illuminate\Support\Facades\Auth::user()->subscribedToPlan($plan->stripe_plan, 'primary'))
                                    <a href="{{ route('subs.plan', ['plan' => $plan->slug]) }}" class="btn btn-sm btn-primary">Subscribe</a>
                                @else
                                    <a href="#" class="btn btn-sm btn-secondary disabled" disabled="disabled">Subscribed</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-3">

        </div>
    </div>
@endsection

@section('scripts')

@endsection