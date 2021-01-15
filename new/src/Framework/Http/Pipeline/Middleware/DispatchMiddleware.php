<?php

declare(strict_types=1);

namespace Framework\Http\Pipeline\Middleware;

use App\Http\Action\NotFoundHandlerInterface;
use Framework\Http\Router\Result;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DispatchMiddleware implements MiddlewareInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Result|null $result */
        $result = $request->getAttribute(Result::class);
        if (!$result) {
            /** @var RequestHandlerInterface $notFoundHandler */
            $notFoundHandler = $this->container->get(NotFoundHandlerInterface::class);
            return $notFoundHandler->handle($request);
        }

        /** @var RequestHandlerInterface $action */
        $action = $this->container->get($result->getHandler());
        return $action->handle($request);
    }
}
