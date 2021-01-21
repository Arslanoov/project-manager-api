<?php

declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    LoggerInterface::class => function (ContainerInterface $container) {
        $config = $container->get('config')['logs'];
        $level = $config['debug'] ? Logger::DEBUG : Logger::INFO;

        $log = new Logger('API');

        if ($config['stderr']) {
            $log->pushHandler(new StreamHandler('php://stderr', $level));
        }

        if (!empty($config['file'])) {
            $log->pushHandler(new StreamHandler($config['file'], $level));
        }

        return $log;
    },

    'config' => [
        'logs' => [
            'debug' => getenv('DEBUG') === 'true' ? true : false,
            'file' => null,
            'stderr' => true
        ]
    ]
];
