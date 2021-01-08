<?php

declare(strict_types=1);

namespace Tests\Builder;

use Domain\Todo\Entity\Person\Person;
use Domain\Todo\Entity\Schedule\Name;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\Id;

final class ScheduleBuilder
{
    private Id $id;
    private Name $name;
    private Person $person;

    public function __construct()
    {
        $this->id = Id::uuid4();
        $this->name = new Name('Schedule name');
        $this->person = (new PersonBuilder())->build();
    }

    public function withId(Id $id): self
    {
        $builder = clone $this;
        $builder->id = $id;
        return $builder;
    }

    public function withName(Name $name): self
    {
        $builder = clone $this;
        $builder->name = $name;
        return $builder;
    }

    public function withPerson(Person $person): self
    {
        $builder = clone $this;
        $builder->person = $person;
        return $builder;
    }

    public function daily(): Schedule
    {
        return Schedule::daily(
            $this->id,
            $this->person
        );
    }

    public function main(): Schedule
    {
        return Schedule::main(
            $this->id,
            $this->person
        );
    }

    public function custom(): Schedule
    {
        return Schedule::custom(
            $this->id,
            $this->name,
            $this->person
        );
    }
}