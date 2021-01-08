<?php

declare(strict_types=1);

namespace Infrastructure\Domain\OAuth;

use Domain\OAuth\Entity\Scope\Scope;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

final class DoctrineScopeRepository implements ScopeRepositoryInterface
{
    /**
     * @var array|ScopeEntityInterface[]
     */
    private array $scopes;

    public function __construct()
    {
        $this->scopes = [
            'common' => new Scope('common')
        ];
    }

    public function getScopeEntityByIdentifier($identifier): ?ScopeEntityInterface
    {
        return $this->scopes[$identifier] ?? null;
    }

    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ): array {
        return array_filter($scopes, function (ScopeEntityInterface $scope) {
            foreach ($this->scopes as $item) {
                if ($scope->getIdentifier() === $item->getIdentifier()) {
                    return true;
                }
            }
            return false;
        });
    }
}
