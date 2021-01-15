<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Person;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="todo_persons", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"login"})
 * })
 * @ORM\Entity()
 */
class Person
{
    /**
     * @var Id
     * @ORM\Column(type="todo_person_id")
     * @ORM\Id()
     */
    private Id $id;
    /**
     * @var Login
     * @ORM\Column(type="todo_person_login")
     */
    private Login $login;
    /**
     * @var BackgroundPhoto
     * @ORM\Column(type="todo_person_background_photo", name="background_photo", nullable=true)
     */
    private ?BackgroundPhoto $backgroundPhoto = null;

    /**
     * Person constructor.
     * @param Id $id
     * @param Login $login
     */
    public function __construct(Id $id, Login $login)
    {
        $this->id = $id;
        $this->login = $login;
    }

    public static function new(Login $login): self
    {
        return new self(Id::uuid4(), $login);
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
     * @return BackgroundPhoto|null
     */
    public function getBackgroundPhoto(): ?BackgroundPhoto
    {
        return $this->backgroundPhoto;
    }

    public function hasBackgroundPhoto(): bool
    {
        return !empty($this->backgroundPhoto);
    }

    public function changeBackgroundPhoto(BackgroundPhoto $photo): void
    {
        $this->backgroundPhoto = $photo;
    }

    public function removeBackgroundPhoto(): void
    {
        $this->backgroundPhoto = null;
    }
}
