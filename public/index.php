<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

use PhpDotNet\Builder\WebApplicationBuilder;
use PhpDotNet\Exceptions\View\LayoutPathDoesNotExistException;
use PhpDotNet\Exceptions\View\ViewDirDoesNotExistException;
use WebUI\Controllers\HomeController;
use WebUI\Core\ViewPath;

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

// Configure View Paths...
try {
    $app->configureViewDir(__DIR__ . '/../' . ViewPath::ViewRoot->value);
} catch (ViewDirDoesNotExistException $exception) {
    $app->logger->error('Unable to configure view directory.\n{message}', ['message' => $exception->getMessage()]);
    die;
}

try {
    $app->configureLayoutPath(__DIR__ . '/../' . ViewPath::getLayout());
} catch (LayoutPathDoesNotExistException $exception) {
    $app->logger->error('Unable to configure layout path.\n{message}', ['message' => $exception->getMessage()]);
    die;
}

$app->run();
