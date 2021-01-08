<?php

use Psr\Container\ContainerInterface;
use Finesse\SwiftMailerDefaultsPlugin\SwiftMailerDefaultsPlugin;

return [
    'factories' => [
        Swift_Mailer::class => static function (ContainerInterface $container) {
            $config = $container->get('config')['mailer'];

            $transport = (new Swift_SmtpTransport($config['host'], $config['port']))
                ->setUsername($config['user'])
                ->setPassword($config['password'])
                ->setEncryption($config['encryption']);

            $mailer = new Swift_Mailer($transport);

            $mailer->registerPlugin(new SwiftMailerDefaultsPlugin([
                'from' => $config['from'],
            ]));

            return new Swift_Mailer($transport);
        }
    ]
];
