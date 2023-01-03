<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http\Interfaces;


use PhpDotNet\Http\HttpMethod;

interface IDataGenerator {
    /**
     * Adds a route to the data generator.
     *
     * @param HttpMethod $httpMethod
     * @param array      $routeData
     * @param callable   $handler
     *
     * @return void
     */
    public function addRoute(HttpMethod $httpMethod, array $routeData, callable $handler): void;

    /**
     * Returns dispatcher data in some unspecified format which depends on the used {@see HttpMethod}.
     *
     * @return array
     */
    public function getData(): array;
}
