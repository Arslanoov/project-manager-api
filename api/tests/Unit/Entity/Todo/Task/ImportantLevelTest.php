<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Task;

use Domain\Todo\Entity\Schedule\Task\ImportantLevel;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ImportantLevelTest extends TestCase
{
    public function testSuccess(): void
    {
        $level = new ImportantLevel($value = 'Important');

        $this->assertEquals($value, $level->getValue());
        $this->assertTrue($level->isImportant());
        $this->assertFalse($level->isNotImportant());
        $this->assertFalse($level->isVeryImportant());

        $level = ImportantLevel::notImportant();
        $this->assertTrue($level->isNotImportant());
        $this->assertFalse($level->isImportant());
        $this->assertFalse($level->isVeryImportant());

        $level = ImportantLevel::important();
        $this->assertFalse($level->isNotImportant());
        $this->assertTrue($level->isImportant());
        $this->assertFalse($level->isVeryImportant());

        $level = ImportantLevel::veryImportant();
        $this->assertFalse($level->isNotImportant());
        $this->assertFalse($level->isImportant());
        $this->assertTrue($level->isVeryImportant());
    }

    public function testTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Task important level must be between 4 and 16 chars length');

        new ImportantLevel('rrrrrrrrrrrrrrrrrrrrr');
    }

    public function testTooShort(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Task important level must be between 4 and 16 chars length');

        new ImportantLevel('r');
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Incorrect task important level');

        new ImportantLevel('Incorrect');
    }
}
