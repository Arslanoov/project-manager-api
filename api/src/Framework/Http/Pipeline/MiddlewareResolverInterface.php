<?php

declare(strict_types=1);

namespace Framework\Http\Pipeline;

use Psr\Http\Server\MiddlewareInterface;

interface MiddlewareResolverInterface
{
    /**
     * @param mixed $handler
     * @return MiddlewareInterface|MiddlewarePipeInterface|string
     */
    public function resolve($handler);
}
