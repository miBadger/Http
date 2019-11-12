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

	public function setUp(): void
	{
		$this->resource = fopen('php://temp', 'r+');
		fwrite($this->resource, 'test');
		fseek($this->resource, 0);

		$this->stream = new Stream($this->resource);
	}

	public function test__contruct()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Invalid resource');

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

	public function testTellError()
	{
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Error while getting the position of the pointer');

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

	public function testSeekFail()
	{
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Stream is not seekable');

		$this->assertEquals($this->resource, $this->stream->detach());
		$this->stream->seek(0);
	}

	public function testSeekError()
	{
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Error while seeking the stream');

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

	public function testWriteFail()
	{
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Stream is not writable');

		$this->assertEquals($this->resource, $this->stream->detach());
		$this->stream->write('test');
	}

	public function testWriteError()
	{
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Error while writing the stream');

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

	public function testReadFail()
	{
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Stream is not readable');

		$this->assertEquals($this->resource, $this->stream->detach());
		$this->stream->read(1024);
	}

	public function testReadError()
	{
		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('Error while reading the stream');

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
