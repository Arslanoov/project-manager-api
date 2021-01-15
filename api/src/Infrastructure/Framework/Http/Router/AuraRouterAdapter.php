<?php

declare(strict_types=1);

namespace Infrastructure\Framework\Http\Router;

use Aura\Router\Exception\RouteNotFound;
use Aura\Router\Route;
use Aura\Router\RouterContainer;
use Framework\Http\Router\Exception\RequestNotMatched;
use Framework\Http\Router\Exception\UnableToFoundRoute;
use Framework\Http\Router\Result;
use Framework\Http\Router\RouteData;
use Framework\Http\Router\RouterInterface;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AuraRouterAdapter
 * @package Infrastructure\Framework\Router
 * @author ElisDN https://github.com/ElisDN
 */
final class AuraRouterAdapter implements RouterInterface
{
    private RouterContainer $aura;

    public function __construct(RouterContainer $aura)
    {
        $this->aura = $aura;
    }

    public function match(ServerRequestInterface $request): Result
    {
        $matcher = $this->aura->getMatcher();
        /** @var Route $route */
        if ($route = $matcher->match($request)) {
            /** @var string $handler */
            $handler = $route->handler;
            return new Result($route->name, $handler, $route->attributes);
        }

        throw new RequestNotMatched($request);
    }

    public function generate(string $name, array $params): string
    {
        $generator = $this->aura->getGenerator();
        try {
            /** @var string $result */
            $result = $generator->generate($name, $params);
            return $result;
        } catch (RouteNotFound $e) {
            throw new UnableToFoundRoute($name, $params);
        }
    }

    public function addRoute(RouteData $data): void
    {
        $route = new Route();
        $route->name($data->name);
        $route->path($data->path);
        $route->handler($data->handler);

        /**
         * @var string $name
         * @var array|string $value
         */
        foreach ($data->options as $name => $value) {
            switch ($name) {
                case 'tokens':
                    /** @var array $value */
                    $route->tokens($value);
                    break;
                case 'defaults':
                    /** @var array $value */
                    $route->defaults($value);
                    break;
                case 'wildcard':
                    /** @var string $value */
                    $route->wildcard($value);
                    break;
                default:
                    throw new InvalidArgumentException('Undefined option "' . $name . '"');
            }
        }

        if ($methods = $data->methods) {
            $route->allows($methods);
        }

        $this->aura->getMap()->addRoute($route);
    }
}
