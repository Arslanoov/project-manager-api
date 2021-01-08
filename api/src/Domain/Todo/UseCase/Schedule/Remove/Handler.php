<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Remove;

use Domain\FlusherInterface;
use Domain\Todo\Entity\Schedule\Id;
use Domain\Todo\Entity\Schedule\ScheduleRepository;

final class Handler
{
    private ScheduleRepository $schedules;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param ScheduleRepository $schedules
     * @param FlusherInterface $flusher
     */
    public function __construct(ScheduleRepository $schedules, FlusherInterface $flusher)
    {
        $this->schedules = $schedules;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $schedule = $this->schedules->getById(new Id($command->id));

        $this->schedules->remove($schedule);

        $this->flusher->flush();
    }
}
