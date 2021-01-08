<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Person;

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
        Assert::notEmpty($value, 'Person login required');
        Assert::string($value, 'Person login must be string');
        Assert::lengthBetween($value, 4, 32, 'Person login must be between 4 nd 32 chars length');
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
