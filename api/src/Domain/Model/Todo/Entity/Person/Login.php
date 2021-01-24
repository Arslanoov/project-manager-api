<?php

declare(strict_types=1);

namespace Domain\Model\Todo\Entity\Person;

use JetBrains\PhpStorm\Pure;
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
        Assert::lengthBetween($value, 4, 32, 'Person login must be between 4 nd 32 chars length');
        $this->value = $value;
    }

    public function getRaw(): string
    {
        return $this->value;
    }

    #[Pure]
    public function isEqual(Login $login): bool
    {
        return $this->value === $login->getRaw();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
