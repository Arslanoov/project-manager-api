<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Webmozart\Assert\Assert;

final class Login
{
    private string $value;

    /**
     * Login constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'User login required');
        Assert::string($value, 'User login must be string');
        Assert::lengthBetween($value, 4, 32, 'User login must be between 4 and 32 chars length');
        $this->value = $value;
    }

    public function getRaw(): string
    {
        return $this->value;
    }

    public function isEqual(Login $login): bool
    {
        return $this->value === $login->getRaw();
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
