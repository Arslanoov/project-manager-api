<?php

declare(strict_types=1);

namespace Infrastructure\App\Doctrine\Type\Todo\Schedule\Task\Step;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\Todo\Entity\Schedule\Task\Step\Status;

final class StatusType extends StringType
{
    public const NAME = 'todo_schedule_task_step_status';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Status ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Status($value) : null;
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
