<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Task;

use Domain\Todo\Entity\Schedule\Task\Status;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StatusStep extends TestCase
{
    public function testSuccess(): void
    {
        $level = new Status($value = 'Complete');

        $this->assertEquals($value, $level->getValue());
        $this->assertTrue($level->isComplete());
        $this->assertFalse($level->isNotComplete());

        $level = Status::notComplete();
        $this->assertFalse($level->isComplete());
        $this->assertTrue($level->isNotComplete());

        $level = Status::complete();
        $this->assertTrue($level->isComplete());
        $this->assertFalse($level->isNotComplete());
    }

    public function testTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Task status must be between 4 and 16 chars length');

        new Status('rrrrrrrrrrrrrrrrrrrrr');
    }

    public function testTooShort(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Task status must be between 4 and 16 chars length');

        new Status('r');
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Incorrect task status');

        new Status('Incorrect');
    }
}
