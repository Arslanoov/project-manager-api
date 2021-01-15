<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task\Step;

final class SortOrder
{
    private ?int $value = null;

    /**
     * SortOrder constructor.
     * @param int|null $value
     */
    public function __construct(?int $value = null)
    {
        $this->value = $value;
    }

    /**
     * @return int|null
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    public function isEqual(SortOrder $order): bool
    {
        return $this->value === $order->getValue();
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
