<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 */

namespace miBadger\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * The server request class
 *
 * @see http://www.php-fig.org/psr/psr-7/
 * @since 1.0.0
 */
class ServerRequest extends Request implements ServerRequestInterface
{
	/** @var array The server parameters. */
	private $serverParams;

	/** @var array The cookie parameters. */
	private $cookieParams;

	/** @var array The query parameters. */
	private $queryParams;

	/** @var array The post parameters. */
	private $postParams;

	/** @var array The files parameters. */
	private $filesParams;

	/** @var array The uploaded files. */
	private $uploadedFiles;

	/** @var null|array|object The parsed body. */
	private $parsedBody;

	/** @var array The attributes. */
	private $attributes;

	/**
	 * Construct a Request object with the given method, uri, version, headers & body.
	 *
	 * @global array $_SERVER The server parameters.
	 * @global array $_COOKIE The cookie parameters.
	 * @global array $_GET The query parameters.
	 * @global array $_POST The post parameters.
	 * @global array $_FILES The files parameters.
	 *
	 * @param string $method = ''
	 * @param UriInterface|null $uri = null
	 * @param string $version = self::DEFAULT_VERSION
	 * @param array $headers = []
	 * @param StreamInterface|null $body = null
	 */
	public function __construct($method = '', UriInterface $uri = null, $version = self::DEFAULT_VERSION, array $headers = [], StreamInterface $body = null)
	{
		if ($body === null) {
			$body = new Stream(fopen('php://input', 'r'));
		}

		$this->serverParams = $_SERVER;
		$this->cookieParams = $_COOKIE;
		$this->queryParams = $this->initQueryParams($this->serverParams);
		$this->postParams = $_POST;
		$this->filesParams = $_FILES;
		$this->uploadedFiles = $this->initUploadedFiles($this->filesParams);
		$this->attributes = [];

		parent::__construct($this->initMethod($method), $this->initUri($uri), $version, $this->initHeaders($headers), $body);
	}

	/**
	 * Initialize the method.
	 *
	 * @param string $method
	 * @return string the method.
	 */
	private function initMethod($method)
	{
		return $method === '' && isset($this->getServerParams()['REQUEST_METHOD']) ? $this->getServerParams()['REQUEST_METHOD'] : $method;
	}

	/**
	 * Initialize the URI.
	 *
	 * @param UriInterface|null $uri
	 * @return UriInterface the URI.
	 */
	private function initUri($uri)
	{
		if ($uri !== null) {
			return $uri;
		}

		$scheme = isset($this->getServerParams()['HTTPS']) ? 'https://' : 'http://';
		$host = isset($this->getServerParams()['HTTP_HOST']) ? $scheme . $this->getServerParams()['HTTP_HOST'] : '';
		$path = isset($this->getServerParams()['REQUEST_URI']) ? $this->getServerParams()['REQUEST_URI'] : '';

		return new URI($host . $path);
	}

	/**
	 * Initialize the headers.
	 *
	 * @param array $headers
	 * @return array the headers.
	 */
	private function initHeaders($headers)
	{
		return $headers ?: getallheaders();
	}

	/**
	 * Initialize the headers.
	 *
	 * @param string $serverParams
	 * @return array the headers.
	 */
	private function initQueryParams($serverParams)
	{
		$result = [];

		if (isset($serverParams['REQUEST_URI']) && ($query = parse_url($serverParams['REQUEST_URI'], \PHP_URL_QUERY))) {
			parse_str($query, $result);
		}

		return $result ?? [];
	}

	/**
	 * Initialize the uploaded files.
	 *
	 * @param array $files
	 * @return array the uploaded files.
	 */
	private function initUploadedFiles(array $files)
	{
		$result = [];

		foreach ($files as $key => $value) {
			$result[$key] = $this->parseUploadedFiles($value);
		}

		return $result;
	}

