<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 */

namespace miBadger\Http;

use Psr\Http\Message\UploadedFileInterface;

/**
 * The uploaded file class
 *
 * @see http://www.php-fig.org/psr/psr-7/
 * @since 1.0.0
 */
class UploadedFile implements UploadedFileInterface
{
	/** @var string The name */
	private $name;

	/** @var string The type. */
	private $type;

	/** @var string The tmp name. */
	private $tmpName;

	/** @var int The error. */
	private $error;

	/** @var int The size. */
	private $size;

	/**
	 * Construct a Stream object with the given name, type, tmp name, error and size.
	 *
	 * @param string $name
	 * @param string $type
	 * @param string $tmpName
	 * @param int $error
	 * @param int $size
	 */
	public function __construct($name, $type, $tmpName, $error, $size)
	{
		$this->name = $name;
		$this->type = $type;
		$this->tmpName = $tmpName;
		$this->error = $error;
		$this->size = $size;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getStream()
	{
		return new Stream(fopen($this->tmpName, 'r'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function moveTo($targetPath)
	{
		if ($this->getError() != UPLOAD_ERR_OK || !is_uploaded_file($this->tmpName) || !move_uploaded_file($this->tmpName, $targetPath)) {
			throw new \RuntimeException('Can\'t move the file');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSize()
	{
		return $this->size;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getClientFilename()
	{
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getClientMediaType()
	{
		return $this->type;
	}
}
