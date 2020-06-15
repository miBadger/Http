<?php

/**
 * This file is part of the miBadger package.
 *
 * @author Michael Webbers <michael@webbers.io>
 * @license http://opensource.org/licenses/Apache-2.0 Apache v2 License
 */

namespace miBadger\Http;

use miBadger\Singleton\SingletonTrait;

/**
 * The session class
 *
 * @since 1.0.0
 */
class Session implements \IteratorAggregate
{
	use SingletonTrait;

	/** @var array The session. */
	private $session;

	/**
	 * Construct a Session object.
	 *
	 * @global array $_SESSION The session parameters.
	 */
	protected function __construct()
	{
		$this->session = &$_SESSION;
	}

	/**
	 * Initialize the session, if none exists.
	 *
	 * @param $name = null
	 * @param $domain bool 
	 * @return null
	 * @throws \RuntimeException on failure.
	 */
	public static function start($name = null, $domain = false)
	{
		if (session_status() !== PHP_SESSION_NONE) {
			throw new \RuntimeException('Can\'t start a new session.');
		}
		if ($name !== null) {
			session_name($name);
		}
		if ($domain){
			preg_match("/[^\.\/]+\.[^\.\/]+$/", $_SERVER['HTTP_HOST'], $matches);
			session_set_cookie_params(0, '/', $matches[0]);
		}
		session_start();
	}

	/**
	 * Destroy the session, if one exists.
	 *
	 * @return null
	 * @throws \RuntimeException on failure.
	 */
	public static function destroy()
	{
		if (session_status() !== PHP_SESSION_ACTIVE) {
			throw new \RuntimeException('Can\'t destroy the session. There is no active session.');
		}

		if (!session_destroy()) {
			throw new \RuntimeException('Failed to destroy the active session.');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIterator()
	{
		return new \ArrayIterator(static::getInstance()->session);
	}

	/**
	 * Returns the number of key-value mappings in the session map.
	 *
	 * @return int the number of key-value mappings in the session map.
	 */
	public static function count()
	{
		return count(static::getInstance()->session);
	}

	/**
	 * Returns true if the session map contains no key-value mappings.
	 *
	 * @return bool true if the session map contains no key-value mappings.
	 */
	public static function isEmpty()
	{
		return empty(static::getInstance()->session);
	}

	/**
	 * Returns true if the session map contains a mapping for the specified key.
	 *
	 * @param string $key
	 * @return bool true if the session map contains a mapping for the specified key.
	 */
	public static function containsKey($key)
	{
		return isset(static::getInstance()->session[$key]);
	}

	/**
	 * Returns true if the session map maps one or more keys to the specified value.
	 *
	 * @param string $value
	 * @return bool true if the session map maps one or more keys to the specified value.
	 */
	public static function containsValue($value)
	{
		return in_array($value, static::getInstance()->session);
	}

	/**
	 * Returns the value to which the specified key is mapped, or null if the session map contains no mapping for the key.
	 *
	 * @param string $key
	 * @return string|null the value to which the specified key is mapped, or null if the session map contains no mapping for the key.
	 */
	public static function get($key)
	{
		return static::containsKey($key) ? static::getInstance()->session[$key] : null;
	}

	/**
	 * Associates the specified value with the specified key in the session map.
	 *
	 * @param string $key
	 * @param string $value
	 * @return null
	 */
	public static function set($key, $value)
	{
		static::getInstance()->session[$key] = $value;
	}

	/**
	 * Removes the mapping for the specified key from the session map if present.
	 *
	 * @param string $key
	 * @return null
	 */
	public static function remove($key)
	{
		if (static::containsKey($key)) {
			unset(static::getInstance()->session[$key]);
		}
	}

	/**
	 * Removes all of the mappings from the session map.
	 *
	 * @return null
	 */
	public static function clear()
	{
		static::getInstance()->session = [];
	}
}
