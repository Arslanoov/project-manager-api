<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Step\Action;

use Tests\Builder\StepBuilder;
use Domain\Todo\Entity\Schedule\Task\Step\Name;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use PHPUnit\Framework\TestCase;

class ChangeNameTest extends TestCase
{
    public function testSuccess(): void
    {
        $step = $this->createStep();

        $step->changeName($name = new Name('Ne step name'));

        $this->assertEquals($name, $step->getName());
    }

    private function createStep(): Step
    {
        return (new StepBuilder())->build();
    }
}
