<?php

use App\Http\Action\Auth\OAuthAction;
use Framework\Http\Psr7\ResponseFactory;
use Infrastructure\Domain\OAuth\DoctrineAccessTokenRepository;
use Infrastructure\Domain\OAuth\DoctrineAuthCodeRepository;
use Infrastructure\Domain\OAuth\DoctrineClientRepository;
use Infrastructure\Domain\OAuth\DoctrineRefreshTokenRepository;
use Infrastructure\Domain\OAuth\DoctrineScopeRepository;
use Infrastructure\Domain\OAuth\DoctrineUserRepository;
use League\OAuth2\Server;
use Psr\Container\ContainerInterface;

return [
    'factories' => [
        OAuthAction::class => function (ContainerInterface $ContainerInterface) {
            return new OAuthAction(
                $ContainerInterface->get(Server\AuthorizationServer::class),
                $ContainerInterface->get(ResponseFactory::class)
            );
        },
        Server\AuthorizationServer::class => function (ContainerInterface $ContainerInterface) {
            $config = $ContainerInterface->get('config')['oauth'];

            $clientRepository = $ContainerInterface->get(Server\Repositories\ClientRepositoryInterface::class);
            $scopeRepository = $ContainerInterface->get(Server\Repositories\ScopeRepositoryInterface::class);
            $accessTokenRepository = $ContainerInterface->get(Server\Repositories\AccessTokenRepositoryInterface::class);
            $authCodeRepository = $ContainerInterface->get(Server\Repositories\AuthCodeRepositoryInterface::class);
            $refreshTokenRepository = $ContainerInterface->get(Server\Repositories\RefreshTokenRepositoryInterface::class);
            $userRepository = $ContainerInterface->get(Server\Repositories\UserRepositoryInterface::class);

            $server = new Server\AuthorizationServer(
                $clientRepository,
                $accessTokenRepository,
                $scopeRepository,
                new Server\CryptKey($config['private_key_path'], null, false),
                $config['encryption_key']
            );

            $grant = new Server\Grant\AuthCodeGrant(
                $authCodeRepository,
                $refreshTokenRepository,
                new DateInterval('PT10M')
            );

            $server->enableGrantType($grant, new DateInterval('PT1H'));

            $server->enableGrantType(new Server\Grant\ClientCredentialsGrant(), new DateInterval('PT1H'));

            $server->enableGrantType(new Server\Grant\ImplicitGrant(new DateInterval('PT1H')));

            $grant = new Server\Grant\PasswordGrant($userRepository, $refreshTokenRepository);
            $grant->setRefreshTokenTTL(new DateInterval('P1M'));
            $server->enableGrantType($grant, new DateInterval('PT1H'));

            $grant = new Server\Grant\RefreshTokenGrant($refreshTokenRepository);
            $grant->setRefreshTokenTTL(new DateInterval('P1M'));
            $server->enableGrantType($grant, new DateInterval('PT1H'));

            return $server;
        },
        Server\ResourceServer::class => function (ContainerInterface $ContainerInterface) {
            $config = $ContainerInterface->get('config')['oauth'];

            $accessTokenRepository = $ContainerInterface->get(Server\Repositories\AccessTokenRepositoryInterface::class);

            return new Server\ResourceServer(
                $accessTokenRepository,
                new Server\CryptKey($config['public_key_path'], null, false)
            );
        },
        Server\Middleware\ResourceServerMiddleware::class => function (ContainerInterface $ContainerInterface) {
            return new Server\Middleware\ResourceServerMiddleware(
                $ContainerInterface->get(Server\ResourceServer::class)
            );
        },
        Server\Repositories\ClientRepositoryInterface::class => function (ContainerInterface $ContainerInterface) {
            $config = $ContainerInterface->get('config')['oauth'];
            return new DoctrineClientRepository($config['clients']);
        },
        Server\Repositories\ScopeRepositoryInterface::class => function (ContainerInterface $ContainerInterface) {
            return new DoctrineScopeRepository();
        },
        Server\Repositories\AuthCodeRepositoryInterface::class => function (ContainerInterface $ContainerInterface) {
            return $ContainerInterface->get(DoctrineAuthCodeRepository::class);
        },
        Server\Repositories\AccessTokenRepositoryInterface::class => function (ContainerInterface $ContainerInterface) {
            return $ContainerInterface->get(DoctrineAccessTokenRepository::class);
        },
        Server\Repositories\RefreshTokenRepositoryInterface::class => function (ContainerInterface $ContainerInterface) {
            return $ContainerInterface->get(DoctrineRefreshTokenRepository::class);
        },
        Server\Repositories\UserRepositoryInterface::class => function (ContainerInterface $ContainerInterface) {
            return $ContainerInterface->get(DoctrineUserRepository::class);
        }
    ]
];
