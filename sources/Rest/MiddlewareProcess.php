<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

use Widoz\Wp\Konomi\Types;

/**
 * @internal
 *
 * @psalm-import-type MiddlewareCallable from Middleware
 */
class MiddlewareProcess
{
    /**
     * @psalm-suppress UnusedConstructor - This class should not be instantiated
     */
    final private function __construct()
    {
    }

    /**
     * @param array<Middleware> $middlewares
     * @param MiddlewareCallable $controller
     */
    public static function run(
        array $middlewares,
        callable $controller,
        \WP_REST_Request $request
    ): \WP_REST_Response|\WP_Error {

        /** @var MiddlewareCallable $runner */
        $runner = array_reduce(
            array_reverse($middlewares),
            /** @param MiddlewareCallable $next */
            static fn (callable $next, Middleware $middleware)
                => static fn (\WP_REST_Request $request): \WP_REST_Response|\WP_Error
                => $middleware($request, $next),
            $controller
        );

        return $runner($request);
    }
}
