<?php

declare(strict_types=1);

namespace Domain\Model\User\UseCase\User\SignUp\Confirm;

use Doctrine\ORM\EntityManagerInterface;
use Domain\Model\Exception\DomainException;
use Domain\Model\FlusherInterface;
use DateTimeImmutable;
use Domain\Model\User\Entity\User\ConfirmToken;
use Domain\Model\User\Entity\User\UserRepository;

final class Handler
{
    private UserRepository $users;
    private EntityManagerInterface $em;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param UserRepository $users
     * @param EntityManagerInterface $em
     * @param FlusherInterface $flusher
     */
    public function __construct(UserRepository $users, EntityManagerInterface $em, FlusherInterface $flusher)
    {
        $this->users = $users;
        $this->em = $em;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->users->findBySignUpConfirmToken($command->token)) {
            throw new DomainException('Incorrect token.');
        }

        $user->confirmSignUp(new ConfirmToken($command->token, new DateTimeImmutable()));

        $this->em->persist($user);

        $this->flusher->flush();
    }
}
