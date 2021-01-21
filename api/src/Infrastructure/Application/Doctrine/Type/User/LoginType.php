<?php

declare(strict_types=1);

namespace Infrastructure\Application\Doctrine\Type\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\Model\User\Entity\User\Login;

final class LoginType extends StringType
{
    public const NAME = 'user_user_login';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Login ? $value->getRaw() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Login($value) : null;
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
