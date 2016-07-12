# Session

The session class.

## Example(s)

```php
<?php

use miBadger\Http\Session;

/**
 * Initialize the session, if none exists.
 */
Session::start($name = null);

/**
 * Destroy the session, if one exists.
 */
Session::destroy();

/**
 * Returns the number of key-value mappings in the session map.
 */
Session::count();

/**
 * Returns true if the session map contains no key-value mappings.
 */
Session::isEmpty();

/**
 * Returns true if the session map contains a mapping for the specified key.
 */
Session::containsKey($key);

/**
 * Returns true if the session map maps one or more keys to the specified value.
 */
Session::containsValue($value);

/**
 * Returns the value to which the specified key is mapped, or null if the session map contains no mapping for the key.
 */
Session::get($key);

/**
 * Associates the specified value with the specified key in the session map.
 */
Session::set($key, $value);

/**
 * Removes the mapping for the specified key from the session map if present.
 */
Session::remove($key);

/**
 * Removes all of the mappings from the session map.
 */
Session::clear();
```
