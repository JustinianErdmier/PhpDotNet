<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Common;

abstract class InsightsUtil {
    public static function getCallingMethodName(): string {
        $trace  = debug_backtrace();
        $caller = $trace[2];
        return $caller['function'];
    }

    public static function getCallingClassName(): string {
        $trace  = debug_backtrace();
        $caller = $trace[2];
        return $caller['class'];
    }
}
