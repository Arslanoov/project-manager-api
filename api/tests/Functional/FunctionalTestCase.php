<?php

declare(strict_types=1);

namespace Tests\Functional;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Framework\Http\Application;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Stream;
use Laminas\Diactoros\Uri;
use Symfony\Component\Dotenv\Dotenv;

class FunctionalTestCase extends TestCase
{
    private ?Application $app = null;
    private array $fixtures = [];

    protected function get(string $uri, array $headers = []): ResponseInterface
    {
        return $this->method($uri, 'GET', [], $headers);
    }

    protected function post(string $uri, array $params = [], array $headers = []): ResponseInterface
    {
        return $this->method($uri, 'POST', $params, $headers);
    }

    protected function delete(string $uri, array $params = [], array $headers = []): ResponseInterface
    {
        return $this->method($uri, 'DELETE', $params, $headers);
    }

    protected function method(string $uri, string $method, array $params = [], array $headers = []): ResponseInterface
    {
        $body = new Stream('php://temp', 'r+');
        $body->write(json_encode($params));
        $body->rewind();

        /** @var ServerRequestInterface $request */
        $request = (new ServerRequest())
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', 'application/json')
            ->withUri(new Uri('http://test' . $uri))
            ->withMethod($method)
            ->withBody($body)
            ->withParsedBody($params)
        ;

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        return $this->request($request);
    }

    protected function request(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->app()->handle($request);
        $response->getBody()->rewind();
        return $response;
    }

    protected function loadFixtures(array $fixtures): void
    {
        $container = $this->container();
        $em = $container->get(EntityManagerInterface::class);
        $loader = new Loader();
        foreach ($fixtures as $name => $class) {
            if ($container->has($class)) {
                $fixture = $container->get($class);
            } else {
                $fixture = new $class;
            }
            $loader->addFixture($fixture);
            $this->fixtures[$name] = $fixture;
        }
        $executor = new ORMExecutor($em, new ORMPurger($em));
        $executor->execute($loader->getFixtures());
    }

    protected function getFixture($name)
    {
        if (!array_key_exists($name, $this->fixtures)) {
            throw new InvalidArgumentException('Undefined fixture ' . $name);
        }
        return $this->fixtures[$name];
    }

    private function app(): Application
    {
        $container = $this->container();
        if ($this->app === null) {
            $this->app = $container->get(Application::class);
        }
        (require 'config/routes.php')($this->app);
        (require 'config/pipeline.php')($this->app);
        return $this->app;
    }

    private function container(): ContainerInterface
    {
        (new Dotenv(true))->load('.env');
        return require 'config/container.php';
    }
}
