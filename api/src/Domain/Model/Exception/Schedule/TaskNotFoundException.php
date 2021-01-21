<?php

declare(strict_types=1);

namespace Domain\Model\Exception\Schedule;

use Domain\Model\Exception\DomainException;
use Throwable;

final class TaskNotFoundException extends DomainException
{
    public function __construct(string $message = "Task not found.", int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
