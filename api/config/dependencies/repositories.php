<?php

use Infrastructure\Domain as Implementation;
use Psr\Container\ContainerInterface;

return [
    'factories' => [
        Domain\User\Entity\User\UserRepository::class => function (ContainerInterface $container) {
            return $container->get(Implementation\User\Repository\DoctrineUserRepository::class);
        },

        Domain\Todo\Entity\Person\PersonRepository::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Todo\Repository\DoctrinePersonRepository::class);
        },
        Domain\Todo\Entity\Schedule\ScheduleRepository::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Todo\Repository\DoctrineScheduleRepository::class);
        },
        Domain\Todo\Entity\Schedule\Task\TaskRepository::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Todo\Repository\DoctrineTaskRepository::class);
        },
        Domain\Todo\Entity\Schedule\Task\Step\StepRepository::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Todo\Repository\DoctrineStepRepository::class);
        }
    ]
];
