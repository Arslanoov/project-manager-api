<?php

declare(strict_types=1);

namespace Tests\Functional\Auth;

use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Domain\User\Entity\User\ConfirmToken;
use Domain\User\Entity\User\Email;
use Domain\User\Entity\User\Id;
use Domain\Todo\Entity\Person\Id as PersonId;
use Domain\Todo\Entity\Person\Login as PersonLogin;
use Domain\User\Entity\User\Login;
use Tests\Builder\PersonBuilder;
use Tests\Builder\UserBuilder;

final class ConfirmFixture extends AbstractFixture
{
    public const SUCCESS_USER_TOKEN = '8ab82383-67fe-4174-ae89-99f242bac2d3';
    public const EXPIRED_USER_TOKEN = 'bf9398e7-a913-4097-b28d-66bf8b344119';

    public function load(ObjectManager $manager)
    {
        $successUser = (new UserBuilder())
            ->withId($id = Id::uuid4())
            ->withLogin($login = new Login('success'))
            ->withEmail(new Email('success@email.com'))
            ->withSignUpConfirmToken(new ConfirmToken(self::SUCCESS_USER_TOKEN, (new DateTimeImmutable())->modify('+1 hour')))
            ->build();

        $successPerson = (new PersonBuilder())
            ->withId(new PersonId($id->getValue()))
            ->withLogin(new PersonLogin($login->getRaw()))
            ->build();

        $expiredUser = (new UserBuilder())
            ->withId($id = Id::uuid4())
            ->withLogin($login = new Login('expired'))
            ->withEmail(new Email('expired@email.com'))
            ->withSignUpConfirmToken(new ConfirmToken(self::EXPIRED_USER_TOKEN, (new DateTimeImmutable())->modify('-1 month')))
            ->build();

        $expiredPerson = (new PersonBuilder())
            ->withId(new PersonId($id->getValue()))
            ->withLogin(new PersonLogin($login->getRaw()))
            ->build();

        $manager->persist($successUser);
        $manager->persist($successPerson);
        $manager->persist($expiredUser);
        $manager->persist($expiredPerson);
        $manager->flush();
    }
}
