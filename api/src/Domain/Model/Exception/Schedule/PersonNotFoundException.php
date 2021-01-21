<?php

declare(strict_types=1);

namespace Domain\Model\Exception\Schedule;

use Domain\Model\Exception\DomainException;
use Throwable;

final class PersonNotFoundException extends DomainException
{
    public function __construct(string $message = "Person not found.", int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
