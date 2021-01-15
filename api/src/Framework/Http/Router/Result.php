<?php

declare(strict_types=1);

namespace Framework\Http\Router;

final class Result
{
    private string $routeName;
    private string $handler;
    private array $params;

    public function __construct(string $routeName, string $handler, array $params)
    {
        $this->routeName = $routeName;
        $this->handler = $handler;
        $this->params = $params;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
