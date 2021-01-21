<?php

declare(strict_types=1);

use App\Http\Action\EnvAction;
use App\Http\Action\NotFoundHandlerInterface;
use App\Http\Middleware\ErrorHandler;
use App\Http\Response\ResponseFactory;
use Infrastructure\Application\Http\Action\NotFoundHandler;
use Infrastructure\Application\Http\NyholmResponseFactory;
use Psr\Container\ContainerInterface;

return [
    ResponseFactory::class => static function (ContainerInterface $container): ResponseFactory {
        return $container->get(NyholmResponseFactory::class);
    },
    NotFoundHandlerInterface::class => static function (ContainerInterface $container): NotFoundHandlerInterface {
        return $container->get(NotFoundHandler::class);
    },
    ErrorHandler::class => static function (ContainerInterface $container): ErrorHandler {
        return new ErrorHandler(
            $container->get(ResponseFactory::class),
            $container->get('config')['app']['debug']
        );
    },
    EnvAction::class => function (ContainerInterface $container) {
        return new EnvAction(
            $container->get('config')['app']['env'],
            $container->get('config')['app']['debug'],
            $container->get(ResponseFactory::class)
        );
    },

    'config' => [
        'app' => [
            'debug' => getenv('DEBUG') === 'true',
            'frontend' => [
                'url' => getenv('FRONTEND_URL') ?? 'http://localhost:8080'
            ],
            'env' => getenv('ENV') ?? 'prod'
        ]
    ]
];
