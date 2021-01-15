<?php

declare(strict_types=1);

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

    'config' => [
        'app' => [
            'debug' => getenv('DEBUG') === 'true'
        ]
    ]
];
