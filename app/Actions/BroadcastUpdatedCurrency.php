<?php

namespace App\Actions;

use App\Actions\Monobank\GetCurrency;
use App\Events\Monobank\CurrencyUpdated;
use Cache;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class BroadcastUpdatedCurrency
{
    use AsAction;

    public string $commandSignature = 'monobank:broadcast-currency-updated';

    public function handle(): void
    {
        $data = GetCurrency::run();

        $previousFingerprint = Cache::get('monobank_currency_md5');
        $currentFingerprint = md5($data);

        if($previousFingerprint !== $currentFingerprint) {
            Cache::rememberForever('monobank_currency_md5', fn() => md5($data));
            event(new CurrencyUpdated());
        }
    }

    public function asCommand(Command $command): void
    {
        $this->handle();

        $command->info('Done!');
    }
}
