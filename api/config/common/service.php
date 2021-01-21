<?php

declare(strict_types=1);

use App\Service\TransactionInterface;
use App\Service\UuidGeneratorInterface;
use Domain\Model\FlusherInterface;
use Domain\Model\Todo\Service\PhotoRemoverInterface;
use Domain\Model\Todo\Service\PhotoUploaderInterface;
use Domain\Model\User\Service\PasswordHasherInterface;
use Domain\Model\User\Service\PasswordValidatorInterface;
use Domain\Model\User\Service\SignUpConfirmSender;
use Domain\Model\User\Service\TokenGenerator;
use Frontend\Service\FrontendUrlBuilderInterface;
use Infrastructure as Implementation;
use Psr\Container\ContainerInterface;

return [
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
        return $container->get(Implementation\Domain\Model\User\Service\SignUpConfirmEmailSender::class);
    },
    TokenGenerator::class => function (ContainerInterface $container) {
        return new Implementation\Domain\Model\User\Service\ConfirmTokenGenerator(new DateInterval(
            $container->get('config')['user']['confirm_token_life_length']
        ));
    },

    PasswordHasherInterface::class => function (ContainerInterface $container) {
        return $container->get(Implementation\Domain\Model\User\Service\PasswordHasher::class);
    },
    PasswordValidatorInterface::class => function (ContainerInterface $container)  {
        return $container->get(Implementation\Domain\Model\User\Service\PasswordValidator::class);
    },

    PhotoUploaderInterface::class => function (ContainerInterface $container) {
        return new Implementation\Domain\Model\Todo\Service\PhotoUploader(
            $container->get('config')['service']['background_photo_path']
        );
    },
    PhotoRemoverInterface::class => function (ContainerInterface $container) {
        return new Implementation\Domain\Model\Todo\Service\PhotoRemover(
            $container->get('config')['service']['background_photo_path']
        );
    },

    FrontendUrlBuilderInterface::class => function (ContainerInterface $container) {
        return new Implementation\Frontend\Service\FrontendUrlBuilder($container->get('config')['app']['frontend']['url']);
    }
];
