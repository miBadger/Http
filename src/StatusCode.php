<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 * @version 1.0.0
 */

namespace miBadger\Http;

use miBadger\Enum\Enum;

/**
 * The status code class.
 *
 * @since 1.0.0
 */
class StatusCode extends Enum
{
	const INFORMATIONAL_CONTINUE = 100;
	const INFORMATIONAL_SWITCHING_PROTOCOL = 101;
	const INFORMATIONAL_PROGRESSING = 102;
	const SUCCESFULL_OK = 200;
	const SUCCESFULL_CREATED = 201;
	const SUCCESFULL_ACCEPTED = 202;
	const SUCCESFULL_NON_AUTORITATIVE_INFORMATION = 203;
	const SUCCESFULL_NO_CONTENT = 204;
	const SUCCESFULL_RESET_CONTENT = 205;
	const SUCCESFULL_PARTIAL_CONTENT = 206;
	// 207 => 'Multi-status',
	// 208 => 'Already Reported',
	const REDIRECTION_MULTIPLE_CHOICES = 300;
	const REDIRECTION_MOVED_PERMANENTLY = 301;
	const REDIRECTION_FOUND = 302;
	const REDIRECTION_SEE_OTHER = 303;
	const REDIRECTION_NOT_MODIFIED = 304;
	const REDIRECTION_USE_PROXY = 305;
	// 306 => 'Switch Proxy',
	const REDIRECTION_TEMPORARY_REDIRECT = 307;
	const ERROR_CLIENT_BAD_REQUEST = 400;
	const ERROR_CLIENT_UNAUTHORIZED = 401;
	const ERROR_CLIENT_PAYMENT_REQUIRED = 402;
	const ERROR_CLIENT_FORBIDDEN = 403;
	const ERROR_CLIENT_NOT_FOUND = 404;
	const ERROR_CLIENT_METHOD_NOT_ALLOWED = 405;
	const ERROR_CLIENT_NOT_ACCEPTABLE = 406;
	const ERROR_CLIENT_PROXY_AUTHENTICATION_REQUIRED = 407;
	const ERROR_CLIENT_REQUEST_TIME_OUT = 408;
	const ERROR_CLIENT_CONFLICT = 409;
	const ERROR_CLIENT_GONE = 410;
	const ERROR_CLIENT_LENGTH_REQUIRED = 411;
	const ERROR_CLIENT_PRECONDITION_FAILED = 412;
	const ERROR_CLIENT_REQUEST_ENTITY_TOO_LARGE = 413;
	const ERROR_CLIENT_REQUEST_URI_TOO_LONG = 414;
	const ERROR_CLIENT_UNSUPPORTED_MEDIA = 415;
	const ERROR_CLIENT_REQUEST_RANGE_NOT_SATISFIABLE = 416;
	const ERROR_CLIENT_EXPECTATION_FAILED = 417;
	// 418 => 'I\'m a teapot',
	// 422 => 'Unprocessable Entity',
	// 423 => 'Locked',
	// 424 => 'Failed Dependency',
	// 425 => 'Unordered Collection',
	// 426 => 'Upgrade Required',
	// 428 => 'Precondition Required',
	// 429 => 'Too Many Requests',
	// 431 => 'Request Header Fields Too Large',
	const ERROR_SERVER_INTERNAL_ERROR_SERVER = 500;
	const ERROR_SERVER_NOT_IMPLEMENTED = 501;
	const ERROR_SERVER_BAD_GATEWAY = 502;
	const ERROR_SERVER_SERVICE_UNAVAILABLE = 503;
	const ERROR_SERVER_GATEWAY_TIMEOUT = 504;
	const ERROR_SERVER_HTTP_VERSION_NOT_SUPPORTED = 505;
	// 506 => 'Variant Also Negotiates',
	// 507 => 'Insufficient Storage',
	// 508 => 'Loop Detected',
	// 511 => 'Network Authentication Required',
}
