<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace WebUI\Controllers;

use PhpDotNet\Http\Attributes\HttpGet;
use PhpDotNet\MVC\ControllerBase;
use PhpDotNet\MVC\View;

class HomeController extends ControllerBase {
    #[HttpGet('/')]
    public function index(): View {
        return View::make('Home/Index');
    }

    #[HttpGet('/NotFound')]
    public function notFound(): View {
        return View::make('Home/NotFound');
    }
}
