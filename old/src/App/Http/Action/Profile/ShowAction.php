<?php

declare(strict_types=1);

namespace App\Http\Action\Profile;

use Domain\User\Entity\User\Id;
use Domain\User\Entity\User\UserRepository;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class ShowAction implements RequestHandlerInterface
{
    private UserRepository $users;
    private ResponseFactory $response;

    /**
     * ShowAction constructor.
     * @param UserRepository $users
     * @param ResponseFactory $response
     */
    public function __construct(UserRepository $users, ResponseFactory $response)
    {
        $this->users = $users;
        $this->response = $response;
    }

    /**
     * @OA\Get(
     *     path="/profile",
     *     tags={"Profile"},
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string"),
     *             @OA\Property(property="login", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     security={{"oauth2": {"common"}}}
 *     )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $this->users->getById(new Id($request->getAttribute('oauth_user_id')));

        return $this->response->json([
            'user' => [
                'id' => $request->getAttribute('oauth_user_id'),
                'login' => $user->getLogin()->getRaw(),
                'email' => $user->getEmail()->getValue(),
                'status' => $user->getStatus()->getValue()
            ]
        ]);
    }
}
