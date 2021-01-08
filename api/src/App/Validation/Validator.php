<?php

declare(strict_types=1);

namespace App\Validation;

use App\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class Validator
{
    private ValidatorInterface $validator;
    private LoggerInterface $logger;

    /**
     * Validator constructor.
     * @param ValidatorInterface $validator
     * @param LoggerInterface $logger
     */
    public function __construct(ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * @param object ...$objects
     */
    public function validateObjects(...$objects): void
    {
        foreach ($objects as $object) {
            $this->validate($object);
        }
    }

    public function validate(object $object): void
    {
        $violations = $this->validator->validate($object);
        if ($violations->count() > 0) {
            $this->logger->warning('Validation errors', [
                'violations' => $violations
            ]);
            throw new ValidationException($violations);
        }
    }
}
