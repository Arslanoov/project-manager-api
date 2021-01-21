<?php

declare(strict_types=1);

namespace Domain\Model\OAuth\Entity\User;

use League\OAuth2\Server\Entities\UserEntityInterface;

class User implements UserEntityInterface
{
    private string $identifier;

    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
