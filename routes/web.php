<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/* Subscription Routes */

Route::get('/plans', 'PlansController@index');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/plan/{plan}', 'PlansController@show');
    Route::get('/braintree/token', 'BraintreeTokenController@token');
    Route::post('/subscribe', 'SubscriptionsController@store');
    Route::get('/subscriptions', 'SubscriptionsController@index');
    Route::post('/subscription/cancel', 'SubscriptionsController@cancel');
    Route::post('/subscription/resume', 'SubscriptionsController@resume');
    Route::post('/subscription/change-card', 'SubscriptionsController@changeCard');

    Route::get('user/invoice/{invoice}', function (Request $request, $invoiceId) {
        return $request->user()->downloadInvoice($invoiceId, [
            'vendor'  => 'Your Company',
            'header'  => 'Some Header',
            'product' => 'Your Product',
        ]);
    });

    Route::group(['middleware' => 'subscribed'], function () {
        Route::get('/basic-access', 'LessonsController@basic');
    });
    Route::group(['middleware' => 'premium-subscribed'], function () {
        Route::get('/pro-access', 'LessonsController@pro');
    });

});

Route::post(
    'braintree/webhooks',
    '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook'
);


/* END of Subscription Routes */
