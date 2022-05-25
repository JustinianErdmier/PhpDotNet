<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Builder;

use PhpDotNet\HTTP\Router;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * The web application used to configure the HTTP pipeline and routes.
 */
final class WebApplication {
    public static WebApplication $app;

    /**
     * The list of URLs to which the HTTP server is bound.
     *
     * @var array $routes
     */
    private array $routes = [];

    /**
     * Object responsible for resolving HTTP requests.
     *
     * @var Router $router
     */
    private Router $router;

    /**
     * Instantiates a new {@see WebApplication} object.
     *
     * @param LoggerInterface    $logger
     * @param ContainerInterface $container
     */
    public function __construct(public LoggerInterface $logger, public ContainerInterface $container) {
        self::$app    = $this;
        $this->router = new Router($this->container);
    }

    /**
     * Initializes a new {@see WebApplication} object with preconfigured defaults.
     *
     * @return WebApplication
     */
    public static function create(): self {
        return (new WebApplicationBuilder())->build();
    }

    /**
     * Initializes a new {@see WebApplicationBuilder} object with preconfigured defaults.
     *
     * @return WebApplicationBuilder
     */
    public static function createBuilder(): WebApplicationBuilder {
        return new WebApplicationBuilder();
    }

    /**
     * Sets the expected GET request URI for the desired controller and action method.
     *
     * @param string $path      Expected request URI (e.g., ~/HelloWorld).
     * @param array  $callback  An array expecting two values: 0th index => The class reference of the desired controller, and 1st index => A string representing the desired action
     *                          method.
     *
     * @return void
     */
    public function addGetRoute(string $path, array $callback): void {
        $this->routes['get'][$path] = $callback;
    }

    /**
     * Sets the expected POST request URI for the desired controller and action method.
     *
     * @param string $path      Expected request URI (e.g., ~/HelloWorld).
     * @param array  $callback  An array expecting two values: 0 index => The class reference of the desired controller, and 1 index => A string representing the desired action
     *                          method.
     *
     * @return void
     */
    public function addPostRoute(string $path, array $callback): void {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * Runs the configured application.
     *
     * @return void
     */
    public function run(): void {
        $this->router->registerRoutes($this->routes);
        echo $this->router->resolve();
    }
}
