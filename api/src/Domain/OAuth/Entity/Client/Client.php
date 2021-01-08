<?php

declare(strict_types=1);

namespace Domain\OAuth\Entity\Client;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * Class Client
 * @package Domain\OAuth\Entity\Client
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingParamType
 */
class Client implements ClientEntityInterface
{
    use EntityTrait;
    use ClientTrait;

    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string|string[] $uri
     */
    public function setRedirectUri($uri): void
    {
        $this->redirectUri = $uri;
    }
}
