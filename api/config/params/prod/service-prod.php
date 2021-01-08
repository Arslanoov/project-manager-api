<?php

return [
    'service' => [
        'background_photo_path' => '/var/www/api/storage/public/photos',
        'background_photo_url' => getenv('STORAGE_URL') . '/photos'
    ]
];
