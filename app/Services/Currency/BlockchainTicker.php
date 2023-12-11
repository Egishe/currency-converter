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

    public function rate(string $currency, bool $forceUpdate = true): string
    {
        if (mb_strtoupper($currency) === $this->mainCurrency) {
            return '1.00';
        }

        if ($forceUpdate || empty($this->uploadedRates)) {
            return (string)$this->loadRates()[$currency] ?? '0.00';
        }

        return (string)$this->uploadedRates[$currency] ?? '0.00';
    }

    public function convert(string $from, string $to, string $amount): ConversionInfo
    {
        $conversionInfo = new ConversionInfo();
        $conversionInfo->commissionPercent = 0.0;
        $conversionInfo->accuracy = 2;
        $conversionInfo->from = $from;
        $conversionInfo->to = $to;
        $conversionInfo->amount = $amount;

        $rateFrom = $this->rate($from);
        if ($rateFrom === '0.00') {
            throw new \Exception("Could not get rate for currency $from");
        }
        $rateTo = $this->rate($to, false);
        if ($rateTo === 0.0) {
            throw new \Exception("Could not get rate for currency $to");
        }

        // todo: probably this is the common case. If we have other providers
        // try to optimize this moving to the abstract class
        if (mb_strtoupper($to) === $this->mainCurrency) {
            $rateFrom = bcadd($rateFrom, $this->commission?->calculate($rateFrom, 10));
            $conversionInfo->rate = bcdiv('1',  $rateFrom, 10);
            $amount = bcdiv($amount, $rateFrom, 10);
            $conversionInfo->accuracy = 10;
        } elseif (mb_strtoupper($from) === $this->mainCurrency) {
            $rateTo = bcsub($rateTo, $this->commission?->calculate($rateTo), 2);
            $conversionInfo->rate = $rateTo;
            $amount = bcmul($amount, $rateTo);
        } else {
            // todo: should we take double commission? Now we take only one
            $doubleConversionRate = bcdiv($rateFrom, $rateTo, 2);
            $doubleConversionRate = bcsub($doubleConversionRate, $this->commission?->calculate($doubleConversionRate), 2);
            $amount = bcmul($amount, $doubleConversionRate);
            $conversionInfo->rate = $doubleConversionRate;
        }

        $conversionInfo->convertedAmount = $amount;

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
