<?php

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\DBAL;
use Psr\Container\ContainerInterface;

return [
    'factories' => [
        EntityManagerInterface::class => function (ContainerInterface $container) {
            $params = $container->get('config')['doctrine'];

            $config = Setup::createAnnotationMetadataConfiguration(
                $params['metadata_dirs'],
                $params['dev_mode'],
                $params['cache_dir'],
                new FilesystemCache(
                    $params['cache_dir']
                ),
                false
            );

            foreach ($params['types'] as $type => $class) {
                if (!DBAL\Types\Type::hasType($type)) {
                    DBAL\Types\Type::addType($type, $class);
                }
            }

            return EntityManager::create(
                $params['connection'],
                $config
            );
        }
    ]
];
