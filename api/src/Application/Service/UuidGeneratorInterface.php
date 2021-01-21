<?php

declare(strict_types=1);

namespace App\Service;

interface UuidGeneratorInterface
{
    public function uuid4(): string;

    public function uuid1(): string;
}
