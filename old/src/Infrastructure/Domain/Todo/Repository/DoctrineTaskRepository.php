<?php

declare(strict_types=1);

namespace Infrastructure\Domain\Todo\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Domain\Exception\Schedule\TaskNotFoundException;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;

final class DoctrineTaskRepository implements TaskRepository
{
    private EntityManagerInterface $em;
    private EntityRepository $tasks;

    /**
     * TaskRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        /** @var EntityRepository $tasks */
        $tasks = $em->getRepository(Task::class);
        $this->tasks = $tasks;
    }

    public function findById(Id $id): ?Task
    {
        /** @var Task $task */
        $task = $this->tasks->find($id->getValue());
        return $task;
    }

    public function getById(Id $id): Task
    {
        if (!$task = $this->findById($id)) {
            throw new TaskNotFoundException();
        }

        return $task;
    }

    public function add(Task $task): void
    {
        $this->em->persist($task);
    }

    public function remove(Task $task): void
    {
        $this->em->remove($task);
    }
}
