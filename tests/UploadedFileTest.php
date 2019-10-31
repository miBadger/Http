<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 */

namespace miBadger\Http;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use org\bovigo\vfs\vfsStreamWrapper;

use PHPUnit\Framework\TestCase;

/**
 * The uploaded file test class.
 *
 * @since 1.0.0
 */
class UploadedFileTest extends TestCase
{
	/** @var File The file. */
	private $filePath;

	/** @var UploadedFile The uploaded file. */
	private $uploadedFile;

	public function setUp(): void
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

	public function testMoveToSubsequent()
	{
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Can\'t move the file');

		$this->assertNull($this->uploadedFile->moveTo(vfsStream::url('test/name.txt')));
		$this->uploadedFile->moveTo(vfsStream::url('test/name.txt'));
	}

	public function testMoveToError()
	{
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Can\'t move the file');

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
