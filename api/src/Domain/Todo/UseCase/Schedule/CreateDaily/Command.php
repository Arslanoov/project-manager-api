<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\CreateDaily;

use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public string $personId;

    /**
     * Command constructor.
     * @param string $personId
     */
    public function __construct(string $personId)
    {
        $this->personId = $personId;
    }
}
