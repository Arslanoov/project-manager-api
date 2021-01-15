<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Http\Pipeline;

use Framework\Http\Pipeline\Exception\UnknownMiddlewareType;
use Framework\Http\Pipeline\Middleware\LazyMiddlewareDecorator;
use Framework\Http\Pipeline\Middleware\SinglePassDecoratorMiddleware;
use Framework\Http\Pipeline\MiddlewarePipelineInterface;
use Framework\Http\Pipeline\MiddlewareResolverInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionObject;

class FuriousMiddlewareResolver implements MiddlewareResolverInterface
{
    private ContainerInterface $container;

    /**
     * MiddlewareResolver constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve($handler)
    {
        if (is_array($handler)) {
            return $this->createPipe($handler);
        }

        if (is_string($handler) and $this->container->has($handler)) {
            return new LazyMiddlewareDecorator($this, $this->container, $handler);
        }

        if ($handler instanceof MiddlewareInterface) {
            return $handler;
        }

        if (is_object($handler)) {
            /** @var RequestHandlerInterface $handler */
            $reflection = new ReflectionObject($handler);
            if ($reflection->hasMethod('handle')) {
                return new SinglePassDecoratorMiddleware($handler);
            }
        }

        /** @var string $handler */
        throw new UnknownMiddlewareType($handler);
    }

    private function createPipe(array $handlers): MiddlewarePipelineInterface
    {
        /** @var MiddlewarePipelineInterface $pipeline */
        $pipeline = $this->container->get(MiddlewarePipelineInterface::class);
        /** @var array<string> $handler */
        foreach ($handlers as $handler) {
            /** @var MiddlewareInterface $middleware */
            $middleware = $this->resolve($handler);
            $pipeline->pipe($middleware);
        }
        return $pipeline;
    }
}
