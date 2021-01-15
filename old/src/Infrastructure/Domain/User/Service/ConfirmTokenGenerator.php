<?php

declare(strict_types=1);

namespace Infrastructure\Domain\User\Service;

use DateInterval;
use DateTimeImmutable;
use Domain\User\Entity\User\ConfirmToken;
use Domain\User\Service\TokenGenerator;
use Ramsey\Uuid\Uuid;

final class ConfirmTokenGenerator implements TokenGenerator
{
    private DateInterval $tokenLifeLength;

    /**
     * ConfirmTokenGenerator constructor.
     * @param DateInterval $tokenLifeLength
     */
    public function __construct(DateInterval $tokenLifeLength)
    {
        $this->tokenLifeLength = $tokenLifeLength;
    }

    public function generate(DateTimeImmutable $date): ConfirmToken
    {
        return new ConfirmToken(
            Uuid::uuid4()->toString(),
            $date->add($this->tokenLifeLength)
        );
    }
}
