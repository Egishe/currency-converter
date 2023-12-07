<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class TechTest extends TestCase
{
    public function testHealthNoToken(): void
    {
        $response = $this->json('get', '/api/tech/health');
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'code' => 200,
            'data' => ['isHealthy' => true]
        ]);
    }

    public function testGuardedWithHelper(): void
    {
        $response = $this->jsonWithAuth('get', '/api/tech/guarded-health');
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'code' => 200,
            'data' => ['isHealthy' => true]
        ]);
    }


    /**
     * @dataProvider guardedDataProvider
     */
    public function testGuarded(array $headers, ?string $errorMessage): void
    {
        $response = $this->json('get', '/api/tech/guarded-health', [], $headers);
        if ($errorMessage === null) {
            $response->assertStatus(200);
            $response->assertJson([
                'status' => 'success',
                'code' => 200,
                'data' => ['isHealthy' => true]
            ]);
        } else {
            $response->assertStatus(401);
            $response->assertJson([
                'status' => 'error',
                'code' => 401,
                'message' => $errorMessage,
            ]);
        }
    }

    public static function guardedDataProvider(): array
    {
        return [
            'no_token' => [[], 'Authentication Required'],
            'wrong_token' => [['Authorization' => 'Bearer test.wrong'], 'Authentication Required'],
            'ok' => [['Authorization' => 'Bearer test-auth-token'], null],
            'no bearer' => [['Authorization' => 'test-auth-token'], 'Authentication Required'],
        ];
    }
}
