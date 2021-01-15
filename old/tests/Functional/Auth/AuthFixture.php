<?php

declare(strict_types=1);

namespace Tests\Functional\Auth;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Domain\User\Entity\User\Password;
use Tests\Builder\UserBuilder;

class AuthFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withPassword(new Password('$argon2id$v=19$m=65536,t=4,p=1$T1F6aFJuSXJraVplNDEyZA$DKvWSp+wIRhcx1NUSqe9wQLqM2dtWW6O8Crzo35wDUM'))
            ->build();

        $manager->persist($user);
        $manager->flush();
    }
}
