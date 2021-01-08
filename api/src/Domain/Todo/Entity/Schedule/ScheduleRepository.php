<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule;

use DateTimeImmutable;
use Domain\Todo\Entity\Person\Person;

interface ScheduleRepository
{
    public function findByDate(DateTimeImmutable $date): ?Schedule;

    public function findById(Id $id): ?Schedule;

    public function getById(Id $id): Schedule;

    // By date

    public function findDailyByDate(Person $person, DateTimeImmutable $date): ?Schedule;

    public function getDailyByDate(Person $person, DateTimeImmutable $date): Schedule;

    // Custom

    public function findCustomById(Person $person, Id $id): ?Schedule;

    public function getCustomById(Person $person, Id $id): Schedule;

    public function getPersonCustomSchedules(Person $person): array;

    // Main

    public function findPersonMainSchedule(Person $person): ?Schedule;

    public function getPersonMainSchedule(Person $person): Schedule;

    // Daily

    public function findNextSchedule(Person $person, Schedule $schedule): ?Schedule;

    public function findPreviousSchedule(Person $person, Schedule $schedule): ?Schedule;

    public function findPersonTodaySchedule(Person $person): ?Schedule;

    public function add(Schedule $schedule): void;

    public function remove(Schedule $schedule): void;
}
