<?php

return [
    'mail' => [
        'host' => getenv('MAILER_HOST'),
        'port' => getenv('MAILER_PORT'),
        'user' => getenv('MAILER_USER'),
        'password' => getenv('MAILER_PASSWORD'),
        'encryption' => getenv('MAILER_ENCRYPTION'),
        'from' => [getenv('MAILER_FROM_EMAIL') => 'Todo']
    ]
];
