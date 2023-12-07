<?php
declare(strict_types=1);

namespace App\Services\Currency;

use App\Services\Commission\CommissionInterface;
use GuzzleHttp\ClientInterface;

class BlockchainTicker implements CurrencyRateInterface
{
    private string $mainCurrency = 'BTC';
    private array $uploadedRates = [];

    public function __construct(private ClientInterface $client, private readonly ?CommissionInterface $commission)
    {
    }

    public function rates(): array
    {
        $result = [];
        foreach ($this->loadRates() as $currency => $rate) {
            $result[$currency] = $rate - $this->commission?->calculate($rate);
            $result[$currency] = round($result[$currency], 2);
        }
        uksort($result, function ($a, $b) {
            return $b <=> $a;
        });
        $this->uploadedRates = $result;

        return $result;
    }

    public function rate(string $currency, bool $forceUpdate = true): float
    {
        if (mb_strtoupper($currency) === $this->mainCurrency) {
            return 1.0;
        }

        if ($forceUpdate || empty($this->uploadedRates)) {
            return $this->loadRates()[$currency] ?? 0.0;
        }

        return $this->uploadedRates[$currency] ?? 0.0;
    }

    // todo: use bcmath
    public function convert(string $from, string $to, float $amount): ConversionInfo
    {
        $conversionInfo = new ConversionInfo();
        $conversionInfo->commissionPercent = 0.0;
        $conversionInfo->accuracy = 2;
        $conversionInfo->from = $from;
        $conversionInfo->to = $to;
        $conversionInfo->amount = $amount;

        $rateFrom = $this->rate($from);
        if ($rateFrom === 0.0) {
            throw new \Exception("Could not get rate for currency $from");
        }
        $rateTo = $this->rate($to, false);
        if ($rateTo === 0.0) {
            throw new \Exception("Could not get rate for currency $to");
        }

        // todo: probably this is the common case. If we have other providers
        // try to optimize this moving to the abstract class
        if (mb_strtoupper($to) === $this->mainCurrency) {
            $rateFrom += $this->commission?->calculate($rateFrom);
            $conversionInfo->rate = round(1 / $rateFrom, 10);
            $amount /= $rateFrom;
            $conversionInfo->accuracy = 10;
        } elseif (mb_strtoupper($from) === $this->mainCurrency) {
            $rateTo -= $this->commission?->calculate($rateTo);
            $conversionInfo->rate = $rateTo;
            $amount *= $rateTo;
        } else {
            // todo: should we take double commission? Now we take only one
            $doubleConversionRate = round($rateFrom/$rateTo, 2);
            $doubleConversionRate -= $this->commission?->calculate($doubleConversionRate);
            $amount *= $doubleConversionRate;
            $conversionInfo->rate = $doubleConversionRate;
        }

        $conversionInfo->convertedAmount = round($amount, $conversionInfo->accuracy);

        return $conversionInfo;
    }

    private function loadRates(): array
    {
        $resp = $this->client->request('get', 'ticker')->getBody()->getContents();
        $rates = json_decode($resp, true);
        $result = [];
        foreach ($rates as $currency => $rate) {
            $result[$currency] = round($rate['last'], 2);
        }
        $this->uploadedRates = $result;

        return $result;
    }
}
