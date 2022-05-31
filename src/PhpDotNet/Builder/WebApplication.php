<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Builder;

use PhpDotNet\Exceptions\Http\RoutesNotConfigured;
use PhpDotNet\Exceptions\View\ViewDirDoesNotExistException;
use PhpDotNet\HTTP\Router;
use PhpDotNet\MVC\View;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * The web application used to configure the HTTP pipeline and routes.
 */
final class WebApplication {
    public static WebApplication $app;

    /**
     * Instantiates a new {@see WebApplication} object.
     *
     * @param LoggerInterface    $logger
     * @param ContainerInterface $container
     */
    public function __construct(public LoggerInterface $logger, public ContainerInterface $container) {
        self::$app = $this;
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
     * Attempts to configure the directory for views.
     *
     * @throws ViewDirDoesNotExistException
     */
    public function configureViewDir(string $viewPath): void {
        View::setViewDir($viewPath);
    }

    public function useMiddleware(array $callback): void {
        $this->container->call($callback);
    }

    /**
     * Runs the configured application.
     *
     * @return void
     */
    public function run(): void {
        try {
            echo Router::resolve();
        } catch (RoutesNotConfigured $exception) {
            self::$app->logger->error('Error resolving route.\n {message}', ['message' => $exception->getMessage()]);
        }
    }
}
