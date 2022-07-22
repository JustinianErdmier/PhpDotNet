<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types = 1);

namespace PhpDotNet\Http\Attributes;

use Attribute;
use PhpDotNet\Http\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class HttpPut extends HttpRoute {
    public function __construct(string $route) {
        parent::__construct($route, HttpMethod::Put);
    }
}
