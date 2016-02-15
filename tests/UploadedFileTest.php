<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 * @version 1.0.0
 */

namespace miBadger\Http;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use org\bovigo\vfs\vfsStreamWrapper;

/**
 * The uploaded file test class.
 *
 * @since 1.0.0
 */
class UploadedFileTest extends \PHPUnit_Framework_TestCase
{
	/** @var File The file. */
	private $filePath;

	/** @var UploadedFile The uploaded file. */
	private $uploadedFile;

	public function setUp()
	{
		vfsStreamWrapper::register();
		vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
		vfsStreamWrapper::getRoot()->addChild(new vfsStreamFile('tmp_name.txt'));

		$this->path = vfsStream::url('test/tmp_name.txt');
		$this->uploadedFile = new UploadedFile('test.txt', 'text/plain', $this->path, UPLOAD_ERR_OK, 0);
	}

	public function testGetStream()
	{
		$this->assertEquals('', $this->uploadedFile->getStream()->getContents());
	}

	public function testMoveTo()
	{
		$this->assertNull($this->uploadedFile->moveTo(vfsStream::url('test/name.txt')));
	}

	/**
	 * @expectedException RuntimeException
 	 * @expectedExceptionMessage Can't move the file
	 */
	public function testMoveToSubsequent()
	{
		$this->assertNull($this->uploadedFile->moveTo(vfsStream::url('test/name.txt')));
		$this->uploadedFile->moveTo(vfsStream::url('test/name.txt'));
	}

	/**
	 * @expectedException RuntimeException
 	 * @expectedExceptionMessage Can't move the file
	 */
	public function testMoveToError()
	{
		$uploadedFile = new UploadedFile('error.txt', 'text/plain', 'error.txt', UPLOAD_ERR_NO_FILE, 0);
		$uploadedFile->moveTo(vfsStream::url('test/name.txt'));
	}

	public function testGetSize()
	{
		$this->assertEquals(0, $this->uploadedFile->getSize());
	}

	public function testGetError()
	{
		$this->assertEquals(UPLOAD_ERR_OK, $this->uploadedFile->getError());
	}

	public function testGetClientFilename()
	{
		$this->assertEquals('test.txt', $this->uploadedFile->getClientFilename());
	}

	public function testGetClientMediaType()
	{
		$this->assertEquals('text/plain', $this->uploadedFile->getClientMediaType());
	}
}

/**
 * Overwrite safe methods for testing
 */

namespace miBadger\Http;

function is_uploaded_file($filename)
{
	return file_exists($filename);
}

function move_uploaded_file($filename, $destination)
{
	return rename($filename, $destination);
}
