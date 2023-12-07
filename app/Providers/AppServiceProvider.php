<?php

namespace App\Providers;

use App\Services\Commission\CommissionInterface;
use App\Services\Commission\InternalCommission;
use App\Services\Currency\CurrencyRateInterface;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // todo: add a config option to switch between the different implementations
        $this->app->bind(CommissionInterface::class, function () {
//            dd(config('services.commission.internal.percent'));
            return new InternalCommission(config('services.commission.internal.percent', 0.02));
        });

        $this->app->when(\App\Services\Currency\BlockchainTicker::class)
            ->needs(ClientInterface::class)
            ->give(fn() => new \GuzzleHttp\Client([
                'base_uri' => config('services.currency.blockchain_ticker.host', 'https://blockchain.info/')
            ]));

        // todo: add a config option to switch between the different implementations
        $this->app->bind(CurrencyRateInterface::class, function () {
            return $this->app->get(\App\Services\Currency\BlockchainTicker::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
