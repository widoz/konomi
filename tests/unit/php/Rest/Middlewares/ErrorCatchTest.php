<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Tests\Unit\Rest\Middlewares;

use Widoz\Wp\Konomi\Rest\Middlewares\ErrorCatch;

beforeAll(function (): void {
    setUpWpRest();
    setUpWpError();
});

describe('ErrorCatch Middleware', function (): void {
    describe('__invoke', function (): void {
        it('passes through successful response', function (): void {
            $request = new \WP_Rest_Request();
            $next = fn (\WP_Rest_Request $request) => new \WP_REST_Response(['success' => true]);

            $errorCatch = ErrorCatch::new();
            $response = $errorCatch($request, $next);

            expect($response)
                ->toBeInstanceOf(\WP_REST_Response::class)
                ->and($response->get_data())->toBe(['success' => true]);
        });

        it('catches exceptions and returns WP_Error', function (): void {

            $request = new \WP_Rest_Request();
            $next = function (): never {
                throw new \Exception('Test error', 100);
            };

            $errorCatch = ErrorCatch::new();
            $response = $errorCatch($request, $next);

            expect($response)
                ->toBeInstanceOf(\WP_Error::class)
                ->and($response->get_error_code())->toBe('internal_error')
                ->and($response->get_error_data())
                ->toHaveKey('status', 500)
                ->toHaveKey('code', 100)
                ->toHaveKey('message', 'Test error')
                ->toHaveKeys(['file', 'line', 'stackTrace']);
        });
    });
});
