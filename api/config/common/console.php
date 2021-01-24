<?php

declare(strict_types=1);

use App\Console\Commands\Api\GenerateDocCommand;
use Doctrine\Migrations;
use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;

return [
    'config' => [
        'console' => [
            'commands' => [
                // Doctrine Migrations
                ValidateSchemaCommand::class,
                Migrations\Tools\Console\Command\ExecuteCommand::class,
                Migrations\Tools\Console\Command\DiffCommand::class,
                Migrations\Tools\Console\Command\MigrateCommand::class,
                Migrations\Tools\Console\Command\LatestCommand::class,
                Migrations\Tools\Console\Command\ListCommand::class,
                Migrations\Tools\Console\Command\StatusCommand::class,
                Migrations\Tools\Console\Command\UpToDateCommand::class,

                // App
                GenerateDocCommand::class
            ]
        ]
    ]
];
