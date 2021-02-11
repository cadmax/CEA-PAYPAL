<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/paypal', function () {
    return view('paypal');
})->name('paypal');

Route::post('/payment', 'PaypalController@payment')->name('paypal_payment');
Route::get('/payment', 'PaypalController@finish')->name('finishPayment');
