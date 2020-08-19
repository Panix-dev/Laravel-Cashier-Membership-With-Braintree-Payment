# Laravel Cashier Membership With Braintree Payment

> An example of how to implement a membership functionality using Laravel and the Cashier extension 

An example of how to implement the Laravel Cashier extension and allow users to pay for monthly subscriptions by creating plans on Braintree by PayPal. Users can subscribe to plans, swap plans, cancel subscriptions, resume subscriptions, and change their payment card details. Cashier provides the appropriate middleware to protect routes based on subscription status and protect premium content from users with the basic subscription. We also handle Braintree notifications to various events through api listeners called webhooks provided by Braintree. Cashier is an extension powered by Laravel making it more reliable due to security updates and patches provided by Laravel itself.


## Table of Contents


> Try `clicking` on each of the `anchor links` below so you can get redirected to the appropriate section.

- [Cashier](#cashier)
- [Braintree](#braintree)
- [Controller Operations](#controller-operations)
- [Card Payment Blade Component](#card-payment-blade-component)
- [Contact Details](#contact-details)
- [Inspiration](#inspiration)


## Cashier


<br/>
<img src="https://laravel.com/img/logomark.min.svg" title="Laravel" alt="Laravel">

Laravel Cashier provides an expressive, fluent interface to subscription billing services. It handles almost all of the boilerplate subscription billing code you are dreading writing. In addition to basic subscription management, Cashier can handle coupons, swapping subscription, subscription "quantities", cancellation grace periods, and even generate invoice PDFs.

- :link: [Laravel Cashier](https://laravel.com/docs/7.x/billing)


## Braintree


<br/>
<img src="https://pagapiou.com/images/Braintree.png" title="Braintree" alt="Braintree">

Braintree is a full-stack payments platform that makes it easy to accept payments in your app or website. Their service replaces the traditional model of sourcing a payment gateway and merchant account from different providers.

- :link: [Braintree by PayPal](https://www.braintreepayments.com/gr)


## Controller Operations


```php
public function resume(Request $request)
{
    $request->user()->subscription('main')->resume();

    return redirect()->back()->with('success', 'You have successfully resumed your subscription');
}

public function cancel(Request $request)
{
    $request->user()->subscription('main')->cancel();

    return redirect()->back()->with('success', 'You have successfully cancelled your subscription');
}

public function store(Request $request)
{
      $plan = Plan::findOrFail($request->plan);

	if ($request->user()->subscribedToPlan($plan->braintree_plan, 'main')) {
	    return redirect('home')->with('error', 'Unauthorised operation');
	}

	if (!$request->user()->subscribed('main')) {
        $request->user()->newSubscription('main', $plan->braintree_plan)->create($request->payment_method_nonce);
    } else {
        $request->user()->subscription('main')->swap($plan->braintree_plan);
    }

    return redirect('home')->with('success', 'Subscribed to '.$plan->braintree_plan.' successfully');
}
```


## Card Payment Blade Component


```javascript

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
```


## Contact Details


> :computer: [https://pagapiou.com](https://pagapiou.com)

> :email: [hello@pagapiou.com](mailto:hello@pagapiou.com)

> :iphone: [LinkedIn](https://www.linkedin.com/in/agapiou/)

> :iphone: [Instagram](https://www.instagram.com/panos_agapiou/)

> :iphone: [Facebook](https://www.facebook.com/panagiotis.agapiou)


## Inspiration


- **[sitepoint.com](https://www.sitepoint.com/laravel-and-braintree-sitting-in-a-tree/)**
