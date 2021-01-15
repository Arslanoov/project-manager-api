<?php

declare(strict_types=1);

namespace Tests\Builder;

use Domain\Todo\Entity\Schedule\Task\Step\Id;
use Domain\Todo\Entity\Schedule\Task\Step\Name;
use Domain\Todo\Entity\Schedule\Task\Step\SortOrder;
use Domain\Todo\Entity\Schedule\Task\Step\Status;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Task;
use Tests\Builder\TaskBuilder;

final class StepBuilder
{
    private Id $id;
    private Task $task;
    private Name $name;
    private SortOrder $sortOrder;
    private Status $status;

    public function __construct()
    {
        $this->id = new Id(12);
        $this->task = (new TaskBuilder())->build();
        $this->name = new Name('Step name');
        $this->sortOrder = new SortOrder(null);
        $this->status = Status::notComplete();
    }

    public function withId(Id $id): self
    {
        $builder = clone $this;
        $builder->id = $id;
        return $builder;
    }

    public function withTask(Task $task): self
    {
        $builder = clone $this;
        $builder->task = $task;
        return $builder;
    }

    public function withName(Name $name): self
    {
        $builder = clone $this;
        $builder->name = $name;
        return $builder;
    }

    public function withSortOrder(SortOrder $sortOrder): self
    {
        $builder = clone $this;
        $builder->sortOrder = $sortOrder;
        return $builder;
    }

    public function withStatus(Status $status): self
    {
        $builder = clone $this;
        $builder->status = $status;
        return $builder;
    }

    public function build(): Step
    {
        return new Step(
            $this->id,
            $this->task,
            $this->name,
            $this->sortOrder,
            $this->status
        );
    }
}
