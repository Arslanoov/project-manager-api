<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

define('ENV', getenv('ENV') ?: 'prod');

$configAggregator = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/params/*-common.php'),
    new PhpFileProvider(__DIR__ . '/params/' . ENV . '/*-' . ENV . '.php')
]);

return $configAggregator->getMergedConfig();
