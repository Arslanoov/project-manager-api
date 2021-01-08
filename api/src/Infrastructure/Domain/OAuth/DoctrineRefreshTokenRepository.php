<?php

declare(strict_types=1);

namespace Infrastructure\Domain\OAuth;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Domain\OAuth\Entity\RefreshToken\RefreshToken;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

final class DoctrineRefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    private EntityRepository $repo;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        /** @var EntityRepository $repo */
        $repo = $em->getRepository(RefreshToken::class);
        $this->repo = $repo;
        $this->em = $em;
    }

    public function getNewRefreshToken(): RefreshTokenEntityInterface
    {
        return new RefreshToken();
    }

    /**
     * @param RefreshTokenEntityInterface $accessTokenEntity
     * @throws UniqueTokenIdentifierConstraintViolationException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $accessTokenEntity): void
    {
        if ($this->exists($accessTokenEntity->getIdentifier())) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->em->persist($accessTokenEntity);
        $this->em->flush();
    }

    public function revokeRefreshToken($tokenId): void
    {
        if ($token = $this->repo->find($tokenId)) {
            $this->em->remove($token);
            $this->em->flush();
        }
    }

    /**
     * @param string $tokenId
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function isRefreshTokenRevoked($tokenId): bool
    {
        return !$this->exists($tokenId);
    }

    /**
     * @param string $id
     * @return bool
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    private function exists(string $id): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.identifier)')
                ->andWhere('t.identifier = :identifier')
                ->setParameter(':identifier', $id)
                ->getQuery()->getSingleScalarResult() > 0;
    }
}
