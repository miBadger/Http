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
 * The URI test class.
 *
 * @since 1.0.0
 */
class URITest extends TestCase
{
	/** @var URI The URI. */
	private $uri;

	public function setUp()
	{
		$this->uri = new URI('http://username:password@www.example.org:123/directory/file?key=value#fragment');
	}

	/**
	 * @expectedException \UnexpectedValueException
	 * @expectedExceptionMessage Invalid uri
	 */
	public function test__ConstructInvalidUri()
	{
		new URI('http:///www.example.org');
	}

	public function testGetUriStart()
	{
		$this->assertEquals('http://username:password@www.example.org:123/directory/file?key=value#fragment', $this->uri->getUri());
		$this->assertEquals('//username:password@www.example.org:123/directory/file?key=value#fragment', $this->uri->getUri(URI::USERNAME));
		$this->assertEquals('//www.example.org:123/directory/file?key=value#fragment', $this->uri->getUri(URI::HOST));
		$this->assertEquals(':123/directory/file?key=value#fragment', $this->uri->getUri(URI::PORT));
		$this->assertEquals('/directory/file?key=value#fragment', $this->uri->getUri(URI::DIRECTORY));
		$this->assertEquals('/directory/file?key=value#fragment', $this->uri->getUri(URI::PATH));
		$this->assertEquals('file?key=value#fragment', $this->uri->getUri(URI::FILE));
		$this->assertEquals('?key=value#fragment', $this->uri->getUri(URI::QUERY));
		$this->assertEquals('#fragment', $this->uri->getUri(URI::FRAGMENT));
	}

	public function testGetUriEnd()
	{
		$this->assertEquals('http://username:password@www.example.org:123/directory/file?key=value', $this->uri->getUri(URI::SCHEME, URI::QUERY));
		$this->assertEquals('http://username:password@www.example.org:123/directory/file', $this->uri->getUri(URI::SCHEME, URI::FILE));
		$this->assertEquals('http://username:password@www.example.org:123/directory/file', $this->uri->getUri(URI::SCHEME, URI::PATH));
		$this->assertEquals('http://username:password@www.example.org:123/directory/', $this->uri->getUri(URI::SCHEME, URI::DIRECTORY));
		$this->assertEquals('http://username:password@www.example.org:123', $this->uri->getUri(URI::SCHEME, URI::PORT));
		$this->assertEquals('http://username:password@www.example.org', $this->uri->getUri(URI::SCHEME, URI::HOST));
		$this->assertEquals('http://username:password@', $this->uri->getUri(URI::SCHEME, URI::USERNAME));
		$this->assertEquals('http:', $this->uri->getUri(URI::SCHEME, URI::SCHEME));
	}

	public function testGetUriPathDelimiter()
	{
		$this->assertEquals((string) $this->uri, $this->uri->withDirectory('directory'));
	}

	public function testGetScheme()
	{
		$this->assertEquals('http', $this->uri->getScheme());
	}

	/**
	 * @depends testGetScheme
	 */
	public function testWithScheme()
	{
		$uri = $this->uri->withScheme('https');

		$this->assertEquals('https', $uri->getScheme());
		$this->assertNotSame($this->uri, $uri);
	}

	public function testGetAuthority()
	{
		$this->assertEquals('username:password@www.example.org:123', $this->uri->getAuthority());
		$this->assertEquals('', $this->uri->withHost('')->getAuthority());
	}

	public function testGetUserInfo()
	{
		$this->assertEquals('username:password', $this->uri->getUserInfo());
	}

	/**
	 * @depends testGetUserInfo
	 */
	public function testWithUserInfo()
	{
		$uri = $this->uri->withUserInfo('username2', 'password2');

		$this->assertEquals('username2:password2', $uri->getUserInfo());
		$this->assertNotSame($this->uri, $uri);
	}

	public function testGetHost()
	{
		$this->assertEquals('www.example.org', $this->uri->getHost());
	}

	/**
	 * @depends testGetHost
	 */
	public function testWithHost()
	{
		$uri = $this->uri->withHost('example.org');

		$this->assertEquals('example.org', $uri->getHost());
		$this->assertNotSame($this->uri, $uri);
	}

	public function testGetPort()
	{
		$this->assertEquals(123, $this->uri->getPort());
	}

