<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;

return [
    'factories' => [
        ValidatorInterface::class => function (): ValidatorInterface {
            AnnotationRegistry::registerLoader('class_exists');

            return Validation::createValidatorBuilder()
                ->enableAnnotationMapping()
                ->getValidator();
        },
    ]
];
