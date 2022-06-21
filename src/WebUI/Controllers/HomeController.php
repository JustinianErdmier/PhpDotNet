<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace WebUI\Controllers;

use PhpDotNet\Builder\WebApplication;
use PhpDotNet\Http\Attributes\HttpGet;
use PhpDotNet\MVC\ControllerBase;
use PhpDotNet\MVC\View;

class HomeController extends ControllerBase {

    #[HttpGet('/')]
    public function index(): View {
        return View::make(view:'Home/Index');
    }
    
    #[HttpGet('/Manage/{name}')]
    public function manage(string $name): View {
        WebApplication::$app->logger->info('Home/Manage/Manage route parameter: $name => {name}', ['name' => $name]);
        return View::make('Home/Manage/Manage');
    }

    #[HttpGet('/NotFound')]
    public function notFound(): View {
        return View::make('Shared/NotFound');
    }

    #[HttpGet('/Error')]
    public function error(string $message): View {
        WebApplication::$app->logger->info('Error view parameter: $message => {message}', ['message' => $message]);
        return View::make('Shared/Error');
    }
}
