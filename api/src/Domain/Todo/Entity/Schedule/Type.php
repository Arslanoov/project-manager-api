<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule;

use Webmozart\Assert\Assert;

final class Type
{
    private const TYPE_MAIN = 'Main';
    private const TYPE_DAILY = 'Daily';
    private const TYPE_CUSTOM = 'Custom';

    private string $value;

    /**
     * Type constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'Schedule type required');
        Assert::string($value, 'Schedule type must be string');
        Assert::lengthBetween($value, 2, 16, 'Schedule type must be between 2 and 16 chars length');
        Assert::oneOf($value, self::types(), 'Incorrect type');
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public static function main(): self
    {
        return new self(self::TYPE_MAIN);
    }

    public static function daily(): self
    {
        return new self(self::TYPE_DAILY);
    }

    public static function custom(): self
    {
        return new self(self::TYPE_CUSTOM);
    }

    public function isMain(): bool
    {
        return $this->value === self::TYPE_MAIN;
    }

    public function isDaily(): bool
    {
        return $this->value === self::TYPE_DAILY;
    }

    public function isCustom(): bool
    {
        return $this->value === self::TYPE_CUSTOM;
    }

    public static function types(): array
    {
        return [
            self::TYPE_MAIN,
            self::TYPE_DAILY,
            self::TYPE_CUSTOM
        ];
    }
}
