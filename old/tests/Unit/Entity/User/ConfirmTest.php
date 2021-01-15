<?php

declare(strict_types=1);

namespace Unit\Entity\User;

use DateTimeImmutable;
use Domain\User\Entity\User\ConfirmToken;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\Builder\UserBuilder;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->withSignUpConfirmToken($token = $this->createConfirmToken())
            ->build()
        ;

        $this->assertTrue($user->isDraft());
        $this->assertFalse($user->isActive());
        $this->assertNotEmpty($user->getSignUpConfirmToken());

        $newToken = new ConfirmToken(
            $user->getSignUpConfirmToken()->getValue(),
            $user->getSignUpConfirmToken()->getExpires()->modify('-1 hour')
        );

        $user->confirmSignUp($newToken);

        $this->assertTrue($user->isActive());
        $this->assertFalse($user->isDraft());
        $this->assertEmpty($user->getSignUpConfirmToken());
    }

    private function createConfirmToken(): ConfirmToken
    {
        return new ConfirmToken(
            Uuid::uuid4()->toString(),
            new DateTimeImmutable('+1 hour')
        );
    }
}
