<?php

namespace Actions\BroadcastUpdatedCurrency;

use App\Actions\BroadcastUpdatedCurrency;
use App\Events\Monobank\CurrencyUpdatedEvent;
use App\Exceptions\Monobank\TooManyRequestException;
use Cache;
use Event;
use Illuminate\Support\Facades\Http;
use Storage;
use Tests\TestCase;

class BroadcastUpdatedCurrencyTest extends TestCase
{
    protected string $basePath = 'Feature/Actions/BroadcastUpdatedCurrency/FakeHttpResponses';

    public function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
    }

    /**
     * @test
     */
    public function successful(): void
    {
        //1. Pre test setup
        $response = Storage::disk('test')
            ->get($this->basePath . '/api.monobank.ua.bank.currency-200.json');

        Http::fake([
            'https://api.monobank.ua/bank/currency' => Http::response($response, 200)
        ]);

        Event::fake();

        //2. Running code to be tested
        BroadcastUpdatedCurrency::run();

        //3. Assertions
        Event::assertDispatched(CurrencyUpdatedEvent::class);
    }

    /**
     * @test
     */
    public function failed_429(): void
    {
        //1. Pre test setup
        $response = Storage::disk('test')
            ->get($this->basePath . '/api.monobank.ua.bank.currency-429.json');

        Http::fake([
            'https://api.monobank.ua/bank/currency' => Http::response($response,429)
        ]);

        Event::fake();
        $this->expectException(TooManyRequestException::class);

        //2. Running code to be tested
        BroadcastUpdatedCurrency::run();

        //3. Assertions
        Event::assertNotDispatched(CurrencyUpdatedEvent::class);
    }

    /**
     * @test
     */
    public function noChanges(): void
    {
        //1. Pre test setup
        $response = Storage::disk('test')
            ->get($this->basePath . '/api.monobank.ua.bank.currency-200.json');

        Cache::put('monobank_currency_md5', md5($response));

        Http::fake([
            'https://api.monobank.ua/bank/currency' => Http::response($response,200)
        ]);

        Event::fake();

        //2. Running code to be tested
        BroadcastUpdatedCurrency::run();

        //3. Assertions
        Event::assertNotDispatched(CurrencyUpdatedEvent::class);
    }
}
