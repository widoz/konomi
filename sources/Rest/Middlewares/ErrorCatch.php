<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest\Middlewares;

use Widoz\Wp\Konomi\Rest\Middleware;

class ErrorCatch implements Middleware
{
    public static function new(): ErrorCatch
    {
        return new self();
    }

    final private function __construct()
    {
    }

    public function __invoke(\WP_REST_Request $request, callable $next): \WP_REST_Response|\WP_Error
    {
        try {
            return $next($request);
        } catch (\Throwable $exception) {
            // TODO Only in DEBUG MODE. Or we risk to leak sensitive information.
            return new \WP_Error(
                'internal_error',
                __(
                    'An internal error occurred. Contact the site owner or try again later.',
                    'konomi'
                ),
                [
                    'status' => 500,
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage(),
                    'stackTrace' => $exception->getTraceAsString(),
                ]
            );
        }
    }
}
