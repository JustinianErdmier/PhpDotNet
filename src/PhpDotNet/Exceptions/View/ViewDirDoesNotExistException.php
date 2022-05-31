<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

namespace PhpDotNet\Exceptions\View;

use Exception;

class ViewDirDoesNotExistException extends Exception {
    protected $message = 'The directory specified for the Views does not exists.';
}
