<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http\Dispatchers\Enums;

/**
 * Defines the types of dispatchers.
 */
enum DispatcherType:int {
    case CharCountBased = 0;
    case GroupCountBased = 1;
    case GroupPosBased = 2;
    case MarkBased = 3;
}
