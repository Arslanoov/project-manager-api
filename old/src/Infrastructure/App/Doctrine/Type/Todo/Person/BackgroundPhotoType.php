<?php

declare(strict_types=1);

namespace Infrastructure\App\Doctrine\Type\Todo\Person;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\Todo\Entity\Person\BackgroundPhoto;

final class BackgroundPhotoType extends StringType
{
    public const NAME = 'todo_person_background_photo';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof BackgroundPhoto ? $value->getPath() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new BackgroundPhoto($value) : null;
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
