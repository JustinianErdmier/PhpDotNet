<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\API;

use PhpDotNet\Http\HttpStatusCodes;

/**
 * Represents a {@see StatusCodeResult} that when executed will produce an Unprocessable Entity (422) response.
 */
class UnprocessableEntityResult extends StatusCodeResult {
    private const DefaultStatusCode = HttpStatusCodes::Status422UnprocessableEntity;

    /**
     * Instantiates a new {@see UnprocessableEntityResult}.
     */
    public function __construct() {
        parent::__construct(self::DefaultStatusCode->value);
    }
}
