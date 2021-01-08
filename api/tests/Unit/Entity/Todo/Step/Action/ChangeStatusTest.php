<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Step\Action;

use Tests\Builder\StepBuilder;
use Domain\Todo\Entity\Schedule\Task\Step\Status;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use PHPUnit\Framework\TestCase;

class ChangeStatusTest extends TestCase
{
    public function testSuccess(): void
    {
        $step = $this->createStep();

        $step->changeStatus($status = Status::complete());

        $this->assertEquals($status, $step->getStatus());
    }

    private function createStep(): Step
    {
        return (new StepBuilder())->build();
    }
}
