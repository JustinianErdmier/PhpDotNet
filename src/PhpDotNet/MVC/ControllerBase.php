<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\MVC;

use PhpDotNet\Http\Request;

abstract class ControllerBase {
    protected array $body = [];

    public function __construct() {
        $this->body = Request::getBody();
    }
}
