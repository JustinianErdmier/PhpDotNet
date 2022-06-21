<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

/** @noinspection PhpToStringImplementationInspection */

declare(strict_types = 1);

namespace WebUI\Core;

use WebUI\Core\Exceptions\ViewPathCannotBeBuiltException;

/**
 * List of view directories and functions for building view paths.
 */
enum ViewPath:string {
    case ViewRoot = 'src/WebUI/Views/';
    case Shared = 'Shared/';
    case Layout = 'Layout.phtml';
    case HomeRoot = 'Home/';
    case HomeManageRoot = 'Home/Manage/';

    /**
     * Gets the path for the Layout, including the extension.
     *
     * @return string
     */
    public static function getLayout(): string {
        return self::ViewRoot->value . self::Shared->value . self::Layout->value;
    }

    /**
     * Attempts to build the path by proxying the call to {@see ViewPath::buildPath()}.
     *
     * @throws ViewPathCannotBeBuiltException
     */
    public function build(string $view): string {
        return self::buildPath($this, $view);
    }

    /**
     * Attempts to build a path using the given {@see ViewPath} case and view name.
     *
     * @throws ViewPathCannotBeBuiltException
     */
    public static function buildPath(self $value, string $view): string {
        return match ($value) {
            self::ViewRoot       => self::ViewRoot->value . $view,
            self::Shared         => self::Shared->value . $view,
            self::Layout         => throw new ViewPathCannotBeBuiltException('Unable to build view path under ViewPath::Layout.'),
            self::HomeRoot       => self::HomeRoot->value . $view,
            self::HomeManageRoot => self::HomeManageRoot->value . $view,
        };
    }
}
