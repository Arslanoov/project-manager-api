<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;

interface TransactionInterface
{
    public function begin(): void;

    /**
     * @throws ConnectionException
     */
    public function commit(): void;

    /**
     * @throws ConnectionException
     */
    public function rollback(): void;
}
