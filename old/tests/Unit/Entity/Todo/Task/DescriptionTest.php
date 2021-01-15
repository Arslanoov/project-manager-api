<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Task;

use Domain\Todo\Entity\Schedule\Task\Description;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
    public function testSuccess(): void
    {
        $description = new Description($value = 'Some desc');

        $this->assertEquals($value, $description->getValue());
        $this->assertTrue($description->isEqual($description));
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Task description required');

        new Description('');
    }
}
