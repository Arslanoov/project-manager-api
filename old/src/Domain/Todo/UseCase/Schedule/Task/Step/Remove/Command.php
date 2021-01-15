<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\Remove;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    public int $stepId;

    /**
     * Command constructor.
     * @param int $stepId
     */
    public function __construct(int $stepId)
    {
        $this->stepId = $stepId;
    }
}
