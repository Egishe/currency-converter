<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class VerifyApiAuthToken
{
    /**
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $authHeaderValue = trim($request->header('Authorization') ?? '');
        if (empty($authHeaderValue)) {
            throw new AuthenticationException('Authentication Required');
        }
        if (preg_match('/Bearer\s(\S+)/', $authHeaderValue, $matches) !== 1 || empty($matches[1])) {
            throw new AuthenticationException('Authentication Required');
        }
        $token = $matches[1];

        $configToken = config('auth.api_auth_token');

        if (
            $configToken && $token === $configToken
        ) {
            return $next($request);
        } else {
            throw new AuthenticationException('Authentication Required');
        }
    }
}
