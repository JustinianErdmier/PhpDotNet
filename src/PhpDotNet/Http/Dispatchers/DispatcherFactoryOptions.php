<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http\Dispatchers;

use PhpDotNet\Http\Dispatchers\Enums\DispatcherType;
use PhpDotNet\Http\Dispatchers\Interfaces\IDispatcher;
use PhpDotNet\Http\Interfaces\IDataGenerator;
use PhpDotNet\Http\Interfaces\IRouteCollector;
use PhpDotNet\Http\Interfaces\IRouteParser;

/**
 * Defines the options for configuring the {@see IDispatcher} instances created by a {@see DispatcherFactory}.
 */
final class DispatcherFactoryOptions {
    /**
     * @var DispatcherType The type of {@see IDispatcher} to create.
     */
    public DispatcherType $dispatcherType;

    /**
     * @var IRouteParser The {@see IRouteParser} to use when parsing routes.
     */
    public IRouteParser $routeParser;

    /**
     * @var IDataGenerator The {@see IDataGenerator} to use when generating data.
     */
    public IDataGenerator $dataGenerator;

    /**
     * @var IRouteCollector The {@see IRouteCollector} to use when collecting routes.
     */
    public IRouteCollector $routeCollector;

    /**
     * @var string|null The file used to cache the route data.
     */
    public ?string $cacheFile = null;

    /**
     * @var bool Indicates whether caching is enabled.
     */
    public bool $cacheEnabled;

    /**
     * Instantiates a new {@see DispatcherFactoryOptions} instance.
     *
     * @param DispatcherType  $dispatcherType  The type of {@see IDispatcher} to create.
     * @param IRouteParser    $routeParser     The {@see IRouteParser} to use when parsing routes.
     * @param IDataGenerator  $dataGenerator   The {@see IDataGenerator} to use when generating data.
     * @param IRouteCollector $routeCollector  The {@see IRouteCollector} to use when collecting routes.
     */
    public function __construct(DispatcherType $dispatcherType, IRouteParser $routeParser, IDataGenerator $dataGenerator, IRouteCollector $routeCollector,
                                ?string        $cacheFile = null, bool $cacheEnabled = false) {
        $this->dispatcherType = $dispatcherType;
        $this->routeParser    = $routeParser;
        $this->dataGenerator  = $dataGenerator;
        $this->routeCollector = $routeCollector;
        $this->cacheFile      = $cacheFile;
        $this->cacheEnabled   = $cacheEnabled;
    }

    public static function createDefault(?string $cacheFile = null, bool $cacheEnabled = false): DispatcherFactoryOptions {
        $routeParser    = new StandardRouteParser();
        $dataGenerator  = new GroupCountBasedDataGenerator();
        $routeCollector = new RouteCollector($routeParser, $dataGenerator);
        return new DispatcherFactoryOptions(DispatcherType::GroupCountBased, $routeParser, $dataGenerator, $routeCollector, $cacheFile, $cacheEnabled);
    }
}
