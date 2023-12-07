<?php

namespace App\Http\Resources;

use App\Services\Currency\ConversionInfo;

/**
 * @mixin ConversionInfo
 */
class CurrencyRatesResource extends BaseResource
{
    public function toArray($request): array
    {
        return $this->resource;
    }
}
