<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Task;

use Domain\Todo\Entity\Schedule\Task\Name;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testSuccess(): void
    {
        $name = new Name($value = 'Some name');

        $this->assertEquals($value, $name->getValue());
        $this->assertTrue($name->isEqual($name));
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Task name required');

        new Name('');
    }

    public function testTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Task name must be between 1 and 128 chars length'
        );

        new Name(str_repeat('1', 129));
    }
}
