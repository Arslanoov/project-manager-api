<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\Create;

use Domain\Todo\Entity\Schedule\Task\Step\Id;
use Domain\Todo\Entity\Schedule\Task\Step\SortOrder;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Step\Name;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\Todo\Entity\Schedule\Task\Id as TaskId;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\FlusherInterface;

final class Handler
{
    private StepRepository $steps;
    private TaskRepository $tasks;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param StepRepository $steps
     * @param TaskRepository $tasks
     * @param FlusherInterface $flusher
     */
    public function __construct(StepRepository $steps, TaskRepository $tasks, FlusherInterface $flusher)
    {
        $this->steps = $steps;
        $this->tasks = $tasks;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $task = $this->tasks->getById(new TaskId($command->taskId));

        $step = Step::new(
            new Id($command->id),
            $task,
            new Name($command->name)
        );

        $step->changeSortOrder(new SortOrder($command->id));

        $this->steps->add($step);

        $this->flusher->flush();
    }
}
