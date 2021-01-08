<?php

return [
    'oauth' => [
        'api_oauth_encryption_key' => 'key',
        'public_key_path' => dirname(__DIR__, 2) . '/' . getenv('OAUTH_PUBLIC_KEY_FILE_NAME'),
        'private_key_path' => dirname(__DIR__, 2) . '/' . getenv('OAUTH_PRIVATE_KEY_FILE_NAME'),
        'encryption_key' => getenv('OAUTH_ENCRYPTION_KEY'),
        'clients' => [
            'app' => [
                'secret'          => null,
                'name'            => 'App',
                'redirect_uri'    => null,
                'is_confidential' => false
            ]
        ]
    ]
];
