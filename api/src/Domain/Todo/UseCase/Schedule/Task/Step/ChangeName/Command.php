<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\ChangeName;

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
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=32, allowEmptyString=true)
     */
    public string $name;

    /**
     * Command constructor.
     * @param int $stepId
     * @param string $name
     */
    public function __construct(int $stepId, string $name)
    {
        $this->stepId = $stepId;
        $this->name = $name;
    }
}
