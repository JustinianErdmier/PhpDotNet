<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\API;

use PhpDotNet\Http\HttpStatusCodes;

/**
 * Represents a {@see StatusCodeResult} that when executed will produce an UnsupportedMediaType (415) response.
 */
class UnsupportedMediaTypeResult extends StatusCodeResult {
    private const DefaultStatusCode = HttpStatusCodes::Status415UnsupportedMediaType;

    /**
     * Instantiates a new {@see UnsupportedMediaTypeResult}.
     */
    public function __construct() {
        parent::__construct(self::DefaultStatusCode->value);
    }
}
