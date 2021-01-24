<?php

declare(strict_types=1);

namespace Domain\Model\Todo\Entity\Schedule\Task\Step;

use Webmozart\Assert\Assert;

class Id
{
    private int $value;

    public function __construct(int $value)
    {
        Assert::notEmpty($value, 'Step id required');
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
