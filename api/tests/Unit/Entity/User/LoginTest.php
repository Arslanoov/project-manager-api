<?php

declare(strict_types=1);

namespace Unit\Entity\User;

use Domain\User\Entity\User\Login;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class LoginTest extends TestCase
{
    public function testSuccess(): void
    {
        $login = new Login($value = 'Some login');

        $this->assertEquals($login->getRaw(), $value);
        $this->assertTrue($login->isEqual($login));
    }

    public function testEmptyLogin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User login required');

        new Login('');
    }

    public function testTooLongLogin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User login must be between 4 and 32 chars length');

        new Login('sssssssssssssssssssssssssssssssssssssssssssssssss');
    }

    public function testTooShortLogin(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User login must be between 4 and 32 chars length');

        new Login('s');
    }
}
