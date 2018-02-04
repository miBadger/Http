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
 * The message test class.
 *
 * @since 1.0.0
 */
class MessageTest extends TestCase
{
	/** @var array The headers. */
	private $headers;

	/** @var Message The message. */
	private $message;

	/** @var Stream The stream. */
	private $stream;

	public function setUp()
	{
		$this->headers = [
			'Accept-Charset' => ['utf-8'],
			'Accept-Encoding' => ['gzip', 'deflate']
		];

		$this->stream = new Stream(fopen('php://temp', 'r+'));
		$this->message = new Message('1.1', $this->headers, $this->stream);
	}

	public function testGetProtocolVersion()
	{
		$this->assertEquals('1.1', $this->message->getProtocolVersion());
	}

	public function testWithProtocolVersion()
	{
		$message = $this->message->withProtocolVersion('1.0');

		$this->assertEquals('1.0', $message->getProtocolVersion());
		$this->assertNotSame($this->message, $message);
	}

	public function testGetHeaders()
	{
		$this->assertEquals($this->headers, $this->message->getHeaders());
	}

	public function testHasHeader()
	{
		$this->assertFalse($this->message->hasHeader(''));
		$this->assertTrue($this->message->hasHeader('Accept-Charset'));
		$this->assertTrue($this->message->hasHeader('Accept-Encoding'));
	}

	public function testGetHeader()
	{
		$this->assertEquals([], $this->message->getHeader(''));
		$this->assertEquals(['utf-8'], $this->message->getHeader('Accept-Charset'));
		$this->assertEquals(['gzip', 'deflate'], $this->message->getHeader('Accept-Encoding'));
	}

	public function testGetHeaderLine()
	{
		$this->assertEquals('', $this->message->getHeaderLine(''));
		$this->assertEquals('utf-8', $this->message->getHeaderLine('Accept-Charset'));
		$this->assertEquals('gzip,deflate', $this->message->getHeaderLine('Accept-Encoding'));
	}

	public function testWithHeaderString()
	{
		$message = $this->message->withHeader('name', 'value');

		$this->assertEquals(['value'], $message->getHeader('name'));
		$this->assertNotSame($this->message, $message);
	}

	public function testWithHeaderArray()
	{
		$message = $this->message->withHeader('name', ['value']);

		$this->assertEquals(['value'], $message->getHeader('name'));
		$this->assertNotSame($this->message, $message);
	}

	public function testWithAddedheaderNew()
	{
		$message = $this->message->withAddedHeader('name', ['value']);

		$this->assertEquals(['value'], $message->getHeader('name'));
		$this->assertNotSame($this->message, $message);
	}

	public function testWithAddedheaderString()
	{
		$message = $this->message->withAddedHeader('Accept-Encoding', 'value');

		$this->assertEquals(['gzip', 'deflate', 'value'], $message->getHeader('Accept-Encoding'));
		$this->assertNotSame($this->message, $message);
	}

	public function testWithAddedheaderArray()
	{
		$message = $this->message->withAddedHeader('Accept-Encoding', ['value']);

		$this->assertEquals(['gzip', 'deflate', 'value'], $message->getHeader('Accept-Encoding'));
		$this->assertNotSame($this->message, $message);
	}

	public function testWithoutHeader()
	{
		$message = $this->message->withoutHeader('Accept-Encoding');

		$this->assertEquals([], $message->getHeader('Accept-Encoding'));
		$this->assertNotSame($this->message, $message);
	}

	public function testGetBody()
	{
		$this->assertEquals($this->stream, $this->message->getBody());
	}

	public function testWithBody()
	{
		$stream = new Stream(fopen('php://temp', 'r+'));
		$message = $this->message->withBody($stream);

		$this->assertEquals($stream, $message->getBody());
		$this->assertNotSame($message, $this->message);
	}
}
