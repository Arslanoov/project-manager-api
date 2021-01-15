<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Task;

use Domain\Exception\DomainException;
use Domain\Todo\Entity\Schedule\Task\Task;
use PHPUnit\Framework\TestCase;
use Tests\Builder\TaskBuilder;

class FinishedStepsTest extends TestCase
{
    public function testSuccess(): void
    {
        $task = $this->createTask();

        $this->assertEquals(0, $task->getFinishedSteps());

        $task->finishStep();

        $this->assertEquals(1, $task->getFinishedSteps());

        $task->unFinishStep();

        $this->assertEquals(0, $task->getFinishedSteps());
    }

    public function testNegative(): void
    {
        $task = $this->createTask();

        $this->assertEquals(0, $task->getFinishedSteps());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('The number of steps cannot be negative');

        $task->unFinishStep();
    }

    private function createTask(): Task
    {
        return (new TaskBuilder())->build();
    }
}
