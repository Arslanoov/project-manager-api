<?php

declare(strict_types=1);

namespace Domain\Model\OAuth\Entity\AuthCode;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_auth_codes")
 * @psalm-suppress MissingParamType
 * @psalm-suppress MissingConstructor
 */
class AuthCode implements AuthCodeEntityInterface
{
    use EntityTrait;
    use TokenEntityTrait;
    use AuthCodeTrait;

    /**
     * @var string
     * @ORM\Column(type="string", length=80)
     * @ORM\Id
     */
    protected $identifier;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", name="expiry_date_time")
     */
    protected $expiryDateTime;

    /**
     * @var string
     * @ORM\Column(type="guid", name="user_identifier")
     */
    protected $userIdentifier;

    /**
     * @var ClientEntityInterface
     * @ORM\Column(type="oauth_client")
     */
    protected $client;

    /**
     * @var ScopeEntityInterface[]
     * @ORM\Column(type="oauth_scopes")
     */
    protected $scopes = [];

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     * @ORM\Id
     */
    protected $redirectUri;
}
