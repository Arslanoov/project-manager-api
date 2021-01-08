<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use DateTimeImmutable;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ErrorHandler implements MiddlewareInterface
{
    private ResponseFactory $response;
    private LoggerInterface $logger;
    private bool $debug;
    private string $env;

    /**
     * ErrorHandler constructor.
     * @param ResponseFactory $response
     * @param LoggerInterface $logger
     * @param bool $debug
     * @param string $env
     */
    public function __construct(ResponseFactory $response, LoggerInterface $logger, bool $debug, string $env)
    {
        $this->response = $response;
        $this->logger = $logger;
        $this->debug = $debug;
        $this->env = $env;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            $code = intval($e->getCode() ?: 500);
            $context = [
                'date' => new DateTimeImmutable(),
                'trace' => $e->getTrace()
            ];

            if ($code == 500) {
                $this->logger->error($e->getMessage(), $context);
            } else {
                $this->logger->warning($e->getMessage(), $context);
            }

            return $this->response->json([
                'error' => $this->canShowErrorMessage($code) ? (
                    ($this->isDevEnv() ? '[DEV]' : ''). $e->getMessage()
                ) : 'Something went wrong.'
            ], $code);
        }
    }

    private function canShowErrorMessage(int $code): bool
    {
        return $code !== 500 or $this->debug;
    }

    private function isDevEnv(): bool
    {
        return $this->env === 'dev';
    }
}
