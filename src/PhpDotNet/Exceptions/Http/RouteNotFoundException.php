<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Exceptions\Http;

use Exception;

class RouteNotFoundException extends Exception {
    protected $message = '404 Not Found';
}
