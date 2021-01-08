<?php

declare(strict_types=1);

namespace Domain\OAuth\Entity\RefreshToken;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_refresh_tokens")
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress MissingConstructor
 */
class RefreshToken implements RefreshTokenEntityInterface
{
    use RefreshTokenTrait;
    use EntityTrait;

    /**
     * @var string
     * @ORM\Column(type="string", length=80)
     * @ORM\Id
     */
    protected $identifier;

    /**
     * @var AccessTokenEntityInterface
     * @ORM\ManyToOne(targetEntity="\Domain\OAuth\Entity\AccessToken\AccessToken")
     * @ORM\JoinColumn(
     *     name="access_token_identifier",
     *     referencedColumnName="identifier",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     */
    protected $accessToken;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", name="expiry_date_time")
     */
    protected $expiryDateTime;
}
