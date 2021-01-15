<?php

declare(strict_types=1);

namespace Tests\Functional\Auth;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Tests\Builder\UserBuilder;

class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())->build();
        $manager->persist($user);
        $manager->flush();
    }
}