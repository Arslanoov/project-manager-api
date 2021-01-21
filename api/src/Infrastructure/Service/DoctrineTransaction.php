<?php

declare(strict_types=1);

namespace Infrastructure\Service;

use App\Service\TransactionInterface;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineTransaction implements TransactionInterface
{
    private EntityManagerInterface $em;

    /**
     * Transaction constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function begin(): void
    {
        $this->em->getConnection()->beginTransaction();
    }

    /**
     * @throws ConnectionException
     */
    public function commit(): void
    {
        $this->em->getConnection()->commit();
    }

    /**
     * @throws ConnectionException
     */
    public function rollback(): void
    {
        $this->em->getConnection()->rollback();
    }
}
