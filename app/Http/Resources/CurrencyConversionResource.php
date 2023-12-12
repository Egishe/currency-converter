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
            'converted_value' => $this->convertedAmount, $this->accuracy,
            'rate' => $this->rate, $this->accuracy,
        ];
    }
}
