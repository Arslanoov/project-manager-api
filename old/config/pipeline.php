<?php

use Framework\Http\Middleware as FrameworkMiddleware;
use App\Http\Middleware as Middleware;
use Framework\Http\Application;

return function (Application $app) {
    $router = $app->getRouter();
    $app->pipe(Middleware\ProfilerMiddleware::class);
    $app->pipe(new FrameworkMiddleware\RouteMiddleware($router));
    $app->pipe(Middleware\SentryDecoratorMiddleware::class);
    $app->pipe(Middleware\ValidationMiddleware::class);
    $app->pipe('/api/todo', Middleware\AuthMiddleware::class);
    $app->pipe('/api/profile', Middleware\AuthMiddleware::class);
    $app->pipe('/api/todo', Middleware\IsActiveMiddleware::class);
    $app->pipe(Middleware\InvalidArgumentHandler::class);
    $app->pipe(FrameworkMiddleware\DispatchMiddleware::class);
};
