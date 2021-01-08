<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Task\Action;

use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\Task\Task;
use PHPUnit\Framework\TestCase;
use Tests\Builder\ScheduleBuilder;
use Tests\Builder\TaskBuilder;

class ChangeScheduleTest extends TestCase
{
    public function testSuccess(): void
    {
        $task = $this->createTask();

        $task->changeSchedule($schedule = $this->createSchedule());

        $this->assertEquals($schedule, $task->getSchedule());
    }

    private function createTask(): Task
    {
        return (new TaskBuilder())->build();
    }

    private function createSchedule(): Schedule
    {
        return (new ScheduleBuilder())->main();
    }
}
