<?php

namespace App\Services\Commission;

interface CommissionInterface
{
    public function getPercent(): float;
    public function calculate(string $amount, int $accuracy = 2): string;
}
