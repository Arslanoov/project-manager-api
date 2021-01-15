<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\Exception\DomainException;
use Domain\Todo\Entity\Schedule\Schedule;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="todo_schedule_tasks")
 * @ORM\Entity()
 */
class Task
{
    /**
     * @var Id
     * @ORM\Column(type="todo_schedule_task_id")
     * @ORM\Id()
     */
    private Id $id;
    /**
     * @var Schedule
     * @ORM\ManyToOne(targetEntity="Domain\Todo\Entity\Schedule\Schedule", inversedBy="tasks")
     * @ORM\JoinColumn(name="schedule_id", referencedColumnName="id", nullable=false)
     */
    private Schedule $schedule;
    /**
     * @var Name
     * @ORM\Column(type="todo_schedule_task_name")
     */
    private Name $name;
    /**
     * @var Description
     * @ORM\Column(type="todo_schedule_task_description")
     */
    private Description $description;
    /**
     * @var ImportantLevel
     * @ORM\Column(type="todo_schedule_task_important_level")
     */
    private ImportantLevel $level;
    /**
     * @var Status
     * @ORM\Column(type="todo_schedule_task_status")
     */
    private Status $status;
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Domain\Todo\Entity\Schedule\Task\Step\Step", mappedBy="task", cascade={"REMOVE"})
     */
    private Collection $steps;
    /**
     * @var int
     * @ORM\Column(type="integer", name="finished_steps")
     */
    private int $finishedSteps = 0;

    /**
     * Task constructor.
     * @param Id $id
     * @param Schedule $schedule
     * @param Name $name
     * @param Description $description
     * @param ImportantLevel $level
     * @param Status $status
     * @param int $finishedSteps
     */
    private function __construct(
        Id $id,
        Schedule $schedule,
        Name $name,
        Description $description,
        ImportantLevel $level,
        Status $status,
        int $finishedSteps
    ) {
        $this->id = $id;
        $this->schedule = $schedule;
        $this->name = $name;
        $this->description = $description;
        $this->level = $level;
        $this->status = $status;
        $this->steps = new ArrayCollection();
        $this->finishedSteps = $finishedSteps;
    }

    public static function new(
        Id $id,
        Schedule $schedule,
        Name $name,
        Description $description,
        ImportantLevel $level
    ): self {
        return new self(
            $id,
            $schedule,
            $name,
            $description,
            $level,
            Status::notComplete(),
            0
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
     * @return Schedule
     */
    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return Description
     */
    public function getDescription(): Description
    {
        return $this->description;
    }

    /**
     * @return ImportantLevel
     */
    public function getLevel(): ImportantLevel
    {
        return $this->level;
    }

    /**
     * @return array
     */
    public function getSteps(): array
    {
        return $this->steps->toArray();
    }

    /**
     * @return Collection
     */
    public function getStepsCollection(): Collection
    {
        return $this->steps;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getFinishedSteps(): int
    {
        return $this->finishedSteps;
    }

    public function isNotImportant(): bool
    {
        return $this->getLevel()->isNotImportant();
    }

    public function isImportant(): bool
    {
        return $this->getLevel()->isImportant();
    }

    public function isVeryImportant(): bool
    {
        return $this->getLevel()->isVeryImportant();
    }

    public function isNotComplete(): bool
    {
        return $this->getStatus()->isNotComplete();
    }

    public function isComplete(): bool
    {
        return $this->getStatus()->isComplete();
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function changeImportantLevel(ImportantLevel $importantLevel): void
    {
        $this->level = $importantLevel;
    }

    public function changeDescription(Description $description): void
    {
        $this->description = $description;
    }

    public function changeStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function changeSchedule(Schedule $schedule): void
    {
        $this->schedule = $schedule;
    }

    public function notComplete(): void
    {
        $this->status = Status::notComplete();
    }

    public function complete(): void
    {
        $this->status = Status::complete();
    }

    public function finishStep(): void
    {
        $this->finishedSteps += 1;
    }

    public function unFinishStep(): void
    {
        if ($this->finishedSteps === 0) {
            throw new DomainException('The number of steps cannot be negative');
        }
        $this->finishedSteps -= 1;
    }

    public function makeNotImportant(): void
    {
        $this->level = ImportantLevel::notImportant();
    }

    public function makeImportant(): void
    {
        $this->level = ImportantLevel::important();
    }

    public function makeVeryImportant(): void
    {
        $this->level = ImportantLevel::veryImportant();
    }
}
