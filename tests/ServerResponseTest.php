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
 * The server response test class.
 *
 * @since 1.0.0
 */
class ServerResponseTest extends \PHPUnit_Framework_TestCase
{
	/** @var ServerResponse The server response. */
	private $serverResponse;

	public function setUp()
	{
		$this->serverResponse = (new ServerResponse(200))->withHeader('Server', 'miWebb');
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testSend()
	{
		$this->expectOutputString('test');

		$this->serverResponse->getBody()->write('test');
		$this->serverResponse->send();
	}
}
