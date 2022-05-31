PhpDotNet
============

An open-source PHP micro-framework inspired by Microsoft's ASP.NET Core framework. PhpDotNet is an object-oriented library which consists of modular components. Similar to ASP.
NET Core, this infrastructure requires minimal overhead and allows you to retain flexibility.

PhpDotNet is a micro-framework, meaning that it requires little-to-no configuration, is designed to integrate with other libraries conforming to PSR standards, and allows you
to design/develop your solution with as few constraints as possible.

## Get Started

Using PhpDotNet is as simple as setting-up your application's entrypoint script. By convention, this is index.php. See the following code example showing a basic **index.php**
setup:

````
<?php

declare(strict_types = 1);

use ... // Ommited for brevity.

require_once __DIR__ . '/../vendor/autoload.php';

$builder = WebApplication::createBuilder();

// Add services to the container.
$builder->addService(IExampleService::class, ExampleService::class);

$app = $builder->build();

// Configure HTTP pipeline.
Authenticator::initialize();

$app->run();
````

The preceding example demonstrates an instance of WebApplicationBuilder being instantiated with default configurations. Services are then added to the dependency injection
container. Afterwards, the WebApplicationBuilder is built into a WebApplication, where we configure the HTTP pipeline and finally run the app. In this example,
_"Authenticator"_ is a custom middleware that authenticates each request and is not a PhpDotNet specific class. However, it demonstrates the ability for you to create and
configure your own application's middleware while working seamlessly with PhpDotNet.

The preceding example also carries the following assumption about the project's architecture, which is not required but may help better understand the example:

* /app
    * /public
        * index.php
    * /vendor
        * /phpdotnet

## How to Engage, Contribute, and Give Feedback

PhpDotNet is a valuable library which brings a sense of familiarity and comfort to PHP development for .NET developers, The best ways to contribute and help PhpDotNet grow are
to try things out, submit issues, join design/development conversations, and make pull-requests.
