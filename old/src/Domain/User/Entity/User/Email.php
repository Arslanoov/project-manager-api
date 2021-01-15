<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Webmozart\Assert\Assert;

final class Email
{
    private string $value;

    /**
     * Email constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'User email required');
        Assert::string($value, 'User email must be string');
        Assert::lengthBetween($value, 5, 32, 'User email must be between 5 and 32 chars length');
        Assert::email($value, 'Incorrect email');
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(Email $email): bool
    {
        return $this->value === $email->getValue();
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
