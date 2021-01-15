<?php

declare(strict_types=1);

use App\Http\Action;
use Framework\Http\ApplicationInterface;

return static function (ApplicationInterface $app): void {
    $app->get('home', '/', Action\HomeAction::class);
};
