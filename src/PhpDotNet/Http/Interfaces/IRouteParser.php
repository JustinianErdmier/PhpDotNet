<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http\Interfaces;

interface IRouteParser {
    /**
     * Parses a route string into multiple parts.
     *
     * @param string $route  The route to parse.
     *
     * @return array An array of route data.
     */
    public function parse(string $route): array;
}
