<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Http;

use Framework\Http\ApplicationInterface;
use Framework\Http\Pipeline\MiddlewarePipelineInterface;
use Framework\Http\Pipeline\MiddlewareResolverInterface;
use Framework\Http\Pipeline\PathMiddlewareDecorator;
use Framework\Http\Router\RouteData;
use Framework\Http\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Application implements ApplicationInterface
{
    private RouterInterface $router;
    private MiddlewareResolverInterface $resolver;
    private MiddlewarePipelineInterface $pipeline;

    public function __construct(
        RouterInterface $router,
        MiddlewareResolverInterface $resolver,
        MiddlewarePipelineInterface $pipeline
    ) {
        $this->router = $router;
        $this->resolver = $resolver;
        $this->pipeline = $pipeline;
    }

    public function get(string $name, string $path, string $handler, array $options = []): void
    {
        $this->addRoute($name, $path, $handler, ['GET'], $options);
    }

    public function post(string $name, string $path, string $handler, array $options = []): void
    {
        $this->addRoute($name, $path, $handler, ['POST'], $options);
    }

    public function patch(string $name, string $path, string $handler, array $options = []): void
    {
        $this->addRoute($name, $path, $handler, ['PATCH'], $options);
    }

    public function put(string $name, string $path, string $handler, array $options = []): void
    {
        $this->addRoute($name, $path, $handler, ['PUT'], $options);
    }

    public function delete(string $name, string $path, string $handler, array $options = []): void
    {
        $this->addRoute($name, $path, $handler, ['DELETE'], $options);
    }

    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    public function customMethodsRoute(
        string $name,
        string $path,
        string $handler,
        array $methods,
        array $options = []
    ): void {
        $this->addRoute($name, $path, $handler, $methods, $options);
    }

    private function addRoute(string $name, string $path, string $handler, array $methods, array $options = []): void
    {
        $this->router->addRoute(new RouteData($name, $path, $handler, $methods, $options));
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handle($request);
    }

    /**
     * @param mixed $path
     * @param MiddlewareInterface|null $middleware
     */
    public function pipe($path, MiddlewareInterface $middleware = null): void
    {
        if ($middleware === null) {
            /** @var MiddlewareInterface $middleware */
            $middleware = $this->resolver->resolve($path);
            $this->pipeline->pipe($middleware);
        } else {
            /** @var MiddlewareInterface $middleware */
            $middleware = $this->resolver->resolve($middleware);
            /** @var string $path */
            $this->pipeline->pipe(new PathMiddlewareDecorator($path, $middleware));
        }
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->pipeline->process($request, $handler);
    }

    private function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->pipeline->handle($request);
    }
}
