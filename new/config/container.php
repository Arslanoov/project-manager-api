<?php

declare(strict_types=1);

use Acclimate\Container\ContainerAcclimator;
use DI\ContainerBuilder;

### PHP DI ###
$phpDiContainerBuilder = new ContainerBuilder();
$phpDiContainerBuilder->addDefinitions(require __DIR__ . '/dependencies.php');
$phpDiContainer = $phpDiContainerBuilder->build();

$acclimator = new ContainerAcclimator();
return $acclimator->acclimate($phpDiContainer);
