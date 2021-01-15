<?php

declare(strict_types=1);

namespace Framework\Http\Router;

use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    public function match(ServerRequestInterface $request): Result;

    public function generate(string $name, array $params): string;

    public function addRoute(RouteData $data): void;
}
