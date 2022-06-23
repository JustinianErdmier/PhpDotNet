<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http;

use Composer\Autoload\ClassMapGenerator;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use PhpDotNet\Builder\WebApplication;
use PhpDotNet\Exceptions\Common\DirectoryNotFoundException;
use PhpDotNet\Exceptions\Http\ControllerMapNotFound;
use PhpDotNet\Exceptions\Http\RoutesNotConfigured;
use PhpDotNet\Http\Attributes\HttpRoute;
use Psr\Container\ContainerInterface;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use WebUI\Controllers\HomeController;
use function FastRoute\cachedDispatcher;
use function FastRoute\simpleDispatcher;

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

    private static bool $cacheRoutes = false;

    private static string $routeCache;

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
     * @param string $controllerDir  The path where the controllers are located.
     *
     * @return void
     * @throws DirectoryNotFoundException
     * @throws RuntimeException
     */
    public static function registerControllers(string $controllerDir): void {
        $controllerDir = realpath($controllerDir);

        if ($controllerDir === false || !is_dir($controllerDir)) {
            throw new DirectoryNotFoundException('Could not find directory for mapping controllers.');
        }

        try {
            self::$controllerMap = ClassMapGenerator::createMap($controllerDir);
        } catch (RuntimeException $exception) {
            WebApplication::$app->logger->error('Caught runtime exception while mapping controller directory.\n{message}', ['message' => $exception->getMessage()]);
            throw new RuntimeException($exception->getMessage());
        }

        self::$controllerMap = array_keys(self::$controllerMap);
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
     * Enables caching of routes and sets the location for the cache file.
     *
     * @param string $routeCache  Location where to save the cache file.
     *
     * @return void
     * @return void
     * @throws DirectoryNotFoundException
     */
    public static function enableCache(string $routeCache): void {
        $routeCache = realpath($routeCache);

        if ($routeCache === false || !is_file($routeCache)) {
            throw new DirectoryNotFoundException('Could not find the directory to save the route cache file.');
        }

        self::$routeCache  = $routeCache;
        self::$cacheRoutes = true;
    }

    /**
     * Attempts to resolve the request and return its contents.
     *
     * @return mixed
     * @throws RoutesNotConfigured
     */
    public static function resolve(): mixed {
        // Validate routes have been configured.
        if (empty(self::$routeMap)) {
            throw new RoutesNotConfigured();
        }

        // Build dispatcher.
        if (self::$cacheRoutes) {
            $dispatcher = cachedDispatcher(function(RouteCollector $collector) {
                $registeredMethods = array_keys(self::$routeMap);

                foreach ($registeredMethods as $method) {
                    $registeredRoutes = array_keys(self::$routeMap[$method]);
                    foreach ($registeredRoutes as $route) {
                        $collector->addRoute($method, $route, self::$routeMap[$method][$route]);
                    }
                }
            }, ['cacheFile' => self::$routeCache]);
        } else {
            $dispatcher = simpleDispatcher(function(RouteCollector $collector) {
                $registeredMethods = array_keys(self::$routeMap);

                foreach ($registeredMethods as $method) {
                    $registeredRoutes = array_keys(self::$routeMap[$method]);
                    foreach ($registeredRoutes as $route) {
                        $collector->addRoute($method, $route, self::$routeMap[$method][$route]);
                    }
                }
            });
        }

        // Dispatch request.
        $method    = Request::getMethod();
        $uri       = Request::getURI();
        $routeInfo = $dispatcher->dispatch($method, $uri);
        $callback  = [HomeController::class, 'notFound'];
        $params    = [];

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                Response::setStatusCode(404);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $callback       = [HomeController::class, 'error'];
                $allowedMethods = implode(', ', $routeInfo[1]);
                $message        = 'This request method is not allowed for this URI. Please make your request using ' . $allowedMethods;
                $params         = ['message' => $message];
                Response::setStatusCode(405);
                break;
            case Dispatcher::FOUND:
                $callback = $routeInfo[1];
                /** @noinspection MultiAssignmentUsageInspection */
                $params = $routeInfo[2];
                break;
            default:
                $callback = [HomeController::class, 'error'];
                $message  = 'Unable to process request.';
                $params   = ['message' => $message];

                Response::setStatusCode(500);
                break;
        }

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        return self::$container->call($callback, $params);
    }
}
