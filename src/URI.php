<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 */

namespace miBadger\Http;

use Psr\Http\Message\UriInterface;

/**
 * The URI class.
 *
 * @see http://tools.ietf.org/html/rfc3986
 * @since 1.0.0
 */
class URI implements UriInterface
{
	const DELIMITER_SCHEME = ':';
	const DELIMITER_AUTHORITY = '//';
	const DELIMITER_USER = '@';
	const DELIMITER_PASSWORD = ':';
	const DELIMITER_PORT = ':';
	const DELIMITER_PATH = '/';
	const DELIMITER_QUERY = '?';
	const DELIMITER_QUERY_PAIR = '&';
	const DELIMITER_QUERY_KEY_VALUE = '=';
	const DELIMITER_FRAGMENT = '#';

	const SCHEME = 'scheme';
	const AUTHORITY = 'authority';
	const USERNAME = 'user';
	const PASSWORD = 'pass';
	const HOST = 'host';
	const PORT = 'port';
	const PATH = 'path';
	const DIRECTORY = 'directory';
	const FILE = 'file';
	const QUERY = 'query';
	const FRAGMENT = 'fragment';

	/** @var string The scheme. */
	private $scheme;

	/** @var string The username. */
	private $username;

	/** @var string|null The password. */
	private $password;

	/** @var string The host. */
	private $host;

	/** @var int|null The port. */
	private $port;

	/** @var string The directory. */
	private $directory;

	/** @var string The file. */
	private $file;

	/** @var array The query. */
	private $query;

	/** @var string The fragment. */
	private $fragment;

	/**
	 * Construct a URI object with the given URI.
	 *
	 * @param string $uri
	 * @throws \UnexpectedValueException
	 */
	public function __construct($uri)
	{
		$component = parse_url($uri);

		if ($component === false) {
			throw new \UnexpectedValueException('Invalid uri');
		}

		// Replace with the null coalescing in PHP7. E.g. $component[scheme] ?? ''
		$component += [
			static::SCHEME => '',
			static::USERNAME => '',
			static::PASSWORD => null,
			static::HOST => '',
			static::PORT => null,
			static::PATH => '',
			static::QUERY => '',
			static::FRAGMENT => ''
		];

		$this->setScheme($component[static::SCHEME]);
		$this->setUserInfo($component[static::USERNAME], $component[static::PASSWORD]);
		$this->setHost($component[static::HOST]);
		$this->setPort($component[static::PORT]);
		$this->setPath($component[static::PATH]);
		$this->setQuery($component[static::QUERY]);
		$this->setFragment($component[static::FRAGMENT]);
	}

	/**
	 * Returns a string representation of the URI object.
	 *
	 * @return string a string representation of the URI object.
	 */
	public function __toString()
	{
		return $this->getUri();
	}

