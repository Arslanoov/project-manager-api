<?php

return [
    'doctrine' => [
        'dev_mode' => getenv('ENV') === 'dev' ? true : false,
        'cache_dir' => 'var/cache/doctrine',
        'metadata_dirs' => ['src/Domain'],
        'connection' => [
            'url' => getenv('DB_URL'),
        ],
        'types' => [
            // User
            Infrastructure\App\Doctrine\Type\User\IdType::NAME => Infrastructure\App\Doctrine\Type\User\IdType::class,
            Infrastructure\App\Doctrine\Type\User\LoginType::NAME => Infrastructure\App\Doctrine\Type\User\LoginType::class,
            Infrastructure\App\Doctrine\Type\User\EmailType::NAME => Infrastructure\App\Doctrine\Type\User\EmailType::class,
            Infrastructure\App\Doctrine\Type\User\PasswordType::NAME => Infrastructure\App\Doctrine\Type\User\PasswordType::class,
            Infrastructure\App\Doctrine\Type\User\StatusType::NAME => Infrastructure\App\Doctrine\Type\User\StatusType::class,

            // OAuth
            Infrastructure\App\Doctrine\Type\OAuth\ClientType::NAME => Infrastructure\App\Doctrine\Type\OAuth\ClientType::class,
            Infrastructure\App\Doctrine\Type\OAuth\ScopeType::NAME => Infrastructure\App\Doctrine\Type\OAuth\ScopeType::class,

            // Person
            Infrastructure\App\Doctrine\Type\Todo\Person\IdType::NAME => Infrastructure\App\Doctrine\Type\Todo\Person\IdType::class,
            Infrastructure\App\Doctrine\Type\Todo\Person\LoginType::NAME => Infrastructure\App\Doctrine\Type\Todo\Person\LoginType::class,
            Infrastructure\App\Doctrine\Type\Todo\Person\BackgroundPhotoType::NAME => Infrastructure\App\Doctrine\Type\Todo\Person\BackgroundPhotoType::class,

            // Schedule
            Infrastructure\App\Doctrine\Type\Todo\Schedule\IdType::NAME => Infrastructure\App\Doctrine\Type\Todo\Schedule\IdType::class,
            Infrastructure\App\Doctrine\Type\Todo\Schedule\NameType::NAME => Infrastructure\App\Doctrine\Type\Todo\Schedule\NameType::class,
            Infrastructure\App\Doctrine\Type\Todo\Schedule\TypeType::NAME => Infrastructure\App\Doctrine\Type\Todo\Schedule\TypeType::class,

            // Task
            Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\IdType::NAME => Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\IdType::class,
            Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\NameType::NAME => Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\NameType::class,
            Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\DescriptionType::NAME => Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\DescriptionType::class,
            Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\ImportantLevelType::NAME => Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\ImportantLevelType::class,
            Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\StatusType::NAME => Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\StatusType::class,

            // Step
            Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\Step\IdType::NAME => Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\Step\IdType::class,
            Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\Step\NameType::NAME => Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\Step\NameType::class,
            Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\Step\SortOrderType::NAME => Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\Step\SortOrderType::class,
            Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\Step\StatusType::NAME => Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\Step\StatusType::class
        ]
    ]
];
