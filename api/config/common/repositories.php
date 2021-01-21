<?php

declare(strict_types=1);

use Infrastructure\Domain\Model as Implementation;
use Psr\Container\ContainerInterface;

return [
    Domain\Model\User\Entity\User\UserRepository::class => function (ContainerInterface $container) {
        return $container->get(Implementation\User\Repository\DoctrineUserRepository::class);
    },
    Domain\Model\Todo\Entity\Person\PersonRepository::class => function (ContainerInterface $container) {
        return $container->get(Implementation\Todo\Repository\DoctrinePersonRepository::class);
    },
    Domain\Model\Todo\Entity\Schedule\ScheduleRepository::class => function (ContainerInterface $container) {
        return $container->get(Implementation\Todo\Repository\DoctrineScheduleRepository::class);
    },
    Domain\Model\Todo\Entity\Schedule\Task\TaskRepository::class => function (ContainerInterface $container) {
        return $container->get(Implementation\Todo\Repository\DoctrineTaskRepository::class);
    },
    Domain\Model\Todo\Entity\Schedule\Task\Step\StepRepository::class => function (ContainerInterface $container) {
        return $container->get(Implementation\Todo\Repository\DoctrineStepRepository::class);
    }
];
