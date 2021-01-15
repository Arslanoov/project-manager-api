<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Schedule;

use Domain\Todo\Entity\Schedule\Name;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testSuccess(): void
    {
        $name = new Name($value = 'Schedule name');

        $this->assertEquals($value, $name->getValue());
        $this->assertTrue($name->isEqual($name));
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Schedule name required');

        new Name('');
    }

    public function testTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Schedule name must be between 1 and 32 chars length');

        new Name('111111111111111111111111111111111');
    }
}
