<?php

declare(strict_types=1);

namespace Domain\Model\Todo\Entity\Schedule;

use JetBrains\PhpStorm\Pure;
use Webmozart\Assert\Assert;

final class Name
{
    private string $value;

    /**
     * Name constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'Schedule name required');
        Assert::lengthBetween($value, 1, 32, 'Schedule name must be between 1 and 32 chars length');
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    #[Pure]
    public function isEqual(Name $name): bool
    {
        return $this->value === $name->getValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
