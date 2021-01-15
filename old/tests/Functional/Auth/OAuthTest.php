<?php

declare(strict_types=1);

namespace Tests\Functional\Auth;

use Tests\Functional\FunctionalTestCase;

class OAuthTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        $this->loadFixtures([
            AuthFixture::class,
        ]);

        parent::setUp();
    }

    public function testMethod(): void
    {
        $response = $this->get('/api/oauth/auth');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->post('/api/oauth/auth', [
            'grant_type' => 'password',
            'username' => 'app@test.app',
            'password' => 'Password',
            'client_id' => 'app',
            'client_secret' => '',
            'access_type' => 'offline'
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        $this->assertEquals('Bearer', $data['token_type']);

        $this->assertArrayHasKey('expires_in', $data);
        $this->assertNotEmpty($data['expires_in']);

        $this->assertArrayHasKey('access_token', $data);
        $this->assertNotEmpty($data['access_token']);

        $this->assertArrayHasKey('refresh_token', $data);
        $this->assertNotEmpty($data['refresh_token']);
    }

    public function testIncorrect(): void
    {
        $response = $this->post('/api/oauth/auth', [
            'grant_type' => 'password',
            'username' => 'oauth@example.com',
            'password' => 'incorrect password',
            'client_id' => 'app',
            'client_secret' => '',
        ]);

        $this->assertEquals(400, $response->getStatusCode());
    }
}