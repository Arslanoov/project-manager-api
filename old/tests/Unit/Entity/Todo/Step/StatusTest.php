<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Step;

use Domain\Todo\Entity\Schedule\Task\Step\Name;
use Domain\Todo\Entity\Schedule\Task\Step\Status;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function testSuccess(): void
    {
        $status = Status::notComplete();

        $this->assertTrue($status->isNotComplete());
        $this->assertFalse($status->isComplete());

        $status = Status::complete();

        $this->assertTrue($status->isComplete());
        $this->assertFalse($status->isNotComplete());
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
