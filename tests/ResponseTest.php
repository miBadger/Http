<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 * @version 1.0.0
 */

namespace miBadger\Http;

use PHPUnit\Framework\TestCase;

/**
 * The response test class.
 *
 * @since 1.0.0
 */
class ResponseTest extends TestCase
{
	/** @var Response The response. */
	private $response;

	public function setUp()
	{
		$this->response = new Response(200);
	}

	public function testGetStatusCode()
	{
		$this->assertEquals(200, $this->response->getStatusCode());
	}

	/**
	 * @depends testGetStatusCode
	 */
	public function testWithStatus()
	{
		$response = $this->response->withStatus(404, 'Custom page not found');

		$this->assertEquals(404, $response->getStatusCode());
		$this->assertEquals('Custom page not found', $response->getReasonPhrase());
		$this->assertNotSame($this->response, $response);
	}

	public function testGetReasonPhrase()
	{
		$this->assertEquals('OK', $this->response->getReasonPhrase());
	}
}
