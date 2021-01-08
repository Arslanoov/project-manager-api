<?php

return [
    'logs' => [
        'debug' => getenv('DEBUG') === 'true' ? true : false,
        'file' => null,
        'stderr' => true
    ]
];
