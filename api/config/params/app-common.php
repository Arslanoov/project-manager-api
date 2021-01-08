<?php

return [
    'frontend_url' => getenv('FRONTEND_URL') ?? 'http://localhost:8081',
    'env' => getenv('ENV') ?? 'prod'
];
