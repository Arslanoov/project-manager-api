<?php

declare(strict_types=1);

namespace Tests\Builder;

use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\Login;
use Domain\Todo\Entity\Person\Person;

final class PersonBuilder
{
    private Id $id;
    private Login $login;

    public function __construct()
    {
        $this->id = Id::uuid4();
        $this->login = new Login('Person login');
    }

    public function withId(Id $id): self
    {
        $builder = clone $this;
        $builder->id = $id;
        return $builder;
    }

    public function withLogin(Login $login): self
    {
        $builder = clone $this;
        $builder->login = $login;
        return $builder;
    }

    public function build(): Person
    {
        return new Person(
            $this->id,
            $this->login
        );
    }
}