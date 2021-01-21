<?php

declare(strict_types=1);

namespace Domain\Model\Todo\UseCase\Schedule\Task\Create;

use Domain\Model\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Model\Todo\Entity\Schedule\Task\Description;
use Domain\Model\Todo\Entity\Schedule\Task\Id;
use Domain\Model\Todo\Entity\Schedule\Task\ImportantLevel;
use Domain\Model\Todo\Entity\Schedule\Task\Name;
use Domain\Model\Todo\Entity\Schedule\Task\Task;
use Domain\Model\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\Model\FlusherInterface;
use Domain\Model\Todo\Entity\Schedule\Id as ScheduleId;

final class Handler
{
    private ScheduleRepository $schedules;
    private TaskRepository $tasks;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param ScheduleRepository $schedules
     * @param TaskRepository $tasks
     * @param FlusherInterface $flusher
     */
    public function __construct(ScheduleRepository $schedules, TaskRepository $tasks, FlusherInterface $flusher)
    {
        $this->schedules = $schedules;
        $this->tasks = $tasks;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $schedule = $this->schedules->getById(new ScheduleId($command->scheduleId));

        $task = Task::new(
            new Id($command->id),
            $schedule,
            new Name($command->name),
            new Description($command->description ?: 'Empty description'),
            new ImportantLevel($command->importantLevel)
        );

        $schedule->addTask();

        $this->tasks->add($task);
        $this->schedules->add($schedule);

        $this->flusher->flush();
    }
}
