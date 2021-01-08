<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Task\Action;

use Domain\Todo\Entity\Schedule\Task\ImportantLevel;
use Domain\Todo\Entity\Schedule\Task\Task;
use PHPUnit\Framework\TestCase;
use Tests\Builder\TaskBuilder;

class ChangeImportantLevelTest extends TestCase
{
    public function testSuccess(): void
    {
        $task = $this->createTask();

        $task->changeImportantLevel($newLevel = ImportantLevel::veryImportant());

        $this->assertEquals($newLevel, $task->getLevel());
    }

    private function createTask(): Task
    {
        return (new TaskBuilder())->build();
    }
}
