<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Task\Action;

use Domain\Todo\Entity\Schedule\Task\Name;
use Domain\Todo\Entity\Schedule\Task\Task;
use PHPUnit\Framework\TestCase;
use Tests\Builder\TaskBuilder;

class ChangeNameTest extends TestCase
{
    public function testSuccess(): void
    {
        $task = $this->createTask();

        $task->changeName($newName = new Name('New name'));

        $this->assertEquals($newName, $task->getName());
    }

    private function createTask(): Task
    {
        return (new TaskBuilder())->build();
    }
}
