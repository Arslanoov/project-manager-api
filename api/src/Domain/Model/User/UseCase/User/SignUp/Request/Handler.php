<?php

declare(strict_types=1);

namespace Domain\Model\User\UseCase\User\SignUp\Request;

use DateTimeImmutable;
use Domain\Model\Exception\User\UserAlreadyExistsException;
use Domain\Model\FlusherInterface;
use Domain\Model\User\Entity\User\Email;
use Domain\Model\User\Entity\User\Id;
use Domain\Model\User\Entity\User\Login;
use Domain\Model\User\Entity\User\Password;
use Domain\Model\User\Entity\User\User;
use Domain\Model\User\Entity\User\UserRepository;
use Domain\Model\User\Service\PasswordHasherInterface;
use Domain\Model\User\Service\SignUpConfirmSender;
use Domain\Model\User\Service\TokenGenerator;

final class Handler
{
    private UserRepository $users;
    private PasswordHasherInterface $hasher;
    private SignUpConfirmSender $sender;
    private TokenGenerator $token;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param UserRepository $users
     * @param PasswordHasherInterface $hasher
     * @param SignUpConfirmSender $sender
     * @param TokenGenerator $token
     * @param FlusherInterface $flusher
     */
    public function __construct(
        UserRepository $users,
        PasswordHasherInterface $hasher,
        SignUpConfirmSender $sender,
        TokenGenerator $token,
        FlusherInterface $flusher
    ) {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->sender = $sender;
        $this->token = $token;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        if ($this->users->hasByLogin($login = new Login($command->login))) {
            throw new UserAlreadyExistsException('User with this login already exists.');
        }
        if ($this->users->hasByEmail($email = new Email($command->email))) {
            throw new UserAlreadyExistsException('User with this email already exists.');
        }

        $user = User::signUpByEmail(
            new Id($command->id),
            $login,
            $email,
            new Password($this->hasher->hash($command->password)),
            $signUpConfirmToken = $this->token->generate(new DateTimeImmutable())
        );

        $this->users->add($user);

        $this->flusher->flush();

        $this->sender->send($login, $email, $signUpConfirmToken);
    }
}
