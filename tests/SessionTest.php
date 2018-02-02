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
 * The session test.
 *
 * @since 1.0.0
 */
class SessionTest extends TestCase
{
	public function setUp()
	{
		$_SESSION = [];
		$object = Session::getInstance();
		$reflection = new \ReflectionClass(get_class($object));
		$method = $reflection->getMethod('__construct');
		$method->setAccessible(true);
		$method->invokeArgs($object, []);
	}

	public function tearDown()
	{
		unset($_SESSION);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testStart()
	{
		$this->assertNull(Session::start('test'));
	}

	/**
	 * @runInSeparateProcess
	 * @expectedException \RuntimeException
	 * @expectedExceptionMessage Can't start a new session.
	 */
	public function testStartExceptionStatus()
	{
		$this->assertNull(Session::start('test'));

		Session::start('test');
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testDestroy()
	{
		$this->assertNull(Session::start());
		$this->assertNull(Session::destroy());
	}

	/**
	 * @runInSeparateProcess
	 * @expectedException \RuntimeException
	 * @expectedExceptionMessage Can't destroy the session. There is no active session.
	 */
	public function testDestroyExceptionStatus()
	{
		$this->assertNull(Session::start());
		$this->assertNull(Session::destroy());

		Session::destroy();
	}

	/**
	 * @runInSeparateProcess
	 * @expectedException \RuntimeException
	 * @expectedExceptionMessage Failed to destroy the active session.
	 */
	public function testDestroyExceptionFailed()
	{
		$t = function () { return true; };
		$f = function () { return false; };
		$s = function () { return ''; };
		session_set_save_handler($t, $t, $s, $t, $f, $t);

		$this->assertNull(Session::start());

		@Session::destroy();
	}

	public function testGet()
	{
		$this->assertNull(Session::get('key'));
	}

	/**
	 * @depends testGet
	 */
	public function testSet()
	{
		Session::set('key', 'value');
		$this->assertEquals('value', Session::get('key'));
	}

	/**
	 * @depends testSet
	 */
	public function testGetIterator()
	{
		$this->assertNull(Session::set('key', 'value'));
		$this->assertEquals(new \ArrayIterator(['key' => 'value']), Session::getInstance()->getIterator());
	}

	/**
	 * @depends testSet
	 */
	public function testCount()
	{
		$this->assertEquals(0, Session::count());
		Session::set('key', 'value');
		$this->assertEquals(1, Session::count());
	}

	/**
	 * @depends testSet
	 */
	public function testIsEmpty()
	{
		$this->assertTrue(Session::isEmpty());
		Session::set('key', 'value');
		$this->assertFalse(Session::isEmpty());
	}

	/**
	 * @depends testSet
	 */
	public function testContainsKey()
	{
		$this->assertFalse(Session::containsKey('key'));
		Session::set('key', 'value');
		$this->assertTrue(Session::containsKey('key'));
	}

	/**
	 * @depends testSet
	 */
	public function testContainsValue()
	{
		$this->assertFalse(Session::containsValue('value'));
		Session::set('key', 'value');
		$this->assertTrue(Session::containsValue('value'));
	}

	/**
	 * @depends testContainsKey
	 */
	public function testRemove()
	{
		Session::set('key', 'value');
		$this->assertNull(Session::remove('key'));
		$this->assertFalse(Session::containsKey('key'));
	}

	/**
	 * @depends testIsEmpty
	 */
	public function testClear()
	{
		Session::set('key', 'value');
		$this->assertNull(Session::clear());
		$this->assertTrue(Session::isEmpty());
	}
}
