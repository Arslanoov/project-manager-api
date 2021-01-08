<?php

declare(strict_types=1);

namespace Domain\OAuth\Entity\Scope;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class Scope implements ScopeEntityInterface
{
    use EntityTrait;

    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public function jsonSerialize()
    {
        return $this->getIdentifier();
    }
}
