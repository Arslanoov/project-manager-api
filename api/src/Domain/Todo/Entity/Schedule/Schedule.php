<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\Exception\DomainException;
use Domain\Todo\Entity\Person\Person;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="todo_schedules")
 * @ORM\Entity()
 */
class Schedule
{
    /**
     * @var Id
     * @ORM\Column(type="todo_schedule_id")
     * @ORM\Id()
     */
    private Id $id;
    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Domain\Todo\Entity\Person\Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=false)
     */
    private Person $person;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="date_immutable")
     */
    private DateTimeImmutable $date;
    /**
     * @var Name
     * @ORM\Column(type="todo_schedule_name")
     */
    private Name $name;
    /**
     * @var Type
     * @ORM\Column(type="todo_schedule_type")
     */
    private Type $type;
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Domain\Todo\Entity\Schedule\Task\Task", mappedBy="schedule", cascade={"REMOVE"})
     */
    private Collection $tasks;
    /**
     * @var int
     * @ORM\Column(type="integer", name="tasks_count")
     */
    private int $tasksCount = 0;

    /**
     * Schedule constructor.
     * @param Id $id
     * @param Person $person
     * @param DateTimeImmutable $date
     * @param Name $name
     * @param Type $type
     * @param int $tasksCount
     */
    private function __construct(
        Id $id,
        Person $person,
        DateTimeImmutable $date,
        Name $name,
        Type $type,
        int $tasksCount = 0
    ) {
        $this->id = $id;
        $this->person = $person;
        $this->date = $date;
        $this->name = $name;
        $this->type = $type;
        $this->tasks = new ArrayCollection();
        $this->tasksCount = $tasksCount;
    }

    public static function main(Id $id, Person $person): self
    {
        return new self(
            $id,
            $person,
            new DateTimeImmutable('today'),
            new Name('Main list'),
            Type::main()
        );
    }

    public static function daily(Id $id, Person $person): self
    {
        return new self(
            $id,
            $person,
            new DateTimeImmutable('today'),
            new Name('Daily list'),
            Type::daily()
        );
    }

    public static function byDate(Id $id, Person $person, DateTimeImmutable $date): self
    {
        return new self(
            $id,
            $person,
            $date,
            new Name('Daily list'),
            Type::daily()
        );
    }

    public static function custom(Id $id, Name $name, Person $person): self
    {
        return new self(
            $id,
            $person,
            new DateTimeImmutable('today'),
            $name,
            Type::custom()
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
     * @return Person
     */
    public function getPerson(): Person
    {
        return $this->person;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return Type
     */
    public function getType(): Type
    {
        return $this->type;
    }

    public function getTasks(): array
    {
        return $this->tasks->toArray();
    }

    /**
     * @return int
     */
    public function getTasksCount(): int
    {
        return $this->tasksCount;
    }

    public function getTasksCollection(): Collection
    {
        return $this->tasks;
    }

    public function addTask(): void
    {
        $this->tasksCount += 1;
    }

    public function removeTask(): void
    {
        if ($this->tasksCount === 0) {
            throw new DomainException('The number of tasks cannot be negative');
        }
        $this->tasksCount -= 1;
    }

    public function isMain(): bool
    {
        return $this->getType()->isMain();
    }

    public function isDaily(): bool
    {
        return $this->getType()->isDaily();
    }

    public function isCustom(): bool
    {
        return $this->getType()->isCustom();
    }

    public function isNotCustom(): bool
    {
        return !$this->isCustom();
    }

    public function rename(Name $name): void
    {
        if ($this->isNotCustom()) {
            throw new DomainException('You can rename only custom schedule');
        }
        $this->name = $name;
    }
}
