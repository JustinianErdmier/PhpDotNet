<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\API;

use PhpDotNet\Http\HttpStatusCodes;

/**
 * Represents a {@see StatusCodeResult} that when executed will produce a Not Found (404) response.
 */
class NotFoundResult extends StatusCodeResult {
    private const DefaultStatusCode = HttpStatusCodes::Status404NotFound;

    /**
     * Instantiates a new {@see NotFoundResult}.
     */
    public function __construct() {
        parent::__construct(self::DefaultStatusCode->value);
    }
}
