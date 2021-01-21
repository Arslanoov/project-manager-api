<?php

declare(strict_types=1);

use App\Console\Commands\Api\GenerateDocCommand;

return [
    'config' => [
        'console' => [
            'commands' => [
                GenerateDocCommand::class
            ]
        ]
    ]
];
