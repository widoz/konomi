<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

class Route
{
    private Method $method;

    private array $params = [];

    private Controller $controller;

    /**
     * @var array<Middleware>
     */
    private array $middlewares = [];

    public static function post(string $namespace, string $route): self
    {
        $instance = new self($namespace, $route);
        $instance->method = Method::POST;
        return $instance;
    }

    public static function get(string $namespace, string $route): self
    {
        $instance = new self($namespace, $route);
        $instance->method = Method::GET;
        return $instance;
    }

    final private function __construct(
        private readonly string $namespace,
        private readonly string $route
    ) {
    }

    // TODO This include the Rest Schema arguments.
    public function withParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    public function handle(Controller $controller): self
    {
        $this->controller = $controller;
        return $this;
    }

    public function withMiddleware(Middleware $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function register(): void
    {
        $handler = function (\WP_Rest_Request $request): \WP_REST_Response|\WP_Error {
            return MiddlewareProcess::run(
                $this->middlewares,
                $this->controller,
                $request
            );
        };

        register_rest_route($this->namespace, $this->route, [
            'methods' => $this->method->value,
            'callback' => $handler,
            'args' => $this->params,
            'permission_callback' => '__return_true',
        ]);
    }
}
