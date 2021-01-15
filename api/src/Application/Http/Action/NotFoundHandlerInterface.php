<?php

declare(strict_types=1);

namespace App\Http\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface NotFoundHandlerInterface extends RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface;
}
