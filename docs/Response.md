# Response

The response class.

## Example(s)

```php
<?php

use miBadger\Http\Response;

/**
 * Gets the response status code.
 */
$response->getStatusCode();

/**
 * Return an instance with the specified status code and, optionally, reason phrase.
 */
$response->withStatus($code, $reasonPhrase = '');

/**
 * Gets the response reason phrase associated with the status code.
 */
$response->getReasonPhrase();
```
