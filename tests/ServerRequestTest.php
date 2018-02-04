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
 * The server request test class.
 *
 * @since 1.0.0
 */
class ServerRequestTest extends TestCase
{
	/** @var array The files. */
	private static $files;

	/** @var array The server. */
	private static $server;

	/** @var ServerRequest The server request. */
	private $serverRequest;

	public static function setUpBeforeClass()
	{
		self::$server = $_SERVER;
		self::$files = $_FILES;

		$_SERVER['REQUEST_URI'] = '/test/?key=value&key2[]=value1&key2[]=value2';
		$_FILES = [
			'single' => [
				'name' => 'test.txt',
				'type' => 'text/plain',
				'tmp_name' => 'tmp.txt',
				'error' => UPLOAD_ERR_OK,
				'size' => 0
			],
			'indent' => [
				'multiple' => [
					'name' => [
						'test1.txt',
						'test2.txt'
					],
					'type' => [
						'text/plain',
						'text/plain'
					],
					'tmp_name' => [
						'tmp1.txt',
						'tmp2.txt'
					],
					'error' => [
						UPLOAD_ERR_OK,
						UPLOAD_ERR_OK
					],
					'size' => [
						0,
						0
					]
				]
			]
		];
	}

	public static function tearDownAfterClass()
	{
		$_FILES = self::$files;
		$_SERVER = self::$server;
	}

	public function setUp()
	{
		$this->serverRequest = new ServerRequest();
	}

	public function test__Construct()
	{
		$this->assertInstanceOf(ServerRequest::class, new ServerRequest('GET', new URI('/')));
	}

	public function testGetServerParams()
	{
		$this->assertEquals($_SERVER, $this->serverRequest->getServerParams());
	}

	public function testGetCookieParams()
	{
		$this->assertEquals($_COOKIE, $this->serverRequest->getCookieParams());
	}

	public function testWithCookieParams()
	{
		$this->assertEquals($this->serverRequest, $this->serverRequest->withCookieParams([]));
	}

	public function testGetQueryParams()
	{
		$queryParams = [
			'key' => 'value',
			'key2' => [
				'value1',
				'value2'
			]
		];

		$this->assertEquals($queryParams, $this->serverRequest->getQueryParams());
	}

	public function testGetQueryParamsEmpty()
	{
		$server = $_SERVER;
		unset($_SERVER['REQUEST_URI']);

		$this->assertEquals([], (new ServerRequest())->getQueryParams());

		$_SERVER = $server;
	}

	public function testWithQueryParams()
	{
		$queryParams = [
			'test' => 'test'
		];

		$this->assertEquals($queryParams, $this->serverRequest->withQueryParams($queryParams)->getQueryParams());
	}

	public function testGetUploadedFiles()
	{
		$uploadedFile1 = new UploadedFile('test.txt', 'text/plain', 'tmp.txt', UPLOAD_ERR_OK, 0);
		$uploadedFile2 = new UploadedFile('test1.txt', 'text/plain', 'tmp1.txt', UPLOAD_ERR_OK, 0);
		$uploadedFile3 = new UploadedFile('test2.txt', 'text/plain', 'tmp2.txt', UPLOAD_ERR_OK, 0);

		$result = [
			'single' => $uploadedFile1,
			'indent' => [
				'multiple' => [
					$uploadedFile2,
					$uploadedFile3
				]
			]
		];

		$this->assertEquals($result, $this->serverRequest->getUploadedFiles());
	}

	public function testWithUploadedFiles()
	{
		$serverRequest = $this->serverRequest->withUploadedFiles([]);

		$this->assertEquals([], $serverRequest->getUploadedFiles());
		$this->assertNotSame($this->serverRequest, $serverRequest);
	}

	public function testGetParsedBody()
	{
		$this->assertNull($this->serverRequest->getParsedBody());
	}

	public function testGetParsedBodyPost()
	{
		$serverRequest = new ServerRequest('POST');
		$serverRequest = $serverRequest->withHeader('Content-Type', 'application/x-www-form-urlencoded');

		$this->assertEquals($_POST, $serverRequest->getParsedBody());
	}

	public function testGetParsedBodyPostMultiple()
	{
		$serverRequest = new ServerRequest('POST');
		$serverRequest = $serverRequest->withHeader('Content-Type', ['application/x-www-form-urlencoded', 'application/extra']);

		$this->assertEquals($_POST, $serverRequest->getParsedBody());
	}

	public function testGetParsedBodyPostBoundary()
	{
		$serverRequest = new ServerRequest('POST');
		$serverRequest = $serverRequest->withHeader('Content-Type', 'application/x-www-form-urlencoded; boundary=----WebKitFormBoundary');

		$this->assertEquals($_POST, $serverRequest->getParsedBody());
	}

	public function testGetParsedBodyJson()
	{
		$body = '{"key" : "value"}';

		$stream = new Stream(fopen('php://temp', 'r+'));
		$stream->write($body);

		$serverRequest = new ServerRequest('POST');
		$serverRequest = $serverRequest->withHeader('Content-Type', 'application/json')
			->withBody($stream);

		$this->assertEquals(json_decode($body), $serverRequest->getParsedBody());
	}

	public function testWithParsedBody()
	{
		$serverRequest = $this->serverRequest->withParsedBody('body');

		$this->assertEquals('body', $serverRequest->getParsedBody());
		$this->assertNotSame($this->serverRequest, $serverRequest);
	}

	public function testGetAttributes()
	{
		$this->assertEquals([], $this->serverRequest->getAttributes());
	}

	public function testGetAttribute()
	{
		$this->assertNull($this->serverRequest->getAttribute('name'));
	}

	public function testWithAttribute()
	{
		$serverRequest = $this->serverRequest->withAttribute('name', 'value');

		$this->assertEquals('value', $serverRequest->getAttribute('name'));
		$this->assertNotSame($this->serverRequest, $serverRequest);
	}

	public function testWithoutAttribute()
	{
		$serverRequest = $this->serverRequest->withoutAttribute('name', 'value');

		$this->assertEquals('default', $serverRequest->getAttribute('name', 'default'));
		$this->assertNotSame($this->serverRequest, $serverRequest);
	}
}
