<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('test', function() {
    //event(new CurrencyUpdated());

    \App\Actions\BroadcastUpdatedCurrency::run();

    return response()->json('done');
});

Route::view('testing', 'testing');

Route::get('/', function () {

    return Inertia::render('Welcome', []);
});
