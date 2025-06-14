<?php

declare(strict_types=1);

namespace SpaghettiDojo\Konomi\Tests\Unit\Rest;

use SpaghettiDojo\Konomi\Rest\MiddlewareProcess;
use SpaghettiDojo\Konomi\Rest\Middleware;

beforeAll(function (): void {
    setUpWpRest();
    setUpWpError();
});

describe('run', function (): void {
    it('loop the middlewares', function (): void {
        $middlewares = [
            new class implements Middleware {
                public function __invoke(
                    \WP_REST_Request $request,
                    callable $next
                ): \WP_REST_Response|\WP_Error {

                    $request->middleware1 = true;
                    return $next($request);
                }
            },
            new class implements Middleware {
                public function __invoke(
                    \WP_REST_Request $request,
                    callable $next
                ): \WP_REST_Response|\WP_Error {

                    $request->middleware2 = true;
                    return $next($request);
                }
            },
        ];

        $controller = function (\WP_REST_Request $request): \WP_REST_Response {
            return new \WP_REST_Response(['data']);
        };

        /** @var \WP_Rest_Request&\Mockery\MockInterface $request */
        $request = new \WP_Rest_Request();
        $response = MiddlewareProcess::run($middlewares, $controller, $request);

        expect($request->middleware1)->toBe(true);
        expect($request->middleware2)->toBe(true);
        expect($response->get_status())->toBe(200);
        expect($response->get_data())->toBe(['data']);
    });
});
