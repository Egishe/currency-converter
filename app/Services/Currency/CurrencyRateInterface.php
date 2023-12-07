<?php

namespace App\Services\Currency;

interface CurrencyRateInterface
{
    public function rates(): array;
    public function rate(string $currency, bool $forceUpdate = true): float;
    public function convert(string $from, string $to, float $amount): ConversionInfo;
}
