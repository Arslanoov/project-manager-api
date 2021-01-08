<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task\Step;

use Domain\Todo\Entity\Schedule\Task\Task;

interface StepRepository
{
    public function findById(Id $id): ?Step;

    public function findHigherStep(Task $task, SortOrder $order): ?Step;

    public function findLowerStep(Task $task, SortOrder $order): ?Step;

    public function getById(Id $id): Step;

    public function getHigherStep(Task $task, SortOrder $order): Step;

    public function getLowerStep(Task $task, SortOrder $order): Step;

    public function getByTask(Task $task): array;

    public function getNextId(): Id;

    public function add(Step $step): void;

    public function remove(Step $step): void;
}
