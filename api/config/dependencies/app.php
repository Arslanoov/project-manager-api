<?php

use App\Http\Action\EnvAction;
use App\Http\Middleware\ErrorHandler;
use Framework\Http\Psr7\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Psr\Log\LoggerInterface;

return [
    'abstract_factories' => [
        ReflectionBasedAbstractFactory::class
    ],
    'factories' => [
        ServerRequestInterface::class => function (ContainerInterface $container) {
            return (new ServerRequestFactory())->fromGlobals();
        },
        ErrorHandler::class => function (ContainerInterface $container) {
            return new ErrorHandler(
                $container->get(ResponseFactory::class),
                $container->get(LoggerInterface::class),
                $container->get('config')['debug'],
                $container->get('config')['env']
            );
        },
        EnvAction::class => function (ContainerInterface $container) {
            return new EnvAction(
                $container->get('config')['env'],
                $container->get('config')['debug'],
                $container->get(ResponseFactory::class)
            );
        }
    ]
];
