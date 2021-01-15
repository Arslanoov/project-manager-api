<?php

declare(strict_types=1);

namespace Unit\Entity\Todo\Step\Action;

use Tests\Builder\StepBuilder;
use Domain\Todo\Entity\Schedule\Task\Step\SortOrder;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use PHPUnit\Framework\TestCase;

class ChangeSortOrderTest extends TestCase
{
    public function testSuccess(): void
    {
        $step = $this->createStep();

        $step->changeSortOrder($sortOrder = new SortOrder(23));

        $this->assertEquals($sortOrder, $step->getSortOrder());
    }

    private function createStep(): Step
    {
        return (new StepBuilder())->build();
    }
}
