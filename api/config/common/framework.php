<?php

declare(strict_types=1);

use Framework\Http\ApplicationInterface;
use Framework\Http\Pipeline\MiddlewarePipelineInterface;
use Framework\Http\Pipeline\MiddlewareResolverInterface;
use Framework\Http\Router\RouterInterface;
use Infrastructure\Framework\Http\Application;
use Infrastructure\Framework\Http\Pipeline\BrokerPipelineAdapter;
use Infrastructure\Framework\Http\Pipeline\FuriousMiddlewareResolver;
use Infrastructure\Framework\Http\Router\AuraRouterAdapter;
use Psr\Container\ContainerInterface;

return [
    ApplicationInterface::class => static function (ContainerInterface $container): ApplicationInterface {
        return new Application(
            $container->get(RouterInterface::class),
            $container->get(MiddlewareResolverInterface::class),
            $container->get(MiddlewarePipelineInterface::class)
        );
    },
    RouterInterface::class => static function (ContainerInterface $container): RouterInterface {
        return $container->get(AuraRouterAdapter::class);
    },
    MiddlewareResolverInterface::class => static function (ContainerInterface $container): MiddlewareResolverInterface {
        return $container->get(FuriousMiddlewareResolver::class);
    },
    MiddlewarePipelineInterface::class => static function (ContainerInterface $container): MiddlewarePipelineInterface {
        return $container->get(BrokerPipelineAdapter::class);
    }
];
