<?php

declare(strict_types=1);

namespace Widoz\Wp\Konomi\Rest;

class Route
{
    use RestRegistTrait;

    private Method $method;

    private array $schema = [];

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

    public function withSchema(array $schema): self
    {
        $this->schema = [
            'schema' => $schema,
            '$schema' => 'http://json-schema.org/draft-04/schema#',
        ];
        return $this;
    }

    public function withHandle(Controller $controller): self
    {
        $this->controller = $controller;
        return $this;
    }

    public function withMiddleware(Middleware $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }
}
