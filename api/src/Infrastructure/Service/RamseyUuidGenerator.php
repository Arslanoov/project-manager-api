<?php

declare(strict_types=1);

namespace Infrastructure\Service;

use App\Service\UuidGeneratorInterface;
use Ramsey\Uuid\Uuid;

final class RamseyUuidGenerator implements UuidGeneratorInterface
{
    public function uuid1(): string
    {
        return Uuid::uuid1()->toString();
    }

    public function uuid4(): string
    {
        return Uuid::uuid4()->toString();
    }
}
