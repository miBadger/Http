<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 */

namespace miBadger\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

/**
 * The message class.
 *
 * @see http://www.php-fig.org/psr/psr-7/
 * @since 1.1.0
 */
class Message implements MessageInterface
{
	const DEFAULT_VERSION = '1.1';
	const VERSION_DELIMITER = 'HTTP/';
	const HEADER_DELIMITER = ': ';
	const HEADER_VALUE_DELIMITER = ',';

	/** @var string The version. */
	private $version;

	/** @var array The header names. */
	private $headerNames;

	/** @var array The headers. */
	private $headers;

	/** @var StreamInterface The body. */
	private $body;

	/**
	 * Construct a Message object with the given version, headers & body.
	 *
	 * @param string $version = self::DEFAULT_VERSION
	 * @param array $headers = []
	 * @param StreamInterface|null $body = null
	 */
	public function __construct($version = self::DEFAULT_VERSION, array $headers = [], StreamInterface $body = null)
	{
		if ($body === null) {
			$body = new Stream(fopen('php://temp', 'r+'));
		}

		$this->setProtocolVersion($version);
		$this->setHeaders($headers);
		$this->setBody($body);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getProtocolVersion()
	{
		return $this->version;
	}

	/**
	 * Set the protocol version.
	 *
	 * @param string $version
	 * @return $this
	 */
	private function setProtocolVersion($version)
	{
		$this->version = $version;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withProtocolVersion($version)
	{
		$result = clone $this;

		return $result->setProtocolVersion($version);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * Set the headers.
	 *
	 * @param array $headers
	 * @return $this
	 */
	private function setHeaders(array $headers)
	{
		$this->headerNames = [];
		$this->headers = [];

		foreach ($headers as $name => $value) {
			$this->setHeader($name, $value);
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasHeader($name)
	{
		return array_key_exists(strtolower($name), $this->headerNames);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHeader($name)
	{
		if (!$this->hasHeader($name)) {
			return [];
		}

		return $this->headers[$this->headerNames[strtolower($name)]];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHeaderLine($name)
	{
		if (!$this->hasHeader($name)) {
			return null;
		}

		return implode(',', $this->getHeader($name));
	}

	/**
	 * Set the header.
	 *
	 * @param string $name
	 * @param string|string[] $value
	 * @return $this
	 */
	protected function setHeader($name, $value)
	{
		if (!is_array($value)) {
			$value = [$value];
		}

		$this->headerNames[strtolower($name)] = $name;
		array_merge($this->headers[$name] = $value);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withHeader($name, $value)
	{
		$result = clone $this;

		return $result->setHeader($name, $value);
	}

	/**
	 * Add the header.
	 *
	 * @param string $name
	 * @param string|string[] $value
	 * @return $this
	 */
	private function addHeader($name, $value)
	{
		if (!$this->hasHeader($name)) {
			return $this->setHeader($name, $value);
		}

		if (!is_array($value)) {
			$value = [$value];
		}

		foreach ($value as $element) {
			$this->headers[$this->headerNames[strtolower($name)]][] = $element;
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withAddedHeader($name, $value)
	{
		$result = clone $this;

		return $result->addHeader($name, $value);
	}

	/**
	 * Remove the header.
	 *
	 * @param string $name
	 * @return $this
	 */
	private function removeHeader($name)
	{
		if ($this->hasHeader($name)) {
			$normalized = strtolower($name);

			unset($this->headers[$this->headerNames[$normalized]], $this->headerNames[$normalized]);
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withoutHeader($name)
	{
		$result = clone $this;

		return $result->removeHeader($name);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBody()
	{
		return $this->body;
	}

	/**
	 * Sets the body.
	 *
	 * @param StreamInterface $body
	 * @return $this
	 */
	private function setBody(StreamInterface $body)
	{
		$this->body = $body;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withBody(StreamInterface $body)
	{
		$result = clone $this;

		return $result->setBody($body);
	}
}
