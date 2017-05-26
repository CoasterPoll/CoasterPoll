@extends('layouts.app')

@section('title')
    Manage @if($user->id !== \Illuminate\Support\Facades\Auth::id()) {{ $user->name }}'s @endif {{ str_plural('Subscription', $user->subscriptions->count()) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">@if($user->id !== \Illuminate\Support\Facades\Auth::id()) {{ $user->name }}'s @else Your @endif Subscriptions</h2>
                </div>
                <div class="card-block">
                    @if($user->subscriptions->count() > 0)
                        <table class="table">
                            @foreach($user->subscriptions as $subscription)
                                <tr class="@if($subscription->ends_at && $subscription->ends_at->isFuture()) table-warning @endif">
                                    <td>{{ $subscription->plan->name }}</td>
                                    <td>
                                        @if($subscription->ends_at == null)
                                            Since {{ $subscription->updated_at->format("M 'y") }}, Renews on {{ $subscription->renews() }}
                                        @else
                                            Expires {{ $subscription->ends_at->format("D, M jS") }}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($subscription->cancelled())
                                            <form action="{{ route('subs.manage.resume', ['user' => $user->id]) }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="subscription" value="{{ $subscription->id }}">
                                                <button type="submit" class="btn btn-success btn-sm confirm-form"><i class="fa fa-refresh"></i> Restart</button>
                                            </form>
                                        @else
                                            <form action="{{ route('subs.manage.cancel', ['user' => $user->id]) }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="subscription" value="{{ $subscription->id }}">
                                                <button type="submit" class="btn btn-danger btn-sm confirm-form"><i class="fa fa-hand-stop-o"></i> Cancel</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p class="lead text-center">Hmm. Looks a little empty here.</p>
                        <p class="text-center">Maybe have a look at our <a href="{{ route('subs.plans') }}">plans</a>.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection