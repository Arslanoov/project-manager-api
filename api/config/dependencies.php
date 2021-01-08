<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

$dependenciesAggregator = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/dependencies/*.php'),
    new PhpFileProvider(__DIR__ . '/dependencies/' . (getenv('ENV') ?? 'prod') . '/*.php')
]);

return $dependenciesAggregator->getMergedConfig();
