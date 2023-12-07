<?php

namespace App\Services\Commission;

interface CommissionInterface
{
    public function getPercent(): float;
    public function calculate(float $amount): float;
}
