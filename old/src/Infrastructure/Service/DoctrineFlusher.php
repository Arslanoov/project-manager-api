<?php

declare(strict_types=1);

namespace Infrastructure\Service;

use Doctrine\ORM\EntityManagerInterface;
use Domain\FlusherInterface;

final class DoctrineFlusher implements FlusherInterface
{
    private EntityManagerInterface $em;

    /**
     * Flusher constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}
