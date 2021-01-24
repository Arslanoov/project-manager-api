<?php

declare(strict_types=1);

namespace Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\Model\Todo\Entity\Schedule\Task\ImportantLevel;
use JetBrains\PhpStorm\Pure;

final class ImportantLevelType extends StringType
{
    public const NAME = 'todo_schedule_task_important_level';

    #[Pure]
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof ImportantLevel ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ImportantLevel
    {
        return !empty($value) ? new ImportantLevel($value) : null;
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
