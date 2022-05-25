<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Exceptions\Container;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends Exception implements NotFoundExceptionInterface { }
