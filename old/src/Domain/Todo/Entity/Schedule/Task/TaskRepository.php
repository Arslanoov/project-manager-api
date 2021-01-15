<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

interface TaskRepository
{
    public function findById(Id $id): ?Task;

    public function getById(Id $id): Task;

    public function add(Task $task): void;

    public function remove(Task $task): void;
}
