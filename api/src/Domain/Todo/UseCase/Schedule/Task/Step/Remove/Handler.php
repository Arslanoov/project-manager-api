<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\Remove;

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
        $task = $step->getTask();

        if ($step->isComplete()) {
            $task->unFinishStep();
        }

        $this->steps->remove($step);

        $this->flusher->flush();
    }
}
