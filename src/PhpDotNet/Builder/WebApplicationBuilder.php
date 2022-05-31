<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Builder;

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use PhpDotNet\Exceptions\Http\ControllerMapNotFound;
use PhpDotNet\Http\Router;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;
use function DI\autowire;
use function DI\create;

/**
 * A builder for web applications and services.
 */
final class WebApplicationBuilder {
    /**
     * A collection of services for the application to compose. This is useful for adding user provided or framework provided services.
     *
     * @var array $services
     */
    private array $services;

    /**
     * The builder object which configures the dependency injection container.
     *
     * @var ContainerBuilder $servicesBuilder
     */
    private ContainerBuilder $servicesBuilder;

    /**
     * The default logger for the application.
     *
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    /**
     * The list of URLs to which the HTTP server is bound.
     *
     * @var array $routes
     */
    private array $routes = [];

    /**
     * The list of controllers used to implement an MVC pattern.
     *
     * @var array $controllers
     */
    private array $controllers = [];

    /**
     * Instantiates a new {@see WebApplicationBuilder} object.
     */
    public function __construct() {
        // 1. Load environment variables.
        $this->loadConfiguration();

        // 2. Create default logger.
        $this->logger = $this->createLogger();

        // 3. Create services container builder.
        $this->servicesBuilder = $this->createContainerBuilder();
    }

    /**
     * Loads the environment variables from the .env file and places them in the global $_ENV array.
     *
     * @return void
     */
    private function loadConfiguration(): void {
        $dotenv = Dotenv::createImmutable('/app');
        $dotenv->load();
    }

    /**
     * Creates the default logger for the application.
     *
     * @return LoggerInterface
     */
    private function createLogger(): LoggerInterface {
        $logger   = new Logger('General-Logger');
        $logLevel = Logger::NOTICE;

        if ($_ENV['ENVIRONMENT'] === 'Development') {
            $logLevel = Logger::DEBUG;
        } elseif ($_ENV['ENVIRONMENT'] === 'Test') {
            $logLevel = Logger::INFO;
        }

        $logger->pushHandler(new StreamHandler('/app/runtime/logs.log', $logLevel))
               ->pushProcessor((new PsrLogMessageProcessor()))
               ->useMicrosecondTimestamps(false);

        return $logger;
    }

    /**
     * Creates an object for configuring the application's dependency injection container.
     *
     * @return ContainerBuilder
     */
    private function createContainerBuilder(): ContainerBuilder {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAutowiring(true);
        return $containerBuilder;
    }

    /**
     * Configures an interface to be mapped to an implementation via auto-wiring during dependency injection.
     *
     * @param string $interfaceFQN  The fully qualified name of the interface to map.
     * @param string $classFQN      The fully qualified name of the class to implement.
     *
     * @return void
     */
    public function addAutoWireService(string $interfaceFQN, string $classFQN): void {
        $this->services[$interfaceFQN] = autowire($classFQN);
    }

    /**
     * Adds a services to the {@see ContainerInterface} using the create method.
     *
     * @param string $classFQN
     *
     * @return void
     */
    public function addCreateService(string $classFQN): void {
        $this->services[$classFQN] = create();
    }

    /**
     * Adds a single controller to the array of controllers to be used when configuring routes.
     *
     * @param string $controllerFQN
     *
     * @return void
     */
    public function addController(string $controllerFQN): void {
        $this->controllers[] = $controllerFQN;
    }

    /**
     * Adds a batch of controllers to the array of controllers to be used when configuring routes.
     *
     * @param array $controllerFQNs
     *
     * @return void
     */
    public function addControllers(array $controllerFQNs): void {
        foreach ($controllerFQNs as $controllerFQN) {
            $this->controllers[] = $controllerFQN;
        }
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
     * Builds the {@see WebApplication}.
     *
     * @return WebApplication
     */
    public function build(): WebApplication {
        if (!empty($this->services)) {
            $this->servicesBuilder->addDefinitions($this->services);
        }

        try {
            $container = $this->servicesBuilder->build();
        } catch (Exception $exception) {
            $this->logger->error('WebApplicationBuilder cannot build dependency injection container: {exception}', ['exception' => $exception->getMessage()]);
            exit;
        }

        if (!empty($this->routes)) {
            Router::registerRoutes($this->routes);
        }

        if (!empty($this->controllers)) {
            Router::registerControllers($this->controllers);
            try {
                Router::registerAttributeRoutes();
            } catch (ControllerMapNotFound|ReflectionException $exception) {
                $this->logger->error('Error registering attribute routes.\n{message}', ['message' => $exception->getMessage()]);
            }
        }

        Router::registerContainer($container);

        return new WebApplication($this->logger, $container);
    }
}
