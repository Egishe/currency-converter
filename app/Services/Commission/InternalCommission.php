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

    public function calculate(string $amount, int $accuracy = 2): string
    {
        return bcmul($amount, $this->percent, $accuracy);
    }
}
