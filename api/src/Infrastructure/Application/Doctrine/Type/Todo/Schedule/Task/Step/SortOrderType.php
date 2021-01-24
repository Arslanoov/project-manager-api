<?php

declare(strict_types=1);

namespace Infrastructure\Application\Doctrine\Type\Todo\Schedule\Task\Step;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use Domain\Model\Todo\Entity\Schedule\Task\Step\SortOrder;
use JetBrains\PhpStorm\Pure;

final class SortOrderType extends IntegerType
{
    public const NAME = 'todo_schedule_task_step_sort_order';

    #[Pure]
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof SortOrder ? $value->getValue() : $value;
    }

    #[Pure]
    public function convertToPHPValue($value, AbstractPlatform $platform): SortOrder
    {
        return new SortOrder($value);
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
