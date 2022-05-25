<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http;

use Psr\Container\ContainerInterface;

/**
 * Core class responsible for handling URL requests and passing the control to the appropriate part of the application.
 */
final class Router {
    /**
     * The list of URLs to which the HTTP server is bound.
     *
     * @var array $routeMap
     */
    private array $routeMap;

    /**
     * The utility object for retrieving data from the super global $_SERVER, $_GET, and $_POST arrays.
     *
     * @var Request $request
     */
    private Request $request;

    /**
     * The utility object for setting data in the super global $_SERVER array.
     *
     * @var Response $response
     */
    private Response $response;

    /**
     * The application's configured services.
     *
     * @var ContainerInterface $container
     */
    private ContainerInterface $container;

    /**
     * Instantiates a new {@see Router}.
     *
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->request   = new Request();
        $this->response  = new Response();
    }

    /**
     * Registers the routes to be resolved by the {@see Router}.
     *
     * @param array $routes  {@see Router::$routeMap}.
     *
     * @return void
     */
    public function registerRoutes(array $routes): void {
        $this->routeMap = $routes;
    }

    /**
     * Resolves requests by confirming that the request is a configured route. If the request is not configured, then the notFound action method from the SiteController is called.
     *
     * @return mixed
     */
    public function resolve(): mixed {
        $method   = $this->request->getMethod();
        $path     = $this->request->getPath();
        $callback = $this->routeMap[$method][$path] ?? $this->routeMap['get']['/not-found'];

        if ($callback === $this->routeMap['get']['/not-found']) {
            $this->response->setStatusCode(404);
        }

        return $this->container->call($callback);
    }
}
