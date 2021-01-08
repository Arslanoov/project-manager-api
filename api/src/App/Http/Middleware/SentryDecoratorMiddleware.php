<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function Sentry\captureException;

final class SentryDecoratorMiddleware implements MiddlewareInterface
{
    private ErrorHandler $handler;

    /**
     * SentryDecoratorMiddleware constructor.
     * @param ErrorHandler $handler
     */
    public function __construct(ErrorHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $this->handler->process($request, $handler);
        } catch (Exception $e) {
            captureException($e);
            throw $e;
        }
    }
}
