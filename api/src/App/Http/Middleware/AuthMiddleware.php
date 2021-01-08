<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Exception;
use Framework\Http\Psr7\ResponseFactory;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthMiddleware implements MiddlewareInterface
{
    private ResourceServer $server;
    private ResponseFactory $response;

    /**
     * AuthMiddleware constructor.
     * @param ResourceServer $server
     * @param ResponseFactory $response
     */
    public function __construct(ResourceServer $server, ResponseFactory $response)
    {
        $this->server = $server;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $request = $this->server->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($this->response->simple());
        } catch (Exception $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse($this->response->simple());
        }

        return $handler->handle($request);
    }
}
