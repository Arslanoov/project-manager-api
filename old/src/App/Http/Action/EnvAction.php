<?php

declare(strict_types=1);

namespace App\Http\Action;

use DateTimeImmutable;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EnvAction implements RequestHandlerInterface
{
    private string $env;
    private bool $debug;
    private ResponseFactory $response;

    /**
     * EnvAction constructor.
     * @param string $env
     * @param bool $debug
     * @param ResponseFactory $response
     */
    public function __construct(string $env, bool $debug, ResponseFactory $response)
    {
        $this->env = $env;
        $this->debug = $debug;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->response->json([
            'env' => $this->env,
            'debug' => (string) $this->debug,
            'date' => (new DateTimeImmutable())->format('d-m-Y H:i:s')
        ]);
    }
}
