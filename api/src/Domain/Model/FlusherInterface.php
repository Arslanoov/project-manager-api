<?php

declare(strict_types=1);

namespace Domain\Model;

interface FlusherInterface
{
    public function flush(): void;
}