	/**
	 * @depends testGetPort
	 */
	public function testWithPort()
	{
		$uri = $this->uri->withPort(1234);

		$this->assertEquals(1234, $uri->getPort());
		$this->assertNotSame($this->uri, $uri);
	}

	/**
	 * @depends testGetPort
 	 * @expectedException \InvalidArgumentException
 	 * @expectedExceptionMessage Invalid port
	 */
	public function testWithInvalidPort()
	{
		$this->uri->withPort(123456789);
	}

	public function testGetPath()
	{
		$this->assertEquals('/directory/file', $this->uri->getPath());
		$this->assertEquals('/directory/file', $this->uri->withDirectory('/directory')->getPath());
	}

	/**
	 * @depends testGetPath
	 */
	public function testWithPath()
	{
		$this->assertEquals('', $this->uri->withPath('')->getPath());
		$this->assertEquals('file', $this->uri->withPath('file')->getPath());
		$this->assertEquals('directory/', $this->uri->withPath('directory/')->getPath());
		$this->assertEquals('directory/file', $this->uri->withPath('directory/file')->getPath());
		$this->assertEquals('/', $this->uri->withPath('/')->getPath());
		$this->assertEquals('/file', $this->uri->withPath('/file')->getPath());
		$this->assertEquals('/directory/', $this->uri->withPath('/directory/')->getPath());
		$this->assertEquals('/directory/file', $this->uri->withPath('/directory/file')->getPath());
		$this->assertNotSame($this->uri, $this->uri->withPath(''));
	}

	/**
	 * @depends testWithPath
	 */
	public function testGetSegments()
	{
		$this->assertEquals(['directory', 'file'], $this->uri->getSegments());

		$uri = $this->uri->withPath('/');
		$this->assertEquals([], $uri->getSegments());
	}

	/**
	 * @depends testGetSegments
	 */
	public function testGetSegment()
	{
		$this->assertEquals('directory', $this->uri->getSegment(0));
		$this->assertEquals('file', $this->uri->getSegment(1));
		$this->assertNull($this->uri->getSegment(2));

		$uri = $this->uri->withPath('/');
		$this->assertNull($uri->getSegment(0));
		$this->assertNull($uri->getSegment(1));
	}

	public function testGetDirectory()
	{
		$this->assertEquals('/directory/', $this->uri->getDirectory());
	}

	/**
	 * @depends testGetDirectory
	 */
	public function testWithDirectory()
	{
		$uri = $this->uri->withDirectory('/directory2/');

		$this->assertEquals('/directory2/', $uri->getDirectory());
		$this->assertNotSame($this->uri, $uri);
	}

	public function testGetFile()
	{
		$this->assertEquals('file', $this->uri->getFile());
	}

	/**
	 * @depends testGetFile
	 */
	public function testWithFile()
	{
		$uri = $this->uri->withFile('file2');

		$this->assertEquals('file2', $uri->getFile());
		$this->assertNotSame($this->uri, $uri);
	}

	public function testGetQuery()
	{
		$this->assertEquals('key=value', $this->uri->getQuery());
	}

	/**
	 * @depends testGetQuery
	 */
	public function testWithQuery()
	{
		$uri = $this->uri->withQuery('key2=value2');

		$this->assertEquals('key2=value2', $uri->getQuery());
		$this->assertNotSame($this->uri, $uri);
	}

	public function testGetQueryValue()
	{
		$this->assertEquals('value', $this->uri->getQueryValue('key'));
	}

	/**
	 * @depends testGetQueryValue
	 */
	public function testWithQueryValue()
	{
		$uri = $this->uri->withQueryValue('key', 'value2');

		$this->assertEquals('value2', $uri->getQueryValue('key'));
		$this->assertNotSame($this->uri, $uri);
	}

	public function testGetFragment()
	{
		$this->assertEquals('fragment', $this->uri->getFragment());
	}

	/**
	 * @depends testGetFragment
	 */
	public function testWithFragment()
	{
		$uri = $this->uri->withFragment('fragment2');

		$this->assertEquals('fragment2', $uri->getFragment());
		$this->assertNotSame($this->uri, $uri);
	}

	public function testDecode()
	{
		$this->assertEquals($this->uri, $this->uri->encode()->decode());
	}
}
