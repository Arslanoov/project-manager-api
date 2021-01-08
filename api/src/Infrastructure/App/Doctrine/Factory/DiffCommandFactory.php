<?php

declare(strict_types=1);

namespace Infrastructure\App\Doctrine\Factory;

use Doctrine\DBAL\Migrations\Provider\OrmSchemaProvider;
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class DiffCommandFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new DiffCommand(
            new OrmSchemaProvider(
                $container->get(EntityManagerInterface::class)
            )
        );
    }
}
