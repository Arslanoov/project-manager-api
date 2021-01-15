<?php

declare(strict_types=1);

namespace Unit\Entity\User;

use Domain\User\Entity\User\Password;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function testSuccess(): void
    {
        $password = new Password($value = 'some_password');

        $this->assertEquals($value, $password->getValue());
        $this->assertTrue($password->isEqual($password));
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User password required');

        new Password('');
    }
}
