<?php

declare(strict_types=1);

namespace App\Http\Action\Auth;

use Exception;
use Framework\Http\Psr7\ResponseFactory;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class OAuthAction implements RequestHandlerInterface
{
    private AuthorizationServer $server;
    private ResponseFactory $response;

    /**
     * OAuthAction constructor.
     * @param AuthorizationServer $server
     * @param ResponseFactory $response
     */
    public function __construct(AuthorizationServer $server, ResponseFactory $response)
    {
        $this->server = $server;
        $this->response = $response;
    }

    /**
     * @OA\Post(
     *     path="/api/oauth/auth",
     *     tags={"Log in"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"grant_type", "username", "password", "client_id", "client_secret", "access_type"},
     *             @OA\Property(property="grant_type", type="string"),
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="client_id", type="string"),
     *             @OA\Property(property="client_secret", type="string"),
     *             @OA\Property(property="access_type", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Errors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", nullable=true)
     *         )
     *     ),
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            return $this->server->respondToAccessTokenRequest($request, $this->response->simple());
        } catch (OAuthServerException $exception) {
            return $this->response->json([
                'error' => 'Incorrect credentials'
            ], 400);
        } catch (Exception $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse($this->response->simple());
        }
    }
}
