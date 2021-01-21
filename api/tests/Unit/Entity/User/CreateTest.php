<?php

declare(strict_types=1);

namespace Tests\Unit\Entity\User;

use DateTimeImmutable;
use Domain\Model\User\Entity\User\ConfirmToken;
use Domain\Model\User\Entity\User\Email;
use Domain\Model\User\Entity\User\Id;
use Domain\Model\User\Entity\User\Login;
use Domain\Model\User\Entity\User\Password;
use Domain\Model\User\Entity\User\Status;
use Domain\Model\User\Entity\User\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = User::signUpByEmail(
            Id::uuid4(),
            $login = new Login('some login'),
            $email = new Email('app@test.app'),
            $password = new Password('Some password'),
            $token = new ConfirmToken(Uuid::uuid4()->toString(), new DateTimeImmutable())
        );

        $this->assertEquals($user->getLogin(), $login);
        $this->assertTrue($user->getLogin()->isEqual($login));

        $this->assertEquals($user->getEmail(), $email);
        $this->assertTrue($user->getEmail()->isEqual($email));

        $this->assertEquals($password, $user->getPassword());
        $this->assertTrue($user->getPassword()->isEqual($password));

        $this->assertNotEmpty($user->getSignUpConfirmToken());

        $this->assertEquals(Status::draft(), $user->getStatus());
        $this->assertTrue($user->isDraft());
        $this->assertFalse($user->isActive());

        $this->assertEquals($token, $user->getSignUpConfirmToken());
    }
}
