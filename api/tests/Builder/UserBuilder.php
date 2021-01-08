<?php

declare(strict_types=1);

namespace Tests\Builder;

use DateTimeImmutable;
use Domain\User\Entity\User\ConfirmToken;
use Domain\User\Entity\User\Email;
use Domain\User\Entity\User\Password;
use Domain\User\Entity\User\Id;
use Domain\User\Entity\User\Login;
use Domain\User\Entity\User\Status;
use Domain\User\Entity\User\User;
use Ramsey\Uuid\Uuid;

final class UserBuilder
{
    private Id $id;
    private Login $login;
    private Email $email;
    private Password $password;
    private Status $status;
    private ConfirmToken $signUpConfirmToken;

    public function __construct()
    {
        $this->id = Id::uuid4();
        $this->login = new Login('User login');
        $this->email = new Email('app@test.app');
        $this->password = new Password('Password');
        $this->status = Status::draft();
        $this->signUpConfirmToken = new ConfirmToken(Uuid::uuid4()->toString(), new DateTimeImmutable());
    }

    public function withId(Id $id): self
    {
        $builder = clone $this;
        $builder->id = $id;
        return $builder;
    }

    public function withLogin(Login $login): self
    {
        $builder = clone $this;
        $builder->login = $login;
        return $builder;
    }

    public function withEmail(Email $email): self
    {
        $builder = clone $this;
        $builder->email = $email;
        return $builder;
    }

    public function withPassword(Password $password): self
    {
        $builder = clone $this;
        $builder->password = $password;
        return $builder;
    }

    public function withStatus(Status $status): self
    {
        $builder = clone $this;
        $builder->status = $status;
        return $builder;
    }

    public function withSignUpConfirmToken(ConfirmToken $token): self
    {
        $builder = clone $this;
        $builder->signUpConfirmToken = $token;
        return $builder;
    }

    public function build(): User
    {
        return new User(
            $this->id,
            $this->login,
            $this->email,
            $this->password,
            $this->status,
            $this->signUpConfirmToken
        );
    }
}
