<?php

declare(strict_types=1);

namespace Unit\Entity\User\Token;

use Domain\User\Entity\User\ConfirmToken;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $token = new ConfirmToken(
            $value = Uuid::uuid4()->toString(),
            $expires = new DateTimeImmutable()
        );

        $this->assertEquals($value, $token->getValue());
        $this->assertEquals($expires, $token->getExpires());
    }

    public function testCase(): void
    {
        $value = Uuid::uuid4()->toString();

        $token = new ConfirmToken(
            mb_strtoupper($value),
            new DateTimeImmutable()
        );

        $this->assertEquals($value, $token->getValue());
    }

    public function testTooShort(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User confirm token value must be between 16 and 64 chars length');
        new ConfirmToken('short', new DateTimeImmutable());
    }

    public function testTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User confirm token value must be between 16 and 64 chars length');
        new ConfirmToken('toolongtoolongtoolongtoolongtoolongtoolongtoolongtoolongtoolongtoolong', new DateTimeImmutable());
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User confirm token value required');
        new ConfirmToken('', new DateTimeImmutable());
    }

    public function testNotUuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User confirm token value must be uuid');
        new ConfirmToken('2222222222222222222', new DateTimeImmutable());
    }
}
