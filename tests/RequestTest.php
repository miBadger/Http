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
 * The request test class.
 *
 * @since 1.0.0
 */
class RequestTest extends TestCase
{
	/** @var Request The request. */
	private $request;

	public function setUp(): void
	{
		$this->uri = new URI('/directory/file?key=value');
		$this->request = new Request('GET', $this->uri);
	}

	public function testGetRequestTarget()
	{
		$this->assertEquals((string) $this->uri, $this->request->getRequestTarget());
	}

	/**
	 * @depends testGetRequestTarget
	 */
	public function testWithRequestTarget()
	{
		$request = $this->request->withRequestTarget('*');

		$this->assertEquals('*', $request->getRequestTarget());
		$this->assertNotSame($this->request, $request);
	}

	public function testGetMethod()
	{
		$this->assertEquals('GET', $this->request->getMethod());
	}

	/**
	 * @depends testGetMethod
	 */
	public function testWithMethod()
	{
		$request = $this->request->withMethod('POST');

		$this->assertEquals('POST', $request->getMethod());
		$this->assertNotSame($this->request, $request);
	}

	public function testGetUri()
	{
		$this->assertEquals($this->uri, $this->request->getUri());
	}

	/**
	 * @depends testGetUri
	 */
	public function testWithUri()
	{
		$uri = new URI('example.org:80/test.html');
		$request = $this->request->withUri($uri);

		$this->assertEquals($uri, $request->getUri());
		$this->assertNotSame($this->request, $request);
	}
}
