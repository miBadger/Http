<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 * @version 1.0.0
 */

namespace miBadger\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * The request class.
 *
 * @see http://www.php-fig.org/psr/psr-7/
 * @since 1.0.0
 */
class Request extends Message implements RequestInterface
{
	/** @var string The request target. */
	private $requestTarget;

	/** @var string The method. */
	private $method;

	/** @var UriInterface The URI. */
	private $uri;

	/**
	 * Construct a Request object with the given method, uri, version, headers & body.
	 *
	 * @param string $method
	 * @param UriInterface $uri
	 * @param string $version = self::DEFAULT_VERSION
	 * @param array $headers = []
	 * @param StreamInterface|null $body = null
	 */
	public function __construct($method, UriInterface $uri, $version = self::DEFAULT_VERSION, array $headers = [], StreamInterface $body = null)
	{
		parent::__construct($version, $headers, $body);

		$this->setMethod($method);
		$this->setUri($uri);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRequestTarget()
	{
		if (isset($this->requestTarget)) {
			return $this->requestTarget;
		}

		$result = $this->getUri()->getPath() ?: URI::DELIMITER_PATH;

		if ($this->getUri()->getQuery()) {
			$result .= URI::DELIMITER_QUERY . $this->getUri()->getQuery();
		}

		return $result;
	}

	/**
	 * Set the request target.
	 *
	 * @param string $requestTarget
	 * @return $this
	 */
	private function setRequestTarget($requestTarget)
	{
		$this->requestTarget = $requestTarget;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withRequestTarget($requestTarget)
	{
		$result = clone $this;

		return $result->setRequestTarget($requestTarget);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Set the method.
	 *
	 * @param string $method
	 * @return $this
	 */
	private function setMethod($method)
	{
		$this->method = $method;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withMethod($method)
	{
		$result = clone $this;

		return $result->setMethod($method);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUri()
	{
		return $this->uri;
	}

	/**
	 * Set the uri.
	 *
	 * @param UriInterface $uri
	 * @param boolean $preserveHost = false
	 * @return $this
	 */
	private function setUri(UriInterface $uri, $preserveHost = false)
	{
		$this->uri = $uri;

		if (!$preserveHost && ($host = $uri->getHost())) {
			if ($uri->getPort() !== null) {
				$host .= URI::DELIMITER_PORT . $uri->getPort();
			}

			$this->setHeader('Host', $host);
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withUri(UriInterface $uri, $preserveHost = false)
	{
		$result = clone $this;

		return $result->setUri($uri, $preserveHost);
	}
}
