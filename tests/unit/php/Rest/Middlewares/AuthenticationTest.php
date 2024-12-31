<?php

use Widoz\Wp\Konomi\Rest\Middleware;
use Widoz\Wp\Konomi\Rest\Middlewares\Authentication;
use Widoz\Wp\Konomi\User\User;

beforeAll(function(): void {
    setUpWpRest();
    setUpWpError();
});

beforeEach(function (): void {
    $this->user = \Mockery::mock(User::class);
    $this->request = \Mockery::mock('WP_Rest_Request');
});

describe('Authentication Middleware', function (): void {
    it('returns error when user is not logged in', function (): void {
        $this->user->expects('isLoggedIn')->andReturnFalse();
        $next = fn() => null;

        $auth = Authentication::new($this->user);
        $response = $auth($this->request, $next);

        expect($response)
            ->toBeInstanceOf(WP_Error::class)
            ->and($response->get_error_code())->toBe('unauthorized')
            ->and($response->get_error_data()['status'])->toBe(401);
    });

    it('calls next middleware when user is logged in', function () {
        $this->user->expects('isLoggedIn')->andReturnTrue();
        $next = fn(\WP_Rest_Request $request) => new WP_REST_Response(['success' => true]);

        $auth = Authentication::new($this->user);
        $response = $auth($this->request, $next);

        expect($response)
            ->toBeInstanceOf(WP_REST_Response::class)
            ->and($response->get_data())->toBe(['success' => true]);
    });
});
