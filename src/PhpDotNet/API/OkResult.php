<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\API;

use PhpDotNet\Http\HttpStatusCodes;

/**
 * Represents a {@see StatusCodeResult} that when executed will produce an empty Ok (200) response.
 */
class OkResult extends StatusCodeResult {
    private const DefaultStatusCode = HttpStatusCodes::Status200OK;

    /**
     * Instantiates a new {@see OkResult}.
     */
    public function __construct() {
        parent::__construct(self::DefaultStatusCode->value);
    }
}
