<?php

namespace App\Services\Currency;

interface CurrencyRateInterface
{
    public function rates(): array;
    public function rate(string $currency, bool $forceUpdate = true): string;
    public function convert(string $from, string $to, string $amount): ConversionInfo;
}
