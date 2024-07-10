<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

class MiddlewareProcess
{
    public static function new(): MiddlewareProcess
    {
        return new self();
    }

    final private function __construct() {}

    /**
     * @param array<Middleware> $middlewares
     * @param callable<Controller> $controller
     * @return array<callable<Middleware|Controller>>
     */
    public static function run(
        array $middlewares,
        callable $controller,
        \WP_REST_Request $request
    ): \WP_REST_Response|\WP_Error {

        $runner = array_reduce(
            array_reverse($middlewares),
            static fn ($next, $middleware) => static fn ($request) => $middleware(
                $request,
                $next
            ),
            $controller
        );

        return $runner($request);
    }
}
