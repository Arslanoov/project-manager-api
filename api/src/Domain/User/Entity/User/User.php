<?php

declare(strict_types=1);

namespace Domain\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Domain\Exception\DomainException;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"login"}),
 *     @ORM\UniqueConstraint(columns={"email"})
 * })
 */
class User
{
    /**
     * @var Id
     * @ORM\Id()
     * @ORM\Column(type="user_user_id")
     */
    private Id $id;
    /**
     * @ORM\Column(type="user_user_login")
     */
    private Login $login;
    /**
     * @ORM\Column(type="user_user_email")
     */
    private Email $email;
    /**
     * @ORM\Column(type="user_user_password")
     */
    private Password $password;
    /**
     * @var ConfirmToken
     * @ORM\Embedded(class="ConfirmToken", columnPrefix="sign_up_confirm_token_")
     */
    private ?ConfirmToken $signUpConfirmToken = null;
    /**
     * @var Status
     * @ORM\Column(type="user_user_status")
     */
    private Status $status;

    public function __construct(
        Id $id,
        Login $login,
        Email $email,
        Password $password,
        Status $status,
        ConfirmToken $token
    ) {
        $this->id = $id;
        $this->login = $login;
        $this->email = $email;
        $this->password = $password;
        $this->status = $status;
        $this->signUpConfirmToken = $token;
    }

    public static function signUpByEmail(
        Id $id,
        Login $login,
        Email $email,
        Password $password,
        ConfirmToken $signUpConfirmToken
    ): self {
        return new self(
            $id,
            $login,
            $email,
            $password,
            Status::draft(),
            $signUpConfirmToken
        );
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return Login
     */
    public function getLogin(): Login
    {
        return $this->login;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @return ConfirmToken|null
     */
    public function getSignUpConfirmToken(): ?ConfirmToken
    {
        return $this->signUpConfirmToken;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    public function isDraft(): bool
    {
        return $this->getStatus()->isDraft();
    }

    public function isActive(): bool
    {
        return $this->getStatus()->isActive();
    }

    public function confirmSignUp(ConfirmToken $token): void
    {
        if ($this->signUpConfirmToken === null) {
            throw new DomainException('Sign Up confirmation is not required.');
        }
        $this->signUpConfirmToken->validate($token->getValue(), $token->getExpires());
        $this->activate();
        $this->removeSignUpConfirmToken();
    }

    private function makeDraft(): void
    {
        if ($this->isDraft()) {
            throw new DomainException('User is already draft.');
        }

        $this->status = Status::draft();
    }

    private function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('User is already active.');
        }

        $this->status = Status::active();
    }

    private function removeSignUpConfirmToken(): void
    {
        $this->signUpConfirmToken = null;
    }
}
