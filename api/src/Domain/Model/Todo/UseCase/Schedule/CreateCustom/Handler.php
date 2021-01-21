<?php

declare(strict_types=1);

namespace Domain\Model\Todo\UseCase\Schedule\CreateCustom;

use Domain\Model\FlusherInterface;
use Domain\Model\Todo\Entity\Person\Id as PersonId;
use Domain\Model\Todo\Entity\Person\PersonRepository;
use Domain\Model\Todo\Entity\Schedule\Id as ScheduleId;
use Domain\Model\Todo\Entity\Schedule\Name;
use Domain\Model\Todo\Entity\Schedule\Schedule;
use Domain\Model\Todo\Entity\Schedule\ScheduleRepository;

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
        $person = $this->persons->getById(new PersonId($command->personId));

        $schedule = Schedule::custom(
            new ScheduleId($command->id),
            new Name($command->name),
            $person
        );

        $this->schedules->add($schedule);

        $this->flusher->flush();
    }
}
