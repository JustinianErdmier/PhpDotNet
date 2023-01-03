<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http\Dispatchers;

use Exception;
use PhpDotNet\Http\Dispatchers\Enums\DispatcherType;
use PhpDotNet\Http\Dispatchers\Interfaces\IDispatcher;

final class DispatcherFactory {
    public static function createSimpleDispatcher(array $routeMap, DispatcherFactoryOptions $options = null): IDispatcher {
        if ($options === null) {
            $options = DispatcherFactoryOptions::createDefault();
        }

        $routeCollector = $options->routeCollector;
        $routeCollector->addRoutes($routeMap);

        $dispatcher = match ($options->dispatcherType) {
            DispatcherType::CharCountBased  => new CharCountBasedDispatcher($routeCollector->getData()),
            DispatcherType::GroupCountBased => new GroupCountBasedDispatcher($routeCollector->getData()),
            DispatcherType::GroupPosBased   => new GroupPosBasedDispatcher($routeCollector->getData()),
            default                         => new MarkBasedDispatcher($routeCollector->getData()),
        };

        return $dispatcher;
    }

    public static function createCachedDispatcher(array $routeMap, DispatcherFactoryOptions $options = null): IDispatcher {
        if ($options === null) {
            $options = DispatcherFactoryOptions::createDefault();
        }

        if (!isset($options->cacheFile) | $options->cacheFile === null) {
            throw new Exception('Cache file not set.');
        }

        $routeCollector = $options->routeCollector;
        $routeCollector->setCacheFile($cacheFile);

        $dispatcher = match ($options->dispatcherType) {
            DispatcherType::CharCountBased  => new CharCountBasedDispatcher($routeCollector->getData()),
            DispatcherType::GroupCountBased => new GroupCountBasedDispatcher($routeCollector->getData()),
            DispatcherType::GroupPosBased   => new GroupPosBasedDispatcher($routeCollector->getData()),
            default                         => new MarkBasedDispatcher($routeCollector->getData()),
        };

        return $dispatcher;
    }
}
