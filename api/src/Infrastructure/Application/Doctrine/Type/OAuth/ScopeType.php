<?php

declare(strict_types=1);

namespace Infrastructure\Application\Doctrine\Type\OAuth;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;
use Domain\Model\OAuth\Entity\Scope\Scope;

final class ScopeType extends JsonType
{
    public const NAME = 'oauth_scopes';

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return mixed
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        $data = array_map(function (Scope $entity): ?string {
            return $entity->getIdentifier();
        }, $value);

        return parent::convertToDatabaseValue($data, $platform);
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return array
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): array
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
