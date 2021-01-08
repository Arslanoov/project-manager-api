<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Person;

interface PersonRepository
{
    public function findById(Id $id): ?Person;

    public function getById(Id $id): Person;

    public function add(Person $person): void;

    public function remove(Person $person): void;
}
