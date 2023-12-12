<?php

namespace App\Factories;

use AvtoDev\JsonRpc\Factories\FactoryInterface;
use AvtoDev\JsonRpc\Factories\RequestFactory;
use AvtoDev\JsonRpc\Responses\ErrorResponseInterface;
use AvtoDev\JsonRpc\Responses\SuccessResponseInterface;

class RpcRequestFactory extends RequestFactory
{
    public function successResponseToJsonString(SuccessResponseInterface $response, int $options = 0): string
    {
        return (string) \json_encode([
            'status' => 'success',
            'code' => 200,
            'data'  => $response->getResult(),
        ], $options | JSON_THROW_ON_ERROR);
    }
}
