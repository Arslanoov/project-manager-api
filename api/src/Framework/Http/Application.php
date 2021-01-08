<?php

declare(strict_types=1);

namespace Framework\Http;

use Framework\Http\Pipeline\MiddlewarePipeInterface;
use Framework\Http\Pipeline\MiddlewareResolverInterface;
use Framework\Http\Pipeline\PathMiddlewareDecorator;
use Framework\Http\Router\Route;
use Framework\Http\Router\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Application
 * @package Framework\Http
 */
final class Application
{
    private Router $router;
    private RequestHandlerInterface $default;
    private MiddlewareResolverInterface $resolver;
    private MiddlewarePipeInterface $pipeline;

    public function __construct(
        MiddlewareResolverInterface $resolver,
        Router $router,
        RequestHandlerInterface $default,
        MiddlewarePipeInterface $pipeline
    ) {
        $this->resolver = $resolver;
        $this->router = $router;
        $this->default = $default;
        $this->pipeline = $pipeline;
    }

    /**
     * @param string|object $path
     * @param MiddlewareInterface|MiddlewarePipeInterface|string $middleware
     * @psalm-suppress PossiblyInvalidArgument
     */
    public function pipe($path, $middleware = null): void
    {
        if ($middleware === null) {
            $this->pipeline->pipe($this->resolver->resolve($path));
        } else {
            $this->pipeline->pipe(new PathMiddlewareDecorator($path, $this->resolver->resolve($middleware)));
        }
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->pipeline->process($request, $this->default);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->pipeline->process($request, $handler);
    }

    private function add(string $name, string $path, string $handler, array $methods, array $options = []): void
    {
        $this->router->addRoute(new Route($name, $path, $handler, $methods, $options));
    }

    public function any(string $name, string $path, string $handler, array $options = []): void
    {
        $this->add($name, $path, $handler, $options);
    }

    public function get(string $name, string $path, string $handler, array $options = []): void
    {
        $this->add($name, $path, $handler, ['GET'], $options);
    }

    public function post(string $name, string $path, string $handler, array $options = []): void
    {
        $this->add($name, $path, $handler, ['POST'], $options);
    }

    public function put(string $name, string $path, string $handler, array $options = []): void
    {
        $this->add($name, $path, $handler, ['PUT'], $options);
    }

    public function patch(string $name, string $path, string $handler, array $options = []): void
    {
        $this->add($name, $path, $handler, ['PATCH'], $options);
    }

    public function delete(string $name, string $path, string $handler, array $options = []): void
    {
        $this->add($name, $path, $handler, ['DELETE'], $options);
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }
}
