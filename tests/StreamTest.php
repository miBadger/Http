<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 */

namespace miBadger\Http;

use PHPUnit\Framework\TestCase;

/**
 * The stream test.
 *
 * @since 1.0.0
 */
class StreamTest extends TestCase
{
	/** @var resource The resource. */
	private $resource;

	/** @var Stream The stream. */
	private $stream;

	/** @var int The level. */
	private $level;

	public function setUp()
	{
		$this->resource = fopen('php://temp', 'r+');
		fwrite($this->resource, 'test');
		fseek($this->resource, 0);

		$this->stream = new Stream($this->resource);
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage Invalid resource
	 */
	public function test__contruct()
	{
		new Stream(null);
	}

	public function test__destroy()
	{
		unset($this->stream);
		$this->assertTrue(true);
	}

	public function test__toString()
	{
		$this->assertEquals('test', $this->stream->__toString());
	}

	public function test__toStringDetach()
	{
		$this->assertEquals($this->resource, $this->stream->detach());
		$this->assertEmpty($this->stream->__toString());
	}

	public function testClose()
	{
		$this->assertNull($this->stream->close());
	}

	public function testDetach()
	{
		$this->assertEquals($this->resource, $this->stream->detach());
		$this->assertNull($this->stream->detach());
	}

	public function testGetSize()
	{
		$this->assertEquals(4, $this->stream->getSize());
	}

	public function testGetSizeError()
	{
		$this->assertEquals($this->resource, $this->stream->detach());
		$this->assertNull($this->stream->getSize());
	}

	public function testTell()
	{
		$this->assertEquals(0, $this->stream->tell());
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Error while getting the position of the pointer
	 */
	public function testTellError()
	{
		fclose($this->resource);
		@$this->stream->tell();
	}

	public function testEof()
	{
		$this->assertFalse($this->stream->eof());
	}

	public function testIsSeekable()
	{
		$this->assertTrue($this->stream->isSeekable());
	}

	public function testSeek()
	{
		$this->assertNull($this->stream->seek(0));
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Stream is not seekable
	 */
	public function testSeekFail()
	{
		$this->assertEquals($this->resource, $this->stream->detach());
		$this->stream->seek(0);
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Error while seeking the stream
	 */
	public function testSeekError()
	{
		$this->assertTrue(fclose($this->resource));
		@$this->stream->seek(0);
	}

	public function testRewind()
	{
		$this->assertNull($this->stream->rewind());
	}

	public function testIsWritable()
	{
		$this->assertTrue($this->stream->isWritable());
	}

	public function testWrite()
	{
		$this->assertEquals(strlen('test'), $this->stream->write('test'));
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Stream is not writable
	 */
	public function testWriteFail()
	{
		$this->assertEquals($this->resource, $this->stream->detach());
		$this->stream->write('test');
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Error while writing the stream
	 */
	public function testWriteError()
	{
		$this->assertTrue(fclose($this->resource));
		@$this->stream->write('test');
	}

	public function testIsReadable()
	{
		$this->assertTrue($this->stream->isReadable());
	}

	public function testRead()
	{
		$this->assertEquals('t', $this->stream->read(1));
		$this->assertEquals('e', $this->stream->read(1));
		$this->assertEquals('s', $this->stream->read(1));
		$this->assertEquals('t', $this->stream->read(1));
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Stream is not readable
	 */
	public function testReadFail()
	{
		$this->assertEquals($this->resource, $this->stream->detach());
		$this->stream->read(1024);
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Error while reading the stream
	 */
	public function testReadError()
	{
		$this->assertTrue(fclose($this->resource));
		@$this->stream->read(1024);
	}

	public function testGetContents()
	{
		$this->assertEquals('test', $this->stream->getContents());
	}

	public function testGetMetadata()
	{
		$metadata = [
			'wrapper_type' => 'PHP',
			'stream_type' => 'TEMP',
			'mode' => 'w+b',
			'unread_bytes' => 0,
			'seekable' => true,
			'uri' => 'php://temp'
		];

		$this->assertEquals($metadata, $this->stream->getMetadata());
	}
}
