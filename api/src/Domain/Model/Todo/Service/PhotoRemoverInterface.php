<?php

declare(strict_types=1);

namespace Domain\Model\Todo\Service;

interface PhotoRemoverInterface
{
    public function remove(string $name): void;
}
