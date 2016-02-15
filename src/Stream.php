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
 * The stream class
 *
 * @see http://www.php-fig.org/psr/psr-7/
 * @since 1.0.0
 */
class Stream implements StreamInterface
{
	/** @var resource The resource. */
	private $resource;

	/** @var array The metadata. */
	private $metadata;

	/** @var string[] The read modes. */
	private static $readModes = ['r', 'w+', 'r+', 'x+', 'c+', 'rb', 'w+b', 'r+b', 'x+b', 'c+b', 'rt', 'w+t', 'r+t', 'x+t', 'c+t', 'a+'];

	/** @var string[] The write modes. */
	private static $writeModes = ['w', 'w+', 'rw', 'r+', 'x+', 'c+', 'wb', 'w+b', 'r+b', 'x+b', 'c+b', 'w+t', 'r+t', 'x+t', 'c+t', 'a', 'a+'];

	/**
	 * Construct a Stream object with the given resource.
	 *
	 * @param resource $resource
	 */
	public function __construct($resource)
	{
		if (!is_resource($resource)) {
			throw new \InvalidArgumentException('Invalid resource');
		}

		$this->resource = $resource;
		$this->metadata = stream_get_meta_data($resource);
	}

	/**
	 * Destruct the Stream object.
	 */
	public function __destruct()
	{
		$this->close();
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString()
	{
		try {
			$this->seek(0);

			return $this->getContents();
		} catch (\Exception $e) {
			return '';
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function close()
	{
		if (isset($this->resource)) {
			if (is_resource($this->resource)) {
				fclose($this->resource);
			}

			$this->detach();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function detach()
	{
		if (!isset($this->resource)) {
			return null;
		}

		$result = $this->resource;
		unset($this->resource);

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSize()
	{
		if (!isset($this->resource)) {
			return null;
		}

		if ($this->getMetadata('uri')) {
			clearstatcache(true, $this->getMetadata('uri'));
		}

		$stats = fstat($this->resource);

		return isset($stats['size']) ? $stats['size'] : null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function tell()
	{
		$result = ftell($this->resource);

		if ($result === false) {
			throw new \RuntimeException('Error while getting the position of the pointer');
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function eof()
	{
		return isset($this->resource) && feof($this->resource);
	}

	/**
	 * {@inheritdoc}
	 */
	public function isSeekable()
	{
		return isset($this->resource) && $this->getMetadata('seekable');
	}

	/**
	 * {@inheritdoc}
	 */
	public function seek($offset, $whence = SEEK_SET)
	{
		if (!$this->isSeekable()) {
			throw new \RuntimeException('Stream is not seekable');
		}

		if (fseek($this->resource, $offset, $whence) === false) {
			throw new \RuntimeException('Error while seeking the stream');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function rewind()
	{
		$this->seek(0);
	}

	/**
	 * {@inheritdoc}
	 */
	public function isWritable()
	{
		return isset($this->resource) && in_array($this->getMetadata('mode'), self::$writeModes);
	}

	/**
	 * {@inheritdoc}
	 */
	public function write($string)
	{
		if (!$this->isWritable()) {
			throw new \RuntimeException('Stream is not writable');
		}

		$result = fwrite($this->resource, $string);

		if ($result === false) {
			throw new \RuntimeException('Error while writing the stream');
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isReadable()
	{
		return isset($this->resource) && in_array($this->getMetadata('mode'), self::$readModes);
	}

	/**
	 * {@inheritdoc}
	 */
	public function read($length)
	{
		if (!$this->isReadable()) {
			throw new \RuntimeException('Stream is not readable');
		}

		$result = stream_get_contents($this->resource, $length);

		if ($result === false) {
			throw new \RuntimeException('Error while reading the stream');
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getContents()
	{
		return $this->read(-1);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMetadata($key = null)
	{
		if ($key === null) {
			return $this->metadata;
		}

		return isset($this->metadata[$key]) ? $this->metadata[$key] : null;
	}
}
