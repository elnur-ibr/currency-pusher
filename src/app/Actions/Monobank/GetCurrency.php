<?php

namespace App\Actions\Monobank;

use App\Exceptions\Monobank\BaseException;
use App\Exceptions\Monobank\ExceptionHandler;
use Cache;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;
use Storage;
use Str;

class GetCurrency
{
    use AsAction;

    private Response $response;

    public function handle()
    {
        $this->response = Http::get('https://api.monobank.ua/bank/currency');

        $this->saveResponse();

        if ($this->response->failed()) {
            throw ExceptionHandler::make($this->response);
        }

        return $this->response->body();
    }

    public function saveResponse():void
    {
        $filePath = 'monobank' . DIRECTORY_SEPARATOR . 'currency' . DIRECTORY_SEPARATOR .
            now()->format('Y-m-d H-i-s')
            . ' ' . $this->response->status();

        Storage::disk('public')->put($filePath, $this->response);
    }
}
