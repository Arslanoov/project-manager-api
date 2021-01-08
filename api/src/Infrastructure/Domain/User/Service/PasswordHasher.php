<?php

declare(strict_types=1);

namespace Infrastructure\Domain\User\Service;

use Domain\User\Service\PasswordHasherInterface;
use RuntimeException;

final class PasswordHasher implements PasswordHasherInterface
{
    public function hash(string $password): string
    {
        /** @var string|null|false $hash */
        $hash = password_hash($password, PASSWORD_ARGON2ID);

        if (null === $hash) {
            throw new RuntimeException('Invalid hash algorithm.');
        }

        if (false === $hash) {
            throw new RuntimeException('Unable to generate hash.');
        }

        return $hash;
    }
}
