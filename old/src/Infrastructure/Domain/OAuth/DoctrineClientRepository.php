<?php

declare(strict_types=1);

namespace Infrastructure\Domain\OAuth;

use Domain\OAuth\Entity\Client\Client;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

final class DoctrineClientRepository implements ClientRepositoryInterface
{
    private array $clients;

    public function __construct(array $clients)
    {
        $this->clients = $clients;
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     * @param string $clientIdentifier
     * @param string $grantType
     * @param string|null $clientSecret
     * @param bool $mustValidateSecret
     * @return ClientEntityInterface|null
     */
    public function getClientEntity(
        $clientIdentifier,
        string $grantType = null,
        string $clientSecret = null,
        bool $mustValidateSecret = true
    ): ?ClientEntityInterface {
        if (array_key_exists($clientIdentifier, $this->clients) === false) {
            return null;
        }

        if (
            $mustValidateSecret === true and
            $this->clients[$clientIdentifier]['is_confidential'] === true and
            !password_verify($clientSecret, $this->clients[$clientIdentifier]['secret'])
        ) {
            return null;
        }

        $client = new Client($clientIdentifier);
        $client->setName($this->clients[$clientIdentifier]['name']);
        $client->setRedirectUri($this->clients[$clientIdentifier]['redirect_uri']);

        return $client;
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        return true;
    }
}
