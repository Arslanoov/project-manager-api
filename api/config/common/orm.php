<?php

declare(strict_types=1);

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;

return [
    EntityManagerInterface::class => function (ContainerInterface $container): EntityManagerInterface {
        $settings = $container->get('config')['doctrine'];

        foreach ($settings['types'] as $name => $class) {
            if (!Type::hasType($name)) {
                Type::addType($name, $class);
            }
        }

        $config = Setup::createAnnotationMetadataConfiguration(
            $settings['metadata_dirs'],
            $settings['dev_mode'],
            $settings['proxy_dir'],
            $settings['cache_dir'] ? new FilesystemCache($settings['cache_dir']) : new ArrayCache(),
            false
        );

        $config->setNamingStrategy(new UnderscoreNamingStrategy());

        return EntityManager::create(
            $settings['connection'],
            $config
        );
    },

    'config' => [
        'doctrine' => [
            'dev_mode' => true,
            'cache_dir' => __DIR__ . '/../../var/cache/doctrine/cache',
            'proxy_dir' => __DIR__ . '/../../var/cache/doctrine/proxy',
            'connection' => [
                'driver' => getenv('DATABASE_DRIVER'),
                'url' => getenv('DATABASE_URL'),
                'charset' => getenv('DATABASE_CHARSET')
            ],
            'metadata_dirs' => [
                __DIR__ . '/../../src/Domain/Model'
            ],
            'types' => [
                // User
                Infrastructure\Application\Doctrine\Type\User\IdType::NAME => Infrastructure\Application\Doctrine\Type\User\IdType::class,
                Infrastructure\Application\Doctrine\Type\User\LoginType::NAME => Infrastructure\Application\Doctrine\Type\User\LoginType::class,
                Infrastructure\Application\Doctrine\Type\User\EmailType::NAME => Infrastructure\Application\Doctrine\Type\User\EmailType::class,
                Infrastructure\Application\Doctrine\Type\User\PasswordType::NAME => Infrastructure\Application\Doctrine\Type\User\PasswordType::class,
                Infrastructure\Application\Doctrine\Type\User\StatusType::NAME => Infrastructure\Application\Doctrine\Type\User\StatusType::class,

                // OAuth
                Infrastructure\Application\Doctrine\Type\OAuth\ClientType::NAME => Infrastructure\Application\Doctrine\Type\OAuth\ClientType::class,
                Infrastructure\Application\Doctrine\Type\OAuth\ScopeType::NAME => Infrastructure\Application\Doctrine\Type\OAuth\ScopeType::class,

                // Person
                Infrastructure\Application\Doctrine\Type\Todo\Person\IdType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Person\IdType::class,
                Infrastructure\Application\Doctrine\Type\Todo\Person\LoginType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Person\LoginType::class,
                Infrastructure\Application\Doctrine\Type\Todo\Person\BackgroundPhotoType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Person\BackgroundPhotoType::class,

                // Schedule
                Infrastructure\Application\Doctrine\Type\Todo\Schedule\IdType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Schedule\IdType::class,
                Infrastructure\Application\Doctrine\Type\Todo\Schedule\NameType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Schedule\NameType::class,
                Infrastructure\Application\Doctrine\Type\Todo\Schedule\TypeType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Schedule\TypeType::class,

                // Task
                Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\IdType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\IdType::class,
                Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\NameType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\NameType::class,
                Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\DescriptionType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\DescriptionType::class,
                Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\ImportantLevelType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\ImportantLevelType::class,
                Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\StatusType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\StatusType::class,

                // Step
                Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\Step\IdType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\Step\IdType::class,
                Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\Step\NameType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\Step\NameType::class,
                Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\Step\SortOrderType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\Step\SortOrderType::class,
                Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\Step\StatusType::NAME => Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\Step\StatusType::class
            ]
        ]
    ],
];
