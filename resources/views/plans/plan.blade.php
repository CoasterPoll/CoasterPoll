@extends('layouts.app')

@section('title')
    {{ $plan->name }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">{{ $plan->name }} Subscription</h2>
                </div>
                <div class="card-block">
                    @if(\Illuminate\Support\Facades\Auth::user()->hasCardOnFile())
                        <div class="alert alert-info">Hey! You've already got a card saved that you can use.</div>
                        <form action="{{ route('subs.plan.post', ['plan' => $plan->slug]) }}" method="post" id="nonpayment-form">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary">Use your {{ \Illuminate\Support\Facades\Auth::user()->card_brand }}, Ending in {{ \Illuminate\Support\Facades\Auth::user()->card_last_four }}</button>
                        </form>
                        <hr>
                        <p>Or your can update your card.</p>
                    @endif
                    <form action="{{ route('subs.plan.post', ['plan' => $plan->slug]) }}" method="post" id="payment-form">
                        <div class="form-row">
                            <fieldset class="form-group">
                                <label for="card-number">Credit/Debit Card Number</label>
                                <div id="card-number-element"></div>
                                <div id="card-number-error"></div>
                            </fieldset>
                            <fieldset class="form-group">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <label for="card-number">Expiration</label>
                                        <div id="card-expire-element"></div>
                                        <div id="card-expire-error"></div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="card-cvc">CVC</label>
                                        <div id="card-cvc-element"></div>
                                        <div id="card-cvc-error"></div>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Used to display Element errors -->
                            <div id="card-errors" role="alert"></div>
                        </div>
                        {{ csrf_field() }}
                        <input id="stripe-token" name="stripe_token" value="" type="hidden">
                        <button class="btn btn-primary" id="submit-btn">Submit Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{!! config('services.stripe.key', null) !!}');
        var elements = stripe.elements();

        var style = {
            base: {
                fontSize: '16px',
                lineHeight: '24px'
            }
        };

        window.number = elements.create('cardNumber', {style: style, classes: {base: "form-control", invalid: "form-control-danger"}});
        number.mount('#card-number-element');

        window.expire = elements.create('cardExpiry', {style: style, classes: {base: "form-control", invalid: "form-control-danger"}});
        expire.mount('#card-expire-element');

        window.cvc = elements.create('cardCvc', {style: style, classes: {base: "form-control", invalid: "form-control-danger"}});
        cvc.mount('#card-cvc-element');

        number.addEventListener('change', function(event) {
            if(event.error) {
                $('#card-number-error').html(event.error.message).fadeIn();
            } else {
                $('#card-number-error').html("").fadeOut();
            }
        });
        expire.addEventListener('change', function(event) {
            if(event.error) {
                $('#card-expire-error').html(event.error.message).fadeIn();
            } else {
                $('#card-expire-error').html("").fadeOut();
            }
        });
        cvc.addEventListener('change', function(event) {
            if(event.error) {
                $('#card-cvc-error').html(event.error.message).fadeIn();
            } else {
                $('#card-cvc-error').html("").fadeOut();
            }
        });

        $('#submit-btn').on('click', function(e) {
            e.preventDefault();
            stripe.createToken(number).then(function(result) {
                if(result.error) {
                    bootbox.alert(result.error.message);
                } else {
                    console.log(result.token);
                    $('#stripe-token').val(result.token.id);
                    $('#payment-form').submit();
                }
            })
        });


    </script>
@endsection

@section('head')
    <meta http-equiv="Content-Security-Policy" content="default-src *; style-src 'self' 'unsafe-inline'; script-src * 'self' 'unsafe-inline' https://js.stripe.com">
@endsection