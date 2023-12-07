<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (HttpException $exception, $request) {
            return response()
                ->json([
                    'traceId' => $exception->getStatusCode(),
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ], $exception->getStatusCode());
        });

        $this->renderable(function (AuthenticationException $exception, $request) {
            return response()
                ->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => $exception->getMessage(),
                ], 401);
        });

        $this->renderable(function (Throwable $exception, $request) {
            return response()
                ->json([
                    'status' => 'error',
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ], 500);
        });
    }
}
