<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Http;

use Framework\Http\ActionResolverInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class FuriousActionResolver
 * @package Infrastructure\Framework\Http
 * @psalm-suppress ImplementedParamTypeMismatch
 */
final class FuriousActionResolver implements ActionResolverInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $handler
     * @return callable
     */
    public function resolve($handler): callable
    {
        return $this->container->get($handler);
    }
}
