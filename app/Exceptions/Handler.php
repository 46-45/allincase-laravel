<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response (JSON for API).
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            if ($e instanceof ValidationException) {
                return response()->json([
                    'detail' => $e->validator->errors()->first(),
                ], 422);
            }

            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'detail' => 'Token tidak valid atau sudah expired',
                ], 401);
            }

            if ($e instanceof HttpException) {
                return response()->json([
                    'detail' => $e->getMessage() ?: 'Error',
                ], $e->getStatusCode());
            }

            // Generic error
            return response()->json([
                'detail' => config('app.debug')
                    ? $e->getMessage()
                    : 'Terjadi kesalahan internal. Silakan coba lagi.',
            ], 500);
        }

        return parent::render($request, $e);
    }
}
