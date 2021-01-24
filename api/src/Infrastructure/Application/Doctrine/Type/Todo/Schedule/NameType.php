<?php

declare(strict_types=1);

namespace Infrastructure\Application\Doctrine\Type\Todo\Schedule;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\Model\Todo\Entity\Schedule\Name;
use JetBrains\PhpStorm\Pure;

final class NameType extends StringType
{
    public const NAME = 'todo_schedule_name';

    #[Pure]
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Name ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Name
    {
        return !empty($value) ? new Name($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
