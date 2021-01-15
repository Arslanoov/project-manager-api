<?php

#!/usr/bin/env php

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\DBAL\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

if (file_exists('.env')) {
    (new Dotenv(true))->load('.env');
}

if (getenv('SENTRY_DSN')) {
    Sentry\init(['dsn' => getenv('SENTRY_DSN')]);
}

/**
 * @var ContainerInterface $container
 */
$container = require 'config/container.php';

$app = new Application('Application console');
if (getenv('SENTRY_DSN')) {
    $app->setCatchExceptions(false);
}

$entityManager = $container->get(EntityManagerInterface::class);
$connection = $entityManager->getConnection();

$configuration = new Doctrine\DBAL\Migrations\Configuration\Configuration($connection);
$configuration->setMigrationsDirectory('src/App/Database/Migration');
$configuration->setMigrationsNamespace('App\Database\Migration');

$app->getHelperSet()->set(new EntityManagerHelper($entityManager), 'em');
$app->getHelperSet()->set(new ConfigurationHelper($connection, $configuration), 'configuration');

Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($app);
Doctrine\DBAL\Migrations\Tools\Console\ConsoleRunner::addCommands($app);

$commands = $container->get('config')['console']['commands'];
foreach ($commands as $command) {
    $app->add($container->get($command));
}

$app->run();
