<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 * @version 1.0.0
 */

namespace miBadger\Http;

use Psr\Http\Message\StreamInterface;

/**
 * The server request class
 *
 * @since 1.0.0
 */
class ServerResponse extends Response
{
	/**
	 * Construct a ServerResponse object with the given status code, reason phrase, version, headers & body.
	 *
	 * @param int $statusCode
	 * @param string $reasonPhrase = ''
	 * @param string $version = self::DEFAULT_VERSION
	 * @param array $headers = []
	 * @param StreamInterface|null $body = null
	 */
	public function __construct($statusCode, $reasonPhrase = '', $version = self::DEFAULT_VERSION, array $headers = [], StreamInterface $body = null)
	{
		if ($body === null) {
			$body = new Stream(fopen('php://output', 'w'));
		}

		parent::__construct($statusCode, $reasonPhrase, $version, $headers, $body);
	}

	/**
	 * Send the server response.
	 *
	 * @return null
	 */
	public function send()
	{
		if ($this->getProtocolVersion() && $this->getStatusCode()) {
			header(static::VERSION_DELIMITER . $this->getProtocolVersion() . ' ' . $this->getStatusCode() . ' ' . $this->getReasonPhrase());
		}

		foreach ($this->getHeaders() as $key => $value) {
			header($key . static::HEADER_DELIMITER . implode(static::HEADER_VALUE_DELIMITER, $value));
		}

		echo $this->getBody();
	}
}
