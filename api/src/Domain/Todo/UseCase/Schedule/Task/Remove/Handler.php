<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Remove;

use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\FlusherInterface;

final class Handler
{
    private TaskRepository $tasks;
    private ScheduleRepository $schedules;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param TaskRepository $tasks
     * @param ScheduleRepository $schedules
     * @param FlusherInterface $flusher
     */
    public function __construct(TaskRepository $tasks, ScheduleRepository $schedules, FlusherInterface $flusher)
    {
        $this->tasks = $tasks;
        $this->schedules = $schedules;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $task = $this->tasks->getById(new Id($command->taskId));
        $schedule = $task->getSchedule();
        $schedule->removeTask();

        $this->tasks->remove($task);
        $this->schedules->add($schedule);

        $this->flusher->flush();
    }
}
