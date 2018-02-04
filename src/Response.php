<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 */

namespace miBadger\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * The response class.
 *
 * @see http://www.php-fig.org/psr/psr-7/
 * @since 1.0.0
 */
class Response extends Message implements ResponseInterface
{
	/** @var int The status code. */
	private $statusCode;

	/** @var string The reason phrase. */
	private $reasonPhrase;

	/** @var array The reason phrases */
	private static $reasonPhrases = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-status',
		208 => 'Already Reported',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Switch Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Large',
		415 => 'Unsupported Media Type',
		416 => 'Requested range not satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Time-out',
		505 => 'HTTP Version not supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'Loop Detected',
		511 => 'Network Authentication Required',
	];

	/**
	 * Construct a Response object with the given status code, reason phrase, version, headers & body.
	 *
	 * @param int $statusCode
	 * @param string $reasonPhrase = ''
	 * @param string $version = self::DEFAULT_VERSION
	 * @param array $headers = []
	 * @param StreamInterface|null $body = null
	 */
	public function __construct($statusCode, $reasonPhrase = '', $version = self::DEFAULT_VERSION, array $headers = [], StreamInterface $body = null)
	{
		parent::__construct($version, $headers, $body);

		$this->setStatus($statusCode, $reasonPhrase);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * Set the status.
	 *
	 * @param int $statusCode
	 * @param string $reasonPhrase = ''
	 * @return $this
	 */
	private function setStatus($statusCode, $reasonPhrase = '')
	{
		if ($reasonPhrase === '' && isset(self::$reasonPhrases[$statusCode])) {
			$reasonPhrase = self::$reasonPhrases[$statusCode];
		}

		$this->statusCode = $statusCode;
		$this->reasonPhrase = $reasonPhrase;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withStatus($statusCode, $reasonPhrase = '')
	{
		$result = clone $this;

		return $result->setStatus($statusCode, $reasonPhrase);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getReasonPhrase()
	{
		return $this->reasonPhrase;
	}
}