	/**
	 * Parse uploaded files.
	 *
	 * @param array $files
	 * @return UploadedFile|array uploaded files.
	 */
	private function parseUploadedFiles($files)
	{
		// Empty
		$first = reset($files);

		// Single
		if (!is_array($first)) {
			return $this->parseSingleUploadedFiles($files);
		}

		// Multiple
		if (count(array_filter(array_keys($first), 'is_string')) === 0) {
			return $this->parseMultipleUploadedFiles($files);
		}

		// Namespace
		return $this->initUploadedFiles($files);
	}

	/**
	 * Parse single uploaded file.
	 *
	 * @param array $file
	 * @return UploadedFile single uploaded file.
	 */
	private function parseSingleUploadedFiles(array $file)
	{
		return new UploadedFile($file['name'], $file['type'], $file['tmp_name'], $file['error'], $file['size']);
	}

	/**
	 * Parse multiple uploaded files.
	 *
	 * @param array $files
	 * @return UploadedFiles[] multiple uploaded files.
	 */
	private function parseMultipleUploadedFiles(array $files)
	{
		$count = count($files['name']);
		$result = [];

		for ($i = 0; $i < $count; $i++) {
			$result[] = new UploadedFile($files['name'][$i], $files['type'][$i], $files['tmp_name'][$i], $files['error'][$i], $files['size'][$i]);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getServerParams()
	{
		return $this->serverParams;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCookieParams()
	{
		return $this->cookieParams;
	}

	/**
	 * Set the cookie params.
	 *
	 * @param array $cookieParams
	 * @return $this
	 */
	private function setCookieParams(array $cookieParams)
	{
		$this->cookieParams = $cookieParams;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withCookieParams(array $cookieParams)
	{
		$result = clone $this;

		return $result->setCookieParams($cookieParams);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getQueryParams()
	{
		return $this->queryParams;
	}

	/**
	 * Set the query params.
	 *
	 * @param array $queryParams
	 * @return $this
	 */
	private function setQueryParams(array $queryParams)
	{
		$this->queryParams = $queryParams;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withQueryParams(array $queryParams)
	{
		$result = clone $this;

		return $result->setQueryParams($queryParams);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUploadedFiles()
	{
		return $this->uploadedFiles;
	}

	/**
	 * Set the uploaded files.
	 *
	 * @param array $uploadedFiles
	 * @return $this
	 */
	private function setUploadedFiles(array $uploadedFiles)
	{
		$this->uploadedFiles = $uploadedFiles;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withUploadedFiles(array $uploadedFiles)
	{
		$result = clone $this;

		return $result->setUploadedFiles($uploadedFiles);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParsedBody()
	{
		if ($this->parsedBody !== null) {
			return $this->parsedBody;
		}
		if ($this->getMethod() === 'POST' && ($this->hasContentType('application/x-www-form-urlencoded') || $this->hasContentType('multipart/form-data'))) {
			return $this->postParams;
		}
		if ($this->hasContentType('application/json') || $this->hasContentType('text/plain')) {
			return json_decode((string) $this->getBody(), true);
		}
		return null;
	}


	/**
	 * Checks if a content type header exists with the given content type.
	 *
	 * @param string $contentType
	 * @return bool true if a content type header exists with the given content type.
	 */
	private function hasContentType($contentType)
	{
		foreach ($this->getHeader('Content-Type') as $key => $value) {
			if (mb_substr($value, 0, strlen($contentType)) == $contentType) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Set the parsed body.
	 *
	 * @param null|array|object $parsedBody
	 * @return $this
	 */
	private function setParsedBody($parsedBody)
	{
		$this->parsedBody = $parsedBody;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withParsedBody($parsedBody)
	{
		$result = clone $this;

		return $result->setParsedBody($parsedBody);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAttribute($name, $default = null)
	{
		return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
	}

	/**
	 * Set the attribute.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	private function setAttribute($name, $value)
	{
		$this->attributes[$name] = $value;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withAttribute($name, $value)
	{
		$result = clone $this;

		return $result->setAttribute($name, $value);
	}

	/**
	 * Remove the attribute.
	 *
	 * @param string $name
	 * @return $this
	 */
	private function removeAttribute($name)
	{
		unset($this->attributes[$name]);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withoutAttribute($name)
	{
		$result = clone $this;

		return $result->removeAttribute($name);
	}
}
