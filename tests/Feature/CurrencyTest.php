<?php

declare(strict_types=1);

namespace Tests\Feature;

use GuzzleHttp\ClientInterface;
use Tests\TestCase;

class CurrencyTest extends TestCase
{

    /**
     * @dataProvider convertDataProvider
     */
    public function testBlockchainConvert(
        string $from,
        string $to,
        float $amount,
        array $expectedData,
        ?float $commission
    ): void
    {
        if ($commission) {
            config()->set('services.commission.internal.percent', $commission);
        }
        // todo: uncomment this when there will be an option to choose provider
//        config()->set('services.currency.provider', 'blockchain');

        $httpClientMock = $this->createMock(ClientInterface::class);
        $httpClientMock->method('request')->willReturn(
            new \GuzzleHttp\Psr7\Response(
                200,
                [],
                json_encode($this->getRates())
            )
        );
        $this->app->when(\App\Services\Currency\BlockchainTicker::class)
            ->needs(ClientInterface::class)
            ->give(fn() => $httpClientMock);

        $response = $this->jsonWithAuth('post', '/api/currency/convert', [
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'code' => 200,
            'data' => $expectedData
        ]);
    }

    public static function convertDataProvider(): array
    {
        return [
            'blockchainConvertDataset1' => [
                'from' => 'USD',
                'to' => 'AUD',
                'amount' => 3000.94,
                'expectedData' => [
                    'currency_from' => 'USD',
                    'currency_to' => 'AUD',
                    'value' => 3000.94,
                    'converted_value' => '1872.59',
                    'rate' => '0.62',
                ],
                'commission' => 0.2,
            ],
            'blockchainConvertDataset2' => [
                'from' => 'USD',
                'to' => 'BTC',
                'amount' => 3000.94,
                'expectedData' => [
                    'currency_from' => 'USD',
                    'currency_to' => 'BTC',
                    'value' => 3000.94,
                    'converted_value' => '0.0668553196',
                    'rate' => '0.0000222781',
                ],
                'commission' => null,
            ],
            'blockchainConvertDataset3' => [
                'from' => 'BTC',
                'to' => 'USD',
                'amount' => 0.1023,
                'expectedData' => [
                    'currency_from' => 'BTC',
                    'currency_to' => 'USD',
                    'value' => 0.1023,
                    'converted_value' => '4411.87',
                    'rate' => '43126.80',
                ],
                'commission' => null,
            ],
        ];
    }

    public function getRates():array
    {
        return [
            'USD' => ['last' => 44006.94, 'buy' => 44006.94, 'sell' => 44006.94, ],
            'EUR' => ['last' => 36688.12, 'buy' => 36688.12, 'sell' => 36688.12, ],
            'GBP' => ['last' => 31836.99, 'buy' => 31836.99, 'sell' => 31836.99, ],
            'AUD' => ['last' => 56678.12, 'buy' => 56678.12, 'sell' => 56678.12, ],
            'JPY' => ['last' => 4839.12, 'buy' => 4839.12, 'sell' => 4839.12, ],
        ];
    }
}

//0.025
//0.02
