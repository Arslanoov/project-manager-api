<?php

declare(strict_types=1);

namespace App\Http\Action;

use Domain\Exception\DomainException;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="ToDo API",
 *     description="HTTP JSON API",
 * ),
 * @OA\Server(
 *     url="http://localhost:8081/api"
 * ),
 * @OA\SecurityScheme(
 *     type="oauth2",
 *     securityScheme="oauth2",
 *     @OA\Flow(
 *         flow="implicit",
 *         authorizationUrl="/api/oauth/auth",
 *         scopes={
 *             "common": "Common"
 *         }
 *     )
 * )
 */
final class HomeAction implements RequestHandlerInterface
{
    private ResponseFactory $response;

    /**
     * HomeAction constructor.
     * @param ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    /**
     * @OA\Get(
     *     path="/",
     *     tags={"API"},
     *     description="API Home",
     *     @OA\Response(
     *         response="200",
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="version", type="string")
     *         )
     *     )
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->response->json([
            'version' => '1.0'
        ]);
    }
}
