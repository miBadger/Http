# ServerResponseException

The server response exception class.

## Example(s)

```php
<?php

use miBadger\Http\ServerRequestException;

/**
 * Throw a new server response exception.
 */
throw new ServerResponseException($serverResponse);

/**
 * Returns the server response.
 */
$serverResponseException->getServerResponse()
```
