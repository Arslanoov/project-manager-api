<?php

use App\Service\TransactionInterface;
use App\Service\UuidGeneratorInterface;
use Domain\FlusherInterface;
use Domain\Todo\Service\PhotoRemoverInterface;
use Domain\Todo\Service\PhotoUploaderInterface;
use Domain\User\Service\PasswordHasherInterface;
use Domain\User\Service\PasswordValidatorInterface;
use Domain\User\Service\SignUpConfirmSender;
use Domain\User\Service\TokenGenerator;
use Frontend\Service\FrontendUrlBuilderInterface;
use Infrastructure as Implementation;
use Psr\Container\ContainerInterface;

return [
    'factories' => [
        FlusherInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Service\DoctrineFlusher::class);
        },
        UuidGeneratorInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Service\RamseyUuidGenerator::class);
        },
        TransactionInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Service\DoctrineTransaction::class);
        },
        SignUpConfirmSender::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Domain\User\Service\SignUpConfirmEmailSender::class);
        },
        TokenGenerator::class => function (ContainerInterface $container) {
            return new Implementation\Domain\User\Service\ConfirmTokenGenerator(new DateInterval(
                $container->get('config')['user']['confirm_token_life_length']
            ));
        },

        PasswordHasherInterface::class => function (ContainerInterface $container) {
            return $container->get(Implementation\Domain\User\Service\PasswordHasher::class);
        },
        PasswordValidatorInterface::class => function (ContainerInterface $container)  {
            return $container->get(Implementation\Domain\User\Service\PasswordValidator::class);
        },

        PhotoUploaderInterface::class => function (ContainerInterface $container) {
            return new Implementation\Domain\Todo\Service\PhotoUploader(
                $container->get('config')['service']['background_photo_path']
            );
        },
        PhotoRemoverInterface::class => function (ContainerInterface $container) {
            return new Implementation\Domain\Todo\Service\PhotoRemover(
                $container->get('config')['service']['background_photo_path']
            );
        },

        FrontendUrlBuilderInterface::class => function (ContainerInterface $container) {
            return new Implementation\Frontend\Service\FrontendUrlBuilder($container->get('config')['frontend']['url']);
        }
    ]
];
