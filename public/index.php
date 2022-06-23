<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

use PhpDotNet\Builder\WebApplicationBuilder;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    // Create Web Application Builder...
    $builder = new WebApplicationBuilder();

    // Add services...
    $builder->addControllersWithViews(['WebUIDirectory' => __DIR__ . '/../src/WebUI/']);

    // Build Web Application...
    $app = $builder->build();

    $app->useCachedRoutes(__DIR__ . '/../runtime/');

    $app->run();
} catch (Exception $exception) {
    echo $exception->getMessage();
    die;
}
