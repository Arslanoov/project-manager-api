<?php

declare(strict_types=1);

namespace Infrastructure\Application\Doctrine\Type\OAuth;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Domain\Model\OAuth\Entity\Client\Client;

final class ClientType extends StringType
{
    public const NAME = 'oauth_client';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Client ? $value->getIdentifier() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!empty($value)) {
            $client = new Client($value);
            $client->setName($value);
            return $client;
        }
        return null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
