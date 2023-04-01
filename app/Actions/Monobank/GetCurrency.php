<?php

namespace App\Actions\Monobank;

use App\Exceptions\Monobank\BaseException;
use App\Exceptions\Monobank\ExceptionHandler;
use Cache;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;
use Storage;
use Str;

class GetCurrency
{
    use AsAction;

    public function handle()
    {
        $response = Http::get('https://api.monobank.ua/bank/currency');

        $filePath = 'monobank' . DIRECTORY_SEPARATOR . 'currency' . DIRECTORY_SEPARATOR .
            now()->format('Y-m-d H-i-s')
            . ' ' . $response->status();

        Storage::disk('public')->put($filePath, $response);

        if ($response->failed()) {
            throw ExceptionHandler::make($response);
        }

        return $response->body();
    }
}
