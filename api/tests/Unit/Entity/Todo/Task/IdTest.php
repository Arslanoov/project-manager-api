<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Task;

use Domain\Todo\Entity\Schedule\Task\Id;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class IdTest extends TestCase
{
    public function testSuccess(): void
    {
        $id = new Id($value = Uuid::uuid4()->toString());

        $this->assertEquals($value, $id->getValue());
        $this->assertTrue($id->isEqual($id));
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Task id required');

        new Id('');
    }

    public function testNotUuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Task id must be uuid');

        new Id('id');
    }
}
