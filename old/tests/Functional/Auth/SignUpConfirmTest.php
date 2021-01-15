<?php

declare(strict_types=1);

namespace Tests\Functional\Auth;

use Domain\User\Entity\User\ConfirmToken;
use Tests\Functional\FunctionalTestCase;

final class SignUpConfirmTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        $this->loadFixtures([
            ConfirmFixture::class,
        ]);

        parent::setUp();
    }

    public function testSuccess(): void
    {
        $response = $this->post('/api/auth/sign-up/confirm', [
            'token' => ConfirmFixture::SUCCESS_USER_TOKEN
        ]);

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertJson($content = $response->getBody()->getContents());
    }

    public function testExpired(): void
    {
        $response = $this->post('/api/auth/sign-up/confirm', [
            'token' => ConfirmFixture::EXPIRED_USER_TOKEN
        ]);

        $this->assertEquals(409, $response->getStatusCode());
        $this->assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        $this->assertEquals([
            'error' => 'Token is expired.',
        ], $data);
    }

    public function testNotFound(): void
    {
        $response = $this->post('/api/auth/sign-up/confirm', [
            'token' => 'token not found'
        ]);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        $this->assertEquals([
            'error' => 'User is not found.',
        ], $data);
    }
}
