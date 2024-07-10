<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest\Middlewares;

use Widoz\Wp\Konomi\Rest;
use Widoz\Wp\Konomi\User;

class Authentication implements Rest\Middleware
{
    public static function new(User\User $user): Authentication
    {
        return new self($user);
    }

    final private function __construct(
        private readonly User\User $user
    ) {}

    public function __invoke(
        \WP_REST_Request $request,
        callable $next
    ): \WP_REST_Response|\WP_Error {

        if (!$this->user->isLoggedIn()) {
            return new \WP_Error(
                'unauthorized',
                'Unauthorized',
                ['status' => 401]
            );
        }

        return $next($request);
    }
}
