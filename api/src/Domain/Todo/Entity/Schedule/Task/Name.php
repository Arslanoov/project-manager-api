<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

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
        Assert::notEmpty($value, 'Task name required');
        Assert::string($value, 'Task name must be string');
        Assert::lengthBetween($value, 1, 128, 'Task name must be between 1 and 128 chars length');
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(Name $name): bool
    {
        return $this->value === $name->getValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
