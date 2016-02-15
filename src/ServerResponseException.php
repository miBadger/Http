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
 * The server response exception class.
 *
 * @since 1.0.0
 */
class ServerResponseException extends \Exception
{
	/** @var ServerResponse The server response. */
	private $serverResponse;

	/**
	 * Contract a server response exception with the gieven server response.
	 *
	 * @param ServerResponse $serverResponse
	 * @param \Exception|null $exception = null
	 */
	public function __construct(ServerResponse $serverResponse, \Exception $exception = null)
	{
		parent::__construct($serverResponse->getReasonPhrase(), $serverResponse->getStatusCode(), $exception);

		$this->serverResponse = $serverResponse;
	}

	/**
	 * Returns the server response.
	 *
	 * @return ServerResponse the server response.
	 */
	public function getServerResponse()
	{
		return $this->serverResponse;
	}
}
