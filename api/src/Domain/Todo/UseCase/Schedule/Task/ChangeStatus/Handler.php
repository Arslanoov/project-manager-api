<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\ChangeStatus;

use Domain\FlusherInterface;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\Status;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;

final class Handler
{
    private TaskRepository $tasks;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param TaskRepository $tasks
     * @param FlusherInterface $flusher
     */
    public function __construct(TaskRepository $tasks, FlusherInterface $flusher)
    {
        $this->tasks = $tasks;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $task = $this->tasks->getById(new Id($command->id));

        $task->changeStatus(new Status($command->status));

        $this->tasks->add($task);

        $this->flusher->flush();
    }
}
