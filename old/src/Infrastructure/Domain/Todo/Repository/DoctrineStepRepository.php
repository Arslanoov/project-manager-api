<?php

declare(strict_types=1);

namespace Infrastructure\Domain\Todo\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Domain\Exception\Schedule\StepNotFoundException;
use Domain\Todo\Entity\Schedule\Task\Step\Id;
use Domain\Todo\Entity\Schedule\Task\Step\SortOrder;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\Todo\Entity\Schedule\Task\Task;

final class DoctrineStepRepository implements StepRepository
{
    private EntityManagerInterface $em;
    private EntityRepository $steps;
    private Connection $connection;

    /**
     * StepRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        /** @var EntityRepository $steps */
        $steps = $em->getRepository(Step::class);
        $this->steps = $steps;
        $this->connection = $em->getConnection();
    }

    public function findById(Id $id): ?Step
    {
        /** @var Step $step */
        $step = $this->steps->find($id->getValue());
        return $step;
    }

    public function findHigherStep(Task $task, SortOrder $order): ?Step
    {
        $steps = $this->steps->createQueryBuilder('s')
            ->andWhere('s.sortOrder < :sortOrder')
            ->andWhere('s.task = :task')
            ->setParameter(':sortOrder', $order)
            ->setParameter(':task', $task)
            ->orderBy('s.sortOrder', 'DESC')
            ->getQuery()->getResult()
        ;

        return $steps[0] ?? null;
    }

    public function findLowerStep(Task $task, SortOrder $order): ?Step
    {
        $steps = $this->steps->createQueryBuilder('s')
            ->andWhere('s.sortOrder > :sortOrder')
            ->andWhere('s.task = :task')
            ->setParameter(':sortOrder', $order)
            ->setParameter(':task', $task)
            ->orderBy('s.sortOrder', 'ASC')
            ->getQuery()->getResult()
        ;

        return $steps[0] ?? null;
    }

    public function getById(Id $id): Step
    {
        if (!$step = $this->findById($id)) {
            throw new StepNotFoundException();
        }

        return $step;
    }

    public function getHigherStep(Task $task, SortOrder $order): Step
    {
        if (!$step = $this->findHigherStep($task, $order)) {
            throw new StepNotFoundException('Higher step not found.');
        }

        return $step;
    }

    public function getLowerStep(Task $task, SortOrder $order): Step
    {
        if (!$step = $this->findLowerStep($task, $order)) {
            throw new StepNotFoundException('Lower step not found.');
        }

        return $step;
    }

    public function getByTask(Task $task): array
    {
        return $this->steps->createQueryBuilder('s')
            ->andWhere('s.task = :task')
            ->setParameter(':task', $task)
            ->orderBy('s.sortOrder', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Id
     * @throws DBALException
     */
    public function getNextId(): Id
    {
        return new Id(
            (int) $this->connection
                ->query("SELECT nextval('todo_schedule_task_steps_id_seq')")
                ->fetchColumn()
        );
    }

    public function add(Step $step): void
    {
        $this->em->persist($step);
    }

    public function remove(Step $step): void
    {
        $this->em->remove($step);
    }
}
