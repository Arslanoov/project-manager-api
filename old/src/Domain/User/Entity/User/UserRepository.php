<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Domain\Exception\User\UserNotFoundException;

interface UserRepository
{
    public function findById(Id $id): ?User;

    public function findByLogin(Login $login): ?User;

    public function findByEmail(Email $email): ?User;

    public function findBySignUpConfirmToken(string $token): ?User;

    public function getBySignUpConfirmToken(string $token): User;

    public function hasByLogin(Login $login): bool;

    public function hasByEmail(Email $email): bool;

    public function getById(Id $id): User;

    public function add(User $user): void;

    public function remove(User $user): void;
}
