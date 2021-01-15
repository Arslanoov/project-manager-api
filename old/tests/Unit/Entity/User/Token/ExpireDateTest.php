<?php

declare(strict_types=1);

namespace Unit\Entity\User\Token;

use Domain\User\Entity\User\ConfirmToken;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ExpireDateTest extends TestCase
{
    public function testNot(): void
    {
        $token = new ConfirmToken(
            $value = Uuid::uuid4()->toString(),
            $expires = new DateTimeImmutable()
        );

        $this->assertFalse($token->isExpiredTo($expires->modify('-1 secs')));
        $this->assertTrue($token->isExpiredTo($expires));
        $this->assertTrue($token->isExpiredTo($expires->modify('+1 secs')));
    }
}
