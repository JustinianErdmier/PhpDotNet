<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\API;

use Exception;
use PhpDotNet\Builder\WebApplication;
use PhpDotNet\Http\Response;

/**
 * Represents the result of an action method
 */
class ActionResult {
    /**
     * Instantiates a new {@see ActionResult}.
     *
     * @param object|null $result
     */
    public function __construct(private readonly ?object $result) {}

    /**
     * Encodes the {@see ActionResult::$result} as JSON to be returned.
     *
     * @return string
     */
    public function returnResult(): string {
        try {
            return $this->result !== null ? utf8_decode(json_encode($this->result, JSON_THROW_ON_ERROR)) : '';
        } catch (Exception $exception) {
            WebApplication::$app->logger->error('Failed to encode JSON from {result}.\n{message}', ['result' => $this->result, 'message' => $exception->getMessage()]);
            Response::setStatusCode(500);
            return 'Failed to encode and return data as JSON.';
        }
    }
}
