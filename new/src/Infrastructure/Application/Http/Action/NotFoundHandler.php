<?php

declare(strict_types=1);

namespace Infrastructure\Application\Http\Action;

use App\Http\Action\NotFoundHandlerInterface;
use App\Http\Response\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class NotFoundHandler implements NotFoundHandlerInterface
{
    private ResponseFactory $response;

    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->response->json([
            'error' => 'Page not found.'
        ], 404);
    }
}
