<?php

declare(strict_types=1);

namespace Infrastructure\Domain\Todo\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Domain\Exception\Schedule\PersonNotFoundException;
use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\Person;
use Domain\Todo\Entity\Person\PersonRepository;

final class DoctrinePersonRepository implements PersonRepository
{
    private EntityManagerInterface $em;
    private EntityRepository $persons;

    /**
     * PersonRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        /** @var EntityRepository $persons */
        $persons = $em->getRepository(Person::class);
        $this->persons = $persons;
    }

    public function findById(Id $id): ?Person
    {
        /** @var Person|null $person */
        $person = $this->persons->find($id->getValue());
        return $person;
    }

    public function getById(Id $id): Person
    {
        if (!$person = $this->findById($id)) {
            throw new PersonNotFoundException();
        }

        return $person;
    }

    public function add(Person $person): void
    {
        $this->em->persist($person);
    }

    public function remove(Person $person): void
    {
        $this->em->remove($person);
    }
}
