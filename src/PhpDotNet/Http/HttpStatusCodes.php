<?php

/******************************************************************************
 * Copyright (c) 2022. Justin Erdmier - All Rights Reserved                   *
 * Licensed under the MIT License - See LICENSE in repository root.           *
 ******************************************************************************/

declare(strict_types = 1);

namespace PhpDotNet\Http;

/**
 * A collection of enumerable constants for HTTP status codes.
 */
enum HttpStatusCodes:int {
    case Status100Continue = 100;
    case Status101SwitchingProtocols = 101;
    case Status102Processing = 102;
    case Status200OK = 200;
    case Status201Created = 201;
    case Status202Accepted = 202;
    case Status203NonAuthoritative = 203;
    case Status204NoContent = 204;
    case Status205ResetContent = 205;
    case Status206PartialContent = 206;
    case Status207MultiStatus = 207;
    case Status208AlreadyReported = 208;
    case Status226IMUsed = 226;
    case Status300MultipleChoices = 300;
    case Status301MovedPermanently = 301;
    case Status302Found = 302;
    case Status303SeeOther = 303;
    case Status304NotModified = 304;
    case Status305UseProxy = 305;
    case Status306SwitchProxy = 306;
    case Status307TemporaryRedirect = 307;
    case Status308PermanentRedirect = 308;
    case Status400BadRequest = 400;
    case Status401Unauthorized = 401;
    case Status402PaymentRequired = 402;
    case Status403Forbidden = 403;
    case Status404NotFound = 404;
    case Status405MethodNotAllowed = 405;
    case Status406NotAcceptable = 406;
    case Status407ProxyAuthenticationRequired = 407;
    case Status408RequestTimeout = 408;
    case Status409Conflict = 409;
    case Status410Gone = 410;
    case Status411LengthRequired = 411;
    case Status412PreconditionFailed = 412;
    case Status413RequestEntityTooLargeOrPayloadTooLarge = 413;
    case Status414RequestUriTooLongOrUriTooLong = 414;
    case Status415UnsupportedMediaType = 415;
    case Status416RequestedRangeNotSatisfiableOrRangeNotSatisfiable = 416;
    case Status417ExpectationFailed = 417;
    case Status418ImATeapot = 418;
    case Status419AuthenticationTimeout = 419;
    case Status421MisdirectedRequest = 421;
    case Status422UnprocessableEntity = 422;
    case Status423Locked = 423;
    case Status424FailedDependency = 424;
    case Status426UpgradeRequired = 426;
    case Status428PreconditionRequired = 428;
    case Status429TooManyRequests = 429;
    case Status431RequestHeaderFieldsTooLarge = 431;
    case Status451UnavailableForLegalReasons = 451;
    case Status500InternalServerError = 500;
    case Status501NotImplemented = 501;
    case Status502BadGateway = 502;
    case Status503ServiceUnavailable = 503;
    case Status504GatewayTimeout = 504;
    case Status505HttpVersionNotSupported = 505;
    case Status506VariantAlsoNegotiates = 506;
    case Status507InsufficientStorage = 507;
    case Status508LoopDetected = 508;
    case Status510NotExtended = 510;
    case Status511NetworkAuthenticationRequired = 511;
}