	/**
	 * Returns the URI with the given start and stop component.
	 *
	 * @param string $start = self::SCHEME
	 * @param string $end = self::FRAGMENT
	 * @return string the URI.
	 */
	public function getUri($start = self::SCHEME, $end = self::FRAGMENT)
	{
		$result = '';

		switch ($start) {
			default:
			case static::SCHEME:
				$scheme = $this->getScheme();

				if ($scheme) {
					$result .= $scheme . static::DELIMITER_SCHEME;
				}

				if ($end === static::SCHEME) {
					break;
				}

				// no break

			case static::AUTHORITY:
			case static::USERNAME:
				$username = $this->getUserInfo();

				if ($username && $this->getHost()) {
					$result .= static::DELIMITER_AUTHORITY . $username . static::DELIMITER_USER;
				}

				if ($end === static::USERNAME) {
					break;
				}

				// no break

			case static::HOST:
				$host = $this->getHost();

				if ($host && ($result === '' || !$this->getUserInfo())) {
					$result .= static::DELIMITER_AUTHORITY;
				}

				$result .= $host;

				if ($end === static::HOST) {
					break;
				}

				// no break

			case static::PORT:
				$port = $this->getPort();

				if ($port !== null && $this->getHost()) {
					$result .= static::DELIMITER_PORT . $port;
				}

				if ($end === static::PORT || $end === static::AUTHORITY) {
					break;
				}

				// no break

			case static::PATH:
			case static::DIRECTORY:
				$directory = $this->getDirectory();

				if ($result !== '' && $directory !== '' && substr($directory, 0, 1) !== static::DELIMITER_PATH) {
					$result .= static::DELIMITER_PATH;
				}

				$result .= $directory;

				if ($end === static::DIRECTORY) {
					break;
				}

				// no break

			case static::FILE:
				$file = $this->getFile();

				if ($result !== '' && substr($result, -1) !== static::DELIMITER_PATH && $file !== '') {
					$result .= static::DELIMITER_PATH;
				}

				$result .= $this->getFile();

				if ($end === static::FILE || $end === static::PATH) {
					break;
				}

				// no break

			case static::QUERY:
				$query = $this->getQuery();

				if ($query) {
					$result .= static::DELIMITER_QUERY . $query;
				}

				if ($end === static::QUERY) {
					break;
				}

				// no break

			case static::FRAGMENT:
				$fragment = $this->getFragment();

				if ($fragment) {
					$result .= static::DELIMITER_FRAGMENT . $fragment;
				}

				// no break
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getScheme()
	{
		return $this->scheme;
	}

	/**
	 * Set the scheme.
	 *
	 * @param string $scheme
	 * @return $this
	 */
	private function setScheme($scheme)
	{
		$this->scheme = strtolower($scheme);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withScheme($scheme)
	{
		$result = clone $this;

		return $result->setScheme($scheme);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAuthority()
	{
		if (!$this->getHost()) {
			return '';
		}

		return substr($this->getUri(self::USERNAME, self::PORT), 2);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUserInfo()
	{
		$result = $this->username;

		if ($this->password !== null) {
			$result .= static::DELIMITER_PASSWORD . $this->password;
		}

		return $result;
	}

	/**
	 * Set the user info.
	 *
	 * @param string $username
	 * @param string|null $password = null
	 * @return $this
	 */
	private function setUserInfo($username, $password = null)
	{
		$this->username = $username;
		$this->password = $password;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withUserInfo($username, $password = null)
	{
		$result = clone $this;

		return $result->setUserInfo($username, $password);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * Set the host.
	 *
	 * @param string $host
	 * @return $this
	 */
	private function setHost($host)
	{
		$this->host = strtolower($host);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withHost($host)
	{
		$result = clone $this;

		return $result->setHost($host);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * Set the port.
	 *
	 * @param int|null $port
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	private function setPort($port = null)
	{
		if ($port !== null && (1 > $port || 0xffff < $port)) {
			throw new \InvalidArgumentException('Invalid port');
		}

		$this->port = $port;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withPort($port)
	{
		$result = clone $this;

		return $result->setPort($port);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPath()
	{
		$result = $this->getDirectory();

		if ($result !== '' && substr($result, -1) !== static::DELIMITER_PATH && $this->getFile()) {
			$result .= static::DELIMITER_PATH;
		}

		return $result . $this->getFile();
	}

	/**
	 * Set the path.
	 *
	 * @param string $path
	 * @return $this
	 */
	private function setPath($path)
	{
		$directory = dirname($path);
		$file = basename($path);

		// If dirname is '.'. Then remove it.
		if ($directory === '.') {
			$directory = '';
		}

		// If the path ends with '/'. Then there is no file.
		if (substr($path, -1) === static::DELIMITER_PATH) {
			$directory = $path;
			$file = '';
		}

		// If the dirname and basename are both set. Then add the missing '/'.
		if (substr($directory, -1) !== static::DELIMITER_PATH && $directory !== '' && $file !== '') {
			$directory .= static::DELIMITER_PATH;
		}

		$this->setDirectory($directory);
		$this->setFile($file);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withPath($path)
	{
		$result = clone $this;

		return $result->setPath($path);
	}

	/**
	 * Returns the URI segements
	 *
	 * @return string[] the URI segments
	 */
	public function getSegments()
	{
		// array_values reindexes the array and array_diff removes the empty elements.
		return array_values(array_diff(explode(static::DELIMITER_PATH, $this->getPath()), ['']));
	}

	/**
	 * Returns the segment at the given index or null if the segment at the given index doesn't exists.
	 *
	 * @param int $index
	 * @return string|null the segment at the given index or null if the segment at the given index doesn't exists
	 */
	public function getSegment($index)
	{
		$result = $this->getSegments();

		return isset($result[$index]) ? $result[$index] : null;
	}

	/**
	 * Returns the directory.
	 *
	 * @return string the directory.
	 */
	public function getDirectory()
	{
		return $this->directory;
	}

	/**
	 * Set the directory.
	 *
	 * @param string $directory
	 * @return $this
	 */
	private function setDirectory($directory)
	{
		$this->directory = $directory;

		return $this;
	}

	/**
	 * Return an instance with the specified directory.
	 *
	 * @param string $directory
	 * @return self
	 */
	public function withDirectory($directory)
	{
		$result = clone $this;

		return $result->setDirectory($directory);
	}

	/**
	 * Returns the file.
	 *
	 * @return string the file.
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * Set the file.
	 *
	 * @param string $file
	 * @return $this
	 */
	private function setFile($file)
	{
		$this->file = $file;

		return $this;
	}

	/**
	 * Return an instance with the specified file.
	 *
	 * @param string $file
	 * @return self
	 */
	public function withFile($file)
	{
		$result = clone $this;

		return $result->setFile($file);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getQuery()
	{
		return http_build_query($this->query);
	}

	/**
	 * Set the query.
	 *
	 * @param string $query
	 * @return $this
	 */
	private function setQuery($query)
	{
		$this->query = [];

		parse_str($query, $this->query);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withQuery($query)
	{
		$result = clone $this;

		return $result->setQuery($query);
	}

	/**
	 * Returns the value to which the specified key is mapped, or null if the query map contains no mapping for the key.
	 *
	 * @param string $key
	 * @return string the value to which the specified key is mapped, or null if the query map contains no mapping for the key.
	 */
	public function getQueryValue($key)
	{
		return isset($this->query[$key]) ? $this->query[$key] : null;
	}

	/**
	 * Associates the specified value with the specified key in the query map.
	 *
	 * @param string $key
	 * @param string $value
	 * @return $this
	 */
	private function setQueryValue($key, $value)
	{
		$this->query[$key] = $value;

		return $this;
	}

	/**
	 * Return an instance with the specified query value.
	 *
	 * @param string $key
	 * @param string $value
	 * @return self
	 */
	public function withQueryValue($key, $value)
	{
		$result = clone $this;

		return $result->setQueryValue($key, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFragment()
	{
		return $this->fragment;
	}

	/**
	 * Set the fragment.
	 *
	 * @param string $fragment
	 * @return $this
	 */
	private function setFragment($fragment)
	{
		$this->fragment = $fragment;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function withFragment($fragment)
	{
		$result = clone $this;

		return $result->setFragment($fragment);
	}

	/**
	 * Returns an instance with the decoded URI.
	 *
	 * @return self
	 */
	public function decode()
	{
		return new URI(html_entity_decode($this));
	}

	/**
	 * Returns an instance with the encoded URI.
	 *
	 * @return self
	 */
	public function encode()
	{
		return new URI(htmlentities($this));
	}
}
