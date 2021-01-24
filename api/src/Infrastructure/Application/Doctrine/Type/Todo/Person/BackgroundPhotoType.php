<?php

declare(strict_types=1);

namespace Infrastructure\Application\Doctrine\Type\Todo\Person;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\Model\Todo\Entity\Person\BackgroundPhoto;
use JetBrains\PhpStorm\Pure;

final class BackgroundPhotoType extends StringType
{
    public const NAME = 'todo_person_background_photo';

    #[Pure]
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof BackgroundPhoto ? $value->getPath() : $value;
    }

    #[Pure]
    public function convertToPHPValue($value, AbstractPlatform $platform): ?BackgroundPhoto
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
