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
use Psr\Log\LoggerInterface;
use function DI\autowire;

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
    public function addService(string $interfaceFQN, string $classFQN): void {
        $this->services[$interfaceFQN] = autowire($classFQN);
    }

    /**
     * Builds the {@see WebApplication}.
     *
     * @return WebApplication
     */
    public function build(): WebApplication {
        $this->servicesBuilder->addDefinitions($this->services);
        try {
            $container = $this->servicesBuilder->build();
        } catch (Exception $exception) {
            $this->logger->error('WebApplicationBuilder cannot build dependency injection container: {exception}', ['exception' => $exception->getMessage()]);
            exit;
        }

        return new WebApplication($this->logger, $container);
    }
}
