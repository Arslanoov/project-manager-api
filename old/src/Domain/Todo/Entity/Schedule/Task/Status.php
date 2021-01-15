<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

use Webmozart\Assert\Assert;

final class Status
{
    private const STATUS_NOT_COMPLETE = 'Not Complete';
    private const STATUS_COMPLETE = 'Complete';

    private string $value;

    /**
     * Status constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'Task status required');
        Assert::string($value, 'Task status must be string');
        Assert::lengthBetween($value, 4, 16, 'Task status must be between 4 and 16 chars length');
        Assert::oneOf($value, self::statuses(), 'Incorrect task status');
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public static function notComplete(): self
    {
        return new self(self::STATUS_NOT_COMPLETE);
    }

    public static function complete(): self
    {
        return new self(self::STATUS_COMPLETE);
    }

    public function isNotComplete(): bool
    {
        return $this->value === self::STATUS_NOT_COMPLETE;
    }

    public function isComplete(): bool
    {
        return $this->value === self::STATUS_COMPLETE;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private static function statuses(): array
    {
        return [
            self::STATUS_NOT_COMPLETE,
            self::STATUS_COMPLETE
        ];
    }
}
