<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Step;

use Domain\Todo\Entity\Schedule\Task\Step\SortOrder;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SortOrderTest extends TestCase
{
    public function testSuccess(): void
    {
        $sortOrder = new SortOrder($value = 5);

        $this->assertEquals($value, $sortOrder->getValue());
        $this->assertTrue($sortOrder->isEqual($sortOrder));
        $this->assertEquals((string) $value, (string) $sortOrder);

        $sortOrder = new SortOrder(null);

        $this->assertNull($sortOrder->getValue());
    }
}
