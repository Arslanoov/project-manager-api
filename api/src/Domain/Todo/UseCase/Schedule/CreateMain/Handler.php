<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\CreateMain;

use Domain\FlusherInterface;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Person\Id as PersonId;
use Domain\Todo\Entity\Schedule\Id as ScheduleId;

final class Handler
{
    private PersonRepository $persons;
    private ScheduleRepository $schedules;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param PersonRepository $persons
     * @param ScheduleRepository $schedules
     * @param FlusherInterface $flusher
     */
    public function __construct(PersonRepository $persons, ScheduleRepository $schedules, FlusherInterface $flusher)
    {
        $this->persons = $persons;
        $this->schedules = $schedules;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->persons->getById(new PersonId($command->personId));

        $schedule = Schedule::main(
            ScheduleId::uuid4(),
            $user
        );

        $this->schedules->add($schedule);

        $this->flusher->flush();
    }
}
