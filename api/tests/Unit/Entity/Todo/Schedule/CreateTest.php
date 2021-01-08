<?php

declare(strict_types=1);

namespace Tests\Unit\Entity\Todo\Schedule;

use DateTimeImmutable;
use Domain\Todo\Entity\Person\Person;
use Domain\Todo\Entity\Schedule\Name;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\Id;
use PHPUnit\Framework\TestCase;
use Tests\Builder\PersonBuilder;

class CreateTest extends TestCase
{
    private Person $person;

    protected function setUp(): void
    {
        $this->person = (new PersonBuilder())->build();
    }

    public function testSuccessDaily(): void
    {
        $person = $this->person;

        $date = new DateTimeImmutable('today');

        $schedule = Schedule::daily(
            $id = Id::uuid4(),
            $person
        );

        $this->assertEquals($schedule->getId(), $id);
        $this->assertTrue($schedule->getId()->isEqual($id));

        $this->assertEquals($schedule->getPerson(), $person);

        $this->assertEquals($schedule->getName(), new Name('Daily list'));

        $this->assertEquals($schedule->getDate(), $date);

        $this->assertEquals($schedule->getTasksCount(), 0);

        $this->assertFalse($schedule->isMain());
        $this->assertFalse($schedule->isCustom());
        $this->assertTrue($schedule->isNotCustom());
        $this->assertTrue($schedule->isDaily());
    }

    public function testSuccessMain(): void
    {
        $person = $this->person;
        $date = new DateTimeImmutable('today');

        $schedule = Schedule::main(
            $id = Id::uuid4(),
            $person
        );

        $this->assertEquals($schedule->getId(), $id);
        $this->assertTrue($schedule->getId()->isEqual($id));

        $this->assertEquals($schedule->getPerson(), $person);

        $this->assertEquals($schedule->getName(), new Name('Main list'));

        $this->assertEquals($schedule->getDate(), $date);

        $this->assertEquals($schedule->getTasksCount(), 0);

        $this->assertFalse($schedule->isDaily());
        $this->assertFalse($schedule->isCustom());
        $this->assertTrue($schedule->isNotCustom());
        $this->assertTrue($schedule->isMain());
    }

    public function testSuccessCustom(): void
    {
        $person = $this->person;
        $date = new DateTimeImmutable('today');

        $schedule = Schedule::custom(
            $id = Id::uuid4(),
            $name = new Name('Some custom list'),
            $person
        );

        $this->assertEquals($schedule->getId(), $id);
        $this->assertTrue($schedule->getId()->isEqual($id));

        $this->assertEquals($schedule->getPerson(), $person);

        $this->assertEquals($schedule->getName(), $name);

        $this->assertEquals($schedule->getDate(), $date);

        $this->assertEquals($schedule->getTasksCount(), 0);

        $this->assertFalse($schedule->isMain());
        $this->assertFalse($schedule->isDaily());
        $this->assertFalse($schedule->isNotCustom());
        $this->assertTrue($schedule->isCustom());
    }

    public function testSuccessByDate(): void
    {
        $person = $this->person;
        $date = new DateTimeImmutable('-10 day');

        $schedule = Schedule::byDate(
            $id = Id::uuid4(),
            $person,
            $date
        );

        $this->assertEquals($schedule->getId(), $id);
        $this->assertTrue($schedule->getId()->isEqual($id));

        $this->assertEquals($schedule->getPerson(), $person);

        $this->assertEquals($schedule->getName(), new Name('Daily list'));

        $this->assertEquals($schedule->getDate(), $date);

        $this->assertEquals($schedule->getTasksCount(), 0);

        $this->assertFalse($schedule->isMain());
        $this->assertTrue($schedule->isNotCustom());
        $this->assertFalse($schedule->isCustom());
        $this->assertTrue($schedule->isDaily());
    }
}