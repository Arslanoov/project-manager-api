<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\Up;

use Domain\Todo\Entity\Schedule\Task\Step\Id;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\FlusherInterface;

final class Handler
{
    private StepRepository $steps;
    private FlusherInterface $flusher;

    /**
     * Handler constructor.
     * @param StepRepository $steps
     * @param FlusherInterface $flusher
     */
    public function __construct(StepRepository $steps, FlusherInterface $flusher)
    {
        $this->steps = $steps;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $step = $this->steps->getById(new Id($command->stepId));
        $higherStep = $this->steps->getHigherStep($step->getTask(), $step->getSortOrder());

        $oldOrder = $step->getSortOrder();

        $step->changeSortOrder($higherStep->getSortOrder());
        $higherStep->changeSortOrder($oldOrder);

        $this->steps->add($step);
        $this->steps->add($higherStep);

        $this->flusher->flush();
    }
}
