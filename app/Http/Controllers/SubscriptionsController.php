<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Plan;
use App\User;

class SubscriptionsController extends Controller
{
	public function index()
    {
        $user = Auth::user();
        $invoices = $user->invoicesIncludingPending();
        return view('subscriptions.index')->withInvoices($invoices);
    }

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

    public function changeCard(Request $request)
    {

        $request->user()->updateCard($request->payment_method_nonce);

        return redirect('home')->with('success', 'Credit card details updated successfully!');
    }
}
