<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

use Widoz\Wp\Konomi\Types;

/**
 * @psalm-import-type MiddlewareCallable from Types
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
