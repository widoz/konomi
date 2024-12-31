<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Integration\Rest;

use Brain\Monkey\Functions;
use Widoz\Wp\Konomi\Rest\Method;
use Widoz\Wp\Konomi\Rest\Schema;
use Widoz\Wp\Konomi\Rest\Middleware;
use Widoz\Wp\Konomi\Rest\RestRegistTrait;

beforeAll(function(): void {
    setUpWpRest();
    setUpWpError();
});

beforeEach(function () {
    $schema = new class implements Schema {
        public function toArray(): array
        {
            return [];
        }
    };

    $this->controller = new class implements \Widoz\Wp\Konomi\Rest\Controller {
        public function __invoke(\WP_REST_Request $request): \WP_Rest_Response {
            return new \WP_Rest_Response(
                [
                    'success' => true,
                    'middleware1' => $request->middleware1,
                    'middleware2' => $request->middleware2
                ]
            );
        }
    };
    $this->route = \Widoz\Wp\Konomi\Rest\Route::post(
        'test/v1',
        '/test',
        $schema,
        $this->controller
    )
        ->withMiddleware(
            new class implements Middleware
            {
                public function __invoke(
                    \WP_REST_Request $request,
                    callable $next
                ): \WP_REST_Response|\WP_Error {

                    $request->middleware1 = true;
                    return $next($request);
                }
            }
        )
        ->withMiddleware(
            new class implements Middleware
            {
                public function __invoke(
                    \WP_REST_Request $request,
                    callable $next
                ): \WP_Rest_Response|\WP_Error {

                    $request->middleware2 = true;
                    return $next($request);
                }
            }
        );
});

describe('Rest Register', function(): void {
    it('register rest route with correct arguments', function () {
        $handler = null;
        Functions\when('register_rest_route')->alias(
            function(string $namespace, string $route, array $args) use (&$handler): bool {
                $handler = $args['callback'];

                expect($namespace)->toBe('test/v1');
                expect($route)->toBe('/test');
                expect($args['methods'])->toContain(Method::POST->value);
                expect(isset($args['schema']))->toBeTrue();
                expect($args['permission_callback'])->toBe('__return_true');

                return true;
            }
        );

        $this->route->register();
        $response = $handler(new \WP_REST_Request());
        $data = $response->get_data();

        expect($data['success'])->toBeTrue();
        expect($data['middleware1'])->toBeTrue();
        expect($data['middleware2'])->toBeTrue();
    });
});
