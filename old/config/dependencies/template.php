<?php

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;

return [
    'factories' => [
        Environment::class => function (ContainerInterface $container) {
            $config = $container->get('config')['template'];

            $loader = new FilesystemLoader();

            foreach ($config['template_directories'] as $alias => $dir) {
                $loader->addPath($dir, $alias);
            }

            $environment = new Environment($loader, [
                'cache' => $config['debug'] ? false : $config['cache_dir'],
                'debug' => $config['debug'],
                'strict_variables' => $config['debug'],
                'auto_reload' => $config['debug'],
            ]);

            if ($config['debug']) {
                $environment->addExtension(new DebugExtension());
            }

            foreach ($config['extensions'] as $class) {
                /** @var ExtensionInterface $extension */
                $extension = $container->get($class);
                $environment->addExtension($extension);
            }

            return $environment;
        }
    ]
];
