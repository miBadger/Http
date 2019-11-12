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
 * The status response exception test class.
 *
 * @since 1.0.0
 */
class ServerResponseExceptionTest extends TestCase
{
	/** @var ServerResponse The server response. */
	private $serverResponse;

	/** @var ServerResponse The server response. */
	private $serverResponseException;

	public function setUp(): void
	{
		$this->serverResponse = new ServerResponse(404);
		$this->serverResponseException = new ServerResponseException($this->serverResponse);
	}

	public function testGetServerResponse()
	{
		$this->assertEquals($this->serverResponse, $this->serverResponseException->getServerResponse());
	}

	public function testThrow()
	{
		$this->expectException(ServerResponseException::class);
		$this->expectExceptionCode(404);
		$this->expectExceptionMessage('Not Found');

		throw $this->serverResponseException;
	}
}
