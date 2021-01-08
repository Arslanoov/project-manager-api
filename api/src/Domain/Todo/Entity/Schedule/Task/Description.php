<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

use Webmozart\Assert\Assert;

final class Description
{
    private string $value;

    /**
     * Name constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'Task description required');
        Assert::string($value, 'Task description must be string');
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(Description $description): bool
    {
        return $this->value === $description->getValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
