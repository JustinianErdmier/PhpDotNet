<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http;

enum HttpMethod:string {
    case Get = 'get';
    case Post = 'post';
    case Put = 'put';
    case Head = 'head';
}
