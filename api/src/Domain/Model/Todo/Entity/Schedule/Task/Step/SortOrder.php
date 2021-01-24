<?php

declare(strict_types=1);

namespace Domain\Model\Todo\Entity\Schedule\Task\Step;

use JetBrains\PhpStorm\Pure;

final class SortOrder
{
    private ?int $value;

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

    #[Pure]
    public function isEqual(SortOrder $order): bool
    {
        return $this->value === $order->getValue();
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
