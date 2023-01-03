<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http;

use PhpDotNet\Exceptions\Http\BadRouteException;

/**
 * Parses a route string of the following form:
 *
 * "/users/edit/{username}[/{id:[0-9]+}]"
 */
final class StandardRouteParser implements Interfaces\IRouteParser {

    private const VariableRegex = <<<'REGEX'
\{
    \s* ([a-zA-Z_][a-zA-Z0-9_-]*) \s*
    (?:
        : \s* ([^{}]*(?:\{(?-1)\}[^{}]*)*)
    )?
\}
REGEX;

    /**
     * @inheritDoc
     */
    public function parse(string $route): array {
        $routeWithoutClosingOptionals = rtrim($route, ']');
        $numOptionals                 = strlen($route) - strlen($routeWithoutClosingOptionals);

        // Split on [ while skipping placeholders
        $segments = preg_split('~' . self::VariableRegex . '(*SKIP)(*F) | \[~x', $routeWithoutClosingOptionals);

        if ($numOptionals !== count($segments) - 1) {
            // If there are any ] in the middle of the route, throw a more specific error message
            if (preg_match('~' . self::VariableRegex . '(*SKIP)(*F) | \]~x', $routeWithoutClosingOptionals)) {
                throw new BadRouteException('Optional segments can only occur at the end of a route');
            }

            throw new BadRouteException("Number of opening '[' and closing ']' does not match");
        }
    }
}
