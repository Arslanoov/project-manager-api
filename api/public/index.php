<?php

declare(strict_types=1);

use Framework\Http\ApplicationInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));

require './vendor/autoload.php';

if (file_exists('.env')) {
    (new Dotenv(true))->load('.env');
}

(static function () {
    $container = require './config/container.php';

    $application = $container->get(ApplicationInterface::class);

    (require './config/app/routes/index.php')($application);
    (require './config/app/pipeline/index.php')($application);

    $psr17Factory = new Psr17Factory();

    $creator = new ServerRequestCreator(
        $psr17Factory, // ServerRequestFactory
        $psr17Factory, // UriFactory
        $psr17Factory, // UploadedFileFactory
        $psr17Factory  // StreamFactory
    );

    $serverRequest = $creator->fromGlobals();
    $response = $application->run($serverRequest);

    (new SapiEmitter())->emit($response);
})();
