<?php

declare(strict_types=1);

namespace Tests\Builder;

use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\Task\Description;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\ImportantLevel;
use Domain\Todo\Entity\Schedule\Task\Name;
use Domain\Todo\Entity\Schedule\Task\Task;

final class TaskBuilder
{
    private Id $id;
    private Schedule $schedule;
    private Name $name;
    private Description $description;
    private ImportantLevel $level;

    public function __construct()
    {
        $this->id = Id::uuid4();
        $this->schedule = (new ScheduleBuilder())->daily();
        $this->name = new Name('TaskName');
        $this->description = new Description('Description');
        $this->level = ImportantLevel::veryImportant();
    }

    public function withId(Id $id): self
    {
        $builder = clone $this;
        $builder->id = $id;
        return $builder;
    }

    public function withSchedule(Schedule $schedule): self
    {
        $builder = clone $this;
        $builder->schedule = $schedule;
        return $builder;
    }

    public function withName(Name $name): self
    {
        $builder = clone $this;
        $builder->name = $name;
        return $builder;
    }

    public function withDescription(Description $description): self
    {
        $builder = clone $this;
        $builder->description = $description;
        return $builder;
    }

    public function withImportantLevel(ImportantLevel $level): self
    {
        $builder = clone $this;
        $builder->level = $level;
        return $builder;
    }

    public function build(): Task
    {
        return Task::new(
            $this->id,
            $this->schedule,
            $this->name,
            $this->description,
            $this->level
        );
    }
}