<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\CreateByDate;

use Domain\FlusherInterface;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Entity\Schedule\Id;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Person\Id as PersonId;

final class Handler
{
    private ScheduleRepository $schedules;
    private PersonRepository $persons;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param ScheduleRepository $schedules
     * @param PersonRepository $persons
     * @param FlusherInterface $flusher
     */
    public function __construct(ScheduleRepository $schedules, PersonRepository $persons, FlusherInterface $flusher)
    {
        $this->schedules = $schedules;
        $this->persons = $persons;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $person = $this->persons->getById(new PersonId($command->personId));

        $schedule = Schedule::byDate(
            Id::uuid4(),
            $person,
            $command->date
        );

        $this->schedules->add($schedule);

        $this->flusher->flush();
    }
}
