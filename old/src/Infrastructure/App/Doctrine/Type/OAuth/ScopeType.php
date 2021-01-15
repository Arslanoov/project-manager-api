<?php

declare(strict_types=1);

namespace Infrastructure\App\Doctrine\Type\OAuth;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use Domain\OAuth\Entity\Scope\Scope;

final class ScopeType extends JsonType
{
    public const NAME = 'oauth_scopes';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return false|mixed|string|null
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $data = array_map(function (Scope $entity): ?string {
            return $entity->getIdentifier();
        }, $value);

        return parent::convertToDatabaseValue($data, $platform);
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return array|mixed|null
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $values = parent::convertToPHPValue($value, $platform);

        if ($values) {
            return array_map(function (string $value): Scope {
                return new Scope($value);
            }, $values);
        }

        return [];
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
