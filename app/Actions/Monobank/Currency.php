<?php

namespace App\Actions\Monobank;

use App\Exceptions\Monobank\BaseException;
use App\Exceptions\Monobank\ExceptionHandler;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class Currency
{
    use AsAction;

    public function handle()
    {
        $response = Http::get('https://api.monobank.ua/bank/currency');

        if ($response->status() !== 200) {
            throw ExceptionHandler::make($response);
        }

        return $response->json();
    }
}
