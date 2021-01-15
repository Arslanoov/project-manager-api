<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Step;

use Domain\Todo\Entity\Schedule\Task\Step\Id;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class IdTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Id($value = 5);

        $this->assertEquals($value, $id->getValue());
    }
}
