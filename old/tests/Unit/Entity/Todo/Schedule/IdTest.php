<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Schedule;

use Domain\Todo\Entity\Schedule\Id;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
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
        $this->expectExceptionMessage('Schedule id required');

        new Id('');
    }

    public function testNotUuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Schedule id must be uuid');

        new Id('id');
    }
}
