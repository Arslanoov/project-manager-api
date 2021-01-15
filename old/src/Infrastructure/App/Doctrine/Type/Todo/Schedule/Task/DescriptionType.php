<?php

declare(strict_types=1);

namespace Infrastructure\App\Doctrine\Type\Todo\Schedule\Task;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\Todo\Entity\Schedule\Task\Description;

final class DescriptionType extends StringType
{
    public const NAME = 'todo_schedule_task_description';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Description ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Description($value) : null;
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
