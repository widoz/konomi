<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Rest;

/**
 * @internal
 *
 * @phpstan-import-type MiddlewareCallable from Middleware
 */
class MiddlewareProcess
{
    /**
     * @codeCoverageIgnore
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
            static fn (callable $next, Middleware $middleware): callable
                => static fn (\WP_REST_Request $request): \WP_REST_Response|\WP_Error
                /**
                 * phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
                 */
                => $middleware($request, $next),
            $controller
        );

        return $runner($request);
    }
}
