<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

use PhpDotNet\Builder\WebApplicationBuilder;
use PhpDotNet\Exceptions\View\ViewDirDoesNotExistException;
use WebUI\Controllers\HomeController;

require_once __DIR__ . '/../vendor/autoload.php';

// Create Web Application Builder...
$builder = new WebApplicationBuilder();

// Add services...

// Add controllers...
$builder->addControllers([
                             HomeController::class
                         ]);

// Build Web Application...
$app = $builder->build();

// Configure View Directory...
try {
    $app->configureViewDir(__DIR__ . '/../src/WebUI/Views');
} catch (ViewDirDoesNotExistException $exception) {
    $app->logger->error('Unable to configure view directory.\n{message}', ['message' => $exception->getMessage()]);
    die;
}

$app->run();
