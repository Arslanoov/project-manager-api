<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Move;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    public string $taskId;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    public string $newScheduleId;

    /**
     * Command constructor.
     * @param string $taskId
     * @param string $newScheduleId
     */
    public function __construct(string $taskId, string $newScheduleId)
    {
        $this->taskId = $taskId;
        $this->newScheduleId = $newScheduleId;
    }
}
