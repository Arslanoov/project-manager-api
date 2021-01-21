<?php

declare(strict_types=1);

namespace Domain\Model\User\Service;

use DateTimeImmutable;
use Domain\Model\User\Entity\User\ConfirmToken;

interface TokenGenerator
{
    public function generate(DateTimeImmutable $date): ConfirmToken;
}
