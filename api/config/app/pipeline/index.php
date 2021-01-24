<?php

declare(strict_types=1);

use App\Http\Middleware as Middleware;
use Framework\Http\ApplicationInterface;
use Framework\Http\Pipeline\Middleware as FrameworkMiddleware;

return static function (ApplicationInterface $app): void {
    $router = $app->getRouter();

    $app->pipe(Middleware\ProfilerMiddleware::class);
    $app->pipe(new FrameworkMiddleware\RouteMiddleware($router));
    $app->pipe(Middleware\SentryDecoratorMiddleware::class);
    $app->pipe(Middleware\ValidationMiddleware::class);
    $app->pipe('/api/todo', Middleware\AuthMiddleware::class);
    $app->pipe('/api/profile', Middleware\AuthMiddleware::class);
    $app->pipe('/api/todo', Middleware\IsActiveMiddleware::class);
    $app->pipe(Middleware\ErrorHandler::class);
    $app->pipe(Middleware\InvalidArgumentHandler::class);
    $app->pipe(FrameworkMiddleware\DispatchMiddleware::class);
};
