<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Person\RemovePhoto;

use Domain\FlusherInterface;
use Domain\Todo\Entity\Person\BackgroundPhoto;
use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Service\PhotoRemoverInterface;

final class Handler
{
    private PersonRepository $persons;
    private PhotoRemoverInterface $remover;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param PersonRepository $persons
     * @param PhotoRemoverInterface $remover
     * @param FlusherInterface $flusher
     */
    public function __construct(PersonRepository $persons, PhotoRemoverInterface $remover, FlusherInterface $flusher)
    {
        $this->persons = $persons;
        $this->remover = $remover;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $person = $this->persons->getById(new Id($command->id));

        if ($person->hasBackgroundPhoto() and $person->getBackgroundPhoto() !== null) {
            /** @var BackgroundPhoto $photo */
            $photo = $person->getBackgroundPhoto();
            $this->remover->remove($photo->getPath());
        }

        $person->removeBackgroundPhoto();

        $this->flusher->flush();
    }
}
