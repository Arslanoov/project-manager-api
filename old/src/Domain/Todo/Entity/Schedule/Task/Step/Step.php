<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task\Step;

use Domain\Todo\Entity\Schedule\Task\Task;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="todo_schedule_task_steps")
 * @ORM\Entity()
 */
class Step
{
    /**
     * @var Id
     * @ORM\Column(type="todo_schedule_task_step_id")
     * @ORM\Id()
     */
    private Id $id;
    /**
     * @var Task
     * @ORM\ManyToOne(targetEntity="Domain\Todo\Entity\Schedule\Task\Task", inversedBy="steps")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", nullable=false)
     */
    private Task $task;
    /**
     * @var Name
     * @ORM\Column(type="todo_schedule_task_step_name")
     */
    private Name $name;
    /**
     * @var SortOrder
     * @ORM\Column(type="todo_schedule_task_step_sort_order", name="sort_order")
     */
    private SortOrder $sortOrder;
    /**
     * @var Status
     * @ORM\Column(type="todo_schedule_task_step_status")
     */
    private Status $status;

    /**
     * Step constructor.
     * @param Id $id
     * @param Task $task
     * @param Name $name
     * @param SortOrder $sortOrder
     * @param Status $status
     */
    public function __construct(
        Id $id,
        Task $task,
        Name $name,
        SortOrder $sortOrder,
        Status $status
    ) {
        $this->id = $id;
        $this->task = $task;
        $this->name = $name;
        $this->sortOrder = $sortOrder;
        $this->status = $status;
    }

    public static function new(Id $id, Task $task, Name $name): self
    {
        return new self(
            $id,
            $task,
            $name,
            new SortOrder(null),
            Status::notComplete()
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
     * @return Task
     */
    public function getTask(): Task
    {
        return $this->task;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return SortOrder
     */
    public function getSortOrder(): SortOrder
    {
        return $this->sortOrder;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function changeSortOrder(SortOrder $order): void
    {
        $this->sortOrder = $order;
    }

    public function changeStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function isNotComplete(): bool
    {
        return $this->getStatus()->isNotComplete();
    }

    public function isComplete(): bool
    {
        return $this->getStatus()->isComplete();
    }
}
