<?php

declare(strict_types=1);

namespace Domain\Exception\Schedule;

use Domain\Exception\DomainException;
use Throwable;

final class PersonNotFoundException extends DomainException
{
    public function __construct(string $message = "Person not found.", int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
