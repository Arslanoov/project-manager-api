<?php

declare(strict_types=1);

namespace Framework\Http\Router\Exception;

use LogicException;
use Psr\Http\Message\ServerRequestInterface;

final class RequestNotMatched extends LogicException
{
    private ServerRequestInterface $request;

    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct();
        $this->message = 'RouteData matches not found.';
        $this->request = $request;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
