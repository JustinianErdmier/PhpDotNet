<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\API;

use PhpDotNet\Common\Attributes\Controllers\NonAction;
use PhpDotNet\MVC\ControllerBase;

abstract class ApiControllerBase extends ControllerBase {
    /**
     * Creates a {@see ActionResult} object.
     *
     * @param object|null $result  The result set to be serialized and returned.
     *
     * @return ActionResult
     */
    #[NonAction]
    public function actionResult(?object $result = null): ActionResult {
        return new ActionResult($result);
    }

    /**
     * Creates a {@see StatusCodeResult} object by specifying a status code.
     *
     * @param int $statusCode  The status code to set on the response.
     *
     * @return StatusCodeResult
     */
    #[NonAction]
    public function statusCode(int $statusCode): StatusCodeResult {
        return new StatusCodeResult($statusCode);
    }

    /**
     * Creates a {@see BadRequestResult} object.
     *
     * @return BadRequestResult
     */
    #[NonAction]
    public function badRequest(): BadRequestResult {
        return new BadRequestResult();
    }

    /**
     * Creates a {@see ConflictResult} object.
     *
     * @return ConflictResult
     */
    #[NonAction]
    public function conflict(): ConflictResult {
        return new ConflictResult();
    }

    /**
     * Creates a {@see NoContentResult} object.
     *
     * @return NoContentResult
     */
    #[NonAction]
    public function noContent(): NoContentResult {
        return new NoContentResult();
    }

    /**
     * Creates a {@see NotFoundResult} object.
     *
     * @return NotFoundResult
     */
    #[NonAction]
    public function notFound(): NotFoundResult {
        return new NotFoundResult();
    }

    /**
     * Creates a {@see OkResult} object.
     *
     * @return OkResult
     */
    #[NonAction]
    public function ok(): OkResult {
        return new OkResult();
    }

    /**
     * Creates a {@see UnauthorizedResult} object.
     *
     * @return UnauthorizedResult
     */
    #[NonAction]
    public function unauthorized(): UnauthorizedResult {
        return new UnauthorizedResult();
    }

    /**
     * Creates a {@see UnprocessableEntityResult} object.
     *
     * @return UnprocessableEntityResult
     */
    #[NonAction]
    public function unprocessableEntity(): UnprocessableEntityResult {
        return new UnprocessableEntityResult();
    }

    /**
     * Creates a {@see UnsupportedMediaTypeResult} object.
     *
     * @return UnsupportedMediaTypeResult
     */
    #[NonAction]
    public function unsupportedMediaType(): UnsupportedMediaTypeResult {
        return new UnsupportedMediaTypeResult();
    }
}
