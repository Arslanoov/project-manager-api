<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Step;

use Domain\Todo\Entity\Schedule\Task\Step\Name;
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
        $this->expectExceptionMessage('Step name required');

        new Name('');
    }

    public function testTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Step name must be between 1 and 32 chars length');

        new Name('111111111111111111111111111111111');
    }
}
