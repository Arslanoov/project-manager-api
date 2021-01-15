<?php

use Framework\Http\Application;
use App\Http\Action;

/** @var Application $app */

return function (Application $app) {
    $app->get('home', '/api', Action\HomeAction::class);
    $app->get('env', '/env', Action\EnvAction::class);

    // Auth
    $app->post('api.auth.signup.request', '/api/auth/sign-up/request', Action\Auth\SignUp\RequestAction::class);
    $app->post('api.auth.signup.confirm', '/api/auth/sign-up/confirm', Action\Auth\SignUp\ConfirmAction::class);
    $app->post('api.oauth.auth', '/api/oauth/auth', Action\Auth\OAuthAction::class);

    // Profile
    $app->get('api.profile', '/api/profile', Action\Profile\ShowAction::class);
    $app->get('api.profile.photo', '/api/profile/get/photo', Action\Profile\GetPhotoAction::class);
    $app->post('api.profile.upload.photo', '/api/profile/upload/photo', Action\Profile\UploadPhotoAction::class);
    $app->delete('api.profile.upload.remove', '/api/profile/upload/remove', Action\Profile\RemovePhotoAction::class);

    // Main schedule
    $app->get('api.todo.main.index', '/api/todo/main', Action\Todo\Schedule\Main\IndexAction::class);
    $app->get('api.todo.main.tasks.count', '/api/todo/main/tasks/count', Action\Todo\Schedule\Main\TasksCountAction::class);

    // Daily schedule
    $app->get('api.todo.daily.today', '/api/todo/daily/today', Action\Todo\Schedule\Daily\GetTodayAction::class);
    $app->get('api.todo.daily.today.tasks.count', '/api/todo/daily/today/tasks/count', Action\Todo\Schedule\Daily\TodayTasksCountAction::class);
    $app->get('api.todo.daily.next', '/api/todo/daily/next/{id}', Action\Todo\Schedule\Daily\GetNextScheduleAction::class);
    $app->get('api.todo.daily.previous', '/api/todo/daily/previous/{id}', Action\Todo\Schedule\Daily\GetPreviousScheduleAction::class);
    $app->get('api.todo.daily.get-by-date', '/api/todo/daily/get-by-date/{day}/{month}/{year}', Action\Todo\Schedule\Daily\GetByDateAction::class);

    // Custom schedule
    $app->get('api.todo.custom.index', '/api/todo/custom/list', Action\Todo\Schedule\Custom\IndexAction::class);
    $app->get('api.todo.custom.get', '/api/todo/custom/get/{name}', Action\Todo\Schedule\Custom\GetAction::class);
    $app->post('api.todo.custom.create', '/api/todo/custom/create', Action\Todo\Schedule\Custom\CreateAction::class);
    $app->delete('api.todo.custom.remove', '/api/todo/custom/remove', Action\Todo\Schedule\Custom\RemoveAction::class);

    // Tasks
    $app->post('api.todo.main.tasks.create', '/api/todo/task/create', Action\Todo\Task\CreateAction::class);
    $app->patch('api.todo.main.tasks.edit', '/api/todo/task/edit', Action\Todo\Task\EditAction::class);
    $app->patch('api.todo.main.tasks.change.status', '/api/todo/task/change-status', Action\Todo\Task\ChangeStatusAction::class);
    $app->delete('api.todo.main.tasks.remove', '/api/todo/task/remove', Action\Todo\Task\RemoveAction::class);

    // Task steps
    $app->get('api.todo.main.task.steps.index', '/api/todo/task/{id}/steps', Action\Todo\Task\Step\IndexAction::class);
    $app->post('api.todo.main.task.steps.create', '/api/todo/task/step/create', Action\Todo\Task\Step\CreateAction::class);
    $app->patch('api.todo.main.task.steps.up', '/api/todo/task/step/up', Action\Todo\Task\Step\UpAction::class);
    $app->patch('api.todo.main.task.steps.down', '/api/todo/task/step/down', Action\Todo\Task\Step\DownAction::class);
    $app->patch('api.todo.main.task.steps.change.status', '/api/todo/task/step/change-status', Action\Todo\Task\Step\ChangeStatusAction::class);
    $app->delete('api.todo.main.task.steps.remove', '/api/todo/task/step/remove', Action\Todo\Task\Step\RemoveAction::class);
};
