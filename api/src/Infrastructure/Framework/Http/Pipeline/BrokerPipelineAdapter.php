<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Http\Pipeline;

use Framework\Http\Pipeline\MiddlewarePipelineInterface;
use Northwoods\Broker\Broker;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class BrokerPipelineAdapter implements MiddlewarePipelineInterface
{
    private Broker $broker;

    public function __construct(Broker $broker)
    {
        $this->broker = $broker;
    }

    public function pipe(MiddlewareInterface $middleware): void
    {
        $this->broker->append($middleware);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->broker->handle($request);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->broker->process($request, $handler);
    }
}
