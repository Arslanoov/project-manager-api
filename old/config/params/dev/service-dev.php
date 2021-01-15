<?php

return [
    'service' => [
        'background_photo_path' => dirname(__DIR__, 4) . '/storage/public/photos',
        'background_photo_url' => getenv('STORAGE_URL') . '/photos'
    ]
];
