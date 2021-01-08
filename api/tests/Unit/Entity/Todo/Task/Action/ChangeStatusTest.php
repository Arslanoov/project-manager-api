<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Task\Action;

use Domain\Todo\Entity\Schedule\Task\Status;
use Domain\Todo\Entity\Schedule\Task\Task;
use PHPUnit\Framework\TestCase;
use Tests\Builder\TaskBuilder;

class ChangeStatusTest extends TestCase
{
    public function testSuccess(): void
    {
        $task = $this->createTask();

        $task->changeStatus($status = Status::complete());

        $this->assertEquals($status, $task->getStatus());
    }

    private function createTask(): Task
    {
        return (new TaskBuilder())->build();
    }
}
