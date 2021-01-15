<?php

use Frontend\Twig\Extension\FrontendUrlBuilderExtension;
use Twig\Loader\FilesystemLoader;

return [
    'template' => [
        'debug' => getenv('DEBUG') === 'true' ? true : false,
        'template_directories' => [
            FilesystemLoader::MAIN_NAMESPACE => __DIR__ . '/../../templates',
        ],
        'cache_dir' => __DIR__ . '/../../var/cache/template',
        'extensions' => [
            FrontendUrlBuilderExtension::class,
        ]
    ]
];
