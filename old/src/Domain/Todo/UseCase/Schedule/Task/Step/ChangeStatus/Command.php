<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\Step\ChangeStatus;

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
     * @Assert\Length(min=4, max=16, allowEmptyString=true)
     */
    public string $status;

    /**
     * Command constructor.
     * @param int $id
     * @param string $status
     */
    public function __construct(int $id, string $status)
    {
        $this->id = $id;
        $this->status = $status;
    }
}
