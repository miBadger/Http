<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 * @version 1.0.0
 */

namespace miBadger\Http;

/**
 * The status response exception test class.
 *
 * @since 1.0.0
 */
class ServerResponseExceptionTest extends \PHPUnit_Framework_TestCase
{
	/** @var ServerResponse The server response. */
	private $serverResponse;

	/** @var ServerResponse The server response. */
	private $serverResponseException;

	public function setUp()
	{
		$this->serverResponse = new ServerResponse(404);
		$this->serverResponseException = new ServerResponseException($this->serverResponse);
	}

	public function testGetServerResponse()
	{
		$this->assertEquals($this->serverResponse, $this->serverResponseException->getServerResponse());
	}

	/**
	 * @expectedException miBadger\Http\ServerResponseException
	 * @expectedExceptionCode 404
	 * @expectedExceptionMessage Not Found
	 */
	public function testThrow()
	{
		throw $this->serverResponseException;
	}
}
