<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http\Dispatchers\Enums;

use PhpDotNet\Http\Dispatchers\Interfaces\IDispatcher;

/**
 * Defines the possible dispatch statuses returned by {@see IDispatcher::dispatch()}.
 */
enum DispatchStatus:int {
    case NotFound = 0;
    case Found = 1;
    case MethodNotAllowed = 2;
}
