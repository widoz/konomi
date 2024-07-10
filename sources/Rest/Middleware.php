<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

interface Middleware
{
    public function __invoke(
        \WP_REST_Request $request,
        callable $next
    ): \WP_REST_Response|\WP_Error;
}
