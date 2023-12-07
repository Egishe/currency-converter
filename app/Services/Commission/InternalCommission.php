<?php

namespace App\Services\Commission;

class InternalCommission implements CommissionInterface
{
    public function __construct(private float $percent)
    {
    }

    public function getPercent(): float
    {
        return $this->percent;
    }

    public function calculate(float $amount): float
    {
        return $amount * $this->percent;
    }
}
