<?php

use App\Events\Monobank\CurrencyUpdated;
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
    //event(new CurrencyUpdated());

    \App\Actions\BroadcastUpdatedCurrency::run();

    return response()->json('done');
});

Route::view('testing', 'testing');

Route::get('test2', function() {
    $file1 = 'monobank/currency/2023-04-01 21-54-07 monobank-currency.json';
    $file2 = 'monobank/currency/2023-04-01 21-54-46 monobank-currency.json';
    $file1 = Storage::disk('public')->get($file1);
    $file2 = Storage::disk('public')->get($file2);

    dd(
        md5($file1),
        md5($file2),
        md5($file1),
        md5($file2),
    );

    return response()->json('done');
});
