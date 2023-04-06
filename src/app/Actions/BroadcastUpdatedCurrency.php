<?php

namespace App\Actions;

use App\Actions\Monobank\GetCurrency;
use App\Events\Monobank\CurrencyUpdatedEvent;
use Cache;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class BroadcastUpdatedCurrency
{
    use AsAction;

    private string $data;
    private array $changes = [];

    public string $commandSignature = 'monobank:broadcast-currency-updated';

    public function handle(): void
    {
        $this->data = GetCurrency::run();

        if ($this->isCurrencyUpdated()) {
            event(new CurrencyUpdatedEvent('Monobank currency updatet at'.now()->toDateTimeString()));
        }
    }

    public function isCurrencyUpdated(): bool
    {
        $previousFingerprint = Cache::get('monobank_currency_md5');
        $currentFingerprint = md5($this->data);

        if ($previousFingerprint !== $currentFingerprint) {
            Cache::put('monobank_currency_md5', $currentFingerprint);
        }

        return $previousFingerprint !== $currentFingerprint;
    }

    public function asCommand(Command $command): void
    {
        $this->handle();

        $command->info('Done!');
    }
}
