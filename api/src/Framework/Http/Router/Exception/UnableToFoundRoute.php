<?php

declare(strict_types=1);

namespace Framework\Http\Router\Exception;

use LogicException;

final class UnableToFoundRoute extends LogicException
{
    private string $name;
    private array $params;

    public function __construct(string $name, array $params = [])
    {
        parent::__construct();
        $this->message = 'RouteData "' . $name . '" not found.';
        $this->name = $name;
        $this->params = $params;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
