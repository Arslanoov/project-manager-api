<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\PhpFileProvider;
use Laminas\ServiceManager\ServiceManager;
use Laminas\ConfigAggregator\ConfigAggregator;

$dependencies = require(__DIR__ . '/dependencies.php');
$params = require(__DIR__ . '/params.php');

$container = new ServiceManager($dependencies);
$container->setService('config', $params);

return $container;
