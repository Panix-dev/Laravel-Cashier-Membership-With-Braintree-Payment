@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $plan->name }}</div>
                <div class="card-body">
                    <form action="{{ url('/subscribe') }}" method="post">
                        <div id="dropin-container"></div>
                        <input type="hidden" name="plan" value="{{ $plan->id }}">
                        {{ csrf_field() }}
                        <br>
                        <button id="payment-button" class="btn btn-primary btn-flat" hidden type="submit">Pay now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('braintree')
    <script src="https://js.braintreegateway.com/js/braintree-2.30.0.min.js"></script>
    <script>
        (function($){
            $.ajax({
                url: '{{ url('braintree/token') }}'
            }).done(function (response) {
                braintree.setup(response.data.token, 'dropin', {
                    container: 'dropin-container',
                    onReady: function () {
                        $('#payment-button').removeAttr('hidden');
                    }
                });
            });
        })(jQuery); 
    </script>
@endsection