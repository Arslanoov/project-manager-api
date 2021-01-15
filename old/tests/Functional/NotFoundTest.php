<?php

declare(strict_types=1);

namespace Tests\Functional\Auth;

use Tests\Functional\FunctionalTestCase;

class NotFoundTest extends FunctionalTestCase
{
    public function testNotFound(): void
    {
        $response = $this->get('/404');

        $this->assertEquals(404, $response->getStatusCode());
    }
}
