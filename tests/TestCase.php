<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function jsonWithAuth(string $method, string $uri, array $data = [], array $headers = []): TestResponse
    {
        return $this
            ->withHeaders([
                'Authorization' => 'Bearer test-auth-token',
            ])
            ->json($method, $uri, $data, $headers);
    }
}
