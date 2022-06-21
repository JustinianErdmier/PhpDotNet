<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\MVC;

use PhpDotNet\Exceptions\Common\DirectoryNotFoundException;

/**
 * Static class with directories essential to utilizing the MVC architecture within the framework.
 */
abstract class MvcDirMap {
    public static string $root        = 'WebUI/';
    public static string $models      = 'WebUI/Models/';
    public static string $controllers = 'WebUI/Controllers/';
    public static string $views       = 'WebUI/Views/';

    /**
     * Attempts to initialize the MVC dirs with default values based on the provided root dir.
     *
     * @param string $webUIDir  The root directory of the WebUI (e.g., ~/src/WebUI/).
     *
     * @return void
     * @throws DirectoryNotFoundException
     */
    public static function initialize(string $webUIDir): void {
        $webUIDir = realpath($webUIDir);

        if ($webUIDir === false || !is_dir($webUIDir)) {
            throw new DirectoryNotFoundException('Did not find root dir when initializing MVC Dir Map.');
        }

        self::$root        = $webUIDir;
        self::$models      = $webUIDir . '/Models/';
        self::$controllers = $webUIDir . '/Controllers/';
        self::$views       = $webUIDir . '/Views/';
    }
}
