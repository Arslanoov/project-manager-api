<?php

declare(strict_types=1);

namespace Infrastructure\Domain\Todo\Repository;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Domain\Exception\Schedule\ScheduleNotFoundException;
use Domain\Todo\Entity\Person\Person;
use Domain\Todo\Entity\Schedule\Id;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Schedule\Type;

final class DoctrineScheduleRepository implements ScheduleRepository
{
    private EntityManagerInterface $em;
    private EntityRepository $schedules;

    /**
     * ScheduleRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        /** @var EntityRepository $schedules */
        $schedules = $em->getRepository(Schedule::class);
        $this->schedules = $schedules;
    }

    public function findByDate(DateTimeImmutable $date): ?Schedule
    {
        /** @var Schedule $schedule */
        $schedule = $this->schedules->findOneBy([
            'date' => $date
        ]);

        return $schedule;
    }

    public function findById(Id $id): ?Schedule
    {
        /** @var Schedule $schedule */
        $schedule = $this->schedules->find($id->getValue());
        return $schedule;
    }

    public function getById(Id $id): Schedule
    {
        if (!$schedule = $this->findById($id)) {
            throw new ScheduleNotFoundException();
        }

        return $schedule;
    }

    // By date

    public function findDailyByDate(Person $person, DateTimeImmutable $date): ?Schedule
    {
        /** @var Schedule|null $schedule */
        $schedule = $this->schedules->findOneBy([
            'person' => $person,
            'date' => $date,
            'type' => Type::daily()
        ]);

        return $schedule;
    }

    public function getDailyByDate(Person $person, DateTimeImmutable $date): Schedule
    {
        if (!$schedule = $this->findDailyByDate($person, $date)) {
            throw new ScheduleNotFoundException();
        }

        return $schedule;
    }

    // Custom

    public function findCustomById(Person $person, Id $id): ?Schedule
    {
        /** @var Schedule|null $schedule */
        $schedule = $this->schedules->findOneBy([
            'person' => $person,
            'id' => $id,
            'type' => Type::custom()
        ]);

        return $schedule;
    }

    public function getCustomById(Person $person, Id $id): Schedule
    {
        if (!$schedule = $this->findCustomById($person, $id)) {
            throw new ScheduleNotFoundException();
        }

        return $schedule;
    }

    public function getPersonCustomSchedules(Person $person): array
    {
        /** @var array|Schedule[] $schedules */
        $schedules = $this->schedules->findBy([
            'person' => $person,
            'type' => Type::custom()
        ]);

        return $schedules;
    }

    // Main

    public function findPersonMainSchedule(Person $person): ?Schedule
    {
        /** @var Schedule|null $schedule */
        $schedule = $this->schedules->findOneBy([
            'person' => $person,
            'type' => Type::main()
        ]);

        return $schedule;
    }

    public function getPersonMainSchedule(Person $person): Schedule
    {
        if (!$schedule = $this->findPersonMainSchedule($person)) {
            throw new ScheduleNotFoundException();
        }

        return $schedule;
    }

    // Daily

    public function findNextSchedule(Person $person, Schedule $schedule): ?Schedule
    {
        /** @var Schedule $schedule */
        $schedule = $this->schedules->findOneBy([
            'person' => $person,
            'date' => $schedule->getDate()->modify('+1 day'),
            'type' => Type::daily()
        ]);

        return $schedule;
    }

    public function findPreviousSchedule(Person $person, Schedule $schedule): ?Schedule
    {
        /** @var Schedule $schedule */
        $schedule = $this->schedules->findOneBy([
            'person' => $person,
            'date' => $schedule->getDate()->modify('-1 day'),
            'type' => Type::daily()
        ]);

        return $schedule;
    }

    public function findPersonTodaySchedule(Person $person): ?Schedule
    {
        /** @var Schedule $schedule */
        $schedule = $this->schedules->findOneBy([
            'person' => $person,
            'date' => new DateTimeImmutable('today'),
            'type' => Type::daily()
        ]);

        return $schedule;
    }

    public function add(Schedule $schedule): void
    {
        $this->em->persist($schedule);
    }

    public function remove(Schedule $schedule): void
    {
        $this->em->remove($schedule);
    }
}
