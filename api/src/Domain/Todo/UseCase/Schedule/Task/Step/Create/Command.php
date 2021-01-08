<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\Create;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    public int $id;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    public string $taskId;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=32, allowEmptyString=true)
     */
    public string $name;

    /**
     * Command constructor.
     * @param int $id
     * @param string $taskId
     * @param string $name
     */
    public function __construct(int $id, string $taskId, string $name)
    {
        $this->id = $id;
        $this->taskId = $taskId;
        $this->name = $name;
    }
}
