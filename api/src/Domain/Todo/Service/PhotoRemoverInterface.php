<?php

declare(strict_types=1);

namespace Domain\Todo\Service;

interface PhotoRemoverInterface
{
    public function remove(string $name): void;
}
