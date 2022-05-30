<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

namespace PhpDotNet\Exceptions\Http;

use Exception;

class ControllerMapNotFound extends Exception {
    protected $message = 'Controller map not found.';
}
