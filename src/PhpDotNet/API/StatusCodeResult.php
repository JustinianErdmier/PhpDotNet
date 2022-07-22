<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\API;

use PhpDotNet\Http\Response;

/**
 * Represents an {@see ActionResult} that when executed will produce an HTTP response with the given response status code.
 */
class StatusCodeResult extends ActionResult {
    /**
     * Initializes a new {@see StatusCodeResult} with the given {@see StatusCodeResult::$statusCode}.
     *
     * @param int         $statusCode  The HTTP status code of the response.
     * @param object|null $result      {@see ActionResult::$result}
     */
    public function __construct(private int $statusCode, ?object $result) {
        parent::__construct($result);
    }

    /**
     * Gets the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode(): int {
        return $this->statusCode;
    }

    /**
     * Sets the HTTP status code prior to executing {@see ActionResult::returnResult()}.
     *
     * {@inheritDoc}
     */
    public function returnResult(): string {
        Response::setStatusCode($this->statusCode);

        return parent::returnResult();
    }
}
