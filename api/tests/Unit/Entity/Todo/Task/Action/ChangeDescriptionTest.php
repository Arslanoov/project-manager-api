<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Task\Action;

use Domain\Todo\Entity\Schedule\Task\Description;
use Domain\Todo\Entity\Schedule\Task\Task;
use PHPUnit\Framework\TestCase;
use Tests\Builder\TaskBuilder;

class ChangeDescriptionTest extends TestCase
{
    public function testSuccess(): void
    {
        $task = $this->createTask();

        $task->changeDescription($newDesc = new Description('Test'));

        $this->assertEquals($newDesc, $task->getDescription());
    }

    private function createTask(): Task
    {
        return (new TaskBuilder())->build();
    }
}
