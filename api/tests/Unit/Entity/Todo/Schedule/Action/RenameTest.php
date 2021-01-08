<?php

declare(strict_types=1);

namespace Tests\Unit\Entity\Todo\Schedule\Action;

use Domain\Todo\Entity\Schedule\Name;
use Domain\Todo\Entity\Schedule\Schedule;
use PHPUnit\Framework\TestCase;
use Tests\Builder\ScheduleBuilder;

class RenameTest extends TestCase
{
    public function testSuccess(): void
    {
        $schedule = $this->createCustomSchedule();

        $schedule->rename($name = new Name('New name'));

        $this->assertEquals($schedule->getName(), $name);
    }

    private function createCustomSchedule(): Schedule
    {
        return (new ScheduleBuilder())->custom();
    }
}
