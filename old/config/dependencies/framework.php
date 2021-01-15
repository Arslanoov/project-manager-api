<?php

use App\Http\Middleware\NotFoundHandler;
use Framework\Http\ActionResolverInterface;
use Framework\Http\Application;
use Framework\Http\Pipeline\MiddlewarePipeInterface;
use Framework\Http\Pipeline\MiddlewareResolverInterface;
use Framework\Http\Psr7\ResponseFactory;
use Framework\Http\Router\Router;
use Infrastructure as Implementation;
use Psr\Container\ContainerInterface;

return [
    'factories' => [
        ResponseFactory::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Framework\Http\Psr7\LaminasResponseFactory::class);
        },
        Router::class => function (ContainerInterface $container)  {
            return $container->get(Implementation\Framework\Http\Router\FuriousRouterAdapter::class);
        },
        MiddlewareResolverInterface::class => function (ContainerInterface $container) {
            return new Implementation\Framework\Http\Pipeline\FuriousMiddlewareResolver($container);
        },
        MiddlewarePipeInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Framework\Http\Pipeline\FuriousPipelineAdapter::class);
        },
        ActionResolverInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Framework\Http\FuriousActionResolver::class);
        },
        Application::class => function (ContainerInterface $container) {
            return new Application(
                $container->get(MiddlewareResolverInterface::class),
                $container->get(Router::class),
                $container->get(NotFoundHandler::class),
                $container->get(MiddlewarePipeInterface::class)
            );
        }
    ]
];
