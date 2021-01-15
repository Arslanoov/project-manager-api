<?php

declare(strict_types=1);

namespace Framework\Http\Router;

final class RouteData
{
    public string $name;
    public string $path;
    public string $handler;
    public array $methods;
    public array $options;

    public function __construct(
        string $name,
        string $path,
        string $handler,
        array $methods,
        array $options
    ) {
        $this->name = $name;
        $this->path = $path;
        $this->handler = $handler;
        /** @var array<string> $methods */
        $this->methods = array_map('mb_strtoupper', $methods);
        $this->options = $options;
    }
}
