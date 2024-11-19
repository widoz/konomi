<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

trait RestRegistTrait
{
    public function register(): void
    {
        $handler = function (\WP_REST_Request $request): \WP_REST_Response|\WP_Error {
            return MiddlewareProcess::run(
                $this->middlewares,
                $this->controller,
                $request
            );
        };

        register_rest_route($this->namespace, $this->route, [
            'methods' => $this->method->value,
            'callback' => $handler,
            'schema' => [
                'schema' => $this->schema->toArray(),
                '$schema' => 'http://json-schema.org/draft-04/schema#',
            ],
            'permission_callback' => '__return_true',
        ]);
    }
}
