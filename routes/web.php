<?php

use App\Events\PaymentNotification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function() {
    $array = ['name' => 'Ekpono Ambrose']; //data we want to pass
    event(new PaymentNotification(  $array));

    //dd(\App\Actions\Monobank\Currency::run());

    return response()->json('done');
});

Route::view('testing', 'testing');
