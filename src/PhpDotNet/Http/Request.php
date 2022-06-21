<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http;

/**
 *  Helper class responsible for retrieving data from the super global $_SERVER, $_GET, and $_POST arrays.
 */
final class Request {

    /**
     * Gets the canonical request URI (i.e., www.hellowworld.com/login?auth=bla returns /login).
     *
     * @return string
     */
    public static function getURI(): string {
        $uri      = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($uri, '?');
        if ($position === false) {
            return rawurldecode($uri);
        }
        return substr($uri, 0, $position);
    }

    /**
     * Safely retrieve the data provided by the user.
     *
     * @return array
     */
    public static function getBody(): array {
        $body = [];

        if (self::getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
        }

        if (self::getMethod() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
        }

        return $body;
    }

    /**
     * Gets the lower-cased request method (i.e., get or post from GET or POST).
     *
     * @return string
     */
    public static function getMethod(): string {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
