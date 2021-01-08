<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use DateTimeImmutable;
use Domain\Exception\DomainException;
use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ConfirmToken
 * @package Domain\User\Entity\User
 * @ORM\Embeddable()
 */
final class ConfirmToken
{
    /**
     * @var string
     * @ORM\Column(type="string", name="value", nullable=true)
     */
    private string $value;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="expires", nullable=true)
     */
    private DateTimeImmutable $expires;

    /**
     * ConfirmToken constructor.
     * @param string $value
     * @param DateTimeImmutable $expires
     */
    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::notEmpty($value, 'User confirm token value required');
        Assert::string($value, 'User confirm token value must be string');
        Assert::lengthBetween($value, 16, 64, 'User confirm token value must be between 16 and 64 chars length');
        Assert::uuid($value, 'User confirm token value must be uuid');
        $this->value = mb_strtolower($value);
        Assert::notEmpty($expires, 'User confirm token expires date required');
        $this->expires = $expires;
    }

    public function validate(string $value, DateTimeImmutable $date): void
    {
        if (!$this->isEqualTo($value)) {
            throw new DomainException('Token is invalid.');
        }

        if ($this->isExpiredTo($date)) {
            throw new DomainException('Token is expired.');
        }
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getExpires(): DateTimeImmutable
    {
        return $this->expires;
    }

    public function isEqualTo(string $token): bool
    {
        return $this->value === $token;
    }

    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }
}
