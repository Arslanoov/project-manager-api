<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Schedule;

use Domain\Todo\Entity\Schedule\Type;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    public function testSuccess(): void
    {
        $type = new Type($value = 'Main');

        $this->assertTrue($type->isMain());
        $this->assertFalse($type->isDaily());
        $this->assertFalse($type->isCustom());
        $this->assertEquals($value, $type->getValue());

        $type = Type::main();

        $this->assertTrue($type->isMain());
        $this->assertFalse($type->isDaily());
        $this->assertFalse($type->isCustom());

        $type = Type::daily();

        $this->assertFalse($type->isMain());
        $this->assertTrue($type->isDaily());
        $this->assertFalse($type->isCustom());

        $type = Type::custom();

        $this->assertFalse($type->isMain());
        $this->assertFalse($type->isDaily());
        $this->assertTrue($type->isCustom());
    }

    public function testTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Schedule type must be between 2 and 16 chars length');

        new Type('111111111111111111111111111111111');
    }

    public function testTooShort(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Schedule type must be between 2 and 16 chars length');

        new Type('1');
    }

    public function testIncorrectType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Incorrect type');

        new Type('2222222222');
    }
}
