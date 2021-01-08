<?php

declare(strict_types=1);

namespace Infrastructure\Domain\OAuth;

use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Domain\User\Entity\User\User;
use Domain\OAuth\Entity\User\User as OAuthUser;
use Domain\User\Service\PasswordValidatorInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

final class DoctrineUserRepository implements UserRepositoryInterface
{
    private ObjectRepository $repo;
    private PasswordValidatorInterface $validator;

    public function __construct(EntityManagerInterface $em, PasswordValidatorInterface $validator)
    {
        $this->repo = $em->getRepository(User::class);
        $this->validator = $validator;
    }

    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): ?UserEntityInterface {
        /** @var User $user */
        if ($user = $this->repo->findOneBy(['email' => $username])) {
            if (!$this->validator->validate($password, $user->getPassword()->getValue())) {
                return null;
            }
            return new OAuthUser($user->getId()->getValue());
        }
        return  null;
    }
}
