<?php

declare(strict_types=1);

namespace Domain\Model\Todo\UseCase\Schedule\Task\Move;

use Domain\Model\Todo\Entity\Schedule\Id as ScheduleId;
use Domain\Model\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Model\Todo\Entity\Schedule\Task\Id as TaskId;
use Domain\Model\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\Model\FlusherInterface;

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
        $task = $this->tasks->getById(new TaskId($command->taskId));
        $newSchedule = $this->schedules->getById(new ScheduleId($command->newScheduleId));

        $task->changeSchedule($newSchedule);

        $this->flusher->flush();
    }
}
