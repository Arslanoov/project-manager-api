<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\ChangeStatus;

use Domain\FlusherInterface;
use Domain\Todo\Entity\Schedule\Task\Step\Id;
use Domain\Todo\Entity\Schedule\Task\Step\Status;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;

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
        $step = $this->steps->getById(new Id($command->id));
        $task = $step->getTask();

        $oldStatus = $step->getStatus();
        $step->changeStatus(new Status($command->status));

        if ($step->isComplete() and $oldStatus->isNotComplete()) {
            $task->finishStep();
        }
        if ($step->isNotComplete() and $oldStatus->isComplete()) {
            $task->unFinishStep();
        }

        $this->steps->add($step);

        $this->flusher->flush();
    }
}
