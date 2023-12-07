<?php

namespace App\Services\Currency;

class ConversionInfo
{
    public string $from;
    public string $to;
    public float $amount;
    public float $rate;
    public float $convertedAmount;
    public float $commissionPercent;
    public int $accuracy;
}
