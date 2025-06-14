<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Rest;

/**
 * @phpstan-type MiddlewareCallable = callable(\WP_REST_Request): (\WP_REST_Response|\WP_Error)
 * @api
 */
interface Middleware
{
    /**
     * @param MiddlewareCallable $next
     */
    public function __invoke(
        \WP_REST_Request $request,
        callable $next
    ): \WP_REST_Response|\WP_Error;
}
