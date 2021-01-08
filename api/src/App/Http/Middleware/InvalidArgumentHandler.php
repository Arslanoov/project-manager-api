<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Framework\Http\Psr7\ResponseFactory;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

final class InvalidArgumentHandler implements MiddlewareInterface
{
    private ResponseFactory $response;
    private LoggerInterface $logger;

    /**
     * InvalidArgumentHandler constructor.
     * @param ResponseFactory $response
     * @param LoggerInterface $logger
     */
    public function __construct(ResponseFactory $response, LoggerInterface $logger)
    {
        $this->response = $response;
        $this->logger = $logger;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (InvalidArgumentException $e) {
            $code = intval($e->getCode() ?: 400);

            $this->logger->warning($e->getMessage(), [
                'exception' => $e,
                'url' => (string) $request->getUri()
            ]);

            return $this->response->json([
                'error' => $e->getMessage()
            ], $code);
        }
    }
}
