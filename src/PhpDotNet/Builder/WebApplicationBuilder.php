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
use PhpDotNet\Exceptions\Common\DirectoryNotFoundException;
use PhpDotNet\Exceptions\Http\ControllerMapNotFound;
use PhpDotNet\Http\Router;
use PhpDotNet\MVC\MvcDirMap;
use PhpDotNet\MVC\View;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use ReflectionException;
use RuntimeException;
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
     * Configures the application to use an MVC UI architecture.
     *
     * @param array $options  An array of options for configuring the MVC architecture to meet your project's specific structure.
     *
     * @return void
     * @throws DirectoryNotFoundException
     * @throws RuntimeException
     * @throws ControllerMapNotFound
     * @throws ReflectionException
     */
    public function addControllersWithViews(array $options = []): void {
        $options += [
            'WebUIDirectory'      => 'WebUI/',
            'ModelsDirectory'     => 'Models/',
            'ViewDirectory'       => 'Views/',
            'ControllerDirectory' => 'Controllers/'
        ];

        // Configure MVC directory paths.
        MvcDirMap::initialize(__DIR__ . '/../../../src/' . $options['WebUIDirectory']);
        // TODO: Add logic for overriding the remaining dir paths.

        // Configure router for MVC.
        Router::registerControllers(MvcDirMap::$controllers);
        Router::registerAttributeRoutes();

        // Configure View class.
        View::setViewDir(MvcDirMap::$views);
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

        Router::registerContainer($container);

        return new WebApplication($this->logger, $container);
    }
}
