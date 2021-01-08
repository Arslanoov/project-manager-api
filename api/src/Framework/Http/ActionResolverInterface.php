<?php

declare(strict_types=1);

namespace Framework\Http;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface ActionResolverInterface
{
    /**
     * @param RequestHandlerInterface|MiddlewareInterface $handler
     * @return callable
     */
    public function resolve($handler): callable;
}
