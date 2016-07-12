# Request

The request class.

## Example(s)

```php
<?php

use miBadger\Http\Request;

/**
 * Retrieves the message's request target.
 */
$request->getRequestTarget();

/**
 * Return an instance with the specific request-target.
 */
$request->withRequestTarget($requestTarget);

/**
 * Retrieves the HTTP method of the request.
 */
$request->getMethod();

/**
 * Return an instance with the provided HTTP method.
 */
$request->withMethod($method);

/**
 * Retrieves the URI instance.
 */
$request->getUri();

/**
 * Returns an instance with the provided URI.
 */
$request->withUri(UriInterface $uri, $preserveHost = false);
```
