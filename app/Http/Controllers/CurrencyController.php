<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyConvertRequest;
use App\Http\Resources\CurrencyConversionResource;
use App\Http\Resources\CurrencyRatesResource;
use App\Services\Currency\CurrencyRateInterface;
use Illuminate\Routing\Controller as BaseController;

class CurrencyController extends BaseController
{
    public function rates(CurrencyRateInterface $service): CurrencyRatesResource
    {
        return new CurrencyRatesResource($service->rates());
    }

    public function convert(CurrencyConvertRequest $request, CurrencyRateInterface $service): CurrencyConversionResource
    {
        return new CurrencyConversionResource($service->convert($request->currency_from, $request->currency_to, $request->value));
    }
}
