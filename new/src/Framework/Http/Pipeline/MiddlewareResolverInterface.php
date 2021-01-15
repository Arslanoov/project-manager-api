<?php

declare(strict_types=1);

namespace Framework\Http\Pipeline;

interface MiddlewareResolverInterface
{
    /**
     * @param mixed $handler
     * @return mixed
     */
    public function resolve($handler);
}
