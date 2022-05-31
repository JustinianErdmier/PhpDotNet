<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

namespace PhpDotNet\Exceptions\View;

use Exception;

class LayoutPathDoesNotExistException extends Exception {
    protected $message = 'The layout specified does not exist.';
}
