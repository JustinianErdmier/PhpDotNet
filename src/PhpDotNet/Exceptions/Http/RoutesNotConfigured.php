<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

namespace PhpDotNet\Exceptions\Http;

use Exception;

class RoutesNotConfigured extends Exception {
    protected $message = 'No routes have been configured.';
}
