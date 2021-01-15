<?php

declare(strict_types=1);

namespace Domain\Exception\User;

use Domain\Exception\DomainException;
use Throwable;

final class UserNotFoundException extends DomainException
{
    public function __construct(string $message = "User is not found.", int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
