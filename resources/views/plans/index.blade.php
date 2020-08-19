@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Choose a Plan</div>
                <div class="card-body">
                    <div class="card-deck mb-3 text-center">
                    @foreach ($plans as $plan)
                        <div class="card mb-4 shadow-sm">
                          <div class="card-header">
                            <h4 class="my-0 font-weight-normal">{{ $plan->name }}</h4>
                          </div>
                          <div class="card-body">
                            <h1 class="card-title pricing-card-title">{{ number_format($plan->cost, 2) }}&euro;<br/><small class="text-muted">/ mo</small></h1>
                            @if ($plan->description)
                                <p>{{ $plan->description }}</p>
                            @endif
                            @if (!Auth::user()->subscribedToPlan($plan->braintree_plan, 'main'))
                                <a href="{{ url('/plan', $plan->slug) }}" class="btn btn-lg btn-block btn-primary">Get started</a>
                            @endif
                          </div>
                        </div>
                    @endforeach
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
