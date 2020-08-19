@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card" style="margin-bottom: 30px;">
                <div class="card-header">Manage Subscriptions</div>
                <div class="card-body">

                    @if (Auth::user()->subscription('main')->cancelled())
                        <p>Your subscription ends on {{ Auth::user()->subscription('main')->ends_at->format('dS M Y') }}</p>
                        <form action="{{ url('subscription/resume') }}" method="post">
                            <button type="submit" class="btn btn-lg btn-block btn-primary">Resume subscription</button>
                            {{ csrf_field() }}
                        </form>
                    @else
                        @if (Auth::user()->subscription('main')->onTrial())
                            <p>Your trial period ends on {{ Auth::user()->subscription('main')->trial_ends_at->format('dS M Y') }}</p>
                        @endif
                        <p>You are currently subscribed to {{ Auth::user()->subscription('main')->braintree_plan }} plan</p>
                        <form action="{{ url('subscription/cancel') }}" method="post">
                            <button type="submit" class="btn btn-lg btn-block btn-primary">Cancel subscription</button>
                            {{ csrf_field() }}
                        </form>
                    @endif
                </div>
            </div>

            <div class="card" style="margin-bottom: 30px;">
                <div class="card-header">Download Invoices</div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                                    <td>{{ $invoice->total() }}</td>
                                    <td><a href="/user/invoice/{{ $invoice->id }}">Download</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Charging Card Details</div>
                <div class="card-body">
                    <p>You are using the card with the following details</p>
                    <p>
                        <table class="table">
                        <tbody>
                            <tr>
                                <td><strong>Card Brand</strong></td>
                                <td><strong>Card Last 4 digits</strong></td>
                            </tr>
                            <tr>
                                <td>{{ Auth::user()->card_brand }}</td>
                                <td>{{ Auth::user()->card_last_four }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </p>
                    <hr>
                    <p>Change the card to be charged on next cycle</p>
                    <form action="{{ url('/subscription/change-card') }}" method="post">
                        <div id="dropin-container"></div>
                        {{ csrf_field() }}
                        <br>
                        <button id="payment-button" class="btn btn-primary btn-flat" hidden type="submit">Change Card</button>
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
