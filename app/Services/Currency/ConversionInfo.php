<?php

namespace App\Services\Currency;

class ConversionInfo
{
    public string $from;
    public string $to;
    public string $amount;
    public string $rate;
    public string $convertedAmount;
    public float $commissionPercent;
    public int $accuracy;
}
