<?php

declare(strict_types=1);

namespace Infrastructure\Domain\User\Service;

use Domain\User\Service\PasswordValidatorInterface;

final class PasswordValidator implements PasswordValidatorInterface
{
    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
