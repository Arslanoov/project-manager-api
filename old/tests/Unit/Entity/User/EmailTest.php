<?php

declare(strict_types=1);

namespace Unit\Entity\User;

use PHPUnit\Framework\TestCase;
use Domain\User\Entity\User\Email;
use InvalidArgumentException;

class EmailTest extends TestCase
{
    public function testSuccess(): void
    {
        $email = new Email($value = 'test@app.test');

        $this->assertEquals($value, $email->getValue());
        $this->assertTrue($email->isEqual($email));
    }

    public function testEmptyEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User email required');

        new Email('');
    }

    public function testInvalidEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Incorrect email');

        new Email('email');
    }

    public function testTooLongEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User email must be between 5 and 32 chars length');

        new Email('sssssssssssssssssssssssssssssssssssssssssssssssss');
    }

    public function testTooShortEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('User email must be between 5 and 32 chars length');

        new Email('s');
    }
}
