<?php

declare(strict_types=1);

use App\Http\Action\Profile\GetPhotoAction;
use Domain\Model\Todo\Entity\Person\PersonRepository;
use Domain\Model\Todo\Service\PhotoRemoverInterface;
use Domain\Model\Todo\Service\PhotoUploaderInterface;
use Infrastructure\Domain\Model\Todo\Service\PhotoRemover;
use Infrastructure\Domain\Model\Todo\Service\PhotoUploader;
use App\Http\Response\ResponseFactory;
use Psr\Container\ContainerInterface;

return [
    PhotoUploaderInterface::class => function (ContainerInterface $container) {
        return new PhotoUploader($container->get('config')['service']['background_photo_path']);
    },
    PhotoRemoverInterface::class => function (ContainerInterface $container) {
        return new PhotoRemover($container->get('config')['service']['background_photo_path']);
    },
    GetPhotoAction::class => function (ContainerInterface $container) {
        return new GetPhotoAction(
            $container->get('config')['service']['background_photo_url'],
            $container->get(PersonRepository::class),
            $container->get(ResponseFactory::class)
        );
    }
];
