<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Schedule\Action;

use Domain\Exception\DomainException;
use Domain\Todo\Entity\Person\Person;
use Domain\Todo\Entity\Schedule\Id;
use Domain\Todo\Entity\Schedule\Schedule;
use PHPUnit\Framework\TestCase;
use Tests\Builder\PersonBuilder;

class TasksCountTest extends TestCase
{
    public function testSuccess(): void
    {
        $person = $this->createPerson();

        $schedule = Schedule::main(Id::uuid4(), $person);

        $this->assertEquals(0, $schedule->getTasksCount());

        $schedule->addTask();

        $this->assertEquals(1, $schedule->getTasksCount());

        $schedule->removeTask();

        $this->assertEquals(0, $schedule->getTasksCount());
    }

    public function testNegative(): void
    {
        $person = $this->createPerson();

        $schedule = Schedule::main(Id::uuid4(), $person);

        $this->assertEquals(0, $schedule->getTasksCount());

        $this->expectExceptionMessage(DomainException::class);
        $this->expectExceptionMessage('The number of tasks cannot be negative');

        $schedule->removeTask();
    }

    private function createPerson(): Person
    {
        return (new PersonBuilder())->build();
    }
}
