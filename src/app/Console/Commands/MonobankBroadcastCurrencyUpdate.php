<?php

namespace App\Console\Commands;

use Illuminate\Http\Client\Response;
use App\Events\Monobank\CurrencyUpdatedEvent;
use App\Exceptions\Monobank\ExceptionHandler;
use Cache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Storage;

class MonobankBroadcastCurrencyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monobank:broadcast-currency-updated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected string $currencies;

    protected Response $response;

    public const CACHE_KEY = 'monobank_currency_md5';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->response = Http::get('https://api.monobank.ua/bank/currency');

        $this->saveResponse();

        if ($this->response->failed()) {
            throw ExceptionHandler::make($this->response);
        }


        if ($this->isCurrencyUpdated()) {
            event(new CurrencyUpdatedEvent('Monobank currency updatet at' . now()->toDateTimeString()));
        }
    }

    protected function saveResponse(): void
    {
        $filePath = 'monobank' . DIRECTORY_SEPARATOR . 'currency' . DIRECTORY_SEPARATOR .
            now()->format('Y-m-d H-i-s')
            . ' ' . $this->response->status();

        Storage::disk('public')->put($filePath, $this->response->body());
    }

    protected function isCurrencyUpdated(): bool
    {
        $previousFingerprint = Cache::get(self::CACHE_KEY);
        $currentFingerprint = md5($this->response->body());

        if ($previousFingerprint !== $currentFingerprint) {
            Cache::put(self::CACHE_KEY, $currentFingerprint);
        }

        return $previousFingerprint !== $currentFingerprint;
    }
}
