<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;

final class Status
{
    private const STATUS_DRAFT = 'Draft';
    private const STATUS_ACTIVE = 'Active';

    private const LIST = [
        self::STATUS_DRAFT,
        self::STATUS_ACTIVE
    ];

    private string $value;

    /**
     * Status constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        Assert::string($value);
        Assert::lengthBetween($value, 4, 7);
        Assert::oneOf($value, self::LIST);
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function isDraft(): bool
    {
        return $this->value === self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->value === self::STATUS_ACTIVE;
    }

    public static function draft(): self
    {
        return new self(self::STATUS_DRAFT);
    }

    public static function active(): self
    {
        return new self(self::STATUS_ACTIVE);
    }

    public function getStatusList(): array
    {
        return self::LIST;
    }
}
