<?php

namespace App\Http\Resources;

use App\Services\Currency\ConversionInfo;

/**
 * @mixin ConversionInfo
 */
class CurrencyConversionResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'currency_from' => $this->from,
            'currency_to' => $this->to,
            'value' => $this->amount,
            'converted_value' => $this->formatAmount($this->convertedAmount, $this->accuracy),
            'rate' => $this->formatAmount($this->rate, $this->accuracy),
        ];
    }

    private function formatAmount(float $amount, int $decimals): string
    {
        return number_format($amount, $decimals, '.', '');
    }
}
