<?php

declare(strict_types=1);

namespace Domain\Todo\UseCase\Schedule\CreateByDate;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final class Command
{
    /**
     * @var DateTimeImmutable
     * @Assert\NotBlank()
     */
    public DateTimeImmutable $date;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    public string $personId;

    /**
     * Command constructor.
     * @param DateTimeImmutable $date
     * @param string $personId
     */
    public function __construct(DateTimeImmutable $date, string $personId)
    {
        $this->date = $date;
        $this->personId = $personId;
    }
}
