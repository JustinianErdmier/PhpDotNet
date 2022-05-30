<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

namespace PhpDotNet\Http;

use PhpDotNet\Builder\WebApplication;
use PhpDotNet\Exceptions\Http\ControllerMapNotFound;
use PhpDotNet\Exceptions\Http\RoutesNotConfigured;
use PhpDotNet\Http\Attributes\HttpRoute;
use Psr\Container\ContainerInterface;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

final class Router {
    /**
     * @var array $routeMap A shared array of registered URLs to which the HTTP server is bound. The array schema expected is as follows:
     *                      [
     *                          ['HttpMethod'] => [
     *                                              'route' => ['controller', 'method']
     *                                            ],
     *                          ['get'] => [
     *                                      '/home' => ['HomeController::class', 'index'],
     *                                      '/about' => ['HomeController::class', 'about']
     *                                     ]
     *                      ]
     */
    private static array $routeMap;

    /**
     * @var array A shared array of registered controllers used to map routes in an MVC pattern.
     */
    private static array $controllerMap;

    /**
     * @var ContainerInterface $container The {@see WebApplication}'s configured services.
     */
    private static ContainerInterface $container;

    /**
     * Manually sets {@see Router::$routeMap}.
     *
     * @param array $routes  Routes should be configured as [['HttpMethod'] => ['route' => ['controller', 'method']]]. Please see the documentation for {@see Router::$routeMap}
     *                       for more information.
     *
     * @return void
     */
    public static function registerRoutes(array $routes): void {
        self::$routeMap = $routes;
    }

    /**
     * Manually sets {@see Router::$controllerMap}.
     *
     * @param array $controllers  Controllers should be configured as a single-dimensional array where the values are the controller's FQN name.
     *
     * @return void
     */
    public static function registerControllers(array $controllers): void {
        self::$controllerMap = $controllers;
    }

    /**
     * Attempts to build the {@see Router::$routeMap} from attributes used on a controller's action method.
     *
     * @return void
     * @throws ControllerMapNotFound If the {@see Router::$controllerMap} is not set prior to calling this function.
     * @throws ReflectionException If there is an error with {@see ReflectionClass} or any of its methods used in this function's execution.
     */
    public static function registerAttributeRoutes(): void {
        if (empty(self::$controllerMap)) {
            throw new ControllerMapNotFound();
        }

        foreach (self::$controllerMap as $controller) {
            $reflectionController = new ReflectionClass($controller);

            foreach ($reflectionController->getMethods() as $method) {
                $attributes = $method->getAttributes(HttpRoute::class, ReflectionAttribute::IS_INSTANCEOF);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();

                    self::$routeMap[$route->method->value][$route->route] = [$controller, $method->getName()];
                }
            }
        }
    }

    public static function registerContainer(ContainerInterface $container): void {
        self::$container = $container;
    }

    /**
     * Attempts to resolve the request and return its contents.
     *
     * @return mixed
     * @throws RoutesNotConfigured
     */
    public static function resolve(): mixed {
        if (empty(self::$routeMap)) {
            throw new RoutesNotConfigured();
        }

        $method   = Request::getMethod();
        $path     = Request::getPath();
        $callback = self::$routeMap[$method][$path] ?? self::$routeMap['get']['/NotFound'];

        if ($callback === self::$routeMap['get']['/NotFound']) {
            Response::setStatusCode(404);
        }

        return self::$container->call($callback);
    }
}
