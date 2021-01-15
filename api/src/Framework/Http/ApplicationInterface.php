<?php

declare(strict_types=1);

namespace Framework\Http;

use Framework\Http\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface ApplicationInterface
{
    public function customMethodsRoute(
        string $name,
        string $path,
        string $handler,
        array $methods,
        array $options = []
    ): void;

    public function get(string $name, string $path, string $handler, array $options = []): void;

    public function post(string $name, string $path, string $handler, array $options = []): void;

    public function patch(string $name, string $path, string $handler, array $options = []): void;

    public function put(string $name, string $path, string $handler, array $options = []): void;

    public function delete(string $name, string $path, string $handler, array $options = []): void;

    public function getRouter(): RouterInterface;

    /**
     * @param mixed $path
     * @param MiddlewareInterface|null $middleware
     */
    public function pipe($path, MiddlewareInterface $middleware = null): void;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;

    public function run(ServerRequestInterface $request): ResponseInterface;
}
