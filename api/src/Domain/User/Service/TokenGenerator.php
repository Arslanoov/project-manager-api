<?php

declare(strict_types=1);

namespace Domain\User\Service;

use DateTimeImmutable;
use Domain\User\Entity\User\ConfirmToken;

interface TokenGenerator
{
    public function generate(DateTimeImmutable $date): ConfirmToken;
}
