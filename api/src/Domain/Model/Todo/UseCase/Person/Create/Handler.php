<?php

declare(strict_types=1);

namespace Domain\Model\Todo\UseCase\Person\Create;

use Domain\Model\FlusherInterface;
use Domain\Model\Todo\Entity\Person\Id;
use Domain\Model\Todo\Entity\Person\Login;
use Domain\Model\Todo\Entity\Person\Person;
use Domain\Model\Todo\Entity\Person\PersonRepository;

final class Handler
{
    private PersonRepository $persons;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param PersonRepository $persons
     * @param FlusherInterface $flusher
     */
    public function __construct(PersonRepository $persons, FlusherInterface $flusher)
    {
        $this->persons = $persons;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $person = new Person(
            new Id($command->id),
            new Login($command->login)
        );

        $this->persons->add($person);

        $this->flusher->flush();
    }
}
