<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http\Dispatchers\Interfaces;

use PhpDotNet\Http\Dispatchers\Enums\DispatchStatus;
use PhpDotNet\Http\HttpMethod;

/**
 * Defines the abstractions for dispatching routes.
 */
interface IDispatcher {

    /**
     * Dispatches against the provided HTTP method and URI.
     *
     * Returns an array with one of the following formats:
     *
     * - [DispatchStatus::NotFound]
     * - [DispatchStatus::Found, $handler, ['varName' => 'value', ...]]
     * - [DispatchStatus::MethodNotAllowed, [HttpMethod::Get, HttpMethod::Post, ...]]
     *
     * @param HttpMethod $httpMethod  The {@see HttpMethod} to dispatch against.
     * @param string     $uri         The URI to dispatch against.
     *
     * @return array An array communicating the {@see DispatchStatus} and any additional data (i.e., the handler and its variables or a list of allowed {@see HttpMethod} cases).
     */
    public function dispatch(HttpMethod $httpMethod, string $uri): array;

}
