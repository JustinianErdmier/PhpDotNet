<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http;

/**
 * Helper class responsible for setting data to the global $_SERVER array.
 */
final class Response {
    /**
     * Sets the status response code for not found pages.
     *
     * Because we internally handle 'not found' or '404' requests, the server will still provide a 200 status response code. This helper method corrects that.
     *
     * @param int $code  The desired status response code (e.g., 404).
     *
     * @return void
     */
    public function setStatusCode(int $code): void {
        http_response_code($code);
    }

    /**
     * Allows controllers to easily redirect to a specific path without building.
     *
     * @param string $path
     *
     * @return void
     */
    public function redirect(string $path): void {
        header("Location: $path");
    }

    /**
     * Dynamically set data in $_POST from a controller.
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function setPostData(string $key, string $value): void {
        $_POST[$key] = $value;
    }
}
