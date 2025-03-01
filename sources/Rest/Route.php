<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

/**
 * @api
 */
class Route
{
    use RestRegistTrait;

    /**
     * @var array<Middleware>
     */
    private array $middlewares = [];

    public static function post(string $namespace, string $route, Schema $schema, Controller $controller): self
    {
        $instance = new self($namespace, $route, Method::POST, $schema, $controller);
        return $instance;
    }

    final private function __construct(
        private readonly string $namespace,
        private readonly string $route,
        private readonly Method $method,
        private readonly Schema $schema,
        private readonly Controller $controller
    ) {
    }

    public function withMiddleware(Middleware $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }
}
