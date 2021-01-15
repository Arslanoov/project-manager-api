<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

use Webmozart\Assert\Assert;

final class ImportantLevel
{
    private const NOT_IMPORTANT_LEVEL = 'Not Important';
    private const IMPORTANT_LEVEL = 'Important';
    private const VERY_IMPORTANT_LEVEL = 'Very Important';

    private string $value;

    /**
     * ImportantLevel constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'Task important level required');
        Assert::string($value, 'Task important level must be string');
        Assert::lengthBetween($value, 4, 16, 'Task important level must be between 4 and 16 chars length');
        Assert::oneOf($value, self::levels(), 'Incorrect task important level');
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function isNotImportant(): bool
    {
        return $this->value === self::NOT_IMPORTANT_LEVEL;
    }

    public function isImportant(): bool
    {
        return $this->value === self::IMPORTANT_LEVEL;
    }

    public function isVeryImportant(): bool
    {
        return $this->value === self::VERY_IMPORTANT_LEVEL;
    }

    public static function notImportant(): self
    {
        return new self(self::NOT_IMPORTANT_LEVEL);
    }

    public static function important(): self
    {
        return new self(self::IMPORTANT_LEVEL);
    }

    public static function veryImportant(): self
    {
        return new self(self::VERY_IMPORTANT_LEVEL);
    }

    public static function levels(): array
    {
        return [
            self::NOT_IMPORTANT_LEVEL,
            self::IMPORTANT_LEVEL,
            self::VERY_IMPORTANT_LEVEL
        ];
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
