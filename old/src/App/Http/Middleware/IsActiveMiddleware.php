<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exception\ForbiddenException;
use Domain\User\Entity\User\Id;
use Domain\User\Entity\User\UserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class IsActiveMiddleware implements MiddlewareInterface
{
    private UserRepository $users;

    /**
     * IsActiveMiddleware constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws ForbiddenException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userId = $request->getAttribute('oauth_user_id');
        if ($user = $this->users->findById(new Id($userId))) {
            if ($user->isDraft()) {
                throw new ForbiddenException('Account is not activated.');
            }
            $request = $request->withAttribute('user', $request);
            return $handler->handle($request);
        }

        return $handler->handle($request);
    }
}
