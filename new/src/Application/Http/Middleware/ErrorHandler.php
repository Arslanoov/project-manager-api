<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Response\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

final class ErrorHandler implements MiddlewareInterface
{
    private ResponseFactory $response;
    private bool $debug;

    public function __construct(ResponseFactory $response, bool $debug)
    {
        $this->response = $response;
        $this->debug = $debug;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            $code = (int) $e->getCode() ?: 500;
            return $this->response->json([
                'error' => $this->canShowErrorMessage($code) ? $e->getMessage() : 'Something went wrong.'
            ], $code);
        }
    }

    private function canShowErrorMessage(int $code): bool
    {
        return $this->debug || $code !== 500;
    }
}
