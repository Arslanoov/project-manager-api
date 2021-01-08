<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\Task\ChangeStatus;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    public string $id;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=4, max=16)
     */
    public string $status;

    /**
     * Command constructor.
     * @param string $id
     * @param string $status
     */
    public function __construct(string $id, string $status)
    {
        $this->id = $id;
        $this->status = $status;
    }
}
