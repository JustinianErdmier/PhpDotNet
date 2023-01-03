<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http\Interfaces;

use PhpDotNet\Http\HttpMethod;

interface IRouteCollector {
    public function registerRouteParser(IRouteParser $routeParser): void;

    public function registerDataGenerator(IDataGenerator $dataGenerator): void;

    public function addRoute(HttpMethod $httpMethod, string $route, callable $handler): void;

    public function addRoutes(array $routes): void;

    public function addGroup(string $prefix, callable $callback): void;

    public function getData(): array;
}
